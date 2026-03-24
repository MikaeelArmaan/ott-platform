<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:512000', // 500MB
            'folder' => 'nullable|string'
        ]);

        $file = $request->file('file');

        $folder = $request->folder ?? 'uploads';

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs($folder, $filename, 'public');

        return response()->json([
            'success' => true,
            'file' => [
                'path' => $path,
                'url'  => asset('storage/' . $path),
                'type' => str_contains($file->getMimeType(), 'video') ? 'video' : 'image',
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]
        ]);
    }
}
