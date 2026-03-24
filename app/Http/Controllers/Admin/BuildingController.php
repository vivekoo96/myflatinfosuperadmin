<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\User;
use App\Models\BuildingUser;
use App\Models\Facility;
use App\Models\BuildingPermission;
use App\Models\City;
use App\Models\Setting;
use Illuminate\Support\Str;
use \DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use \Hash;
use \Auth;
use Mail;

class BuildingController extends Controller
{

    public function index()
    {
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('buildings')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $buildings = Building::orderBy('id','asc')->withTrashed()->get();
        $cities = City::all();
        return view('admin.building.index',compact('buildings','cities'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $msg = 'Building added Susccessfully';
        $building = new Building();
        $user_id = null;
        if ($request->id) {
            $building = Building::withTrashed()->find($request->id);
            $user_id = $building->user->id ?? null;
            $msg = 'Building updated Susccessfully';
        }
       
// Base rules
$rules = [
    'builder_id' => 'required|exists:builders,id',
    'name' => [
        'required',
        'string',
        'min:2',
        'max:80',
        'regex:/^[A-Za-z\s]+$/',
    ],
    'city' => 'required|string|max:50',
    'address' => 'required|string|min:4|max:100',
    'status' => 'required|in:Active,Inactive',
    'ba_option' => 'required|in:existing,promote,new',
];

// Add conditional rules based on BA option
if ($request->ba_option === 'existing') {
    $rules['existing_ba'] = 'required|exists:users,id';
} elseif ($request->ba_option === 'promote') {
    $rules['promote_user_id'] = 'required|exists:users,id';
} elseif ($request->ba_option === 'new') {
    $rules['first_name'] = [
        'required',
        'string',
        'min:2',
        'max:40',
        'regex:/^[A-Za-z\s]+$/',
    ];
    $rules['last_name'] = [
        'required',
        'string',
        'min:2',
        'max:40',
        'regex:/^[A-Za-z\s]+$/',
    ];
    $rules['ba_phone'] = [
        'required',
        'regex:/^[6-9]\d{9}$/',
        Rule::unique('users', 'phone')->ignore($user_id),
    ];
    $rules['email'] = [
        'required',
        'email',
        'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}$/',
        Rule::unique('users', 'email')->ignore($user_id),
    ];
    $rules['password'] = 'required|min:6';
}

$messages = [
    'first_name.required' => 'First name is required.',
    'first_name.min' => 'First name must be at least 2 characters.',
    'first_name.max' => 'First name may not be greater than 40 characters.',
    'first_name.regex' => 'First name should contain only alphabets and spaces.',

    'last_name.required' => 'Last name is required.',
    'last_name.min' => 'Last name must be at least 2 characters.',
    'last_name.max' => 'Last name may not be greater than 40 characters.',
    'last_name.regex' => 'Last name should contain only alphabets and spaces.',

    'name.required' => 'Full name is required.',
    'name.min' => 'Full name must be at least 2 characters.',
    'name.max' => 'Full name may not be greater than 80 characters.',
    'name.regex' => 'Full name should contain only alphabets and spaces.',

    'ba_phone.required' => 'BA Phone number is required.',
    'ba_phone.regex' => 'Please enter a valid 10-digit Indian mobile number starting from 6 to 9.',
    
    'ba_option.required' => 'Please select Building Admin option.',
    'existing_ba.required' => 'Please select an existing Building Admin.',

    'email.required' => 'Email is required.',
    'email.email' => 'Please enter a valid email address.',
    'email.unique' => 'This email is already registered.',

    'city.required' => 'City is required.',

    'address.required' => 'Address is required.',
    'address.min' => 'Address must be at least 4 characters.',
    'address.max' => 'Address may not be greater than 100 characters.',

    'status.required' => 'Status is required.',
    'status.in' => 'Status must be Active or Inactive.',
];

$validation = \Validator::make($request->all(), $rules, $messages);
    
        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first());
        }
        
        // Handle BA assignment based on option
        if ($request->ba_option === 'existing') {
            // Use existing BA
            $user = User::find($request->existing_ba);
        } elseif ($request->ba_option === 'promote') {
            // Promote existing user to BA
            $user = User::find($request->promote_user_id);
            $user->role = 'BA';
            $user->status = 'Active';
            $user->save();
        } else {
            // Create new BA
            $user = new User();
            if($user_id){
                $user = User::where('id',$user_id)->withTrashed()->first();
                $user->restore();
            }
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->ba_phone;
            $user->city_id = $request->city;
            $user->address = $request->address;
            $user->created_by = Null;
            $user->status = 'Active';
            $user->role = 'BA';
            if($request->password){
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }

        $building->builder_id = $request->builder_id;
        $building->name = $request->name;
        $building->phone = $request->building_phone;
        $building->responsible_person = $request->responsible_person;
        $building->address = $request->address;
        $building->city_id = $request->city;
        $building->status = $request->status;
        if(!$request->id){
            $building->licence_key = Str::random(64);
            $building->no_of_flats = 0;
            $building->no_of_logins = 0;
        }
        $building->user_id = $user->id;
        $building->save();

        if($request->password){
            $setting = Setting::first();
            $logo = $setting->logo;
            $info = array(
                'user' => $user,
                'password' => $request->password,
                'logo' => $logo,
                'building' => $building
            );
            // send email
            try {
                Mail::send('email.password', $info, function ($message) use ($user) {
                    $message->to($user->email, $user->name)
                            ->subject('Building Credentials');
                });
            } catch (\Exception $e) {
                $msg = $e->getMessage();
                // return response()->json([
                //     'error' => 'Failed to queue email. ' . $e->getMessage()
                // ], 500);
            }
        }
        
        return redirect()->back()->with('success', $msg);
    }

    public function show($id)
    {
        $building = Building::where('id',$id)->withTrashed()->first();
        if(!$building){
            return redirect()->route('buildings.index');
        }
        return view('admin.building.show',compact('building'));
    }
    
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id, Request $request)
    {
        $building = Building::where('id',$id)->withTrashed()->first();
        if($request->action == 'delete'){
            $building->status = 'Inactive';
            $building->save();
            $building->delete();
        }else{
            $building->status = 'Active';
            $building->save();
            $building->restore();
        }
        return response()->json([
            'msg' => 'success'
        ],200);
    }
    
    public function update_building_status(Request $request)
    {
        $building = Building::where('id',$request->id)->withTrashed()->first();
        if($building->status == 'Active'){
            $building->status = 'Inactive';
        }else{
            $building->status = 'Active';
        }
        $building->save();
        return response()->json([
            'msg' => 'success'
        ],200);
    }
    
    public function store_building_user(Request $request)
    {
        $msg = $request->id ? 'User Updated' : 'User Added';
        $rules = [
            'user_id' => 'required|exists:users,id',
            'building_id' => 'required|exists:buildings,id',
            'block' => 'required',
            'flat_no' => 'required',
        ];

    
        // Validate the request
        $validation = Validator::make($request->all(), $rules);
    
        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first());
        }
        $building_user = new BuildingUser();
    
        if ($request->id) {
            $building_user = BuildingUser::withTrashed()->find($request->id);
        }
        $building_user->user_id = $request->user_id;
        $building_user->building_id = $request->building_id;
        $building_user->block = $request->block;
        $building_user->flat_no = $request->flat_no;
        $building_user->save();
        
        
        return redirect()->back()->with('success', $msg);
    }
    
    public function get_user_by_email(Request $request) {
        $user = User::where('email', $request->email)->where('status','Active')->first();
        if ($user) {
            return response()->json(['success' => true, 'data' => ['id' => $user->id,'name' => $user->name]]);
        }
        return response()->json(['success' => false, 'message' => 'User not found']);
    }
    
    public function delete_building_user(Request $request)
    {
        $building_user = BuildingUser::where('id',$request->id)->withTrashed()->first();
        if($request->action == 'delete'){
            $building_user->delete();
        }else{
            $building_user->restore();
        }
        return response()->json([
            'msg' => 'success'
        ],200);
    }

    public function building_configration(Request $request)
    {
        $building = Building::where('id',$request->id)->withTrashed()->first();
        $building->no_of_flats = $request->no_of_flats;
        $building->no_of_logins = $request->no_of_logins;
        $building->no_of_other_users = $request->no_of_other_users;
        $building->valid_till = $request->valid_till;
        $building->save();
        
        // Handle permissions
        $newPermissionIds = $request->permissions ?? [];

        // Get all permissions for this building (including soft deleted ones)
        $existingPermissions = BuildingPermission::where('building_id', $building->id)->get();

        $existingPermissionIds = $existingPermissions->pluck('permission_id')->toArray();

        // Add new ones if not already added
        foreach ($newPermissionIds as $permissionId) {
            if (!in_array($permissionId, $existingPermissionIds)) {
                BuildingPermission::create([
                    'building_id' => $building->id,
                    'permission_id' => $permissionId,
                ]);
            }
        }

        // Delete unselected ones
        $permissionsToDelete = array_diff($existingPermissionIds, $newPermissionIds);
        if (!empty($permissionsToDelete)) {
            BuildingPermission::where('building_id', $building->id)
                ->whereIn('permission_id', $permissionsToDelete)
                ->delete();
        }

        return redirect()->back()->with('success', 'Building configration updated');
    }

    public function building_payment_options(Request $request)
    {
        $building = Building::where('id',$request->id)->withTrashed()->first();
        $building->payment_is_active = $request->payment_is_active;
        $building->maintenance_is_active = $request->maintenance_is_active;
        $building->corpus_is_active = $request->corpus_is_active;
        $building->donation_is_active = $request->donation_is_active;
        $building->facility_is_active = $request->facility_is_active;
        $building->other_is_active = $request->other_is_active;
        
        if($request->payment_is_active == 'No'){
            $setting = Setting::First();
            $building->razorpay_key = $setting->razorpay_key;
            $building->razorpay_secret = $setting->razorpay_secret;
        }else{
            $building->razorpay_key = '';
            $building->razorpay_secret = '';
        }
        $building->save();

        return redirect()->back()->with('success', 'Building configration updated');
    }

    public function building_classified_limit(Request $request)
    {
        $building = Building::where('id',$request->id)->withTrashed()->first();
        $building->classified_limit_within_building = $request->classified_limit_within_building;
        $building->classified_limit_all_building = $request->classified_limit_all_building;
        $building->within_for_month = $request->within_for_month;
        $building->all_for_month = $request->all_for_month;
        $building->save();

        return redirect()->back()->with('success', 'Building classified limit updated');
    }
    
     public function search_user_for_ba(Request $request)
    {
        try {
            $email = $request->email;
            
            // Search for user by email
            $user = User::where('email', $email)
                       ->where('role', '!=', 'BA') // Exclude existing BAs
                       ->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or user is already a Building Admin.'
                ]);
            }
            
            // Get user's current buildings
            $currentBuildings = [];
            $userBuildings = $user->buildings; // This uses the buildings relationship from User model
            
            foreach ($userBuildings as $building) {
                $currentBuildings[] = [
                    'id' => $building->id,
                    'name' => $building->name,
                    'builder_name' => $building->builder ? $building->builder->name : 'N/A'
                ];
            }
            
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'photo' => $user->photo,
                'current_buildings' => $currentBuildings
            ];
            
            return response()->json([
                'success' => true,
                'user' => $userData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while searching for user.'
            ]);
        }
    }
}
