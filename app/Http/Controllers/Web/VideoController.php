<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\VideoAsset;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function toggleLike(VideoAsset $videoAsset)
    {
        $user = auth()->user();
        $profile = $user?->profiles()->first();

        if (!$profile) {
            return response()->json(['error' => 'No profile'], 400);
        }

        $like = Like::where([
            'profile_id' => $profile->id,
            'video_asset_id' => $videoAsset->id,
        ])->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'profile_id' => $profile->id,
                'video_asset_id' => $videoAsset->id,
            ]);
        }

        // 🔥 reload count
        $videoAsset->loadCount('likes');

        return response()->json([
            'liked' => !$like,
            'count' => $videoAsset->likes_count
        ]);
    }

    public function status($videoAssetId)
    {
        $profileId = auth()->user()->profiles()->first()->id;

        $liked = \App\Models\Like::where([
            'profile_id' => $profileId,
            'video_asset_id' => $videoAssetId,
        ])->exists();

        return response()->json([
            'liked' => $liked
        ]);
    }
}
