<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->enum('type', ['Admin', 'Building Admin', 'Customer'])->default('Customer');
            $table->string('photo')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('otp_expiry')->nullable();
            $table->string('device_token')->nullable();
            $table->string('device_type')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        // Create buildings table
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('photo')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->unsignedBigInteger('city_id');
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Create classifieds table
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

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Create classified_photos table
        Schema::create('classified_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classified_id');
            $table->string('photo');
            $table->timestamps();

            $table->foreign('classified_id')->references('id')->on('classifieds')->onDelete('cascade');
        });

        // Create classified_buildings table
        Schema::create('classified_buildings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classified_id');
            $table->unsignedBigInteger('building_id');
            $table->timestamps();

            $table->foreign('classified_id')->references('id')->on('classifieds')->onDelete('cascade');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            
            $table->index(['classified_id', 'building_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('classified_buildings');
        Schema::dropIfExists('classified_photos');
        Schema::dropIfExists('classifieds');
        Schema::dropIfExists('buildings');
        Schema::dropIfExists('users');
    }
};