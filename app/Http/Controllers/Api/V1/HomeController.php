<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index(Request $r)
    {
        $profileId = (int)$r->query('profile_id', 0);
        if (!$profileId) return response()->json(['message' => 'profile_id required'], 422);

        $continue = WatchHistory::where('profile_id', $profileId)
            ->where('completed', false)
            ->orderByDesc('last_watched_at')
            ->limit(20)->get();

        $since = Carbon::now()->subDays(7);
        $trendingIds = WatchHistory::where('last_watched_at', '>=', $since)
            ->selectRaw('content_id, COUNT(*) as c')
            ->groupBy('content_id')
            ->orderByDesc('c')
            ->limit(20)->pluck('content_id');

        $trending = Content::whereIn('id', $trendingIds)->where('is_published', true)->get();

        $new = Content::where('is_published', true)->orderByDesc('created_at')->limit(20)->get();

        return response()->json([
            'continue_watching' => $continue,
            'trending' => $trending,
            'new_releases' => $new,
        ]);
    }
}
