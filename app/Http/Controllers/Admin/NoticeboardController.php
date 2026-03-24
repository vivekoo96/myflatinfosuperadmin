<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\User;
use App\Models\Noticeboard;
use App\Models\City;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class NoticeboardController extends Controller
{

    public function index()
    {
        $noticeboards = Noticeboard::orderBy('id','asc')->withTrashed()->get();
        $buildings = Building::all();
        return view('admin.noticeboard.index',compact('noticeboards','buildings'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rules = [
            'building_id' => 'required|exists:buildings,id',
            'title' => 'required',
            'desc' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
        ];
    
        $msg = 'Noticeboard added Susccessfully';
        $noticeboard = new Noticeboard();
    
        if ($request->id) {
            $noticeboard = Noticeboard::withTrashed()->find($request->id);
            $msg = 'Noticeboard updated Susccessfully';
        }
    
        $validation = \Validator::make($request->all(), $rules);
    
        if ($validation->fails()) {
            return redirect()->back()->with('error', $validation->errors()->first());
        }
        
        $noticeboard->building_id = $request->building_id;
        $noticeboard->title = $request->title;
        $noticeboard->desc = $request->desc;
        $noticeboard->from_time = $request->from_time;
        $noticeboard->to_time = $request->to_time;
        $noticeboard->save();
    
        return redirect()->back()->with('success', $msg);
    }

    public function show($id)
    {
        $noticeboard = Noticeboard::where('id',$id)->withTrashed()->first();
        if(!$noticeboard){
            return redirect()->route('noticeboard.index');
        }
        return view('admin.noticeboard.show',compact('noticeboard'));
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
        $noticeboard = Noticeboard::where('id',$id)->withTrashed()->first();
        if($request->action == 'delete'){
            $noticeboard->delete();
        }else{
            $noticeboard->restore();
        }
        return response()->json([
            'msg' => 'success'
        ],200);
    }
}
