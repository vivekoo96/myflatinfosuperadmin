<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Core\ServiceBuilder;
use Illuminate\Support\Facades\Storage;

class VisionOCRController extends Controller
{
    public function showForm()
    {
        return view('vision.upload');
    }

    public function process(Request $request)
    {
        $request->validate([
            'sheet' => 'required|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $file = $request->file('sheet');
        $path = $file->storeAs('public/sheets', uniqid() . '.' . $file->getClientOriginalExtension());
        $localPath = storage_path('app/' . $path);

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . env('GOOGLE_APPLICATION_CREDENTIALS'));

        $imageAnnotator = new ImageAnnotatorClient();

        // Read file as string
        $image = file_get_contents($localPath);

        // Perform OCR
        $response = $imageAnnotator->textDetection($image);
        $texts = $response->getTextAnnotations();

        $output = $texts->count() > 0 ? $texts[0]->getDescription() : 'No text found.';

        $imageAnnotator->close();

        return view('vision.result', compact('output'));
    }
}
