<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;

class TokenCleanupService
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function cleanupInvalidTokens()
    {
        $users = User::whereNotNull('device_token')->get();
        
        foreach ($users as $user) {
            try {
                // Send a test message to verify token
                $result = $this->fcmService->sendNotification(
                    $user->device_token,
                    'Token Verification',
                    'Verifying device token',
                    ['type' => 'verification']
                );

                if (!$result['success']) {
                    // If token is invalid, remove it
                    $user->device_token = null;
                    $user->save();
                    Log::info('Removed invalid FCM token from user ' . $user->id);
                }
            } catch (\Exception $e) {
                Log::error('Token verification failed for user ' . $user->id . ': ' . $e->getMessage());
            }
        }
    }
}