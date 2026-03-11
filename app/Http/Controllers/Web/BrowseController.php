<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use App\Models\PlaybackProgress;
use App\Models\Episode;
use App\Models\VideoAsset;
use App\Models\WatchHistory;

class BrowseController extends Controller
{
    public function home()
    {
        $base = Content::where('is_published', true);

        $featured = (clone $base)->latest()->take(5)->get();
        $trending = (clone $base)->latest()->skip(5)->take(12)->get();
        $movies   = (clone $base)->where('type', 'movie')->latest()->take(12)->get();
        $series   = (clone $base)->where('type', 'series')->latest()->take(12)->get();
        $recent   = (clone $base)->latest()->take(12)->get();

        $continueWatching = collect();
        $watchlist = collect();
        $watchlistIds = [];
        $profile = null;

        if (auth()->check() && auth()->user()->isConsumer()) {
            $profile = auth()->user()->profiles()->first();

            if ($profile) {
                $watchlist = $profile->watchlist()
                    ->latest('watchlists.created_at')
                    ->take(12)
                    ->get();

                $watchlistIds = $profile->watchlist()
                    ->pluck('content_id')
                    ->toArray();

                $continueWatching = WatchHistory::with('content')
                                        ->continueWatching()
                                        ->where('profile_id',$profile->id)
                                        ->orderByDesc('watched_at')
                                        ->limit(10)
                                        ->get();
            }
        }

        return view('front.pages.home', compact(
            'featured',
            'trending',
            'movies',
            'series',
            'recent',
            'continueWatching',
            'watchlist',
            'watchlistIds',
            'profile'
        ));
    }

    public function browse(Request $request)
    {
        $contents = Content::where('is_published', true)
            ->when(
                $request->type,
                fn($q) =>
                $q->where('type', $request->type)
            )
            ->when(
                $request->language,
                fn($q) =>
                $q->where('language', $request->language)
            )
            ->when(
                $request->rating,
                fn($q) =>
                $q->where('maturity_rating', $request->rating)
            )
            ->latest()
            ->paginate(12, [
                'id',
                'title',
                'poster',
                'thumbnail',
                'type',
                'maturity_rating'
            ])
            ->withQueryString();

        // Watchlist ids for card buttons
        $watchlistIds = [];

        if (auth()->check() && auth()->user()->isConsumer()) {
            $profile = auth()->user()->profiles()->first();

            if ($profile) {
                $watchlistIds = $profile
                    ->watchlist()
                    ->pluck('content_id')
                    ->toArray();
            }
        }

        /*
        |--------------------------------
        | AJAX LOAD MORE RESPONSE
        |--------------------------------
        */
        if ($request->ajax()) {
            return view('front.partials.browse-grid-items', [
                'contents' => $contents,
                'watchlistIds' => $watchlistIds
            ])->render();
        }

        return view('front.pages.browse', compact(
            'contents',
            'watchlistIds'
        ));
    }

    public function show(Content $content)
    {
        $similar = Content::where('type', $content->type)
            ->where('id', '!=', $content->id)
            ->where('is_published', true)
            ->latest()
            ->take(12)
            ->get();

        $resume = null;

        if (auth()->check()) {

            $profile = auth()->user()->profiles()->first();

            if ($profile) {
                $resume = \App\Models\WatchHistory::where('profile_id', $profile->id)
                    ->where('content_id', $content->id)
                    ->where('completed', false)
                    ->first();
            }
        }

        $seasons = collect();
        $selectedSeason = null;
        $episodes = collect();

        if ($content->type === 'series') {

            $seasonId = request('season');

            $seasons = $content->seasons()
                ->with('episodes')
                ->orderBy('season_number')
                ->get();

            $selectedSeason = $seasonId
                ? $seasons->firstWhere('id', (int)$seasonId)
                : $seasons->first();

            $episodes = $selectedSeason?->episodes ?? collect();
        }

        return view('front.pages.show', compact(
            'content',
            'similar',
            'resume',
            'seasons',
            'selectedSeason',
            'episodes'
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

        return view('front.search', compact('contents', 'q'));
    }



    public function watch(Content $content, Episode $episode = null)
    {
        $profile = auth()->user()?->profiles()->first();

        $resumeAt = 0;

        if ($profile) {
            $query = PlaybackProgress::where('profile_id', $profile->id)
                ->where('content_id', $content->id);

            if ($episode) {
                $query->where('episode_id', $episode->id);
            } else {
                $query->whereNull('episode_id');
            }

            $resumeAt = $query->value('position_seconds') ?? 0;
        }

        $episodes = [];
        $nextEpisode = null;

        $videoUrl = null;

        /*
        |--------------------------------
        | Resolve Video Source
        |--------------------------------
        */

        if ($episode) {

            $asset = VideoAsset::where('episode_id', $episode->id)
                ->where('is_processed', true)
                ->select('path')
                ->first();

            $videoUrl = $asset?->path;
        } else {
            // for movies
            $asset = VideoAsset::where('content_id', $content->id)
                ->whereNull('episode_id')
                ->where('is_processed', true)
                ->select('path')
                ->first();

            $videoUrl = $asset?->path;
        }
        /*
        |--------------------------------
        | Series Episode Handling
        |--------------------------------
        */

        if ($content->type === 'series') {

            $season = $content->seasons()
                        ->orderBy('season_number')
                        ->first();

            if ($episode) {
                $season = $episode->season;
            }

            if ($season) {
                $episodes = $season
                    ? $season->episodes()->orderBy('episode_number')->get()
                    : collect();
            }

            if ($episode) {
                $nextEpisode = Episode::where('season_id', $episode->season_id)
                    ->where('episode_number', '>', $episode->episode_number)
                    ->orderBy('episode_number')
                    ->first();
            }
        }

        $recommended = Content::where('id', '!=', $content->id)
            ->where('is_published', true)
            ->where('type', $content->type)
            ->latest()
            ->take(10)
            ->get([
                'id',
                'title',
                'poster',
                'thumbnail',
                'type'
            ]);

        return view('front.pages.watch', compact(
            'content',
            'episode',
            'episodes',
            'nextEpisode',
            'resumeAt',
            'videoUrl',
            'recommended'
        ));
    }

    public function watchEpisode(Content $content, Episode $episode)
    {
        abort_if($content->type !== 'series', 404);
        abort_if($episode->content_id !== $content->id, 404);

        $resumeAt = 0;

        if (auth()->check()) {
            $profile = auth()->user()?->profiles()->first();
            if ($profile) {
                $progress = PlaybackProgress::where('profile_id', $profile->id)
                    ->where('content_id', $content->id)
                    ->where('episode_id', $episode->id)
                    ->first();

                $resumeAt = $progress?->position_seconds ?? 0;
            }
        }

        return view('front.pages.watch', [
            'content' => $content,
            'episode' => $episode,
            'resumeAt' => $resumeAt
        ]);
    }
}
