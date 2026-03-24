<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\User;
use App\Models\BuildingUser;
use App\Models\Classified;
use App\Models\ClassifiedPhoto;
use App\Models\Flat;
use App\Models\Setting;
use App\Models\ClassifiedBuilding;
use App\Helpers\NotificationHelper2;

use \Auth;


use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use DB;
use \Session;
use Mail;
use \Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\BadRequestException;

class ClassifiedController extends Controller
{
    public function __construct()
    {
        $rdata = Setting::findOrFail(1);
        $this->keyId = $rdata->razorpay_key;
        $this->keySecret = $rdata->razorpay_secret;
        $this->displayCurrency = 'INR';
        $this->api = new Api($this->keyId, $this->keySecret);
    }

    public function index()
    {
        $classifieds = Classified::where('building_id',0)->orderBy('id','asc')->withTrashed()->get();
        return view('admin.classified.index',compact('classifieds'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        Log::info('Classified store method called with data:', [
            'request_data' => $request->all()
        ]);
        
        $rules = [
            'title' => 'required',
            'desc' => 'required',
            'notification_type' => 'required|in:all,selected',
            'selected_buildings' => 'required_if:notification_type,selected|array',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ];
    
        $msg = 'Classified added Successfully';
        $classified = new Classified();
    
        if ($request->id) {
            $classified = Classified::withTrashed()->find($request->id);
            $msg = 'Classified updated Successfully';
        }
    
        $validation = \Validator::make($request->all(), $rules);
    
        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first());
        }
        // dd($request->all());
        
        $classified->building_id = 0;
        if($request->notification_type  == "all"){
            $classified->category = 'All Buildings';
        }else{
            $classified->category = 'Within Building';
        }
        
        $classified->user_id = Auth::User()->id;
        $classified->title = $request->title;
        $classified->desc = $request->desc;
        $classified->notification_type = $request->notification_type;
        $classified->block_id = 0;
        $classified->flat_id = 0;
        $classified->status = 'Approved';
        $classified->save();

        // Handle building selection
        try {
            DB::beginTransaction();
            
            if ($request->notification_type === 'selected' && !empty($request->selected_buildings)) {
                Log::info('Syncing selected buildings for classified:', [
                    'classified_id' => $classified->id,
                    'selected_buildings' => $request->selected_buildings
                ]);
                
                $classified->buildings()->sync($request->selected_buildings);
                
            } else {
                // For 'all' type, sync all building IDs
                $allBuildingIds = Building::pluck('id')->toArray();
                Log::info('Syncing all buildings for classified:', [
                    'classified_id' => $classified->id,
                    'all_buildings' => $allBuildingIds
                ]);
                
                $classified->buildings()->sync($allBuildingIds);
            }
            
            DB::commit();
            Log::info('Buildings synced successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error syncing buildings:', [
                'error' => $e->getMessage(),
                'classified_id' => $classified->id
            ]);
            return redirect()->back()->with('error', 'Error saving building selections: ' . $e->getMessage());
        }
        
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $allowedfileExtension = ['jpeg', 'jpg', 'png'];
                $extension = $file->getClientOriginalExtension();
                $filename = 'classifieds/' . uniqid() . '.' . $extension;
                $path = $file->move(public_path('/images/classifieds/'), $filename);

                $photo = new ClassifiedPhoto();
                $photo->classified_id = $classified->id;
                $photo->photo = $filename;
                $photo->save();
            }
        }
        
        $users = collect();
        
        // Get flats based on notification type
        if ($classified->notification_type === 'all') {
            $flats = Flat::all();
        } else {
            // Get flats only from selected buildings
            $buildingIds = $classified->buildings->pluck('id');
            $flats = Flat::whereIn('building_id', $buildingIds)->get();
        }
        

foreach ($flats as $flat) {
    if ($flat->living_status === 'Owner' && $flat->owner) {
        $users->push([
            'user' => $flat->owner,
            'flat_id' => $flat->id,
        ]);
    } elseif ($flat->tenant) {
        $users->push([
            'user' => $flat->tenant,
            'flat_id' => $flat->id,
        ]);
    }
}

        
        $title = '[' . $classified->user->name . '] has shared something';
$body  = 'Click to view';

$dataPayload = [
    'screen'   => 'Timeline',
    'params'   => json_encode([
        'ScreenTab'    => 'Classifieds',
        'classified_id' => $classified->id,
        'building_id'   => $classified->building_id,
    ]),
    'categoryId' => 'Classifieds',
    'channelId'  => 'Community',
    'type'       => 'classified',
];

$successCount = 0;
$failureCount = 0;
$totalDevices = 0;


        foreach ($users as $item) {
    $user   = $item['user'];
    $flatId = $item['flat_id'];

    if (!$user || !$user->id) {
        continue;
    }

    $result = NotificationHelper2::sendNotification(
        $user->id,
        $title,
        $body,
        $dataPayload,
        [
            'from_id'     => $user->id,
            'flat_id'     => $flatId, // ✅ now available
            'building_id' => $classified->building_id,
            'type'        => 'classified',
            'save_to_db'  => true,
        ],
        ['user']
    );

    if ($result['success']) {
        $successCount++;
        $totalDevices += $result['devices_notified'] ?? 0;
    } else {
        $failureCount++;
    }
}

        
        // foreach ($flats as $flat) {
        //     if ($flat->living_status == 'Owner') {
        //         $users->push($flat->owner);
        //     } elseif ($flat->tanent) {
        //         $users->push($flat->tanent);
        //     }
        // }
        
        // // Remove duplicates based on user ID
        // $users = $users->unique('id');
            
        //     // Get all user IDs
        //     $userIds = $users->pluck('id');
            
        //     // Get devices for all users with additional validation
        //     $devices = DB::table('user_devices')
        //         ->whereIn('user_id', $userIds)
        //         ->whereNotNull('fcm_token')
        //         ->where('is_active', 1)
        //         ->where('fcm_token', '!=', '')
        //         ->where('fcm_token', '!=', 'null')
        //         ->where('fcm_token', '!=', 'undefined')
        //         ->select('fcm_token', 'device_type')
        //         ->get();


        //     if (!empty($devices)) {
        //         try {
        //             $title = '['.$classified->user->name.'] has shared something';
        //             $body = 'Click to view';
                    
        //             // Prepare data payload for NotificationHelper2
        //             $dataPayload = [
        //                 'screen' => 'Timeline',
        //                 'params' => json_encode([
        //                     'ScreenTab' => 'Classifieds', 
        //                     'classified_id' => $classified->id, 
        //                     'building_id' => $classified->building_id
        //                 ]),
        //                 'categoryId' => 'Classifieds',
        //                 'channelId' => 'Community',
        //                 'type' => 'classified'
        //             ];

        //             // Send notifications to each user using NotificationHelper2
        //             $successCount = 0;
        //             $failureCount = 0;
        //             $totalDevices = 0;

        //             foreach ($users as $user) {
        //                   if (!$user || !$user->id) {
        //                     continue;
        //                 }
        //                 $result = NotificationHelper2::sendNotification(
        //                     $user->id,
        //                     $title,
        //                     $body,
        //                     $dataPayload,
        //                     // $options,
        //                     [
        //                         'from_id' => $user->id,
        //                         // 'flat_id' => flat_id, i am unable no get flat so i want to loop the flat
        //                         'building_id' => $classified->building_id,
        //                         'type' => 'classified',
        //                         'save_to_db' => true
        //                     ],
        //                     ['user']
        //                 );

        //                 if ($result['success']) {
        //                     $successCount++;
        //                     $totalDevices += $result['devices_notified'] ?? 0;
        //                 } else {
        //                     $failureCount++;
        //                 }
        //             }

        //             Log::info('Classified notifications sent using NotificationHelper2', [
        //                 'success_count' => $successCount,
        //                 'failure_count' => $failureCount,
        //                 'total_devices' => $totalDevices,
        //                 'classified_id' => $classified->id,
        //                 'user_count' => $users->count()
        //             ]);

        //         } catch (\Exception $e) {
        //             Log::error('Error sending classified notifications', [
        //                 'error' => $e->getMessage(),
        //                 'classified_id' => $classified->id,
        //                 'trace' => $e->getTraceAsString()
        //             ]);
        //         }
        //     }
            
            return redirect()->back()->with('success', $msg);
    }
    

    public function show($id)
    {
        
        $classified = Classified::where('id',$id)->withTrashed()->first();
        if(!$classified){
            return redirect()->route('classified.index');
        }
        
        // Get buildings associated with this classified
        $targetBuildings = collect();
        
        // Always check classified_buildings table first (more reliable)
        $buildingIds = ClassifiedBuilding::where('classified_id', $classified->id)
            ->pluck('building_id');
            
        if ($buildingIds->count() > 0) {
            // Get buildings from classified_buildings table
            $targetBuildings = Building::whereIn('id', $buildingIds)->get();
        } else {
            // Fallback: Get single building from classified record
            if ($classified->building_id && $classified->building_id != 0) {
                $targetBuildings = Building::where('id', $classified->building_id)->get();
            }
        }
        
        return view('admin.classified.show', compact('classified', 'targetBuildings'));
    }
    
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function getBuildings($id)
    {
        $classified = Classified::with('buildings')->findOrFail($id);
        return response()->json([
            'notification_type' => $classified->notification_type,
            'buildings' => $classified->buildings->pluck('id')
        ]);
    }

    public function destroy($id, Request $request)
    {
        $classified = Classified::where('id',$id)->withTrashed()->first();
        if($request->action == 'delete'){
            $classified->delete();
        }else{
            $classified->restore();
        }
        return response()->json([
            'msg' => 'success'
        ],200);
    }
}
