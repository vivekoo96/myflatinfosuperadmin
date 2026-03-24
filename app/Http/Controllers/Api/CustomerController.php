<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\Mandal;
use App\Models\Notification;


use DB;
use \Session;
use Mail;
use \Str;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;

use Illuminate\Support\Arr;


use \Hash;
use \Auth;

class CustomerController extends Controller
{
    public function __construct()
    {
        
        $rdata = Setting::findOrFail(1);
        $this->keyId = $rdata->razorpay_key;
        $this->keySecret = $rdata->razorpay_secret;
        $this->displayCurrency = 'INR';

        $this->api = new Api($this->keyId, $this->keySecret);
    }
    
    public function get_setting()
    {
        $setting = Setting::first();
        return response()->json([
            'setting' => $setting
        ],200); 
    }
    
    public function user_status()
    {
        $user = Auth::User();
        
        return response()->json([
            'profile_status' => $user->profile_status,
            'status' => $user->status,
        ],200); 
    }
    
    public function send_otp(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if($user){
            if($user->status == 'Active'){
                return response()->json([
                    'error' => 'You are already registerd with us, Please login'
                ],422);
            }else{
                $otp = rand(1000,9999);
                $user->otp = Hash::make($otp);
                $user->save();
                //Send email with OTP
                Mail::send([], [], function ($message) use ($user, $otp) {
                    $message->to($user->email)
                        ->subject('Sign up OTP')
                        ->setBody("Your OTP for signup in Myflatinfo is: $otp", 'text/html');
                });
                return response()->json([
                    'msg' => 'otp sent successfully.'
                ],200);
            }
            
            
        }else{
            $user = new User();
        }
        $rules = [
            'email' => 'required|unique:users|email',
        ];
        
        $validation = \Validator::make( $request->all(), $rules );
        $error = $validation->errors()->first();
        if ($validation->fails()) {
            return response()->json([
                'error' => $validation->errors()->first()
            ], 422);
        }
        
        $user->role = 'customer';
        $otp = rand(1000,9999);
        $user->email = $request->email;
        $user->status = 'Pending';
        $user->otp = Hash::make($otp);
        $user->otp_status = 'Sent';
        $user->referal_code = 'TRT'.rand(100000,999999);
        $user->save();
        
        //Send email with OTP
        Mail::send([], [], function ($message) use ($user, $otp) {
            $message->to($user->email)
                ->subject('Sign up OTP')
                ->setBody("Your OTP for signup in Myflatinfo is: $otp", 'text/html');
        });
        
            
        return response()->json([
            'msg' => 'otp sent successfully.'
        ],200);
    }
    
    public function resend_otp(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];
        $validation = \Validator::make( $request->all(), $rules );
        $error = $validation->errors()->first();
        if($error){
            return response()->json([
                'error' => $error
            ],422);
        }
        
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response()->json([
                'error' => 'This email is not registered with us'
            ],422);
        }
        
        $otp = rand(1000,9999);
        
        $info = array(
            'user' => Auth::User(),
            'otp' => $otp
        );
        Mail::send([], [], function ($message) use ($user, $otp) {
            $message->to($user->email)
                ->subject('Sign up OTP')
                ->setBody("Your OTP for Myflatinfo is: $otp", 'text/html');
        });

        $user->otp = Hash::make($otp);
        $user->otp_status = 'Sent';
        $user->save();

        return response()->json([
            'msg' => 'otp sent successfully.'
        ],200);
    }
    
    public function forget_password(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:users,email',
        ];
        $validation = \Validator::make( $request->all(), $rules );
        $error = $validation->errors()->first();
        if($error){
            return response()->json([
                'error' => $error
            ],422);
        }
        
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response()->json([
                'error' => 'This email is not registered with us'
            ],422);
        }
        
        $otp = rand(1000,9999);
        
        $info = array(
            'user' => $user,
            'otp' => $otp
        );
        Mail::send('email.forget_password2', $info, function ($message) use ($user)
        {
            $message->to($user->email, $user->name)
            ->subject('Myflatinfo Forget Password');
        });

        $user->otp = Hash::make($otp);
        $user->otp_status = 'Sent';
        $user->save();

        return response()->json([
            'msg' => 'otp sent successfully.'
        ],200);
    }
    
    public function verify_otp(Request $request)
    {
        
        $rules = [
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:4',
        ];
        $validation = \Validator::make( $request->all(), $rules );
        $error = $validation->errors()->first();
        if($error){
            return response()->json([
                'error' => $error
            ],422);
        }
        $user = User::where('email',$request->email)->where('otp_status','Sent')->first();
        if($user){
            if (Hash::check($request->otp, $user->otp)) {
                $user->otp_status = 'Verified';
                $token = $user->createToken('MyApp')->accessToken;
                $user->api_token = $token;
                $user->device_token = $request->device_token;
                $user->save();
                Auth::login($user, true);
                return response()->json([
                    'token' => $token,
                    'msg' => 'OTP verified successfully.'
                ],200);
            }
        }
        return response()->json([
                'error' => 'Invalid email or OTP.'
        ],422);
    }
    
    public function update_password(Request $request)
    {
        $rules = [
            'password' => [
                'required',
                'string',
                'min:8',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
                'confirmed',
            ],
        ];

        $validation = \Validator::make( $request->all(), $rules );
        $error = $validation->errors()->first();
        if($error){
            return response()->json([
                'error' => $error
            ],422);
        }
        
        $user = Auth::User();
        
        if($user){
            $user->password = Hash::make($request->password);
            $user->status = 'Active';
            $user->save();
            return response()->json([
                'msg' => 'Password updated.'
            ],200);
        }
        return response()->json([
                'error' => 'User not found.'
        ],422);
    }

    public function login(Request $request)
    {
        $rules = [
            
            'email' => 'required|email',
            'password' => [
                'required',
            ],
        ];

        $validation = \Validator::make( $request->all(), $rules );
        $error = $validation->errors()->first();
        if($error){
            return response()->json([
                'error' => $error
            ],422);
        }
        
        $user = User::where('email',$request->email)->first();
        
        if($user){
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('MyApp')->accessToken;
                $user->api_token = $token;
                $user->device_token = $request->device_token;
                $user->save();
                Auth::login($user, true);
                return response()->json([
                    'token' => $token
                ],200);
            }else{
                return response()->json([
                    'error' => 'Invalid Password !!'
                ],422);
            }
        }
        return response()->json([
            'error' => 'This email is not registered with us!'
        ],422);
    }

    public function profile(Request $request)
    {
        $user = Auth::User();
        $user = User::where('id',$user->id)->with(['country','state','district','mandal'])->first();
        return response()->json([
            'user' => $user
        ],200);
    }
    
    public function update_profile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'first_name' => 'required|string|max:255|regex:/^[A-Z][a-z]*$/',
            'last_name' => 'required|string|max:255|regex:/^[A-Z][a-z]*$/',
            'phone' => 'required|unique:users,phone,' . $user->id . '|regex:/^([0-9\s\-\+\(\)]*)$/|size:10',
            'gender' => 'required|in:Male,Female,Other',
            'country_id' => 'required|numeric',
            'state_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'mandal_id' => 'required|numeric',
            'address' => 'required|string|min:4',
            'pincode' => 'required|numeric|digits:6|',
        ];
    
        $validation = \Validator::make($request->all(), $rules);
        $error = $validation->errors()->first();
        if ($error) {
            return response()->json([
                'error' => $error
            ], 422);
        }
        //return $request->all();
        if($request->hasFile('photo')) {
            $file= $request->file('photo');
            $allowedfileExtension=['JPEG','jpeg','jpg','png'];
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension,$allowedfileExtension);
            if($check){
                $oldFilePath = public_path('/images/profiles/'.$user->photo_filename);
                if (file_exists($oldFilePath) && $user->photo_filename != '') {
                    unlink($oldFilePath);
                }
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $filename = substr(str_shuffle(str_repeat($pool, 5)), 0, 12) .'.'.$extension;
                $path = $file->move(public_path('/images/profiles'), $filename);
                $user->photo = $filename;
            }else{
                return response()->json([
                    'error' => 'Invalid file format, please upload valid image file'
                ], 422);
            }
        }
        $referal = User::where('referal_code',$request->referal_code)->first();
        
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->country_id = $request->country_id;
        $user->state_id = $request->state_id;
        $user->district_id = $request->district_id;
        $user->mandal_id = $request->mandal_id;
        $user->address = $request->address;
        $user->pincode = $request->pincode;
        $user->save();
    
        return response()->json([
            'msg' => 'Profile updated',
            'user' => $user
        ], 200);
    }
    
    public function get_countries(Request $request)
    {
        $countries = Country::all();
        return response()->json([
            'countries' => $countries
        ], 200);
    }
    public function get_states(Request $request)
    {
        $rules = [
            'country_id' => 'required|numeric',
        ];
    
        $validation = \Validator::make($request->all(), $rules);
        $error = $validation->errors()->first();
        if ($error) {
            return response()->json([
                'error' => $error
            ], 422);
        }
        $states = State::where('country_id',$request->country_id)->get();
        return response()->json([
            'states' => $states
        ], 200);
    }
    public function get_districts(Request $request)
    {
        $rules = [
            'state_id' => 'required|numeric',
        ];
    
        $validation = \Validator::make($request->all(), $rules);
        $error = $validation->errors()->first();
        if ($error) {
            return response()->json([
                'error' => $error
            ], 422);
        }
        $districts = District::where('state_id',$request->state_id)->get();
        return response()->json([
            'districts' => $districts
        ], 200);
    }
    public function get_mandals(Request $request)
    {
        $rules = [
            'district_id' => 'required|numeric',
        ];
    
        $validation = \Validator::make($request->all(), $rules);
        $error = $validation->errors()->first();
        if ($error) {
            return response()->json([
                'error' => $error
            ], 422);
        }
        $mandals = Mandal::where('district_id',$request->district_id)->get();
        return response()->json([
            'mandals' => $mandals
        ], 200);
    }
    
    public function get_logo(Request $request)
    {
        $setting = Setting::first();
        $logo = $setting->logo;
        return response()->json([
                'logo' => $logo
        ],200);
    }
    
    public function onboarding(Request $request)
    {
        $setting = Setting::first();
        return response()->json([
                'logo' => $setting->logo,
                'title' => 'Title',
                'text' => 'Lorem Ipsum'
        ],200);
    }
    
    public function create_razorpay_order(Request $request)
    {
        $rules = [
            'package_id' => 'required|numeric',
        ];
    
        $validation = \Validator::make($request->all(), $rules);
        $error = $validation->errors()->first();
        if ($error) {
            return response()->json([
                'error' => $error
            ], 422);
        }
        $user = Auth::User();
        $package = Package::find($request->package_id);
        
        if(!$package){
            return response()->json([
                'error' => 'Package not found'
            ], 422);
        }
        $subscription = Subscription::where('user_id',$user->id)->where('package_id',$package->id)->where('status','Active')->first();
        if($subscription){
            return response()->json([
                'error' => 'You already have purchased this package'
            ], 422);
        }
        $item_name = $package->name;
        $item_number = $package->id;
        $item_amount = $package->price;

        $orderData = [
            'receipt'         => $item_number,
            'amount'          => $item_amount * 100, // 2000 rupees in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];
        
        $razorpayOrder = $this->api->order->create($orderData);
        $razorpayOrderId = $razorpayOrder['id'];
        $displayAmount = $amount = $orderData['amount'];
                    
        if ($this->displayCurrency !== 'INR')
        {
            $url = "https://api.fixer.io/latest?symbols=$this->displayCurrency&base=INR";
            $exchange = json_decode(file_get_contents($url), true);
                    
            $displayAmount = $exchange['rates'][$this->displayCurrency] * $amount / 100;
        }
                    
        $data = [
            "key"               => $this->keyId,
            "amount"            => $amount,
            "name"              => $item_name,
            "description"       => $item_name,
            "prefill"           => [
    			"name"              => $user->name,
    			"email"             => $user->email,
    			"contact"           => $user->phone,
            ],
            "notes"             => [
				"address"           => $user->address,
				"merchant_order_id" => $item_number,
            ],
            "theme"             => [
				"color"             => "#3399cc"
            ],
            "order_id"          => $razorpayOrderId,
        ];
                    
        if ($this->displayCurrency !== 'INR')
        {
            $data['display_currency']  = $this->displayCurrency;
            $data['display_amount']    = $displayAmount;
        }
                    
        $displayCurrency = $this->displayCurrency;
        
        $order = new Order();
        $order->user_id = $user->id;
        $order->package_id = $package->id;
        $order->order_id = $razorpayOrderId;
        $order->status = 'Created';
        $order->save();
        
        return response()->json([
                'data' => $data,
                'displayCurrency' => $displayCurrency
        ],200);
    }
    public function verify_razorpay_signature(Request $request)
    {
        $rules = [
            'razorpay_order_id' => 'required',
        ];
    
        $validation = \Validator::make($request->all(), $rules);
        $error = $validation->errors()->first();
        if ($error) {
            return response()->json([
                'error' => $error
            ], 422);
        }
        $user = Auth::User();
        $order = Order::where('order_id',$request->razorpay_order_id)->where('status','Created')->first();
        if(!$order){
            return response()->json([
                    'error' => 'Order id not found',
            ],422);
        }
        $success = true;
        $error = "Payment Failed";
        
        $razorpay_order_id = $request->razorpay_order_id;
        $razorpay_payment_id = $request->razorpay_payment_id;
        $razorpay_signature = $request->razorpay_signature;
        
        // try{
        //     $attributes = array(
        //         'razorpay_order_id' => $razorpay_order_id,
        //         'razorpay_payment_id' => $razorpay_payment_id,
        //         'razorpay_signature' => $razorpay_signature
        //     );
        
        //     $this->api->utility->verifyPaymentSignature($attributes);
            
        // }
        // catch(SignatureVerificationError $e){
        //     $success = false;
        //     $error = 'Razorpay Error : ' . $e->getMessage();
        //     return response()->json([
        //             'error' => $error
        //     ],400);
        // }

        // if ($success === true)
        // {
            $razorpayOrder = $this->api->order->fetch($razorpay_order_id);
            $reciept = $razorpayOrder['receipt'];
            $transaction_id = $razorpay_payment_id;
            
            $order->payment_id = $razorpay_payment_id;
            $order->signature = $razorpay_signature;
            $order->status = 'Verified';
            $order->save();
            
            $subscription = new Subscription();
            $subscription->user_id = $user->id;
            $subscription->package_id = $order->package_id;
            $subscription->status = 'Active';
            $subscription->save();
            
            $package = Package::find($order->package_id);
            
            $info = array(
                'package' => $package
            );
                
            Mail::send('email.package_purchased', ['info' => $info], function ($message) use ($user)
            {
                $message->to($user->email, $user->name)
                ->subject('Package purchased');
            });
            
            $user->create_circle($package->id);
            $user->update_circle_member($package->id);
            
            // $user->wallet = $user->wallet + $package->price;
            // $user->save();
            
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->type = 'Credit';
            $transaction->reason = $package->name;
            $transaction->amount = $package->price;
            $transaction->balance = $user->wallet;
            $transaction->save();
            
            return response()->json([
                    'message' => 'Package purchased successfully'
            ],200);

        // }
        // else
        // {
        //     return response()->json([
        //             'error' => $error
        //     ],422);
        // }
    }
    
    public function send_push_notification(Request $request)
    {
        $rules = [
            'device_token' => 'required|string',
            'device_type' => 'required|string|in:android,ios',
            'title' => 'required|string',
            'body' => 'required|string',
            'screen' => 'nullable|string',
            'params' => 'nullable|array',
            'categoryId' => 'nullable|string',
            'channelId' => 'nullable|string',
            'sound' => 'nullable|string',
            'type' => 'required|string',
            'user_id' => 'required|string',
            'flat_id' => 'nullable|string',
            'building_id' => 'nullable|string'
        ];

        $validation = \Validator::make($request->all(), $rules);
        $error = $validation->errors()->first();
        if ($error) {
            return response()->json([
                'error' => $error
            ], 422);
        }

        // Use FCM HTTP v1 API
        $fcmService = new \App\Services\FCMService();
        
        $fcmData = [
            'screen' => $request->screen ?? '',
            'type' => $request->type ?? '',
            'user_id' => (string)$request->user_id,
            'flat_id' => (string)($request->flat_id ?? ''),
            'building_id' => (string)($request->building_id ?? ''),
            'categoryId' => $request->categoryId ?? '',
            'channelId' => $request->channelId ?? '',
            'params' => json_encode($request->params ?? [])
        ];

        $result = $fcmService->sendNotification(
            $request->device_token,
            $request->title,
            $request->body,
            $fcmData
        );

        $httpCode = $result['status'];
        $response = json_encode($result['response'] ?? []);
        $curlError = $result['error'] ?? '';
        
        // Log detailed FCM response for debugging
        \Log::info('FCM v1 Response', [
            'mode' => 'FCM_HTTP_V1',
            'http_code' => $httpCode,
            'success' => $result['success'] ?? false,
            'response' => substr($response, 0, 200) . '...',
            'error' => $curlError,
            'device_token' => substr($request->device_token, 0, 20) . '...'
        ]);

        // Store notification in database
        $notification = new Notification();
        $notification->user_id = $request->user_id;
        $notification->title = $request->title;
        $notification->body = $request->body;
        $notification->dataPayload = json_encode([
            'type' => $request->type,
            'screen' => $request->screen,
            'params' => $request->params ?? [],
            'device_type' => $request->device_type
        ]);
        $notification->save();

        if ($httpCode == 200) {
            return response()->json([
                'success' => true,
                'message' => 'Push notification sent successfully',
                'response' => json_decode($response, true),
                'debug' => [
                    'mode' => 'FCM_HTTP_V1',
                    'http_code' => $httpCode,
                    'fcm_success' => $result['success'] ?? false
                ]
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send push notification',
                'response' => json_decode($response, true),
                'debug' => [
                    'mode' => 'FCM_HTTP_V1',
                    'http_code' => $httpCode,
                    'error' => $curlError,
                    'fcm_success' => $result['success'] ?? false,
                    'device_token_length' => strlen($request->device_token ?? '')
                ]
            ], 400);
        }
    }

    public function get_notifications(Request $request)
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'notifications' => $notifications
        ], 200);
    }

    public function read_notification(Request $request)
    {
        $rules = [
            'notification_id' => 'required|numeric|exists:notifications,id'
        ];

        $validation = \Validator::make($request->all(), $rules);
        $error = $validation->errors()->first();
        if ($error) {
            return response()->json([
                'error' => $error
            ], 422);
        }

        $user = Auth::user();
        $notification = Notification::where('id', $request->notification_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$notification) {
            return response()->json([
                'error' => 'Notification not found'
            ], 404);
        }

        $notification->read_at = now();
        $notification->save();

        return response()->json([
            'message' => 'Notification marked as read'
        ], 200);
    }
    
}
