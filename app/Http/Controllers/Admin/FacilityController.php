<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use \Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{

    public function index()
    {
        $facilities = Facility::withTrashed()->get();
        return view('admin.facility.index',compact('facilities'));
    }


    public function create()
    {
        //
        
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'status' => 'required|in:Pending,Active',
            'icon' => 'nullable|image',
            'color' => 'required',
        ];
    
        $msg = 'Facility added successfully';
        $facility = new Facility();
    
        if ($request->id) {
            $facility = Facility::withTrashed()->find($request->id);
            $msg = 'Facility Updated';
        }
    
        $validation = \Validator::make($request->all(), $rules);
    
        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first());
        }
        $facility->name = $request->name;
        $facility->status = $request->status;
        $facility->color = $request->color;
        if($request->hasFile('icon')) {
            $file= $request->file('icon');
            $allowedfileExtension=['jpeg','jpeg','png'];
            $extension = $file->getClientOriginalExtension();
            Storage::disk('s3')->delete($facility->getIconFilenameAttribute());
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $filename = 'images/facilities/' . uniqid() . '.' . $extension;
            Storage::disk('s3')->put($filename, file_get_contents($file));
            $facility->icon = $filename;
        }
        $facility->save();
    
        return redirect()->back()->with('success', $msg);
    }

    public function show($id)
    {
        $facility = Facility::where('id',$id)->withTrashed()->first();
        if(!$facility){
            return redirect()->route('facility.index');
        }
        return view('admin.facility.show',compact('facility'));
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
        $facility = Facility::where('id',$id)->withTrashed()->first();
        if($request->action == 'delete'){
            $facility->delete();
        }else{
            $facility->restore();
        }
        return response()->json([
            'msg' => 'success'
        ],200);
    }
}
