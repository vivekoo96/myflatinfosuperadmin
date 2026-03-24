<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{

    public function index()
    {
        $setting = Setting::first();
        return view('admin.settings.index',compact('setting'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rules = [
            'bussiness_name' => 'required',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:2048',
            'pagination' => 'required|int|min:10'
            
        ];
        $validation = \Validator::make($request->all(), $rules);
    
        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first());
        }
        
        $setting = Setting::first();
        if(!$setting){
            $setting = new Setting();
        }
        $oldKey = $setting->getOriginal('razorpay_key');
        $oldSecret = $setting->getOriginal('razorpay_secret');
        
        $setting->razorpay_key = $request->razorpay_key;
        $setting->razorpay_secret = $request->razorpay_secret;



        $setting->bussiness_name = $request->bussiness_name;
        $setting->msg91_key = $request->msg91_key;
        $setting->msg91_sender = $request->msg91_sender;
        $setting->msg91_flow_id = $request->msg91_flow_id;
        // $setting->razorpay_key = $request->razorpay_key;
        // $setting->razorpay_secret = $request->razorpay_secret;
        $setting->fcm_key = $request->fcm_key;
        $setting->google_map_api_key = $request->google_map_api_key;

        $setting->call_support_number = $request->call_support_number;
        $setting->whatsapp_support_number = $request->whatsapp_support_number;
        $setting->pagination = $request->pagination;
        // if($request->hasFile('logo')) {
        //     $file= $request->file('logo');
        //     $allowedfileExtension=['jpeg','jpeg','png'];
        //     $extension = $file->getClientOriginalExtension();
        //     Storage::disk('s3')->delete($setting->getLogoFilenameAttribute());
        //     $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //     $filename = 'bussiness/' . uniqid() . '.' . $extension;
        //     Storage::disk('s3')->put($filename, file_get_contents($file));
        //     $setting->logo = $filename;
        // }
        
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $allowedfileExtension = ['jpeg', 'jpg', 'png'];
            $extension = $file->getClientOriginalExtension();
            $file_path = public_path('/images/'.$setting->logo_filename);
            if(file_exists($file_path) && $setting->logo != '')
            {
                unlink($file_path);
            }
            $filename = 'business/' . uniqid() . '.' . $extension;
            $path = $file->move(public_path('/images/business/'), $filename);
            $setting->logo = $filename;
        }


        // if($request->hasFile('favicon')) {
        //     $file= $request->file('favicon');
        //     $allowedfileExtension=['jpeg','jpeg','png'];
        //     $extension = $file->getClientOriginalExtension();
        //     Storage::disk('s3')->delete($setting->getFaviconFilenameAttribute());
        //     $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //     $filename = 'bussiness/' . uniqid() . '.' . $extension;
        //     Storage::disk('s3')->put($filename, file_get_contents($file));
        //     $setting->favicon = $filename;
        // }
        
        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $allowedfileExtension = ['jpeg', 'jpg', 'png'];
            $extension = $file->getClientOriginalExtension();
            $file_path = public_path('/images/'.$setting->favicon_filename);
            if(file_exists($file_path) && $setting->favicon != '')
            {
                unlink($file_path);
            }
            $filename = 'business/' . uniqid() . '.' . $extension;
            $path = $file->move(public_path('/images/business/'), $filename);
            $setting->favicon = $filename;
        }
        
        
        $setting->save();
        
        if($oldKey != $setting->razorpay_key || $oldSecret != $setting->razorpay_secret){

                DB::table('buildings')
                    ->where('payment_is_active', 'No') // or 0 if boolean
                    ->update([
                        'razorpay_key' => $setting->razorpay_key,
                        'razorpay_secret' => $setting->razorpay_secret
                    ]);
            }
        return redirect()->back()->with('success','Setting saved');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
    
    public function add()
    {
        $setting = Setting::select('add_type','add_url')->first();
        return view('admin.settings.add',compact('setting'));
    }
    
    public function update_add(Request $request)
    {
        $rules = [
            'add_type' => 'required|in:Image,Video',
            'add_url' => 'nullable|mimes:jpeg,png,jpg,mp4,mov,avi|max:40960', // Max 40MB
        ];
    
        // Validate the request
        $validation = Validator::make($request->all(), $rules);
    
        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first());
        }
    
        // Retrieve the current settings
        $setting = Setting::first(); // Assuming you have a `Setting` model
    
        // Update add_type
        $setting->add_type = $request->add_type;
    
        // Handle file upload
        if ($request->hasFile('add_url')) {
            $file = $request->file('add_url');
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $extension;
            $path = public_path('uploads/adds'); // Ensure this directory exists
    
            // Delete the old file if it exists
            if (!empty($setting->add_url) && file_exists(public_path($setting->add_url))) {
                unlink(public_path($setting->add_url));
            }
    
            // Move the new file to the directory
            $file->move($path, $filename);
    
            // Save the new file path
            $setting->add_url = 'uploads/adds/' . $filename;
        }
    
        // Save the updated settings
        $setting->save();
    
        // Redirect with success message
        return redirect()->back()->with('success', 'Add settings updated successfully.');
    }

    
    public function taxes()
    {
        $setting = Setting::first();
        return view('admin.settings.taxes',compact('setting'));
    }
    
    public function update_taxes(Request $request)
    {
        $setting = Setting::first();
        $setting->cancellation_check_amount = $request->cancellation_check_amount;
        $setting->cgst = $request->cgst;
        $setting->sgst = $request->sgst;
        $setting->admin_charge = $request->admin_charge;
        $setting->save();
        return redirect()->back()->with('success','Taxes are updated as goverment rules');
    }
    
    public function privacy_policy()
    {
        $privacy_policy = Setting::pluck('privacy_policy')->first();
        return view('admin.settings.privacy_policy',compact('privacy_policy'));
    }
    
    public function update_privacy_policy(Request $request)
    {
        $setting = Setting::first();
        $setting->privacy_policy = $request->privacy_policy;
        $setting->save();
        return redirect()->back()->with('success','Privacy Policy has updated');
    }
    
    public function terms_conditions()
    {
        $terms_conditions = Setting::pluck('terms_conditions')->first();
        return view('admin.settings.terms_conditions',compact('terms_conditions'));
    }
    
    public function update_terms_conditions(Request $request)
    {
        $setting = Setting::first();
        $setting->terms_conditions = $request->terms_conditions;
        $setting->save();
        return redirect()->back()->with('success','Terms and conditions has updated');
    }
    
    public function about_us()
    {
        $about_us = Setting::pluck('about_us')->first();
        return view('admin.settings.about_us',compact('about_us'));
    }
    
    public function update_about_us(Request $request)
    {
        $setting = Setting::first();
        $setting->about_us = $request->about_us;
        $setting->save();
        return redirect()->back()->with('success','About us has updated');
    }
    
    public function how_it_works()
    {
        $how_it_works = Setting::pluck('how_it_works')->first();
        return view('admin.settings.how_it_works',compact('how_it_works'));
    }
    
    public function update_how_it_works(Request $request)
    {
        $setting = Setting::first();
        $setting->how_it_works = $request->how_it_works;
        $setting->save();
        return redirect()->back()->with('success','How it works has updated');
    }
    
    public function return_and_refund_policy()
    {
        $return_and_refund_policy = Setting::pluck('return_and_refund_policy')->first();
        return view('admin.settings.return_and_refund_policy',compact('return_and_refund_policy'));
    }
    
    public function update_return_and_refund_policy(Request $request)
    {
        $setting = Setting::first();
        $setting->return_and_refund_policy = $request->return_and_refund_policy;
        $setting->save();
        return redirect()->back()->with('success','Return and Refund Policy updated');
    }
    
    public function accidental_policy()
    {
        $accidental_policy = Setting::pluck('accidental_policy')->first();
        return view('admin.settings.accidental_policy',compact('accidental_policy'));
    }
    
    public function update_accidental_policy(Request $request)
    {
        $setting = Setting::first();
        $setting->accidental_policy = $request->accidental_policy;
        $setting->save();
        return redirect()->back()->with('success','Accidental Policy updated');
    }
    
    public function cancellation_policy()
    {
        $cancellation_policy = Setting::pluck('cancellation_policy')->first();
        return view('admin.settings.cancellation_policy',compact('cancellation_policy'));
    }
    
    public function update_cancellation_policy(Request $request)
    {
        $setting = Setting::first();
        $setting->cancellation_policy = $request->cancellation_policy;
        $setting->save();
        return redirect()->back()->with('success','Cacellation Policy updated');
    }
    
    public function delete_account_policy()
    {
        $delete_account_policy = Setting::pluck('delete_account_policy')->first();
        return view('admin.settings.delete_account_policy',compact('delete_account_policy'));
    }
    
    public function update_delete_account_policy(Request $request)
    {
        $setting = Setting::first();
        $setting->delete_account_policy = $request->delete_account_policy;
        $setting->save();
        return redirect()->back()->with('success','Delete Account Policy Updated');
    }
    
    public function faqs()
    {
        $faqs = Setting::pluck('faqs')->first();
        return view('admin.settings.faqs',compact('faqs'));
    }
    
    public function update_faqs(Request $request)
    {
        $setting = Setting::first();
        $setting->faqs = $request->faqs;
        $setting->save();
        return redirect()->back()->with('success','Faqs has updated');
    }
    
    public function app_info()
    {
        $setting = Setting::first();
        return view('public.app-info', compact('setting'));
    }
    
    
}
