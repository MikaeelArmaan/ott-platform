<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $folder = $request->get('folder', 'uploads');

        if (!Storage::disk('public')->exists($folder)) {
            return response()->json([
                'success' => true,
                'files' => []
            ]);
        }

        $files = collect(Storage::disk('public')->files($folder))
            ->map(function ($path) {
                return [
                    'path' => $path,
                    'url' => str_starts_with($path, 'http')
                        ? $path
                        : asset('storage/' . $path),
                    'name' => basename($path),
                    'type' => $this->detectType($path),
                    'size' => Storage::disk('public')->size($path),
                    'last_modified' => Storage::disk('public')->lastModified($path),
                ];
            })
            ->sortByDesc('last_modified')
            ->values();

        return response()->json([
            'success' => true,
            'files' => $files,
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $path = $request->path;

        if (!Storage::disk('public')->exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found.'
            ], 404);
        }

        Storage::disk('public')->delete($path);

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully.'
        ]);
    }

    private function detectType(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            return 'image';
        }

        if (in_array($ext, ['mp4', 'webm', 'mkv', 'mov'])) {
            return 'video';
        }

        return 'file';
    }
}
