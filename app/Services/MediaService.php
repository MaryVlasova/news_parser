<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Сlass for getting and saving media resources
 */
class MediaService 
{

    /**
     * upload file
     *
     * @param string $url
     * @return string
     */
    public function uploadResource ($url) {

        $content = file_get_contents($url);
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        $folder = $extension == 'mp4' ? 'video/' : 'images/' ; 
        $filename = $folder . time() . '_' . Str::random(5) . '.'. $extension; 
        Storage::disk('public')->put($filename, $content);
        return $filename;

    }
}