<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OCRController extends Controller
{
    public function showForm()
    {
        return view('ocr.upload');
    }

    public function process(Request $request)
    {
        $request->validate([
            'sheet' => 'required|mimes:jpeg,jpg,png,pdf|max:10240',
        ]);

        $file = $request->file('sheet');
        $extension = $file->getClientOriginalExtension();
        $path = $file->storeAs('public/sheets', uniqid() . '.' . $extension);

        $localPath = storage_path('app/' . $path);

        // If it's PDF, convert to image using Imagick (first page only)
        if ($extension === 'pdf') {
            if (!extension_loaded('imagick')) {
                return back()->with('error', 'Imagick not installed for PDF support.');
            }

            $imagick = new \Imagick();
            $imagick->setResolution(300, 300);
            $imagick->readImage($localPath . '[0]');
            $imagePath = storage_path('app/public/sheets/' . uniqid() . '.jpg');
            $imagick->writeImage($imagePath);
            $imagick->clear();
            $imagick->destroy();
        } else {
            $imagePath = $localPath;
        }

        $text = (new TesseractOCR($imagePath))
                    ->lang('eng')
                    ->run();

        return view('ocr.result', compact('text'));
    }
}
