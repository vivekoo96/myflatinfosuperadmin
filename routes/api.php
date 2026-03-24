<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\TruthScreenController;

// Route::prefix('customer')->group(function () {
    Route::post('login',[CustomerController::class,'login']);
    Route::post('send-otp',[CustomerController::class,'send_otp']);
    Route::post('resend-otp',[CustomerController::class,'resend_otp']);
    Route::post('forget-password',[CustomerController::class,'forget_password']);
    Route::post('verify-otp',[CustomerController::class,'verify_otp']);

    Route::post('get-setting',[CustomerController::class,'get_setting']);
    Route::post('get-logo',[CustomerController::class,'get_logo']);
    Route::post('onboarding',[CustomerController::class,'onboarding']);
    
    // Send push notification without authentication
    Route::post('send-push-notification',[CustomerController::class,'send_push_notification']);
    
    Route::middleware(['auth:api'])->group(function (){
        Route::post('update-password',[CustomerController::class,'update_password']);
        Route::post('update-profile',[CustomerController::class,'update_profile']);
        Route::post('get-countries',[CustomerController::class,'get_countries']);
        Route::post('get-states',[CustomerController::class,'get_states']);
        Route::post('get-districts',[CustomerController::class,'get_districts']);
        Route::post('get-mandals',[CustomerController::class,'get_mandals']);
        
            Route::post('profile',[CustomerController::class,'profile']);
            Route::post('create-razorpay-order', [CustomerController::class,'create_razorpay_order']);
            Route::post('verify-razorpay-signature', [CustomerController::class,'verify_razorpay_signature']);
            
            Route::post('get-notifications', [CustomerController::class,'get_notifications']);
            Route::post('read-notification', [CustomerController::class,'read_notification']);
            
    });
    
// });





