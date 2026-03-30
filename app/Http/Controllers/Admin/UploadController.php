<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file'   => 'required|file|max:512000|mimes:jpg,jpeg,png,webp,gif,mp4,webm,mkv,mov',
            'folder' => 'nullable|string'
        ]);

        $file = $request->file('file');
        $folder = $request->folder ?? 'uploads';

        // 🔥 KEEP ORIGINAL NAME (SAFE)
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        $originalName = Str::slug( $originalName.' '.Str::uuid());

        $filename = $originalName . '.' . $extension;

        $path = $file->storeAs($folder, $filename, 'public');
        return response()->json([
            'success' => true,
            'file' => [
                'path' => $path,
                'url'  => asset('storage/' . $path),
                'type' => str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image',
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'last_modified' => Storage::disk('public')->lastModified($path),
            ]
        ]);
    }
}
