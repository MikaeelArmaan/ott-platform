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

class ContentController extends Controller
{
    public function index()
    {
        return view('admin.contents.index', [
            'contents' => Content::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required',
            'title' => 'required',
            'description' => 'nullable',
            'language' => 'nullable',
            'release_date' => 'nullable',
            'runtime_seconds' => 'nullable|integer',
            'maturity_rating' => 'nullable',
            'poster' => 'nullable|image|max:2048',
            'thumbnail' => 'nullable|image|max:2048',
            'backdrop' => 'nullable|image|max:4096',
            'video' => 'nullable|mimes:mp4|max:200000'
        ]);

        // Checkbox fix
        $data['is_published'] = $request->has('is_published');

        // Upload images
        if ($request->hasFile('poster')) {
            $data['poster_url'] = $request->file('poster')
                ->store('posters','public');
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_url'] = $request->file('thumbnail')
                ->store('thumbnails','public');
        }

        if ($request->hasFile('backdrop')) {
            $data['backdrop_url'] = $request->file('backdrop')
                ->store('backdrops','public');
        }

        if ($request->hasFile('video')) {
            $data['video_url'] = $request->file('video')
                ->store('videos','public');
        }

        // after Content::create($data);
        $content = Content::create($data);

        if (!empty($data['video_url'])) {
            $asset = VideoAsset::create([
                'content_id' => $content->id,
                'source_url' => $data['video_url'],
                'status' => 'processing'
            ]);

            ConvertVideoToHls::dispatch($asset->id);
        }

        return back()->with('success','Content added successfully!');
    }


    public function destroy(Content $content)
    {
        if ($content->poster_url) {
            Storage::disk('public')->delete($content->poster_url);
        }

        if ($content->thumbnail_url) {
            Storage::disk('public')->delete($content->thumbnail_url);
        }

        if ($content->backdrop_url) {
            Storage::disk('public')->delete($content->backdrop_url);
        }

        if ($content->video_url) {
            Storage::disk('public')->delete($content->video_url);
        }

        $content->delete();

        return back()->with('success','Content deleted');
    }

    public function storeSeason(Request $r, Content $content)
    {
        abort_if($content->type !== 'series', 400);

        $data = $r->validate([
            'season_number' => 'required|integer|min:1',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Season::updateOrCreate(
            ['content_id' => $content->id, 'season_number' => $data['season_number']],
            $data
        );

        return back()->with('success','Season saved');
    }

    public function storeEpisode(Request $r, Season $season)
    {
        $data = $r->validate([
            'episode_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'runtime_seconds' => 'nullable|integer',
            'release_date' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4|max:200000',
            'is_published' => 'nullable',
        ]);

        $data['is_published'] = $r->has('is_published');

        if ($r->hasFile('thumbnail')) {
            $data['thumbnail_url'] = $r->file('thumbnail')->store('episode-thumbs','public');
        }
        if ($r->hasFile('video')) {
            $data['video_url'] = $r->file('video')->store('episode-videos','public');
        }

        $episode = Episode::updateOrCreate(
            ['season_id' => $season->id, 'episode_number' => $data['episode_number']],
            $data
        );

        // Create asset + dispatch HLS if video_url exists
        if (!empty($data['video_url'])) {
            $asset = \App\Models\VideoAsset::create([
                'content_id' => $season->content_id,
                'episode_id' => $episode->id,
                'source_url' => $data['video_url'],
                'status' => 'processing'
            ]);

            \App\Jobs\ConvertVideoToHls::dispatch($asset->id);
        }

        return back()->with('success','Episode saved');
    }

}
