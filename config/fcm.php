<?php

return [

    /*
    |--------------------------------------------------------------------------
    | FCM HTTP v1 Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Firebase Cloud Messaging HTTP v1 API
    |
    */

    'project_id' => env('FCM_PROJECT_ID'),
    
    'service_account' => env('FCM_SERVICE_ACCOUNT_JSON'),
    
    // Alternative: path to service account JSON file
    'service_account_path' => env('FCM_SERVICE_ACCOUNT_PATH'),

];
