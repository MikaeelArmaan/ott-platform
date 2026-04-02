<?php

namespace App\Jobs;

use App\Models\Content;
use App\Models\Episode;
use App\Models\VideoAsset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ConvertVideoToHls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $videoAssetId) {}

    public function handle(): void
    {
        Log::info('HLS Job started', [
            'asset_id' => $this->videoAssetId
        ]);

        $asset = VideoAsset::findOrFail($this->videoAssetId);

        $src = Storage::disk('public')->path($asset->path);

        if (!$asset->path || !file_exists($src)) {
            $asset->update([
                'status' => 'failed',
                'error' => 'Source file not found'
            ]);

            Log::error('Source file missing', [
                'path' => $asset->path
            ]);

            return;
        }

        $src = str_replace('\\', '/', $src);

        $baseRel = $asset->episode_id
            ? "hls/episode/{$asset->episode_id}"
            : "hls/content/{$asset->content_id}";

        $outAbs = Storage::disk('public')->path($baseRel);
        $outAbs = str_replace('\\', '/', $outAbs);

        if (!is_dir($outAbs)) {
            mkdir($outAbs, 0777, true);
        }

        // Cleanup old HLS
        foreach (glob($outAbs . '/*') as $file) {
            if (is_file($file)) unlink($file);
        }

        $asset->update(['status' => 'processing']);

        Log::info('FFmpeg processing started', [
            'output_dir' => $baseRel
        ]);

        $cmd = sprintf(
            'ffmpeg -y -i %s -filter_complex ' .
                '"[0:v]split=2[v1][v2];' .
                '[v1]scale=640:360[v360];' .
                '[v2]scale=1280:720[v720]" ' .

                '-map "[v360]" -map 0:a:0? -c:v:0 libx264 -preset fast -crf 23 -b:v:0 800k -c:a:0 aac -b:a:0 128k ' .
                '-map "[v720]" -map 0:a:0? -c:v:1 libx264 -preset fast -crf 23 -b:v:1 2800k -c:a:1 aac -b:a:1 128k ' .

                '-f hls -hls_time 6 -hls_playlist_type vod ' .
                '-hls_flags independent_segments ' .
                '-hls_segment_filename %s/v%%v_%%03d.ts ' .
                '-master_pl_name master.m3u8 ' .
                '-var_stream_map "v:0,a:0 v:1,a:1" ' .
                '%s/v%%v.m3u8',

            escapeshellarg($src),
            $outAbs,
            $outAbs
        );

        exec($cmd . ' 2>&1', $output, $code);

        Log::info('FFmpeg finished', [
            'code' => $code
        ]);

        Log::debug('FFmpeg output', $output);

        if ($code !== 0 || !file_exists($outAbs . '/master.m3u8')) {

            $asset->update([
                'status' => 'failed',
                'error' => implode("\n", $output)
            ]);

            Log::error('HLS generation failed', [
                'asset_id' => $asset->id
            ]);

            return;
        }

        // Duration
        $duration = shell_exec(
            "ffprobe -v error -show_entries format=duration -of default=nw=1:nk=1 " . escapeshellarg($src)
        );

        $duration = (int) round($duration);

        // ✅ IMPORTANT FIX
        $asset->update([
            'status' => 'ready',
            'is_processed' => true, // 🔥 REQUIRED FOR UI
            'hls_master_url' => "{$baseRel}/master.m3u8",
            'duration' => $duration
        ]);

        Log::info('HLS completed successfully', [
            'asset_id' => $asset->id,
            'duration' => $duration
        ]);

        if ($asset->episode_id) {
            Episode::where('id', $asset->episode_id)
                ->update(['duration' => $duration]);
        } else {
            Content::where('id', $asset->content_id)
                ->update(['duration' => $duration]);
        }

        // ❌ REMOVED THIS (VERY IMPORTANT)
        // Storage::disk('public')->delete($asset->path);
    }
}