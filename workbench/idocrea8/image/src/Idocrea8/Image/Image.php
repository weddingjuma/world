<?php

namespace Idocrea8\Image;

use Idocrea8\Image\ImageProcessor;
use Illuminate\Config\Repository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Image
{
    /**
     * Load a ImageProcessor for the uploaded file
     *
     * @param $file
     * @param bool $isUrl
     * @return \iDocrea8\Image\ImageProcessor
     */
    public function load($file, $isUrl = false)
    {
        return new ImageProcessor($file, $isUrl);
    }

    /**
     * Format image url properly
     *
     * @param string $imagePath
     * @param int $size
     * @return string
     */
    public function url($imagePath, $size = 50)
    {
        $size = $this->getCorrectSize($size);

        $CDNRepository = app('App\\Repositories\\CDNRepository');

        if ($img = $CDNRepository->getLink($imagePath, $size)) {
            return $img;
        }
        $url = str_replace('%d', $size, $imagePath);
         if (!file_exists(base_path().'/'.$url)) {
             switch($size) {
                 case 300:
                     $url = str_replace('%d', 200, $imagePath);
                     break;
                 case 960:
                     $url = str_replace('%d', 'original', $imagePath);
                     break;
             }
         }


        return \URL::to($url);
    }

    public function getCorrectSize($size)
    {
        if ($size < 50 or $size == 50) return 50;
        if ($size > 50 and $size < 600) return 200;
        return $size;
    }

    public function exists($image)
    {
        $basePath = public_path('').'/';
        if (file_exists($basePath.str_replace('%d', 50, $image))) return true;
        $photoRepository = app('App\\Repositories\\PhotoRepository');

        if ($photoRepository->existsInDB($image)) return true;

        return false;
    }

    /**
     * delete image
     *
     * @param string $path
     * @return boolean
     */
    public function delete($path)
    {
        $sizes = \Config::get('image::sizes');
        $CDNRepository = app('App\\Repositories\\CDNRepository');

        if ($CDNRepository->has($path)) {
            $CDNRepository->delete($path, $sizes);
            return true;
        }
        $basePath = public_path('').'/';
        if (preg_match('#%d#', $path)) {
            foreach($sizes as $size)
            {
                $filePath = $basePath.str_replace('%d', $size, $path);

                if (file_exists($filePath)) {
                    \File::delete($filePath);
                }
            }

            $originalPath = $basePath.str_replace('%d', 'original', $path);
            if (file_exists($originalPath)) {
                \File::delete($originalPath);
            }
        } else {
            $path = $basePath.$path;

            if (file_exists($path)) {
                \File::delete($path);
            }
        }

        return true;
    }
}