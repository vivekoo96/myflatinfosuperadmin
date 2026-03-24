<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedSendNotificationPermission extends Migration
{
    public function up()
    {
        $exists = DB::table('permissions')
            ->where('guard', 'feature')
            ->where('name', 'Send Notification')
            ->exists();

        if (!$exists) {
            DB::table('permissions')->insert([
                'guard'      => 'feature',
                'group'      => 'Notifications',
                'name'       => 'Send Notification',
                'slug'       => 'send.notification',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        DB::table('permissions')
            ->where('guard', 'feature')
            ->where('name', 'Send Notification')
            ->delete();
    }
}
