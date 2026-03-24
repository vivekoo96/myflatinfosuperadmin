<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use App\Models\Setting;
use App\Services\FCMService;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function sendToAllUsers($title, $body, $type = 'NEW_AD', $screen = 'Ads', $params = [])
    {
        // Get all active users with device tokens
        $users = User::where('status', 'Active')
                    ->whereNotNull('device_token')
                    ->where('device_token', '!=', '')
                    ->get();

        if ($users->isEmpty()) {
            Log::info('No users with device tokens found for notification');
            return false;
        }

        $fcmService = new FCMService();
        $successCount = 0;
        $failureCount = 0;

        foreach ($users as $user) {
            try {
                // Prepare FCM data
                $fcmData = [
                    'screen' => $screen,
                    'type' => $type,
                    'user_id' => (string)$user->id,
                    'flat_id' => (string)($user->flat_id ?? ''),
                    'building_id' => (string)($user->building_id ?? ''),
                    'params' => json_encode($params)
                ];

                // Send push notification using FCM v1
                $result = $fcmService->sendNotification(
                    $user->device_token,
                    $title,
                    $body,
                    $fcmData
                );

                if ($result['success']) {
                    $successCount++;
                    
                    // Store notification in database
                    Notification::create([
                        'user_id' => $user->id,
                        'title' => $title,
                        'body' => $body,
                        'dataPayload' => json_encode([
                            'type' => $type,
                            'screen' => $screen,
                            'params' => $params
                        ]),
                        'admin_read' => 0
                    ]);
                } else {
                    $failureCount++;
                }
            } catch (\Exception $e) {
                Log::error('Failed to send notification to user ' . $user->id . ': ' . $e->getMessage());
                $failureCount++;
            }
        }

        Log::info("Notification sent to {$successCount} users, {$failureCount} failures");
        return ['success' => $successCount, 'failures' => $failureCount];
    }

    public function sendToSpecificUsers($userIds, $title, $body, $type = 'NOTIFICATION', $screen = null, $params = [])
    {
        $users = User::whereIn('id', $userIds)
                    ->where('status', 'Active')
                    ->whereNotNull('device_token')
                    ->where('device_token', '!=', '')
                    ->get();

        if ($users->isEmpty()) {
            return false;
        }

        $fcmService = new FCMService();
        $successCount = 0;
        $failureCount = 0;

        foreach ($users as $user) {
            try {
                // Prepare FCM data
                $fcmData = [
                    'screen' => $screen ?? '',
                    'type' => $type,
                    'user_id' => (string)$user->id,
                    'flat_id' => (string)($user->flat_id ?? ''),
                    'building_id' => (string)($user->building_id ?? ''),
                    'params' => json_encode($params)
                ];

                // Send push notification using FCM v1
                $result = $fcmService->sendNotification(
                    $user->device_token,
                    $title,
                    $body,
                    $fcmData
                );

                if ($result['success']) {
                    $successCount++;
                    
                    Notification::create([
                        'user_id' => $user->id,
                        'title' => $title,
                        'body' => $body,
                        'dataPayload' => json_encode([
                            'type' => $type,
                            'screen' => $screen,
                            'params' => $params
                        ]),
                        'admin_read' => 0
                    ]);
                } else {
                    $failureCount++;
                }
            } catch (\Exception $e) {
                Log::error('Failed to send notification to user ' . $user->id . ': ' . $e->getMessage());
                $failureCount++;
            }
        }

        return ['success' => $successCount, 'failures' => $failureCount];
    }

    private function sendPushNotification($deviceToken, $title, $body, $type, $screen, $params, $userId, $flatId, $buildingId, $serverApiKey)
    {
        $data = [
            "to" => $deviceToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => "bellnotificationsound.wav",
            ],
            "data" => [
                "screen" => $screen,
                "params" => $params,
                "categoryId" => $screen,
                "channelId" => "longring",
                "type" => $type,
                "user_id" => (string)$userId,
                "flat_id" => $flatId ? (string)$flatId : null,
                "building_id" => $buildingId ? (string)$buildingId : null
            ]
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $serverApiKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            $responseData = json_decode($response, true);
            return isset($responseData['success']) ? $responseData['success'] : true;
        }

        return false;
    }
}
