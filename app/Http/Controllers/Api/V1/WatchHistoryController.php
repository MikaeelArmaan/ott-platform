<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WatchHistory;

class WatchHistoryController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'profile_id' => 'required|integer',
            'content_id' => 'required|integer',
            'episode_id' => 'nullable|integer',
            'watch_time_seconds' => 'required|integer',
            'duration_seconds' => 'required|integer'
        ]);

        $percent = ($data['watch_time_seconds'] / $data['duration_seconds']) * 100;

        $history = WatchHistory::updateOrCreate(
            [
                'profile_id' => $data['profile_id'],
                'content_id' => $data['content_id'],
                'episode_id' => $data['episode_id']
            ],
            [
                'watch_time_seconds' => $data['watch_time_seconds'],
                'completion_percent' => $percent,
                'completed' => $percent >= 90,
                'watched_at' => now()
            ]
        );

        return response()->json([
            'success' => true
        ]);
    }
}