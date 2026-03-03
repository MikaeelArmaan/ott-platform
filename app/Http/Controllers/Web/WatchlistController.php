<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
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

        return view('front.watchlist', compact('items'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'content_id' => 'required|exists:contents,id'
        ]);

        $profile = auth()->user()
            ->profiles()
            ->first();

        if ($profile->watchlist()->where('content_id',$request->content_id)->exists()) {

            // REMOVE
            $profile->watchlist()->detach($request->content_id);

        } else {

            // ADD
            $profile->watchlist()->attach($request->content_id);

        }

        return back();
    }
}
