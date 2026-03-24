<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\AdminForgotPasswordController;

use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\MandalController;

use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\BuilderController;
use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\NoticeboardController;
use App\Http\Controllers\Admin\ClassifiedController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\OCRController;
use App\Http\Controllers\VisionOCRController;
use App\Http\Controllers\TextractController;
use App\Services\FCMService;
use App\Http\Controllers\InfoController;

Route::get('clear-cache',function(){
    //\Artisan::call('storage:link');
    //\Artisan::call('vendor:publish --provider="Fruitcake\Cors\CorsServiceProvider');
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('view:clear');
    \Artisan::call('route:clear');
    \Artisan::call('config:cache');
});

Route::get('/test-fcm', function() {
    $fcm = new FCMService();
    return response()->json([
        'project_id' => $fcm->getProjectId(),
        'has_service_account' => !empty($fcm->getServiceAccountKey())
    ]);
});

Route::get('/ocr', [OCRController::class, 'showForm']);
Route::post('/ocr', [OCRController::class, 'process'])->name('ocr.process');

Route::get('/vision-ocr', [VisionOCRController::class, 'showForm']);
Route::post('/vision-ocr', [VisionOCRController::class, 'process'])->name('vision.ocr.process');

Route::get('/textract', [TextractController::class, 'index'])->name('textract.index');
Route::post('/textract', [TextractController::class, 'process'])->name('textract.process');

Route::get('/app-info', [SettingController::class, 'app_info'])->name('app.info');

// Public info page for MyFlatInfo
Route::get('/info', [InfoController::class, 'about'])->name('info.about');

Route::middleware('guest')->group(function () {

	Route::get('/',[AdminController::class, 'index'])->name('login');
	Route::post('/login',[AdminController::class, 'login']);
	Route::post('/verifyotp',[AdminController::class, 'verifyotp']);
	
	Route::get('forget-password', [AdminForgotPasswordController::class, 'showForgetPasswordForm']);
	Route::post('forget-password', [AdminForgotPasswordController::class, 'submitForgetPasswordForm']);
    Route::get('reset-password/{token}', [AdminForgotPasswordController::class, 'showResetPasswordForm']);
    Route::post('reset-password', [AdminForgotPasswordController::class, 'submitResetPasswordForm']);
});


Route::post('/save-token',[AdminController::class, 'save_token'])->name('save.token');

Route::middleware('admin')->group(function () {
    Route::post('/clear-database',[AdminController::class, 'clear_database']);
	Route::get('/dashboard',[AdminController::class, 'dashboard']);
	Route::get('/profile',[AdminController::class, 'profile']);
	Route::post('/update-profile',[AdminController::class, 'update_profile']);
	Route::post('/change-password',[AdminController::class, 'change_password']);
	Route::get('/admins',[AdminController::class, 'admins']);
	Route::get('/admin/{id}',[AdminController::class, 'show_admin']);
	Route::get('/building-admins',[AdminController::class, 'building_admins']);
	Route::get('/building-admin/{id}',[AdminController::class, 'show_building_admin']);
	Route::get('/customers',[AdminController::class, 'customers']);
	Route::get('/customer/{id}',[AdminController::class, 'show_customer']);
	Route::post('/store-user',[AdminController::class, 'store_user']);
	Route::post('/delete-user',[AdminController::class, 'delete_user']);
	Route::post('/update-user-status',[AdminController::class, 'update_user_status']);

    Route::resource('/city', CityController::class);
    Route::resource('/role', RoleController::class);
    Route::resource('/permission', PermissionController::class);
    Route::resource('/department', DepartmentController::class);
    
    Route::resource('/builder', BuilderController::class);
    Route::resource('/buildings', BuildingController::class);
    Route::post('/update-building-status',[BuildingController::class, 'update_building_status']);
    Route::post('/store-building-user',[BuildingController::class, 'store_building_user']);
    Route::get('get-user-by-email',[BuildingController::class, 'get_user_by_email']);
    Route::post('/delete-building-user',[BuildingController::class, 'delete_building_user']);
      Route::post('search-user-for-ba',[BuildingController::class, 'search_user_for_ba'])->name('search-user-for-ba');
    Route::post('/building-configration',[BuildingController::class, 'building_configration']);
    Route::post('/building-payment-options',[BuildingController::class, 'building_payment_options']);
    Route::post('/building-classified-limit',[BuildingController::class, 'building_classified_limit']);
    
    Route::resource('/event', EventController::class);
    Route::resource('/noticeboard', NoticeboardController::class);
    Route::resource('/classified', ClassifiedController::class);
    Route::get('/admin/classifieds/{id}/buildings', [ClassifiedController::class, 'getBuildings']);
    Route::resource('/ads', AdController::class);
    Route::post('/update-event-status',[AdController::class, 'update_event_status']);
    
    Route::resource('/facility', FacilityController::class);

	Route::resource('/notification', NotificationController::class);
    Route::resource('/setting',SettingController::class);
    Route::get('/taxes',[SettingController::class, 'taxes']);
    Route::post('/update-taxes',[SettingController::class, 'update_taxes']);
    Route::get('/privacy-policy',[SettingController::class, 'privacy_policy']);
    Route::post('/update-privacy-policy',[SettingController::class, 'update_privacy_policy']);
    Route::get('/terms-conditions',[SettingController::class, 'terms_conditions']);
    Route::post('/update-terms-conditions',[SettingController::class, 'update_terms_conditions']);
    Route::get('/about-us',[SettingController::class, 'about_us']);
    Route::post('/update-about-us',[SettingController::class, 'update_about_us']);
    Route::get('/how-it-works',[SettingController::class, 'how_it_works']);
    Route::post('/update-how-it-works',[SettingController::class, 'update_how_it_works']);
    Route::get('/return-and-refund-policy',[SettingController::class, 'return_and_refund_policy']);
    Route::post('/update-return-and-refund-policy',[SettingController::class, 'update_return_and_refund_policy']);
    Route::get('/accidental-policy',[SettingController::class, 'accidental_policy']);
    Route::post('/update-accidental-policy',[SettingController::class, 'update_accidental_policy']);
    Route::get('/cancellation-policy',[SettingController::class, 'cancellation_policy']);
    Route::post('/update-cancellation-policy',[SettingController::class, 'update_cancellation_policy']);
    Route::get('/delete-account-policy',[SettingController::class, 'delete_account_policy']);
    Route::post('/update-delete-account-policy',[SettingController::class, 'update_delete_account_policy']);
    Route::get('/faqs',[SettingController::class, 'faqs']);
    Route::post('/update-faqs',[SettingController::class, 'update_faqs']);
    
	Route::post('/logout',[AdminController::class, 'logout']);
});