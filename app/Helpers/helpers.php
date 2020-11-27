<?php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\File;

/**
 * Upload File To Akoneya Media
 */

if (!function_exists('base64ImageUploadToAkoneyaMedia')) {
    function base64ImageUploadToAkoneyaMedia($base64String, $directoryName)
    {
        $file = base64_decode($base64String);
        $fileExtension = ".png";
        $fileName = time().Str::random(6).$fileExtension;
        $mediaPath = $directoryName."/".$fileName;
        if (Storage::disk('akoneya')->put($mediaPath, $file)) {
            return $mediaPath;
        }
        return null;
    }
}
/**
 * Delete File From Akoneya Media
 */
if (!function_exists('deleteFileFromAkoneyaMedia')) {
    function deleteFileFromAkoneyaMedia($filePath)
    {
        Storage::disk('akoneya')->delete($filePath);
    }
}

/*
 * Get Fiel URL from Akoneya Medai
 */
if (!function_exists('getFileUrlFromAkoneyaMedia')) {
    function getFileUrlFromAkoneyaMedia($filePath)
    {
        $fileUrl = Storage::disk("akoneya")->url($filePath);
        return $fileUrl;
    }
}
/**
* Check Request Auth User Id
*
*/

if (!function_exists('check_auth_user_id')) {
    function check_auth_user_id($authUserId, $passedId)
    {
        return $authUserId == $passedId;
    }
}

if (!function_exists("strTo24hrFormat")) {
    function strTo24hrFormat($value)
    {
        return date("Y-m-d H:i:s", strtotime($value));
    }
}
