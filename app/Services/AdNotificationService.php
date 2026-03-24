<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\FCMService;
use App\Models\Setting;

class AdNotificationService
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function sendNewAdNotification($ad)
    {
        try {
            $title = 'New Advertisement';
            $body = "Check out new ad: {$ad->name}";

            // Raw data
            $fcmData = [
                'ad_id' => $ad->id,
                'type' => 'advertisement',
                'link' => $ad->link,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'screen' => 'Advertisement',
                'categoryId' => 'Ads',
                'channelId' => 'Community',
                'sound' => 'bellnotificationsound.wav'
            ];

            // ✅ Convert ALL values to string
            $fcmData = collect($fcmData)->mapWithKeys(fn($v, $k) => [$k => (string)$v])->toArray();

            // Collect all active tokens
            $users = User::where('status', 'Active')
                ->whereNotNull('device_token')
                ->where('device_token', '!=', '')
                ->pluck('device_token')
                ->toArray();

            $devices = DB::table('user_devices')
                ->whereNotNull('fcm_token')
                ->where('is_active', 1)
                ->pluck('fcm_token')
                ->toArray();

            $tokens = array_unique(array_merge($users, $devices));

            if (empty($tokens)) {
                Log::warning('No FCM tokens found for advertisement notification.');
                return false;
            }

            Log::info('Sending Ad Notifications to ' . count($tokens) . ' devices');

            // Send
            $results = $this->fcmService->sendToMultipleDevices($tokens, $title, $body, $fcmData);

            // Handle invalid tokens
            foreach ($results['results'] as $index => $res) {
                if (
                    isset($res['response']['error']['details'][0]['errorCode']) &&
                    $res['response']['error']['details'][0]['errorCode'] === 'UNREGISTERED'
                ) {
                    $invalidToken = $tokens[$index];
                    DB::table('user_devices')->where('fcm_token', $invalidToken)->delete();
                    User::where('device_token', $invalidToken)->update(['device_token' => null]);
                    Log::warning("Removed invalid FCM token: {$invalidToken}");
                }
            }

            Log::info("Ad Notifications Summary", [
                'success' => $results['success'] ?? 0,
                'failure' => $results['failure'] ?? 0
            ]);

            return ($results['success'] ?? 0) > 0;

        } catch (\Exception $e) {
            Log::error('Ad Notification Error: ' . $e->getMessage());
            return false;
        }
    }
}
