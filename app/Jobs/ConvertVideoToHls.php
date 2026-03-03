<?php

namespace App\Jobs;

use App\Models\VideoAsset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ConvertVideoToHls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $videoAssetId) {}

    public function handle(): void
    {
        $asset = VideoAsset::findOrFail($this->videoAssetId);

        $src = Storage::disk('public')->path($asset->source_url);
        $outRel = "hls/{$asset->content_id}";
        $outAbs = Storage::disk('public')->path($outRel);

        if (!is_dir($outAbs)) {
            mkdir($outAbs, 0777, true);
        }

        $cmd = sprintf(
                    'ffmpeg -y -i "%s" -filter_complex ' .
                    '"[0:v]split=2[v1][v2];' .
                    '[v1]scale=w=640:h=360[v360];' .
                    '[v2]scale=w=1280:h=720[v720]" ' .

                    '-map "[v360]" -map 0:a? -c:v:0 libx264 -b:v:0 800k ' .
                    '-map "[v720]" -map 0:a? -c:v:1 libx264 -b:v:1 2800k ' .

                    '-f hls -hls_time 6 -hls_playlist_type vod ' .
                    '-hls_segment_filename "%s/v%%v_%%03d.ts" ' .
                    '-master_pl_name master.m3u8 ' .
                    '-var_stream_map "v:0,a:0 v:1,a:1" ' .
                    '"%s/v%%v.m3u8"',
                    $src,
                    $outAbs,
                    $outAbs
                );

        exec($cmd, $o, $code);

        if ($code !== 0) {
            $asset->update([
                'status' => 'failed',
                'error' => 'FFmpeg failed'
            ]);
            return;
        }

        $asset->update([
            'status' => 'ready',
            'hls_master_url' => "{$outRel}/master.m3u8",
            'duration_seconds' => (int) shell_exec(
                "ffprobe -v error -show_entries format=duration -of default=nw=1:nk=1 \"$src\""
            )
        ]);
        Storage::disk('public')->delete($asset->source_url);
    }
}
