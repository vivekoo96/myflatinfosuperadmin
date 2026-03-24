<?php
namespace App\Http\Controllers;

use App\Models\Setting;

class InfoController extends Controller
{
    public function about()
    {
        $aboutUs = Setting::pluck('about_us')->first();
        return view('info', compact('aboutUs'));
    }
}
