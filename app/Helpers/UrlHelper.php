<?php
function media_url($path)
{
    if (!$path) return null;

    return filter_var($path, FILTER_VALIDATE_URL)
        ? $path
        : asset('storage/' . $path);
}

if (!function_exists('formatDuration')) {
    function formatDuration(?int $seconds): ?string
    {
        if (!$seconds || $seconds <= 0) {
            return null;
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        // Format with leading zeros
        $minutesFormatted = str_pad($minutes, 2, '0', STR_PAD_LEFT);
        $secondsFormatted = str_pad($secs, 2, '0', STR_PAD_LEFT);

        if ($hours > 0) {
            return "{$hours}:{$minutesFormatted}:{$secondsFormatted}";
        }

        return "{$minutes}:{$secondsFormatted}";
    }
}
