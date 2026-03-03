<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\VideoAsset;
use App\Models\WatchHistory;
use Illuminate\Http\Request;
use App\Models\PlaybackProgress;

class PlaybackController extends Controller
{
    public function start(Request $r)
    {
        $data = $r->validate([
            'profile_id' => 'required|integer',
            'content_id' => 'required|integer',
            'episode_id' => 'nullable|integer',
        ]);

        $asset = VideoAsset::where('content_id', $data['content_id'])
            ->when($data['episode_id'] ?? null, fn($q) => $q->where('episode_id', $data['episode_id']))
            ->where('status', 'ready')
            ->first();

        if (!$asset) return response()->json(['message' => 'Video not ready'], 409);

        return response()->json([
            'playback_id' => uniqid('pb_', true),
            'hls_url' => $asset->hls_master_url,
        ]);
    }

    public function progress(Request $request)
    {
        $data = $request->validate([
            'content_id' => 'required|exists:contents,id',
            'position_seconds'   => 'required|integer|min:0',
            'duration_seconds'   => 'nullable|integer|min:0',
        ]);

        PlaybackProgress::updateOrCreate(
            [
                'user_id' => auth()->id()??1,
                'content_id' => $data['content_id'],
            ],
            [
                'position_seconds' => $data['position_seconds'],
                'duration_seconds' => $data['duration_seconds'] ?? 0,
            ]
        );

        return response()->json(['ok' => true]);
    }
}
