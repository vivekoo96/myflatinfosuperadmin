<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Building;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EventController extends Controller
{

    public function index()
    {
        $events = Event::orderBy('id','asc')->withTrashed()->get();
        $buildings = Building::all();
        return view('admin.event.index',compact('events','buildings'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rules = [
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
            'status' => 'required|in:Active,Pending',
            'image' => 'nullable|image|max:2048',
        ];
    
        $msg = 'Event added Susccessfully';
        $event = new Event();
    
        if ($request->id) {
            $event = Event::withTrashed()->find($request->id);
            $msg = 'Building added Susccessfully';
        }
    
        $validation = \Validator::make($request->all(), $rules);
    
        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first());
        }
        
        if($request->hasFile('image')) {
            $file= $request->file('image');
            $allowedfileExtension=['JPEG','jpg','png'];
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension,$allowedfileExtension);
            // if($check){
                $file_path = public_path('/images/events'.$event->image);
                if(file_exists($file_path) && $event->image != '')
                {
                    unlink($file_path);
                }
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $filename = substr(str_shuffle(str_repeat($pool, 5)), 0, 12) .'.'.$extension;
                $path = $file->move(public_path('/images/events'), $filename);
                $event->image = $filename;
            // }
        }
        
        $event->building_id = $request->building_id;
        $event->name = $request->name;
        $event->from_time = $request->from_time;
        $event->to_time = $request->to_time;
        $event->status = $request->status;
        $event->save();
    
        return redirect()->back()->with('success', $msg);
    }

    public function show($id)
    {
        $event = Event::where('id',$id)->withTrashed()->first();
        if(!$event){
            return redirect()->route('event.index');
        }
        return view('admin.event.show',compact('event'));
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
        $event = Event::where('id',$id)->withTrashed()->first();
        if($request->action == 'delete'){
            $event->delete();
        }else{
            $event->restore();
        }
        return response()->json([
            'msg' => 'success'
        ],200);
    }
}
