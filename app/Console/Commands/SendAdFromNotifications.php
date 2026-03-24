<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ad;
use App\Models\Notification as DatabaseNotification;
use App\Helpers\NotificationHelper2;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendAdFromNotifications extends Command
{
    protected $signature = 'ads:send-from-notifications';
    protected $description = 'Send ad notifications at their from_time (scheduled)';

    public function handle()
    {
        $now = Carbon::now();
      $ads = Ad::whereNull('from_notified_at')
                ->where('status', 'Active')
                ->where('from_time', '<=', $now)
                ->where('is_notified', 0)
                ->get();
         Log::info('Processing ad for aDDS', ['ADS' => $ads]);
        if ($ads->isEmpty()) {
            Log::info('ads:send-from-notifications - no ads to process');
            return 0;
        }

        foreach ($ads as $ad) {
            try {
                Log::info('Processing ad for from_time send', ['ad_id' => $ad->id]);
                 $ad->update([
                    'from_notified_at' => now(),
                    'is_notified' => 1,
                ]);

                // Determine target flats based on notification_type
                if ($ad->notification_type === 'selected') {
                    $buildingIds = $ad->buildings()->pluck('buildings.id')->toArray();
                    $flats = DB::table('flats')->whereIn('building_id', $buildingIds)->select('owner_id', 'tanent_id', 'id as flat_id', 'building_id')->get();
                } else {
                    $flats = DB::table('flats')->select('owner_id', 'tanent_id', 'id as flat_id', 'building_id')->get();
                }

                $userIds = collect();
                foreach ($flats as $flat) {
                    if ($flat->owner_id) $userIds->push($flat->owner_id);
                    if ($flat->tanent_id) $userIds->push($flat->tanent_id);
                }
                $userIds = $userIds->unique()->toArray();

                // Filter active users
                $userIds = DB::table('users')->whereIn('id', $userIds)->where('status', 'Active')->pluck('id')->toArray();

                // Create DB notifications for each user
                foreach ($userIds as $userId) {
                    $userFlat = DB::table('flats')->where(function ($q) use ($userId) {
                        $q->where('owner_id', $userId)->orWhere('tanent_id', $userId);
                    })->first();

                    $notification = new DatabaseNotification();
                    $notification->user_id = $userId;
                    $notification->from_id = null;
                    $notification->flat_id = $userFlat->flat_id ?? ($userFlat->id ?? null);
                    $notification->building_id = $userFlat->building_id ?? null;
                    $notification->title = 'New Advertisement';
                    $notification->body = "Check out new ad: {$ad->name}";
                    $notification->type = 'advertisement';
                    $notification->dataPayload = json_encode([
                       
                        'ad_id' => (string)$ad->id,
                        'link' => (string)$ad->link
                    ]);
                    $notification->status = 0;
                    $notification->admin_read = 0;
                    $notification->save();
                }

                // Send push if devices exist using NotificationHelper2
                if (!empty($userIds)) {
                    $dataPayload = [
                        'ad_id' => (string)$ad->id,
                        'type' => 'advertisement',
                        'link' => (string)$ad->link,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'screen' => 'Home',
                    ];
                    
                    $result = NotificationHelper2::sendBulkNotifications(
                        $userIds,
                        'New Advertisement',
                        "Check out new ad: {$ad->name}",
                        $dataPayload,
                        ['save_to_db' => false], // Already saved above
                        ['user'] // app_name filter
                    );
                    
                    Log::info('Ad push send result', [
                        'ad_id' => $ad->id,
                        'users_notified' => $result['total_sent'],
                        'total_devices' => $result['total_devices'],
                        'result' => $result
                    ]);
                }

              

            } catch (\Exception $e) {
                Log::error('Error sending ad from notifications', ['ad_id' => $ad->id, 'error' => $e->getMessage()]);
            }
        }

        return 0;
    }
}
