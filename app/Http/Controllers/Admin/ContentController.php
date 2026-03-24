<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\VideoAsset;
use App\Jobs\ConvertVideoToHls;
use App\Models\Season;
use App\Models\Episode;
use App\Models\Genre;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST CONTENTS
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return view('admin.contents.index', [
            'contents' => Content::withCount(['seasons', 'episodes'])
                ->with('videoAsset')
                ->latest()
                ->paginate(20),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE FORM
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('admin.contents.create', [
            'content' => new Content(),
            'genres' => Genre::all()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE CONTENT
    |--------------------------------------------------------------------------
    */
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
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',

            'video' => 'nullable|file|mimetypes:video/mp4|max:204800',
        ]);

        $data['is_published'] = $request->has('is_published');

        DB::transaction(function () use ($request, &$content, &$data) {

            /*
            |-----------------------------
            | Images
            |-----------------------------
            */
            foreach (['poster', 'thumbnail', 'backdrop'] as $img) {
                if ($request->hasFile($img)) {
                    $data[$img . '_url'] = $request->file($img)
                        ->store($img . 's', 'public');
                }
            }

            /*
            |-----------------------------
            | Slug
            |-----------------------------
            */
            $slug = Str::slug($data['title']);
            $original = $slug;
            $i = 1;

            while (Content::where('slug', $slug)->exists()) {
                $slug = $original . '-' . $i++;
            }

            $data['slug'] = $slug;

            /*
            |-----------------------------
            | Create Content
            |-----------------------------
            */
            $content = Content::create($data);

            if ($request->has('genres')) {
                $content->genres()->sync($request->genres);
            }

            /*
            |-----------------------------
            | Movie Video
            |-----------------------------
            */
            if ($request->hasFile('video')) {

                $videoPath = $request->file('video')
                    ->store('videos/movies', 'public');

                $asset = VideoAsset::create([
                    'content_id' => $content->id,
                    'type' => 'movie',
                    'quality' => 'source',
                    'path' => $videoPath,
                    'mime_type' => $request->file('video')->getMimeType(),
                    'size' => $request->file('video')->getSize(),
                    'is_default' => true,
                    'is_processed' => false
                ]);

                ConvertVideoToHls::dispatch($asset->id)
                    ->onQueue('video-processing');
            }
        });

        return redirect()
            ->route('admin.contents.index')
            ->with('success', 'Content added successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Content $content)
    {
        $content->load([
            'videoAsset',
            'genres',
            'seasons.episodes.videoAsset'
        ]);

        $seasonsJson = $content->seasons->map(function ($season) {
            return [
                'id' => $season->id,
                'name' => $season->title,
                'season_number' => $season->season_number,
                'episodes' => $season->episodes->map(function ($ep) {
                    return [
                        'id' => $ep->id,
                        'title' => $ep->title,
                        'description' => $ep->description,
                        'runtime' => $ep->duration ?? 0,
                        'episode_number' => $ep->episode_number,
                        'thumbnail' => $ep->thumbnail,
                        'video' => $ep->videoAsset?->path,
                        'is_processed' => $ep->videoAsset?->is_processed,
                    ];
                })->values()
            ];
        })->values();
        return view('admin.contents.edit', compact('content', 'seasonsJson'))
            ->with('genres', Genre::all());
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Content $content)
    {
        $data = $request->validate([
            'type' => 'required|in:movie,series',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'language' => 'nullable|string',
            'release_date' => 'nullable|date',
            'duration' => 'nullable|integer',
            'maturity_rating' => 'nullable|string',

            'poster' => 'nullable|image|max:2048',
            'thumbnail' => 'nullable|image|max:2048',
            'backdrop' => 'nullable|image|max:4096',

            'video' => 'nullable|file|mimetypes:video/mp4|max:204800',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
            'episode_videos.*.*' => 'nullable|file|mimetypes:video/mp4|max:204800',
            'episode_thumbnails.*.*' => 'nullable|image|max:4096',
        ]);

        $data['is_published'] = $request->has('is_published');

        DB::transaction(function () use ($request, $content, $data) {

            /*
            |-----------------------------
            | Images Replace
            |-----------------------------
            */
            foreach (['poster', 'thumbnail', 'backdrop'] as $img) {

                if ($request->hasFile($img)) {

                    if ($content->{$img . '_url'}) {
                        Storage::disk('public')->delete($content->{$img . '_url'});
                    }

                    $content->{$img . '_url'} =
                        $request->file($img)->store($img . 's', 'public');
                }
            }

            /*
            |-----------------------------
            | Movie Video Replace
            |-----------------------------
            */
            if ($request->hasFile('video')) {
                if ($content->videoAsset) {

                    Storage::disk('public')->delete($content->videoAsset->path);

                    if ($content->videoAsset->hls_master_url) {
                        Storage::disk('public')->deleteDirectory(
                            dirname($content->videoAsset->hls_master_url)
                        );
                    }

                    $content->videoAsset->delete();
                }

                $path = $request->file('video')
                    ->store('videos/movies', 'public');

                $asset = VideoAsset::create([
                    'content_id' => $content->id,
                    'type' => 'movie',
                    'quality' => 'source',
                    'path' => $path,
                    'mime_type' => $request->file('video')->getMimeType(),
                    'size' => $request->file('video')->getSize(),
                    'is_default' => true,
                    'is_processed' => false
                ]);

                ConvertVideoToHls::dispatch($asset->id);
            }

            /*
            |-----------------------------
            | Slug Update
            |-----------------------------
            */
            if ($content->title !== $data['title']) {

                $slug = Str::slug($data['title']);
                $original = $slug;
                $i = 1;

                while (Content::where('slug', $slug)
                    ->where('id', '!=', $content->id)->exists()
                ) {
                    $slug = $original . '-' . $i++;
                }

                $data['slug'] = $slug;
            }

            $content->update($data);

            if ($request->has('genres')) {
                $content->genres()->sync($request->genres);
            }
            /*
            |-----------------------------
            | SERIES LOGIC
            |-----------------------------
            */
            if ($data['type'] === 'series' && $request->seasons) {

                $seasons = json_decode($request->seasons, true);

                foreach ($seasons as $sIndex => $seasonData) {

                    $season = $content->seasons()->updateOrCreate(
                        ['season_number' => $sIndex + 1],
                        ['title' => $seasonData['name']]
                    );

                    foreach ($seasonData['episodes'] as $eIndex => $epData) {

                        $episode = $season->episodes()->updateOrCreate(
                            [
                                'episode_number' => $eIndex + 1,
                                'season_id' => $season->id,
                            ],
                            [
                                'content_id' => $content->id, // 🔥 FIX
                                'title' => $epData['title'],
                                'duration' => 0,
                                'description' => $epData['description'] ?? null,
                            ]
                        );

                        /*
                        | Episode Video
                        */
                        if ($request->hasFile("episode_videos.$sIndex.$eIndex")) {

                            $file = $request->file("episode_videos.$sIndex.$eIndex");

                            $episode->videoAsset()->delete();

                            $path = $file->store('videos/episodes', 'public');

                            $asset = VideoAsset::create([
                                'content_id' => $content->id,
                                'episode_id' => $episode->id,
                                'type' => 'episode',
                                'quality' => 'source',
                                'path' => $path,
                                'mime_type' => $file->getMimeType(),
                                'size' => $file->getSize(),
                                'is_default' => true,
                                'is_processed' => false
                            ]);

                            ConvertVideoToHls::dispatch($asset->id);
                        }

                        /*
                        | Episode Thumbnail
                        */
                        if ($request->hasFile("episode_thumbnails.$sIndex.$eIndex")) {

                            $file = $request->file("episode_thumbnails.$sIndex.$eIndex");

                            if ($episode->thumbnail) {
                                Storage::disk('public')->delete($episode->thumbnail);
                            }

                            $episode->update([
                                'thumbnail' => $file->store('episode-thumbs', 'public')
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()
            ->route('admin.contents.index')
            ->with('success', 'Content updated successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(Content $content)
    {
        DB::transaction(function () use ($content) {

            if ($content->videoAsset) {
                Storage::disk('public')->delete($content->videoAsset->path);
                $content->videoAsset->delete();
            }

            foreach (['poster_url', 'thumbnail_url', 'backdrop_url'] as $img) {
                if ($content->$img) {
                    Storage::disk('public')->delete($content->$img);
                }
            }

            $content->seasons()->each(function ($season) {
                $season->episodes()->each(function ($ep) {
                    $ep->videoAssets()->delete();
                    $ep->delete();
                });
                $season->delete();
            });

            $content->delete();
        });

        return response()->json(['success' => true]);
    }

    /*
    |--------------------------------------------------------------------------
    | GENERIC TOGGLE (PUBLISH / FEATURED / TRENDING / RECOMMENDED)
    |--------------------------------------------------------------------------
    */
    public function togglePublish(Request $request, Content $content)
    {
        $allowed = [
            'is_published',
            'is_featured',
            'is_trending',
            'is_recommended',
        ];

        $field = $request->input('field');
        $value = $request->boolean('value');

        if (!in_array($field, $allowed)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid field'
            ], 422);
        }

        // update
        $content->{$field} = $value;

        if ($field === 'is_published') {
            $content->published_at = $value ? now() : null;
        }

        $content->save();

        // 🔥 human readable labels
        $labels = [
            'is_published'   => 'Published',
            'is_featured'    => 'Featured',
            'is_trending'    => 'Trending',
            'is_recommended' => 'Recommended',
        ];

        $label = $labels[$field] ?? ucfirst(str_replace('_', ' ', $field));

        // 🔥 dynamic message
        $message = $value
            ? "{$content->title} marked as {$label}"
            : "{$label} removed from {$content->title}";

        return response()->json([
            'success' => true,
            'field'   => $field,
            'value'   => $value,
            'message' => $message
        ]);
    }
}
