<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::orderBy('id','asc')->withTrashed()->get();
        return view('admin.role.index',compact('roles'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:roles,name',
            'slug' => 'required|unique:roles,slug',
        ];
    
        $msg = 'Role Added';
        $role = new Role();
    
        if ($request->id) {
            $role = Role::withTrashed()->find($request->id);
            $rules['name'] .= ',' . $city->id;
            $rules['slug'] .= ',' . $city->id;
            $msg = 'Role Updated';
        }
    
        $validation = \Validator::make($request->all(), $rules);
    
        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first());
        }
    
        $role->name = $request->name;
        $role->slug = $request->slug;
        $role->save();
    
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
        $role = Role::where('id',$id)->withTrashed()->first();
        if($request->action == 'delete'){
            $role->delete();
        }else{
            $role->restore();
        }
        return response()->json([
            'msg' => 'success'
        ],200);
    }
}
