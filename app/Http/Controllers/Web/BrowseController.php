<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use App\Models\PlaybackProgress;
use App\Models\Episode;
use App\Models\Season;
use App\Models\WatchHistory;

class BrowseController extends Controller
{
    public function home()
    {
        $base = Content::where('is_published', true);

        $featured = (clone $base)->latest()->take(5)->get();
        $trending = (clone $base)->latest()->skip(5)->take(12)->get();
        $movies   = (clone $base)->where('type','movie')->latest()->take(12)->get();
        $series   = (clone $base)->where('type','series')->latest()->take(12)->get();
        $recent   = (clone $base)->latest()->take(12)->get();

        $continue = collect();

        if (auth()->check()) {
            $continue = \App\Models\PlaybackProgress::with('content')
                ->where('user_id', 1)
                ->whereHas('content', fn($q) => $q->where('is_published', true))
                ->orderBy('updated_at','desc')
                ->limit(10)
                ->get();
        }
        
        return view('front.home',
            compact('featured','trending','movies','series','recent','continue')
        );
    }

    public function browse(Request $request)
    {
        $contents = Content::where('is_published', true)
            ->when($request->type, fn($q) =>
                $q->where('type', $request->type)
            )
            ->when($request->language, fn($q) =>
                $q->where('language', $request->language)
            )
            ->when($request->rating, fn($q) =>
                $q->where('maturity_rating', $request->rating)
            )
            ->latest()
            ->paginate(24)
            ->withQueryString();

        return view('front.browse', compact('contents'));
    }

    public function show(Content $content)
    {
        $similar = Content::where('type', $content->type)
            ->where('id','!=',$content->id)
            ->where('is_published',true)
            ->latest()
            ->take(12)
            ->get();
    
        $seasons = collect();
        $selectedSeason = null;
        $episodes = collect();

        if ($content->type === 'series') {
            $seasons = $content->seasons()->with('episodes')->get();
            $selectedSeason = $seasons->first();
            $episodes = $selectedSeason?->episodes ?? collect();
        }

        return view('front.show', compact(
            'content','similar','seasons','selectedSeason','episodes'
        ));
    }

    

    public function search(Request $request)
    {
        $q = trim((string)$request->query('q', ''));
        $contents = Content::where('is_published', true)
            ->when($q !== '', fn($qq) => $qq->where('title', 'like', "%{$q}%"))
            ->latest()
            ->paginate(24)
            ->appends(['q' => $q]);

        return view('front.search', compact('contents','q'));
    }

    public function watch(Content $content)
    {
        abort_if(!$content->is_published, 404);

        // If series and no episode specified -> play S1E1 (or latest)
        if ($content->type === 'series') {
            $firstEp = $content->episodes()
                ->orderBy('season_id')
                ->orderBy('episode_number')
                ->first();

            abort_if(!$firstEp, 404);

            return redirect()->route('title.watch.episode', [$content->id, $firstEp->id]);
        }

        // movie resume
        $resumeAt = 0;

        if (auth()->check()) {
            $profile = auth()->user()->profiles()->first();
            if ($profile) {
                $progress = WatchHistory::where('profile_id', $profile->id)
                    ->where('content_id', $content->id)
                    ->whereNull('episode_id')
                    ->first();

                $resumeAt = $progress?->position_seconds ?? 0;
            }
        }

        return view('front.watch', compact('content','resumeAt'));
    }

    public function watchEpisode(Content $content, Episode $episode)
    {
        abort_if($content->type !== 'series', 404);
        abort_if($episode->content_id !== $content->id, 404);

        $resumeAt = 0;

        if (auth()->check()) {
            $profile = auth()->user()->profiles()->first();
            if ($profile) {
                $progress = WatchHistory::where('profile_id', $profile->id)
                    ->where('content_id', $content->id)
                    ->where('episode_id', $episode->id)
                    ->first();

                $resumeAt = $progress?->position_seconds ?? 0;
            }
        }

        return view('front.watch', [
            'content' => $content,
            'episode' => $episode,
            'resumeAt' => $resumeAt
        ]);
    }
}
