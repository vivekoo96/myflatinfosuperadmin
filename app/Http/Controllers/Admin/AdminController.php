<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use App\Models\City;
use App\Models\Permission;
use App\Models\UserPermission;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use \Hash;
use \Auth;
use \Response;
use \Mail;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    
    public function clear_database(Request $request)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('ads')->truncate();
        DB::table('blocks')->truncate();
        DB::table('bookings')->truncate();
        DB::table('builders')->truncate();
        DB::table('buildings')->truncate();
        DB::table('building_permissions')->truncate();
        DB::table('building_users')->truncate();
        DB::table('cities')->truncate();
        DB::table('classifieds')->truncate();
        DB::table('classified_photos')->truncate();
        DB::table('comments')->truncate();
        DB::table('essentials')->truncate();
        DB::table('essential_payments')->truncate();
        DB::table('events')->truncate();
        DB::table('expenses')->truncate();
        DB::table('facilities')->truncate();
        DB::table('family_members')->truncate();
        DB::table('flats')->truncate();
        DB::table('flat_parkings')->truncate();
        DB::table('gates')->truncate();
        DB::table('gate_passes')->truncate();
        DB::table('guards')->truncate();
        DB::table('issues')->truncate();
        DB::table('issue_photos')->truncate();
        DB::table('maintenances')->truncate();
        DB::table('maintenance_payments')->truncate();
        DB::table('migrations')->truncate();
        DB::table('noticeboards')->truncate();
        DB::table('notifications')->truncate();
        DB::table('orders')->truncate();
        DB::table('parcels')->truncate();
        DB::table('parkings')->truncate();
        DB::table('password_resets')->truncate();
        DB::table('payments')->truncate();
        // DB::table('permissions')->truncate();
        DB::table('replies')->truncate();
        // DB::table('roles')->truncate();
        // DB::table('role_permissions')->truncate();
        DB::table('timings')->truncate();
        DB::table('transactions')->truncate();
        DB::table('users')->truncate();
        DB::table('user_permissions')->truncate();
        DB::table('vehicles')->truncate();
        DB::table('vehicle_inouts')->truncate();
        DB::table('visitors')->truncate();
        DB::table('visitor_inouts')->truncate();

        $user = new User();
        $user->first_name = 'Super';
        $user->last_name = 'Admin';
        $user->email = 'admin@admin.com';
        $user->phone = '9162035975';
        $user->gender = 'Male';
        $user->address = 'Hello There';
        $user->role = 'super-admin';
        $user->password = Hash::make('Admin@123');
        $user->status = 'Active';
        $user->save();
    }
    
    public function index()
    {

        // DB::statement("ALTER TABLE vehicles ADD COLUMN photo VARCHAR(191) NULL");
        // DB::statement('TRUNCATE TABLE vehicles');
        // DB::statement('TRUNCATE TABLE vehicle_inouts');
        // DB::statement('MODIFY TABLE vehicle_inouts');
        // $user = new User();
        // $user->first_name = 'Super';
        // $user->last_name = 'Admin';
        // $user->email = 'admin@admin.com';
        // $user->phone = '9162035975';
        // $user->gender = 'Male';
        // $user->address = 'Hello There';
        // $user->role = 'super-admin';
        // $user->password = Hash::make('Admin@123');
        // $user->status = 'Active';
        // $user->save();
        
        $setting = Setting::first();
        return view('admin.login',compact('setting'));
    }
    
    public function save_token(Request $request)
    {
        $user = Auth::User();
        $user->device_token = $request->device_token;
        $user->save();
        return response()->json([
            'msg' => 'success'
        ],200);
    }

    public function login(Request $request)
    {
        
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::whereIn('role',['super-admin','admin'])->where('email', $request->email)->first();
        if($user){
            if($user->role == 'admin' && $user->status == 'Inactive'){
                return redirect()->back()->with('error','Your account is inactive please contact super admin');
            }
            // $user->password = Hash::make($request->password);
            // $user->save();
            if(Hash::check($request->password,$user->password)){
                Auth::login($user, true);
                $user->device_token = $request->device_token;
                $user->save();
                return redirect('/dashboard');
            }
        }
        return redirect()->back()->with('error','Invalid Email or Password');
    }

    public function verifyotp(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'otp' => 'required|digits:4|numeric',
        ]);
        $user = User::where('phone', $request->mobile)->where('role','admin')->first();
        if(!$user){
            return response()->json(['status' => 2, 'error' => 'This number is not registered with us']);
        }
        if(Hash::check($request->otp,$user->otp)){
            Auth::login($user, true);
            $user->device_token = $request->device_token;
            $user->save();
            return response()->json(['status' => 1, 'success' => 'You have Logged in Successfully']);
        }
        return response()->json(['status' => 2, 'error' => 'Invalid Otp']);
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
    
    public function profile()
    {
        $customer = Auth::User();
        return view('admin.profile',compact('customer'));
    }
    
   public function update_profile(Request $request)
{
    $user = Auth::User();

    $rules = [
        'first_name' => 'required|string|min:2|max:30',
        'last_name' => 'required|string|min:2|max:30',
        'email' => [
            'required',
            'email',
            'max:255',
            // 'regex:/^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}$/',
            'regex:/^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}$/',
            Rule::unique('users', 'email')->ignore($user->id),
        ],
        'phone' => [
            'required',
            'regex:/^(?!([0-9])\1{9})[6-9][0-9]{9}$/',
            'min:10',
            'max:10',
            Rule::unique('users', 'phone')->ignore($user->id),
        ],
        'gender' => 'required|in:Male,Female,Others',
       'address' => [
    'required',
    'string',
    'min:4',
    'max:100',
    'regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s,\.\-\/\#\(\)\'\~\!\@\$\%]*$/',
],
        'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
    ];

    $messages = [
        // First Name
        'first_name.required' => 'First name is required.',
        'first_name.string' => 'First name must be a valid text.',
        'first_name.min' => 'First name must be at least 2 characters long.',
        'first_name.max' => 'First name cannot exceed 30 characters.',

        // Last Name
        'last_name.required' => 'Last name is required.',
        'last_name.string' => 'Last name must be a valid text.',
        'last_name.min' => 'Last name must be at least 2 characters long.',
        'last_name.max' => 'Last name cannot exceed 30 characters.',

        // Email
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.max' => 'Email should not exceed 255 characters.',
        'email.regex' => 'Email must start with a letter and follow a valid format.',
        'email.unique' => 'This email is already registered.',

        // Phone
        'phone.required' => 'Phone number is required.',
        'phone.regex' => 'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.',
        'phone.min' => 'Phone number must be exactly 10 digits.',
        'phone.max' => 'Phone number must be exactly 10 digits.',
        'phone.unique' => 'This phone number is already registered.',

        // Gender
        'gender.required' => 'Please select your gender.',
        'gender.in' => 'Invalid gender selected. Choose Male, Female, or Others.',

        // Address
        'address.required' => 'Address is required.',
        'address.string' => 'Address must be valid text.',
        'address.min' => 'Address must be at least 4 characters long.',
        'address.max' => 'Address cannot exceed 100 characters.',
        'address.regex' => 'Address must include both letters and numbers and only allowed characters.',

        // Photo
         'photo.image' => 'Invalid format. Only image files are accepted for this field',
            'photo.mimes' => 'Invalid format. Only image files are accepted for this field',
            'photo.max' => 'File size should not exceed 2MB',
    ];

    $validation = \Validator::make($request->all(), $rules, $messages);

    if ($validation->fails()) {
        return redirect()->back()->withErrors($validation)->withInput();
    }

    $customer = Auth::User();

    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $allowedfileExtension = ['jpeg', 'jpg', 'png'];
        $extension = $file->getClientOriginalExtension();

        if (!empty($customer->photo_filename)) {
            $file_path = public_path('images/' . $customer->photo_filename);
            if (is_file($file_path)) {
                unlink($file_path);
            }
        }

        $filename = 'profiles/' . uniqid() . '.' . $extension;
        $file->move(public_path('/images/profiles/'), $filename);
        $customer->photo = $filename;
    }

    $customer->first_name = $request->first_name;
    $customer->last_name = $request->last_name;
    $customer->email = $request->email;
    $customer->phone = $request->phone;
    $customer->gender = $request->gender;
    $customer->address = $request->address;
    $customer->save();

    return redirect()->back()->with('success', 'Profile updated successfully.');
}

    
    public function change_password(Request $request)
    {
        $request->validate([
                'current_password' => 'required',
                'password' =>[
                    'required',
                    'string',
                    'min:8',             // must be at least 8 characters in length
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit
                    'regex:/[@$!%*#?&]/', // must contain a special character
                    'confirmed',
                    function ($attribute, $value, $fail) {
                        if (Hash::check($value, Auth::user()->password)) {
                            $fail('The new password must not be the same as the current password.');
                        }
                    },
                ],
                'password_confirmation' => 'required',
            ], [
                'current_password.required' => 'Current password is required',
                'password.required' => 'New password is required',
                'password.min' => 'Password must be at least 8 characters',
                'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character',
                'password.confirmed' => 'Password confirmation does not match',
                'password_confirmation.required' => 'Password confirmation is required',
            ]);
            
        $user = Auth::User();
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            // Auth::logout();
            return redirect()->back()->with('success','Password updated successfully');
        }
        return redirect()->back()->with('error','Invalid current password');
    }
    
    public function update_user_status(Request $request)
    {
        $user = User::where('id',$request->id)->withTrashed()->first();
        if($user->status == 'Active'){
            $user->status = 'Inactive';
        }else{
            $user->status = 'Active';
        }
        $user->save();
        return response()->json([
            'msg' => 'success'
        ],200);
    }
    
    public function update_document_status(Request $request)
    {
        $user = User::where('id',$request->id)->withTrashed()->first();
        if($user->email_sent_document_status == 'Verified'){
            $user->email_sent_document_status = 'Pending';
        }else{
            $user->email_sent_document_status = 'Verified';
        }
        $user->save();
        return response()->json([
            'msg' => 'success'
        ],200);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
    
    public function admins()
    {
        // $setting = Setting::first();
        // $users = User::orderBy('id','desc')->withTrashed()->get();
        $users = User::where('role','admin')->orderBy('id','desc')->withTrashed()->get();

        // Fetch all permissions for super-admin guard
        $rawPermissions = Permission::where('guard', 'super-admin')->get();

        // Move customer-related permissions from 'User' group into 'Menu' group,
        // then drop the remaining 'User' group entirely.
        $normalizedPermissions = $rawPermissions->map(function ($permission) {
            if ($permission->group === 'User' && Str::contains(Str::lower($permission->name), 'customer')) {
                $permission->group = 'Menu';
            }
            return $permission;
        });

        $permissions = $normalizedPermissions
            ->groupBy('group')
            ->filter(function ($items, $group) {
                return $group !== 'User' && $group !== 'Profile';
            });
        $cities = City::all();
        return view('admin.admin.index',compact('users','cities','permissions'));
    }
    
    public function show_admin($id)
    {
        $customer = User::where('id',$id)->withTrashed()->first();
        if(!$customer){
            return redirect('/admin');
        }
        return view('admin.admin.show',compact('customer'));
    }
    
    public function building_admins()
    {
        // $setting = Setting::first();
        // $users = User::where('role','customer')->orderBy('id','desc')->withTrashed()->paginate($setting->pagination);
        $users = User::where('role','BA')->orderBy('id','desc')->withTrashed()->get();
        $cities = City::all();
        return view('admin.ba.index',compact('users','cities'));
    }
    
    public function show_building_admin($id)
    {
        $customer = User::where('id',$id)->withTrashed()->first();
        if(!$customer){
            return redirect('/building-admin');
        }
        $cities = City::all();
        return view('admin.ba.show',compact('customer','cities'));
    }

    public function customers()
    {
        // $setting = Setting::first();
        $users = User::where('role','test')->where('status','Inactive')->orderBy('id','desc')->withTrashed()->get();
        // $users = User::whereNotIN('role',['admin','super-admin'])->orderBy('id','desc')->withTrashed()->get();
        $cities = City::all();
        return view('admin.customers.index',compact('users','cities'));
    }

    public function show_customer($id)
    {
        $customer = User::where('id',$id)->where('role','customer')->withTrashed()->first();
        if(!$customer){
            return redirect('/customers');
        }
        $cities = City::all();
        return view('admin.customers.show',compact('customer','cities'));
    }

    public function store_user(Request $request)
    {
        
        // dd("|werfgesdgf");
        // If $request->id exists, fetch the user for update; otherwise, create a new instance.
        $user = $request->id ? User::find($request->id) : new User();
        $msg = $request->id ? ucfirst($request->role) . ' Updated' : ucfirst($request->role) . ' Added';
    
        // Define the validation rules
        $rules = [
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'required|min:2|max:30',
            'email' => [
                'required',
                'email',
                'max:255',
                'regex:/^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}$/',
                // 'regex:/^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}$/',
                Rule::unique('users', 'email')->ignore($user->id), 
            ],
            'phone' => [
                'required',
                'regex:/^(?!([0-9])\1{9})[6-9][0-9]{9}$/',
                'min:10',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'gender' => 'required|in:Male,Female,Others',
            'city_id' => 'required|exists:cities,id',
            // Address: allow letters, numbers, spaces, and common special characters
            'address' => [
                'required',
                'min:4',
                'max:500',
                // Must contain at least one letter and only allowed characters
                'regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s,\.\-\/\#\(\)\'\~\!\@\$\%]*$/',
            ],
            'password' =>[
                    'nullable',
                    'string',
                    'min:8',             // must be at least 10 characters in length
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit
                    'regex:/[@$!%*#?&]/', // must contain a special character
                ],
            'role' => 'required|in:customer,BA,admin,super-admin',
            'photo' => 'nullable|image|max:2048',
            
            
            
        ];
        
        $rules['permissions'] = $request->id
            ? 'array'           // For update
            : 'array|required';

    
        // Validate the request
        
       
        $validation = Validator::make($request->all(), $rules);
      
        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first());
        }
        //  dd($request->all());
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $allowedfileExtension = ['jpeg', 'jpg', 'png'];
            $extension = $file->getClientOriginalExtension();
            if (!empty($user->photo_filename)) {
                $file_path = public_path('images/' . $user->photo_filename);
            
                if (is_file($file_path)) {
                    unlink($file_path);
                }
            }

            $filename = 'profiles/' . uniqid() . '.' . $extension;
            $path = $file->move(public_path('/images/profiles/'), $filename);
            $user->photo = $filename;
        }
     
        // Assign user data
        if($request->role == "customer"){
            $user->role = 'test'; 
        }else{
             $user->role = $request->role;
        }
       
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->city_id = $request->city_id;
        $user->address = $request->address;
        $user->status = $request->status;
        // Only set a password if provided
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
    
        // Save the user
        $user->save();
       
        if ($request->has('permissions')) {
            $newPermissionIds = $request->permissions ?? [];
            if (!is_array($newPermissionIds)) {
                $newPermissionIds = [];
            }

            // Get all permissions for this user (including soft deleted ones)
            $existingPermissions = UserPermission::where('user_id', $user->id)->get();

            $existingPermissionIds = $existingPermissions->pluck('permission_id')->toArray();

            // Add new ones if not already added
            foreach ($newPermissionIds as $permissionId) {
                if (!in_array($permissionId, $existingPermissionIds)) {
                    UserPermission::create([
                        'user_id' => $user->id,
                        'permission_id' => $permissionId,
                    ]);
                }
            }

            // Delete unselected ones
            $permissionsToDelete = array_diff($existingPermissionIds, $newPermissionIds);
            if (!empty($permissionsToDelete)) {
                UserPermission::where('user_id', $user->id)
                    ->whereIn('permission_id', $permissionsToDelete)
                    ->delete();
            }
        }
    
        return redirect()->back()->with('success', $msg);
    }

    public function delete_user(Request $request)
    {
        $user = User::where('id',$request->id)->withTrashed()->first();
        if($request->action == 'delete'){
            $user->status = 'Inactive';
            $user->save();
            $user->delete();
        }else{
            $user->status = 'Active';
            $user->save();
            $user->restore();
        }
        return response()->json([
            'msg' => 'success'
        ],200);
    }

    
    public function withdraws()
    {
        $withdraws = Withdraw::all();
        return view('admin.withdraws',compact('withdraws'));
    }
    
    public function update_withdraw(Request $request)
    {
        $rules = [
            'withdraw_id' => 'required|exists:withdraws,id',
            'transfer_details' => 'required',
            'status' => 'required|in:Pending,Rejected,Success',
        ];
        
        $validation = \Validator::make( $request->all(), $rules );
        if( $validation->fails() ) {
            return redirect()->back()->with('error',$validation->errors()->first());
        }
        
        $withdraw = Withdraw::find($request->withdraw_id);
        $withdraw->transfer_details = $request->transfer_details;
        $withdraw->status = $request->status;
        $withdraw->save();
        return redirect()->back()->with('success','Withdraw request updated');
        
    }


}
