<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function index()
    {
        $profile = auth()->user()
            ->profiles()
            ->first();

        $items = $profile
            ->watchlist()
            ->latest()
            ->get();

        return view('front.pages.watchlist', compact('items'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'content_id' => 'required|exists:contents,id'
        ]);

        $profile = auth()->user()->profiles()->first();

        if ($profile->watchlist()->where('content_id', $request->content_id)->exists()) {

            $profile->watchlist()->detach($request->content_id);

            return response()->json([
                'status' => 'removed'
            ]);
        } else {

            $profile->watchlist()->attach($request->content_id);

            return response()->json([
                'status' => 'added'
            ]);
        }
    }

    public function partial()
    {
        $profile = auth()->user()->profiles()->first();
        $watchlist = $profile
            ->watchlist()
            ->latest()
            ->take(12)
            ->get();
        return view('front.partials.watchlist-row', compact('watchlist'));
    }
}
