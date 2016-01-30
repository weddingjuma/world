<?php

namespace Idocrea8\Image;


/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ImageProcessor
{
    /**
     * Gif library
     *
     * @var $gif
     */
    protected $gif;

    /**
     * WideImage
     *
     * @var $wideImage
     */
    protected $wideImage;

    /**
     * File
     * @var $file
     */
    protected $uploadFile;

    protected $name;

    protected $uploadFilePath;

    /**
     * Config
     *
     * @var config
     */
    protected $config;

    protected $file;

    /**
     * upload basePath
     *
     * @var string $basePath
     */
    protected $basePath;

    /**
     * upload baseDir
     * @var string $baseDir
     */
    protected $baseDir;

    /**
     * upload path
     */
    protected $uploadPath;

    /**
     * @var string $ext
     */
    protected $ext;

    /**
     * sizes
     *
     * @var array $sizes
     */
    protected $sizes;

    /**
     * @var bool $oneSize
     */
    protected $oneSize = false;

    /**
     * allow animated gif
     */
    protected $allowGif = true;

    /**
     * save original uploaded image
     */
    protected $saveOriginal = true;

    /**
     * Maximum upload size
     */
    protected $maxSize = 1000;

    /**
     * Minimum width and height
     */
    protected $minWidth = null;

    protected $minHeight = null;

    /**
     * image is process from an image url
     */
    protected $isUrl = false;

    /**
     * know if there is error
     */
    protected $hasError = false;

    protected $result = null;

    protected $allowExt = [];

    protected $cdn = true;

    /**
     * @param $file
     */
    public function __construct($file, $url = false)
    {
        $this->uploadFile = $file;
        $this->config = app('config');
        $this->file = app('files');
        $this->isUrl = $url;

        $this->load();
    }

    public function offCdn()
    {
        $this->cdn = false;
        return $this;
    }

    public function onCdn()
    {
        $this->cdn = true;
        return $this;
    }

    /**
     * Load the image,
     * set the image processor library
     *
     * @return void
     */
    protected function load()
    {
        $this->basePath = public_path($this->config->get('image::dir')).'/';
        $this->baseDir = $this->config->get('image::dir').'/';
        $this->sizes = $this->config->get('image::sizes');
        $this->allowGif = $this->config->get('image::allow-animated-gif');
        $this->saveOriginal = $this->config->get('image::save-original');
        $this->maxSize = $this->config->get('image::max-size');
        $this->allowExt = explode(',', $this->config->get('image::ext-allowed'));

        $this->name = ($this->isUrl) ? md5($this->uploadFile) : $this->uploadFile->getClientOriginalName();
        $this->ext = (string) strtolower(($this->isUrl) ? $this->file->extension($this->uploadFile) : $this->uploadFile->getClientOriginalExtension());
        $this->uploadFilePath = ($this->isUrl) ? $this->uploadFile : $this->uploadFile->getRealPath();

        if (!$this->isUrl and $this->file->size($this->uploadFilePath) > $this->maxSize) {
            $this->hasError = true;
        }

        //also confirm the image type

        if (!in_array($this->ext, $this->allowExt)) {
            $this->hasError = true;
        }
        /**
         * Load our library
         */
        require_once 'library/gif_exg.php';
        //require_once 'library/wideimage/lib/WideImage.php';
        require_once 'library/PHPImageWorkshop/autoload.php';
    }

    public function hasError()
    {
        return $this->hasError;
    }
    /**
     * Set the file extension
     */
    public  function extension()
    {
       return $this->ext;
    }

    /**
     * Method to check if image is a gif
     *
     * @return boolean
     */
    private function isGif()
    {
        return (strtolower($this->ext) == 'gif');
    }

    /**
     * Set the exact upload path
     *
     * @param string $path
     * @return \iDocrea8\Image\ImageProcessor
     */
    public function setPath($path)
    {
        $this->uploadPath = $path;

        return $this;
    }

    /**
     * Set Predefined resize sizes
     *
     * @param array $sizes
     * @return \iDocrea8\Image\ImageProcessor
     */
    public function sizes($sizes)
    {
        if (!empty($sizes)) $this->sizes = $sizes;

        return $this;
    }

    /**
     * Resize image
     *
     * @param int $width
     * @param int $height
     * @param string $fit
     * @param string $scale
     * @return \iDocrea8\Image\ImageProcessor
     */
    public function resize($width = '', $height= '', $fit = 'inside', $any = 'down')
    {
        $this->setFileName();

        list($basePath, $baseDir) = $this->processPath();
        $fileName = $this->name;
        $fullFileName = ((!$this->oneSize) ? '_%d_' : '').$fileName.'.'.$this->ext;
        $this->result = $baseDir.$fullFileName;

        if ($this->hasError) return $this;
        /**
         * Do nothing if there is error already
         */
        //if ($this->hasError) return $this;

        if (!empty($width)) {
            $this->oneSize = true;
            return $this->doResize(str_replace('%d', $width, $fullFileName), $width, $height, $fit, $any);
        } else {
            /**
             * We are using the predefined sizes
             */

            foreach($this->sizes as $size) {
                $this->doResize(str_replace('%d', $size, $fullFileName), $size, $size, $fit, $any);
            }
        }

        if ($this->saveOriginal and is_object($this->uploadFile)) {
            $this->saveOriginal($basePath, $fullFileName);
        }

        return $this;
    }

    /**
     * Help complete the resize of image
     *
     * @param int $width
     * @param int $height
     * @param string $fit
     * @param string $any
     * @return \iDocrea8\Image\ImageProcessor
     */
    protected function doResize($filename, $width, $height, $fit, $any)
    {
        list($basePath, $baseDir) = $this->processPath();

        $destinationPath = $basePath.$filename;



        try {

            if ($this->isGif() and $this->allowGif) {
                $gif = new \GIF_eXG($this->uploadFilePath, 1);
                $gif->resize($destinationPath, $width, $height, 1, 0);
            } else {

                $layer = \PHPImageWorkshop\ImageWorkshop::initFromPath($this->uploadFilePath, true);
                if ($width < 600) {
                    $layer->cropMaximumInPixel(0, 0, "MM");
                    $layer->resizeInPixel($width, $height);
                } else {
                    $layer->resizeInPixel($width, null, true);
                }

                $layer->save($basePath, $filename);
            }

            if ($this->cdn) {
                $CDNRepository = app('App\\Repositories\\CDNRepository');
                $newFileName = $CDNRepository->upload($destinationPath, $baseDir.$filename);

                if ($newFileName != $filename) {
                    //that means file has been succcessfully uploaded to a CDN Server so
                    $fullFileName =  str_replace($width, '%d', $newFileName);
                    $this->result = $fullFileName;
                }
            }
        } catch(\Exception $e) {
            $this->hasError = true;

        }

        return $this;
    }

    public function getOrientation($filename)
    {
        if (!function_exists('exif_read_data')) return false;
        $exif = @exif_read_data($filename);
        if ($exif) {
            return (isset($exif['Orientation'])) ? $exif['Orientation'] : null;
        }

        return false;
    }

    /**
     * method to crop image
     *
     * @param int $left
     * @param int $top
     * @param int $width
     * @param int $height
     * @param boolean $resize
     * @return \iDocrea8\Image\ImageProcessor
     */
    public function crop($left = 0, $top = 0, $width = 0, $height = 0, $resize = true)
    {
        list($basePath, $baseDir) = $this->processPath();
        $fileName = $this->name.time();
        $fullFileName = ((!$this->oneSize) ? '_%d_' : '').$fileName.'.'.$this->ext;
        $this->result = $baseDir.$fullFileName;
        $destinationPath = $basePath.$fullFileName;

        if ($this->hasError()) {
            return $this;
        }

        try{
            /**
             * let first crop the image before resize
             */

            $layer = \PHPImageWorkshop\ImageWorkshop::initFromPath($this->uploadFilePath, true);
            $layer->cropInPixel($width, $height, $left, $top);
            $layer->save($basePath, str_replace('%d', 'original', $fullFileName));

            if ($this->cdn) {
                $CDNRepository = app('App\\Repositories\\CDNRepository');
                $newFileName = $CDNRepository->upload(str_replace('%d', 'original', $destinationPath), $baseDir.str_replace('%d', 'original', $fullFileName));

                if ($newFileName != str_replace('%d', 'original', $fullFileName)) {
                    //that means file has been succcessfully uploaded to a CDN Server so
                    $fullFileName = str_replace('original', '%d', $newFileName);
                    $this->result = $fullFileName;
                }
            }

            /**
             * lets resize
             */
            if (!$this->oneSize and $resize) {
                foreach($this->sizes as $size) {

                    $newWideImage = $wideImage->resize($size, $size, 'inside', 'down');
                    $newWideImage->saveToFile(str_replace('%d', $size, $destinationPath));

                    //$layer = PHPImageWorkshop\ImageWorkshop::initFromPath($this->uploadFilePath, true);
                    if ($size < 600) {
                        $newLayer = $layer;
                        $newLayer->cropMaximumInPixel(0, 0, "MM");
                        $newLayer->resizeInPixel($size, $size);
                    } else {
                        $layer->resizeInPixel($size, null, true);
                    }

                    $layer->save($basePath, str_replace('%d', $size, $fullFileName));
                }
            }

        } catch(\Exception $e) {
            $this->hasError = true;
        }
        return $this;
    }

    /**
     * Method to save original from uploaded file
     *
     * @param string $basePath
     * @param string $filename
     * @return \iDocrea8\Image\ImageProcessor
     */
    protected function saveOriginal($basePath, $filename)
    {
        try{
            $filename = str_replace('%d', 'original', $filename);
            //$this->uploadFile->move($basePath, $filename);
            //$this->saveOriginal = false;
        } catch(\Exception $e){

        }
        return $this;
    }

    /**
     * @return array
     */
    protected function processPath()
    {
        $basePath = $this->basePath.$this->uploadPath.'/';
        $baseDir = $this->baseDir.$this->uploadPath.'/';

        /**
         * To ensure existence of image path
         */
        $this->file->makeDirectory($basePath, 0777, true, true);
        //index.hml must exists in this directory
        if (!$this->file->exists($basePath.'index.html')) {
            $file = @fopen($basePath.'index.html', 'x+');
            fclose($file);
        }
        return [$basePath, $baseDir];
    }

    protected function setFileName()
    {
        $this->name = md5($this->name.time());
    }

    public function result()
    {
        return $this->result;
    }

    protected function meetSize()
    {
        if (empty($this->minWidth)) return true;
        list($width, $height) = getimagesize($this->uploadFile->getRealPath());

        if ($width >= $this->minWidth and  $height >= $this->minHeight) return true;

        return false;
    }

    public function setSize($width = null, $height = null)
    {
        $this->minWidth = $width;
        $this->minHeight = $height;

        return $this;
    }

    /**
     * Method to get exact result
     * for non modifying images
     *
     * @return string
     */
    public function exactResult()
    {
        list($basePath, $baseDir) = $this->processPath();

        /**
         * test for minimum width and height
         */
        if (!$this->meetSize() or $this->hasError()) {
            $this->hasError = true;
            return $this;
        };

        $fileName = md5($this->name.time());
        $this->result = $baseDir.$fileName.'.'.$this->ext;
        $destinationPath = $basePath;
        $this->uploadFile->move($destinationPath, $fileName.'.'.$this->ext);

        if ($this->cdn) {
            $theFileName = $fileName.'.'.$this->ext;
            $realPath = $destinationPath.$theFileName;

            $CDNRepository = app('App\\Repositories\\CDNRepository');
            $newFileName = $CDNRepository->upload($realPath, $baseDir.$theFileName);

            if ($newFileName != $theFileName) {
                //that means file has been succcessfully uploaded to a CDN Server so
                $this->result = $newFileName;
            }
        }

        return $this;
    }
}

/**
 * Class WideImage_Operation_ExifOrient
 * @package Idocrea8\Image
 */
class WideImage_Operation_ExifOrient
{
    /**
     * Rotates and mirrors and image properly based on current orientation value
     *
     * @param WideImage_Image $img
     * @param int $orientation
     * @return WideImage_Image
     */
    function execute($img, $orientation)
    {
        switch ($orientation) {
            case 2:
                return $img->mirror();
                break;

            case 3:
                return $img->rotate(180);
                break;

            case 4:
                return $img->rotate(180)->mirror();
                break;

            case 5:
                return $img->rotate(90)->mirror();
                break;

            case 6:
                return $img->rotate(90);
                break;

            case 7:
                return $img->rotate(-90)->mirror();
                break;

            case 8:
                return $img->rotate(-90);
                break;

            default: return $img->copy();
        }
    }
}