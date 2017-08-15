<?php
/**
 * Created by PhpStorm.
 * User: Kaotic
 * Date: 14/08/2017
 * Time: 21:38
 */

function randomString($l = 8)
{
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $l);
}
function isPlayable($filePath)
{
    $playable = [
        'image/png',
        'image/jpeg',
        'video/mp4'
    ];
    return in_array(mime_content_type($filePath), $playable);
}
function humanFileSize($size, $unit = "")
{
    if ((!$unit && $size >= 1<<30) || $unit == "GB") {
        return number_format($size/(1<<30), 2)."GB";
    }
    if ((!$unit && $size >= 1<<20) || $unit == "MB") {
        return number_format($size/(1<<20), 2)."MB";
    }
    if ((!$unit && $size >= 1<<10) || $unit == "KB") {
        return number_format($size/(1<<10), 2)."KB";
    }
    return number_format($size)." bytes";
}