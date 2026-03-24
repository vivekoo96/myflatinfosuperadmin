<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\Notification as DatabaseNotification;
use App\Helpers\NotificationHelper2;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::all();
        $buildings = \App\Models\Building::orderBy('name')->get();
        return view('admin.ads.index', compact('ads', 'buildings'));
    }
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Validate time duration
        if ($request->from_time && $request->to_time) {
            if (strtotime($request->from_time) >= strtotime($request->to_time)) {
                return redirect()->back()->with('error', 'From Time must be before To Time.');
            }
        }

        $rules = [
            'name' => 'required|min:2|unique:ads,name,' . ($request->id ?? 'NULL') . ',id',
            'image' => 'nullable|image|max:2048',
            'link' => 'required|url',
            'status' => 'required|in:Active,Inactive',
            'notification_type' => 'required|in:all,selected',
            'selected_buildings' => 'required_if:notification_type,selected|array'
        ];

        $msg = 'Ad added Susccessfully';
        $ad = new Ad();

        if ($request->id) {
            $ad = Ad::find($request->id);
            $msg = 'Ad updated Susccessfully';
        }

    $validation = Validator::make($request->all(), $rules);

        // Custom image format validation
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, ['jpg', 'jpeg','png'])) {
                return redirect()->back()->with('error', 'The image format must be an JPG, JPEG , PNG');
            }
        }

        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first());
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $allowedfileExtension = ['jpeg', 'jpg', 'png'];
            $extension = $file->getClientOriginalExtension();
            if (!empty($ad->photo_filename)) {
                $file_path = public_path('images/' . $ad->image_filename);
                if (is_file($file_path)) {
                    unlink($file_path);
                }
            }
            $filename = 'ads/' . uniqid() . '.' . $extension;
            $path = $file->move(public_path('/images/ads/'), $filename);
            $ad->image = $filename;
        }

        // Prevent duplicate ads (by name and image filename)
        $imageForCheck = $ad->image ?? null;
        $duplicateAd = Ad::where('name', $request->name)
            ->where('image', $imageForCheck)
            ->first();
        if (!$request->id && $duplicateAd) {
            return redirect()->back()->with('error', 'Duplicate ad is not allowed.');
        }

        $user = Auth::User();
        $ad->user_id = $user->id;
        $ad->name = $request->name;
        $ad->link = $request->link;
        $ad->status = $request->status;
        $ad->notification_type = $request->notification_type;
        $ad->from_time = $request->from_time;
        $ad->to_time = $request->to_time;
        $ad->save();

        // Handle building selection
        try {
            \DB::beginTransaction();
            
            if ($request->notification_type === 'selected' && $request->has('selected_buildings')) {
                \Log::info('Syncing selected buildings for ad:', [
                    'ad_id' => $ad->id,
                    'selected_buildings' => $request->selected_buildings
                ]);
                $ad->buildings()->sync($request->selected_buildings);
            } else {
                // For 'all' type, sync all building IDs
                $allBuildingIds = \App\Models\Building::pluck('id')->toArray();
                \Log::info('Syncing all buildings for ad:', [
                    'ad_id' => $ad->id,
                    'all_buildings' => $allBuildingIds
                ]);
                $ad->buildings()->sync($allBuildingIds);
            }
            
            \DB::commit();
            \Log::info('Buildings synced successfully for ad_id: ' . $ad->id);
            
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error syncing buildings for ad:', [
                'error' => $e->getMessage(),
                'ad_id' => $ad->id
            ]);
            return redirect()->back()->with('error', 'Error saving building selections: ' . $e->getMessage());
        }

        // Notifications are now handled by scheduler at the ad's `from_time`.
        // We intentionally do not send notifications here to avoid duplicate sends.

        return redirect()->back()->with('success', $msg);
        // Validate time duration
        if ($request->from_time && $request->to_time) {
            if (strtotime($request->from_time) >= strtotime($request->to_time)) {
                return redirect()->back()->with('error', 'From Time must be before To Time.');
            }
        }
    
    }
    public function show($id)
    {
        $ad = Ad::where('id',$id)->withTrashed()->first();
        if(!$ad){
            return redirect()->route('ads.index');
        }
        return view('admin.ads.show',compact('ad'));
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
        $ad = Ad::with('buildings')->findOrFail($id);
        return response()->json([
            'notification_type' => $ad->notification_type,
            'buildings' => $ad->buildings->pluck('id')->toArray()
        ]);
    }

    public function destroy($id, Request $request)
    {
        $ad = Ad::where('id',$id)->first();
        
        if (!$ad) {
            return response()->json([
                'msg' => 'Ad not found'
            ], 404);
        }
        
        try {
            \DB::beginTransaction();
            
            // Delete related ad_building data
            \DB::table('ad_buildings')->where('ad_id', $id)->delete();
            
            // Delete image from storage
            Storage::disk('s3')->delete($ad->getImageFilenameAttribute());
            
            // Delete the ad
            $ad->delete();
            
            \DB::commit();
            
            return response()->json([
                'msg' => 'success'
            ], 200);
            
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error deleting ad:', [
                'ad_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'msg' => 'Error deleting ad'
            ], 500);
        }
    }
    
    public function update_ad_status(Request $request)
    {
        $ad = Ad::where('id',$request->id)->withTrashed()->first();
        if($ad->status == 'Active'){
            $ad->status = 'Inactive';
        }else{
            $ad->status = 'Active';
        }
        $ad->save();
        return response()->json([
            'msg' => 'success'
        ],200);
    }
}
