<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Watchlist;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function index(Request $r)
    {
        $profileId = (int)$r->query('profile_id', 0);
        if (!$profileId) return response()->json(['message' => 'profile_id required'], 422);

        return response()->json([
            'watchlist' => Watchlist::where('profile_id', $profileId)->latest('created_at')->get()
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'profile_id' => 'required|integer',
            'content_id' => 'required|integer',
        ]);

        $w = Watchlist::firstOrCreate($data, ['created_at' => now()]);
        return response()->json(['item' => $w], 201);
    }

    public function destroy(Request $r, $contentId)
    {
        $profileId = (int)$r->query('profile_id', 0);
        Watchlist::where('profile_id', $profileId)->where('content_id', (int)$contentId)->delete();
        return response()->json(['ok' => true]);
    }
}
