<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{

    public function index()
    {
        $permissions = Permission::orderBy('id','asc')->withTrashed()->get();
        return view('admin.permission.index',compact('permissions'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rules = [
            'guard' => [
                'required',
            ],
            'name' => [
                'required',
                Rule::unique('permissions', 'name')->ignore($request->id),
            ],
            'slug' => [
                'required',
                'string',
                'min:4',
                'max:100',
                'regex:/^[a-z.]+$/',
                Rule::unique('permissions', 'slug')->ignore($request->id),
            ],
        ];
    
        $msg = 'Permission Added';
        $permission = new Permission();
    
        if ($request->id) {
            $permission = Permission::withTrashed()->find($request->id);
            $msg = 'Permission Updated';
        }
    
        $validation = \Validator::make($request->all(), $rules);
    
        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first());
        }
        $permission->guard = $request->guard;
        $permission->group = $request->group;
        $permission->name = $request->name;
        $permission->slug = $request->slug;
        $permission->save();
    
        return redirect()->back()->with('success', $msg);
    }

    public function show($id)
    {
        $role = Role::where('id',$id)->withTrashed()->first();
        if(!$role){
            return redirect()->route('role.index');
        }
        return view('admin.role.show',compact('role'));
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
        $permission = Permission::where('id',$id)->withTrashed()->first();
        if($request->action == 'delete'){
            $permission->delete();
        }else{
            $permission->restore();
        }
        return response()->json([
            'msg' => 'success'
        ],200);
    }
}
