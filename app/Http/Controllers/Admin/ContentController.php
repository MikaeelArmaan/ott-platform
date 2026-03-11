<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\VideoAsset;
use App\Jobs\ConvertVideoToHls;
use App\Models\Season;
use App\Models\Episode;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    public function index()
    {
        return view('admin.contents.index', [
            'contents' => Content::withCount([
                'seasons',
                'episodes',
                'videoAsset'
            ])
                ->latest()
                ->paginate(20),
        ]);
    }


    public function create()
    {
        return view('admin.contents.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:movie,series',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'language' => 'nullable|string|max:50',
            'release_date' => 'nullable|date',
            'runtime_seconds' => 'nullable|integer',
            'maturity_rating' => 'nullable|string|max:20',
            'poster' => 'nullable|image|max:2048',
            'thumbnail' => 'nullable|image|max:2048',
            'backdrop' => 'nullable|image|max:4096',
            'video' => 'nullable|mimes:mp4|max:200000'
        ]);

        $data['is_published'] = $request->has('is_published');

        /*
        |--------------------------------------------------------------------------
        | Upload Images
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('poster')) {
            $data['poster_url'] =
                $request->file('poster')->store('posters', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_url'] =
                $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->hasFile('backdrop')) {
            $data['backdrop_url'] =
                $request->file('backdrop')->store('backdrops', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Video
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('video')) {
            $data['video_url'] =
                $request->file('video')->store('videos/movies', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */

        $slug = Str::slug($data['title']);

        if (Content::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(5);
        }

        $data['slug'] = $slug;

        /*
        |--------------------------------------------------------------------------
        | Create Content
        |--------------------------------------------------------------------------
        */

        $content = Content::create($data);

        /*
        |--------------------------------------------------------------------------
        | Create Video Asset
        |--------------------------------------------------------------------------
        */

        if (!empty($data['video_url'])) {

            $asset = VideoAsset::create([
                'content_id' => $content->id,
                'source_url' => $data['video_url'],
                'status' => 'uploaded'
            ]);

            ConvertVideoToHls::dispatch($asset)
                ->onQueue('video-processing');
        }

        return redirect()
            ->route('admin.contents.index')
            ->with('success', 'Content added successfully!');
    }


    /*
    |--------------------------------------------------------------------------
    | STORE SEASON
    |--------------------------------------------------------------------------
    */

    public function storeSeason(Request $request, Content $content)
    {
        abort_if($content->type !== 'series', 400);

        $data = $request->validate([
            'season_number' => 'required|integer|min:1',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Season::updateOrCreate(
            [
                'content_id' => $content->id,
                'season_number' => $data['season_number']
            ],
            $data
        );

        return back()->with('success', 'Season saved');
    }


    /*
    |--------------------------------------------------------------------------
    | STORE EPISODE
    |--------------------------------------------------------------------------
    */

    public function storeEpisode(Request $request, Season $season)
    {
        $data = $request->validate([
            'episode_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'runtime_seconds' => 'nullable|integer',
            'release_date' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4|max:200000',
            'is_published' => 'nullable'
        ]);

        $data['is_published'] = $request->has('is_published');

        /*
        |--------------------------------------------------------------------------
        | Upload Episode Thumbnail
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_url'] =
                $request->file('thumbnail')->store('episode-thumbs', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Episode Video
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('video')) {
            $data['video_url'] =
                $request->file('video')->store('videos/episodes', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Create Episode
        |--------------------------------------------------------------------------
        */

        $episode = Episode::updateOrCreate(
            [
                'season_id' => $season->id,
                'episode_number' => $data['episode_number']
            ],
            $data
        );

        /*
        |--------------------------------------------------------------------------
        | Create Video Asset
        |--------------------------------------------------------------------------
        */

        if (!empty($data['video_url'])) {

            $asset = VideoAsset::updateOrCreate(
                ['episode_id' => $episode->id],
                [
                    'content_id' => $season->content_id,
                    'source_url' => $data['video_url'],
                    'status' => 'uploaded'
                ]
            );

            ConvertVideoToHls::dispatch($asset)
                ->onQueue('video-processing');
        }

        return back()->with('success', 'Episode saved');
    }


    public function edit(Content $content)
    {
        $content->load('seasons.episodes');

        return view('admin.contents.edit', compact('content'));
    }


    public function update(Request $request, Content $content)
    {
        $data = $request->validate([
            'type' => 'required|in:movie,series',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'language' => 'nullable|string',
            'release_date' => 'nullable|date',
            'runtime_seconds' => 'nullable|integer',
            'maturity_rating' => 'nullable|string'
        ]);

        $content->update($data);

        return redirect()
            ->route('admin.contents.index')
            ->with('success', 'Content updated');
    }


    public function destroy(Content $content)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete Video Assets
        |--------------------------------------------------------------------------
        */

        foreach ($content->videoAssets as $asset) {

            if ($asset->source_url) {
                Storage::disk('public')->delete($asset->source_url);
            }

            if ($asset->hls_master_url) {
                Storage::disk('public')
                    ->deleteDirectory(dirname($asset->hls_master_url));
            }

            $asset->delete();
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Images
        |--------------------------------------------------------------------------
        */

        if ($content->poster_url) {
            Storage::disk('public')->delete($content->poster_url);
        }

        if ($content->thumbnail_url) {
            Storage::disk('public')->delete($content->thumbnail_url);
        }

        if ($content->backdrop_url) {
            Storage::disk('public')->delete($content->backdrop_url);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Seasons & Episodes
        |--------------------------------------------------------------------------
        */

        $content->seasons()->each(function ($season) {

            $season->episodes()->each(function ($episode) {

                if ($episode->videoAsset) {
                    $episode->videoAsset->delete();
                }

                $episode->delete();
            });

            $season->delete();
        });

        $content->delete();

        return back()->with('success', 'Content deleted');
    }

    public function togglePublish(Request $request, Content $content)
    {
        $content->is_published = $request->boolean('status');
        $content->save();

        return response()->json([
            'success' => true
        ]);
    }
}
