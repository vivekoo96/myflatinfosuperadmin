<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create classifieds table if it doesn't exist
        if (!Schema::hasTable('classifieds')) {
            Schema::create('classifieds', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('desc');
                $table->enum('category', ['All Buildings'])->default('All Buildings');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('building_id')->default(0);
                $table->unsignedBigInteger('block_id')->default(0);
                $table->unsignedBigInteger('flat_id')->default(0);
                $table->enum('status', ['Approved', 'Pending', 'Rejected'])->default('Approved');
                $table->enum('notification_type', ['all', 'selected'])->default('all');
                $table->text('reason')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // Create classified_photos table if it doesn't exist
        if (!Schema::hasTable('classified_photos')) {
            Schema::create('classified_photos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('classified_id');
                $table->string('photo');
                $table->timestamps();

                $table->foreign('classified_id')
                    ->references('id')
                    ->on('classifieds')
                    ->onDelete('cascade');
            });
        }

        // Create classified_buildings table
        if (!Schema::hasTable('classified_buildings')) {
            Schema::create('classified_buildings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('classified_id');
                $table->unsignedBigInteger('building_id');
                $table->timestamps();

                $table->foreign('classified_id')
                    ->references('id')
                    ->on('classifieds')
                    ->onDelete('cascade');

                $table->foreign('building_id')
                    ->references('id')
                    ->on('buildings')
                    ->onDelete('cascade');

                $table->index(['classified_id', 'building_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('classified_buildings');
        Schema::dropIfExists('classified_photos');
        Schema::dropIfExists('classifieds');
    }
};