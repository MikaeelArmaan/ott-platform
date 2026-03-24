<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController
{
    public function index(Request $request)
    {
        $folder = $request->get('folder', 'uploads'); // videos, posters, etc.

        $files = collect(Storage::disk('public')->files($folder))
            ->map(function ($path) {
                return [
                    'path' => $path,
                    'url'  => asset('storage/' . $path),
                    'name' => basename($path),
                    'type' => $this->type($path),
                ];
            })
            ->sortByDesc('path')
            ->values();

        return response()->json([
            'success' => true,
            'files' => $files
        ]);
    }

    private function type($path)
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) return 'image';
        if (in_array($ext, ['mp4','webm','mkv'])) return 'video';

        return 'file';
    }
}