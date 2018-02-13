<?php

namespace App\Helpers;

Class ImageUpload {
    
    /**
     * To upload image with creating thumb
     * @param File $file
     * @param array $params contain ['originalPath', 'thumbPath', 'thumbHeight', 'thumbWidth', 'previousImage']
     */
    public static function uploadWithThumbImage($file, $params) {
        try {
            if (!empty($file) && !empty($params)) {
                $name = str_random(20). '.' . $file->getClientOriginalExtension();
                
                $originalPath = $params['originalPath'] . $name;
                $thumbPath = $params['thumbPath'] . $name;
                
                if (!file_exists($params['originalPath'])) File::makeDirectory($params['originalPath'], 0777, true, true);
                if (!file_exists($params['thumbPath'])) File::makeDirectory($params['thumbPath'], 0777, true, true);

                // created instance
                $img = Image::make($file->getRealPath());
                $img->save($originalPath);
                
                // resize the image to a height of $this->contestThumbImageHeight and constrain aspect ratio (auto width)
                $imgHeight = ($img->height() < $params['thumbHeight']) ? $img->height(): $params['thumbHeight'];
                
                $img->resize(null, $imgHeight, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumbPath);
                    
                if ($params['previousImage'] != '') {
                    $originalImage = $params['originalPath']. $params['previousImage'];
                    $thumbImage = $params['thumbPath'] . $params['previousImage'];
                    if (file_exists($originalImage)) {
                        File::delete($originalImage);
                    }
                    if (file_exists($thumbImage)) {
                        File::delete($thumbImage);
                    }
                }
                return [
                    'imageName' => $name
                ];
            }
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * To upload image 
     * @param File $file
     * @param array $params contain ['originalPath', 'previousImage']
     */
    public static function uploadImage($file, $params) {
        try {
            if (!empty($file) && !empty($params)) {
                $name = str_random(20). '.' . $file->getClientOriginalExtension();
                
                $originalPath = $params['originalPath'] . $name;
                
                if (!file_exists($params['originalPath'])) File::makeDirectory($params['originalPath'], 0777, true, true);

                // created instance
                $img = Image::make($file->getRealPath());
                $img->save($originalPath);
                
                if ($params['previousImage'] != '') {
                    $originalImage = $params['originalPath']. $params['previousImage'];
                    if (file_exists($originalImage)) {
                        File::delete($originalImage);
                    }
                }
                return [
                    'imageName' => $name
                ];
            }
        } catch (Exception $e) {
            return false;
        }
    }
    
}
