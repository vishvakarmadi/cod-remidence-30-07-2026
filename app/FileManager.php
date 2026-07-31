<?php

namespace App;
use Intervention\Image\ImageManagerStatic as Image;


class FileManager
{
    public static function upload($file = null , $path){
        $name = rand().'.'.$file->extension();
        $file->move(public_path($path), $name);
        return $name;
    }

    public static function update($file = null, $path, $unlink){
        $fileName = FileManager::delete($unlink, $path);
        $name = FileManager::upload($file, $path);
        return $name;
    }

    public static function delete($unlink = null, $path){
        if (file_exists(public_path($path . $unlink))) {
            unlink(public_path($path . $unlink));
        }
        return [
            'success' => 1,
            'message' => 'Removed successfully !'
        ];
    }

    public static function uploadSize($file = null, $path, $width,$height){
        $name = rand(). '.' . $file->getClientOriginalExtension();
        $croppedImage = Image::make($file)
            ->fit($width, $height)
            ->encode();
        $croppedImage->save(public_path($path . $name));
        return $name;
    }

    public static function updateSize($file = null, $path, $unlink = null, $width,$height){
        $fileName = FileManager::delete($unlink, $path);
        $name = FileManager::uploadSize($file, $path, $unlink, $width, $height);
        return $name;
    }
}