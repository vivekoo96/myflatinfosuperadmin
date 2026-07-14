<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPollsPermissionToSuperAdminGuard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('permissions')->insertOrIgnore([
            'name' => 'Polls Menu',
            'slug' => 'menu.polls',
            'guard' => 'super-admin',
            'group' => 'Menu',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('permissions')->where('slug', 'menu.polls')->where('guard', 'super-admin')->delete();
    }
}
