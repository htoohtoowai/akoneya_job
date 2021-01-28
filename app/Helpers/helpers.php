<?php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\File;

/**
 * Upload File To Akoneya Media
 */
if (!function_exists('fileUploadToAkoneyaMedia')) {
    function fileUploadToAkoneyaMedia($file, $directoryName)
    {
        $fileExtension = $file->getClientOriginalExtension();
     
        $fileName = time().Str::random(6) . "." . $fileExtension;
     
        $filePath = $directoryName."/".$fileName;

        if (Storage::put($filePath, file_get_contents($file->getRealPath()))) {
            return $filePath;
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
        Storage::delete($filePath);
    }
}

/*
 * Get Field URL from Akoneya Media
 */
if (!function_exists('getFileUrlFromAkoneyaMedia')) {
    function getFileUrlFromAkoneyaMedia($filePath)
    {
        if($filePath != null){
            $fileUrl = Storage::url($filePath);
            return $fileUrl;
        }
        return null; 
    }
}

/**
* Check Request Auth User Id
*/
if(!function_exists('check_auth_user_id')) {

    function check_auth_user_id($auth_user_id,$passedId) {

        return $auth_user_id == $passedId;
    }
}

/**
* Get Date format
*/
if (!function_exists("strTo24hrFormat")) {
    function strTo24hrFormat($value)
    {
        return date("Y-m-d H:i:s", strtotime($value));
    }
}
