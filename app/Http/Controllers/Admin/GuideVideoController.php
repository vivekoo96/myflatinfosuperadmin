<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GuideVideo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GuideVideoController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        $query = GuideVideo::withTrashed()->with('creator')->orderBy('created_at', 'desc');

        if ($category) {
            $query->where('category', $category);
        }

        $videos = $query->get();

        $categories = [
            'all'            => 'All Apps',
            'user_app'       => 'User App',
            'security_app'   => 'Security App',
            'role_app'       => 'Role App',
            'building_admin' => 'Building Admin',
        ];

        return view('admin.guide_video.index', compact('videos', 'categories', 'category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:all,user_app,security_app,role_app,building_admin',
            'youtube_link' => 'required|url',
            'status'       => 'required|in:active,inactive',
        ]);

        if ($request->id) {
            $video = GuideVideo::withTrashed()->findOrFail($request->id);
            $video->update([
                'title'        => $request->title,
                'description'  => $request->description,
                'category'     => $request->category,
                'youtube_link' => $request->youtube_link,
                'status'       => $request->status,
            ]);
            return redirect()->back()->with('success', 'Video updated successfully.');
        }

        GuideVideo::create([
            'title'        => $request->title,
            'description'  => $request->description,
            'category'     => $request->category,
            'youtube_link' => $request->youtube_link,
            'status'       => $request->status,
            'created_by'   => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Video added successfully.');
    }

    public function destroy($id, Request $request)
    {
        $video = GuideVideo::withTrashed()->findOrFail($id);

        if ($request->action === 'restore') {
            $video->restore();
        } else {
            $video->delete();
        }

        return response()->json(['msg' => 'success']);
    }
}
