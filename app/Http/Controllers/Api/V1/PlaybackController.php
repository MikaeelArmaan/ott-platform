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
            'profile_id' => 'required|integer',
            'content_id' => 'required|integer',
            'episode_id' => 'nullable|integer',
            'position_seconds' => 'required|integer',
            'duration_seconds' => 'required|integer'
        ]);

        $percent = 0;

        if ($data['duration_seconds'] > 0) {
            $percent = ($data['position_seconds'] / $data['duration_seconds']) * 100;
        }

        /*
    |--------------------------------------------------------------------------
    | Playback Progress (Resume playback)
    |--------------------------------------------------------------------------
    */

        \App\Models\PlaybackProgress::updateOrCreate(
            [
                'profile_id' => $data['profile_id'],
                'content_id' => $data['content_id'],
                'episode_id' => $data['episode_id']
            ],
            [
                'position_seconds' => $data['position_seconds'],
                'duration_seconds' => $data['duration_seconds'],
                'updated_at' => now()
            ]
        );

        /*
    |--------------------------------------------------------------------------
    | Watch History (Continue watching + analytics)
    |--------------------------------------------------------------------------
    */

        \App\Models\WatchHistory::updateOrCreate(
            [
                'profile_id' => $data['profile_id'],
                'content_id' => $data['content_id'],
                'episode_id' => $data['episode_id']
            ],
            [
                'watch_time_seconds' => $data['position_seconds'],
                'completion_percent' => $percent,
                'completed' => $percent >= 90,
                'watched_at' => now()
            ]
        );

        return response()->json([
            'status' => 'ok'
        ]);
    }
}
