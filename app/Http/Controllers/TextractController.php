<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TextractService;

class TextractController extends Controller
{
    public function index()
    {
        return view('textract');
    }

    public function process(Request $request, TextractService $textractService)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $path = $request->file('file')->store('uploads', 'local');
        $fullPath = storage_path('app/' . $path);

        try {
            $lines = $textractService->extractTextFromImage($fullPath);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to extract text: ' . $e->getMessage()]);
        }

        return view('textract', ['lines' => $lines]);
    }
}
