<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoModule;
use App\Models\VideoTutorial;
use Illuminate\Http\Request;

class VideoTutorialController extends Controller
{
    // POST /video-tutorial/module
    public function storeModule(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:video_modules,title',
        ]);

        VideoModule::create(['title' => $request->title]);
        return redirect()->back()->with('success', 'Category created successfully');
    }

    // POST /video-tutorial/module/{id}/update
    public function updateModule(Request $request, $id)
    {
        $module = VideoModule::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255|unique:video_modules,title,' . $id,
        ]);

        $module->update(['title' => $request->title]);
        return redirect()->back()->with('success', 'Category updated successfully');
    }

    // POST /video-tutorial/module/{id}/delete
    public function destroyModule($id)
    {
        VideoModule::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Category deleted successfully');
    }

    // POST /video-tutorial/store
    public function store(Request $request)
    {
        $request->validate([
            'module_id'  => 'required|exists:video_modules,id',
            'title'      => 'required|string|max:255',
            'text'       => 'nullable|string',
            'video_url'  => 'required|string',
            'video_type' => 'required|string',
            'interfaces' => 'nullable|array',
        ]);

        VideoTutorial::create([
            'module_id'  => $request->module_id,
            'title'      => $request->title,
            'text'       => $request->text,
            'video_url'  => $request->video_url,
            'video_type' => $request->video_type,
            'interfaces' => $request->interfaces,
        ]);

        return redirect()->back()->with('success', 'Video added successfully');
    }

    // POST /video-tutorial/{id}/update
    public function update(Request $request, $id)
    {
        $video = VideoTutorial::findOrFail($id);
        $request->validate([
            'module_id'  => 'required|exists:video_modules,id',
            'title'      => 'required|string|max:255',
            'text'       => 'nullable|string',
            'video_url'  => 'required|string',
            'video_type' => 'required|string',
            'interfaces' => 'nullable|array',
        ]);

        $video->update([
            'module_id'  => $request->module_id,
            'title'      => $request->title,
            'text'       => $request->text,
            'video_url'  => $request->video_url,
            'video_type' => $request->video_type,
            'interfaces' => $request->interfaces,
        ]);

        return redirect()->back()->with('success', 'Video updated successfully');
    }

    // POST /video-tutorial/{id}/delete
    public function destroy($id)
    {
        VideoTutorial::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Video deleted successfully');
    }
}
