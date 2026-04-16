<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGstFlagsToBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->enum('gst_maintenance_enabled', ['Yes', 'No'])->default('No')->after('other_is_active');
            $table->enum('gst_essentials_enabled',  ['Yes', 'No'])->default('No')->after('gst_maintenance_enabled');
            $table->enum('gst_bookings_enabled',    ['Yes', 'No'])->default('No')->after('gst_essentials_enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn(['gst_maintenance_enabled', 'gst_essentials_enabled', 'gst_bookings_enabled']);
        });
    }
}
