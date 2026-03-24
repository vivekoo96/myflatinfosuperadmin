<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Builder;
use App\Models\Facility;
use App\Models\Permission;
use App\Models\City;
use App\Models\User;
use \Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BuilderController extends Controller
{

    public function index()
    {
        $builders = Builder::withTrashed()->get();
        return view('admin.builder.index',compact('builders'));
    }
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $msg = 'Builder added successfully';
        $builder = new Builder();
    
        if ($request->id) {
            $builder = Builder::find($request->id);
            $msg = 'Builder updated successfully';
        }
                $rules = [
    'name' => [
        'required',
        'string',
        'min:2',
        'max:40',
        'regex:/^[A-Za-z\s]+$/'
    ],
    'company_name' => [
        'nullable',
        'string',
        'min:2',
        'max:40',
        'regex:/^[A-Za-z\s]+$/'
    ],
    'email' => [
    'required',
    'email',
   'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}$/',
    Rule::unique('builders', 'email')->ignore($builder->id)
],
    'phone' => 'required|string|regex:/^(?!([0-9])\1{9})[6-9][0-9]{9}$/',
];

$messages = [
    'name.required' => 'Name is required.',
    'name.min' => 'Name must be at least 2 characters.',
    'name.max' => 'Name may not be greater than 40 characters.',
    'name.regex' => 'Name should contain only alphabets and spaces.',

    'company_name.min' => 'Company name must be at least 2 characters.',
    'company_name.max' => 'Company name may not be greater than 40 characters.',
    'company_name.regex' => 'Company name should contain only alphabets and spaces.',

    'email.required' => 'Email is required.',
    'email.email' => 'Please enter a valid email address.',
    'email.regex' => 'Invalid email format.',
    'email.unique' => 'This email is already registered.',

    'phone.required' => 'Phone number is required.',
    'phone.regex' => 'Please enter a valid 10-digit Indian mobile number starting from 6 to 9.',
];

$validation = \Validator::make($request->all(), $rules, $messages);
    
        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first());
        }
        $builder->name = $request->name;
        $builder->company_name = $request->company_name;
        $builder->email = $request->email;
        $builder->phone = $request->phone;
        $builder->save();
    
        return redirect()->back()->with('success', $msg);
    }

    public function show($id)
    {
        $builder = Builder::with(['buildings' => function ($q) {
            $q->withTrashed()->with(['permissions']);
        }])->find($id);
        
        if(!$builder){
            return redirect()->route('builder.index');
        }
        $permissions = Permission::where('guard', 'feature')->get()->groupBy('group');
        $cities = City::all();
        $building_admins = User::where('role', 'BA')->where('status', 'Active')->withTrashed()->with('buildings')->get();
        return view('admin.builder.show',compact('builder','cities','permissions','building_admins'));
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
        $builder = Builder::where('id',$id)->withTrashed()->first();
        if($request->action == 'delete'){
            $builder->delete();
        }else{
            $builder->restore();
        }
        return response()->json([
            'msg' => 'success'
        ],200);
    }

}
