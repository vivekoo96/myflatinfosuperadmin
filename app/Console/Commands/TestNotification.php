<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use App\Models\User;
use App\Models\Setting;

class TestNotification extends Command
{
    protected $signature = 'notification:test {title} {body} {--type=TEST} {--screen=Home} {--mock : Add mock device token for testing}';
    protected $description = 'Test push notification system by sending to all users';

    public function handle()
    {
        $title = $this->argument('title');
        $body = $this->argument('body');
        $type = $this->option('type');
        $screen = $this->option('screen');
        $mock = $this->option('mock');

        $this->info('🔔 Testing Push Notification System');
        $this->info("Title: {$title}");
        $this->info("Body: {$body}");
        $this->info("Type: {$type}");
        $this->info("Screen: {$screen}");
        $this->line('');

        // Check system requirements
        $this->info('📋 System Check:');
        
        // Check FCM key
        $setting = Setting::first();
        if (!$setting || empty($setting->fcm_key)) {
            $this->error('❌ FCM key not configured in settings');
            return 1;
        }
        $this->info('✅ FCM key configured (length: ' . strlen($setting->fcm_key) . ')');

        // Check users
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'Active')->count();
        $usersWithTokens = User::where('status', 'Active')
                              ->whereNotNull('device_token')
                              ->where('device_token', '!=', '')
                              ->count();

        $this->info("✅ Total users: {$totalUsers}");
        $this->info("✅ Active users: {$activeUsers}");
        $this->info("📱 Users with device tokens: {$usersWithTokens}");

        if ($usersWithTokens == 0) {
            $this->warn('⚠️  No users have device tokens!');
            
            if ($mock) {
                $this->info('🔧 Adding mock device token for testing...');
                $user = User::where('status', 'Active')->first();
                if ($user) {
                    $user->device_token = 'fSwIirBtRS-eKlo4FHxesl:APA91bG4JYiF9pPfa0hDosO0m8BDzfIu-BIBDzR7WEVxinWP8m1PJG2WN2IP3OIwKmBbYtvTMzsVO9L0X6fnx3f_VY1E1NmxfDVxmnl661Mca-64fr_eOzc';
                    $user->save();
                    $this->info("✅ Added mock token to user: {$user->email}");
                    $usersWithTokens = 1;
                } else {
                    $this->error('❌ No active users found to add mock token');
                    return 1;
                }
            } else {
                $this->line('');
                $this->comment('💡 To test with mock data, run:');
                $this->comment('   php artisan notification:test "Test Title" "Test Body" --mock');
                $this->line('');
                $this->comment('💡 In production, users get device tokens when they:');
                $this->comment('   - Login via mobile app');
                $this->comment('   - Verify OTP during registration');
                return 0;
            }
        }

        $this->line('');
        $this->info("🚀 Sending notifications to {$usersWithTokens} users...");

        $notificationService = new NotificationService();
        $result = $notificationService->sendToAllUsers($title, $body, $type, $screen);

        if ($result && ($result['success'] > 0 || $result['failures'] > 0)) {
            $this->line('');
            $this->info("📊 Results:");
            $this->info("✅ Success: {$result['success']} users");
            if ($result['failures'] > 0) {
                $this->warn("❌ Failures: {$result['failures']} users");
            }
            
            if ($result['success'] > 0) {
                $this->info("🎉 Notifications sent successfully!");
            }
        } else {
            $this->error("❌ Failed to send notifications - check logs for details");
        }

        return 0;
    }
}
