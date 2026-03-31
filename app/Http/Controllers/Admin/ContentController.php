<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ConvertVideoToHls;
use App\Models\Content;
use App\Models\Genre;
use App\Models\VideoAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    public function index()
    {
        return view('admin.contents.index', [
            'contents' => Content::withCount(['seasons', 'episodes'])
                ->with('videoAsset')
                ->latest()
                ->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.contents.create', [
            'content' => new Content(),
            'genres' => Genre::all(),
        ]);
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

            'poster' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'backdrop' => 'nullable|string',
            'video' => 'nullable|string',

            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
            'seasons' => 'nullable|string',
            'user_id' => 'nullable|integer',
        ]);

        $data['is_published'] = $request->has('is_published');

        DB::transaction(function () use ($request, $data) {

            // 🔥 CREATE CONTENT
            $content = Content::create([
                'type' => $data['type'],
                'title' => $data['title'],
                'slug' => Str::slug($data['title']) . '-' . uniqid(),
                'description' => $data['description'] ?? null,
                'language' => $data['language'] ?? null,
                'release_date' => $data['release_date'] ?? null,
                'duration' => $data['runtime_seconds'] ?? null,
                'maturity_rating' => $data['maturity_rating'] ?? null,
                'poster_url' => $this->cleanPath($request->input('poster')),
                'thumbnail_url' => $this->cleanPath($request->input('thumbnail')),
                'backdrop_url' => $this->cleanPath($request->input('backdrop')),
                'is_published' => $data['is_published'],
                'user_id'      =>  $data['user_id']??auth()->user()->id(),
            ]);

            // 🔥 MAIN VIDEO (MOVIE)
            if ($request->input('video')) {
                $this->createVideoAsset(
                    $content->id,
                    null,
                    $request->input('video'),
                    'movie'
                );
            }

            // 🔥 SEASONS
            $seasonsData = json_decode($request->input('seasons', '[]'), true) ?? [];

            foreach ($seasonsData as $seasonData) {

                $season = $content->seasons()->create([
                    'title' => $seasonData['title'] ?? '',
                    'season_number' => (int) ($seasonData['season_number'] ?? 0),
                ]);

                foreach ($seasonData['episodes'] ?? [] as $epData) {

                    $episode = $season->episodes()->create([
                        'content_id' => $content->id,
                        'title' => $epData['title'] ?? '',
                        'description' => $epData['description'] ?? null,
                        'duration' => isset($epData['duration']) ? (int) $epData['duration'] : null,
                        'episode_number' => (int) ($epData['episode_number'] ?? 0),
                        'thumbnail' => $this->cleanPath($epData['thumbnail'] ?? null),
                    ]);

                    if (!empty($epData['video'])) {
                        $this->createVideoAsset(
                            $content->id,
                            $episode->id,
                            $epData['video'],
                            'episode'
                        );
                    }
                }
            }

            // 🔥 GENRES
            if ($request->has('genres')) {
                $content->genres()->sync($request->genres);
            }
        });

        return redirect()
            ->route('admin.contents.index')
            ->with('success', 'Content created successfully');
    }

    public function edit(Content $content)
    {
        $content->load([
            'videoAsset',
            'genres',
            'seasons.episodes.videoAsset',
        ]);

        $seasonsJson = $content->seasons->map(function ($season) {
            return [
                'id' => $season->id,
                'title' => $season->title,
                'season_number' => $season->season_number,
                'episodes' => $season->episodes->map(function ($ep) {
                    return [
                        'id' => $ep->id,
                        'title' => $ep->title,
                        'description' => $ep->description,
                        'duration' => $ep->duration,
                        'episode_number' => $ep->episode_number,
                        'thumbnail' => $ep->thumbnail,
                        'video' => $ep->videoAsset?->path,
                        'is_processed' => $ep->videoAsset?->is_processed,
                    ];
                })->values(),
            ];
        })->values();
        return view('admin.contents.edit', compact('content', 'seasonsJson'))
            ->with('genres', Genre::all());
    }

    public function update(Request $request, Content $content)
    {
        $data = $request->validate([
            'type' => 'required|in:movie,series',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'language' => 'nullable|string|max:50',
            'release_date' => 'nullable|date',
            'runtime_seconds' => 'nullable|integer',
            'maturity_rating' => 'nullable|string|max:20',

            'poster' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'backdrop' => 'nullable|string',
            'video' => 'nullable|string',

            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
            'seasons' => 'nullable|string',
        ]);

        $data['is_published'] = $request->has('is_published');

        DB::transaction(function () use ($request, $content, $data) {

            // 🔥 SLUG UPDATE
            $slug = $content->slug;
            if ($content->title !== $data['title']) {
                $slug = Str::slug($data['title']);
                $original = $slug;
                $i = 1;

                while (
                    Content::where('slug', $slug)
                    ->where('id', '!=', $content->id)
                    ->exists()
                ) {
                    $slug = $original . '-' . $i++;
                }
            }

            // 🔥 UPDATE CONTENT
            $content->update([
                'type' => $data['type'],
                'title' => $data['title'],
                'slug' => $slug,
                'description' => $data['description'] ?? null,
                'language' => $data['language'] ?? null,
                'release_date' => $data['release_date'] ?? null,
                'duration' => $data['runtime_seconds'] ?? null,
                'maturity_rating' => $data['maturity_rating'] ?? null,
                'poster_url' => $this->cleanPath($request->input('poster')),
                'thumbnail_url' => $this->cleanPath($request->input('thumbnail')),
                'backdrop_url' => $this->cleanPath($request->input('backdrop')),
                'is_published' => $data['is_published'],
            ]);

            // 🔥 MAIN VIDEO
            $newMainVideo = $request->input('video');
            $oldMainAsset = $content->videoAsset;

            if (empty($newMainVideo) && $oldMainAsset) {
                $this->deleteVideoAsset($oldMainAsset);
            } elseif ($newMainVideo && (!$oldMainAsset || $oldMainAsset->path !== $newMainVideo)) {
                if ($oldMainAsset) {
                    $this->deleteVideoAsset($oldMainAsset);
                }

                $this->createVideoAsset($content->id, null, $newMainVideo, 'movie');
            }

            // 🔥 SEASONS SYNC
            $seasonsData = json_decode($request->input('seasons', '[]'), true) ?? [];

            $existingSeasonIds = $content->seasons()->pluck('id')->toArray();
            $incomingSeasonIds = collect($seasonsData)->pluck('id')->filter()->toArray();

            $seasonIdsToDelete = array_diff($existingSeasonIds, $incomingSeasonIds);

            foreach ($content->seasons()->whereIn('id', $seasonIdsToDelete)->get() as $season) {
                foreach ($season->episodes as $ep) {
                    $this->deleteVideoAsset($ep->videoAsset);
                    $ep->delete();
                }
                $season->delete();
            }

            foreach ($seasonsData as $seasonData) {

                $season = $content->seasons()->updateOrCreate(
                    ['id' => $seasonData['id'] ?? null],
                    [
                        'title' => $seasonData['title'] ?? '',
                        'season_number' => (int) ($seasonData['season_number'] ?? 0),
                    ]
                );

                $existingEpisodeIds = $season->episodes()->pluck('id')->toArray();
                $incomingEpisodeIds = collect($seasonData['episodes'] ?? [])
                    ->pluck('id')->filter()->toArray();

                $episodeIdsToDelete = array_diff($existingEpisodeIds, $incomingEpisodeIds);

                foreach ($season->episodes()->whereIn('id', $episodeIdsToDelete)->get() as $ep) {
                    $this->deleteVideoAsset($ep->videoAsset);
                    $ep->delete();
                }

                foreach ($seasonData['episodes'] ?? [] as $epData) {

                    $episode = $season->episodes()->updateOrCreate(
                        ['id' => $epData['id'] ?? null],
                        [
                            'content_id' => $content->id,
                            'title' => $epData['title'] ?? '',
                            'description' => $epData['description'] ?? null,
                            'duration' => isset($epData['duration']) ? (int) $epData['duration'] : null,
                            'episode_number' => (int) ($epData['episode_number'] ?? 0),
                            'thumbnail' => $this->cleanPath($epData['thumbnail'] ?? null),
                        ]
                    );

                    $newVideo = $epData['video'] ?? null;
                    $oldAsset = $episode->videoAsset;

                    if (empty($newVideo) && $oldAsset) {
                        $this->deleteVideoAsset($oldAsset);
                    } elseif ($newVideo && (!$oldAsset || $oldAsset->path !== $newVideo)) {
                        if ($oldAsset) {
                            $this->deleteVideoAsset($oldAsset);
                        }

                        $this->createVideoAsset(
                            $content->id,
                            $episode->id,
                            $newVideo,
                            'episode'
                        );
                    }
                }
            }

            if ($request->has('genres')) {
                $content->genres()->sync($request->genres);
            }
        });

        return redirect()
            ->route('admin.contents.index')
            ->with('success', 'Content updated successfully');
    }

    // 🔥 HELPERS

    private function createVideoAsset($contentId, $episodeId, $path, $type)
    {
        $asset = VideoAsset::create([
            'content_id' => $contentId,
            'episode_id' => $episodeId,
            'type' => $type,
            'quality' => 'source',
            'path' => $this->cleanPath($path),
            'is_default' => true,
            'is_processed' => false,
        ]);

        if (!str_starts_with($asset->path, 'http')) {
            ConvertVideoToHls::dispatch($asset->id)->onQueue('video-processing');
        }
    }

    private function deleteVideoAsset($asset)
    {
        if (!$asset) return;

        if ($asset->hls_master_url) {
            Storage::disk('public')->deleteDirectory(dirname($asset->hls_master_url));
        }

        $asset->delete();
    }

    private function cleanPath($path)
    {
        if (!$path) return null;

        // 🔥 FIX: normalize slashes
        $path = str_replace('\\', '/', $path);

        return preg_replace('#^https?://[^/]+/storage/#', '', $path);
    }
}
