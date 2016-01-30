<?php

namespace App\Repositories;

use App\Interfaces\PhotoRepositoryInterface;
use App\Models\Photos;
use Idocrea8\Image\Image;
/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class PhotoRepository implements PhotoRepositoryInterface
{
    public function __construct(Photos $photos, Image $image)
    {
        $this->model = $photos;
        $this->image = $image;
    }

    /*
     * Upload and save image
     *
     * @param $file
     * @param array $settings
     * @return string
     */
    public function upload($file, $settings = [])
    {
        $expected = [
            'path' => '',
            'userid' => '',
            'slug' => '',
            'resize' => true,
            'url' => false,
            'width' => '',
            'height' => '',
            'fit' => 'inside',
            'scale' => 'down',
            'page_id' => '0',
            'privacy' => 1,
            'cdn' => true
        ];
        /**
         * @var $path
         * @var $userid
         * @var $slug
         * @var $resize
         * @var $url
         * @var $width
         * @var $height
         * @var $fit
         * @var $scale
         * @var $privacy
         * @var $page_id
         * @var $cdn
         */
        extract(array_merge($expected, $settings));

        $image = $this->image->load($file, $url)->setPath($path);
        if (!$cdn) $image->offCdn();
        if ($resize) {
            $image->resize($width, $height, $fit, $scale);
        } else {
            $image->exactResult();
        }

        if ($image->hasError()) {
            return false;
        }
        $image = $image->result();

        return $this->add($image, $userid, $slug, $privacy, $page_id);

    }

    /**
     * Method to upload without cropping or resizing
     * @param $file
     * @param string $path
     * @param int $minWidth
     * @return string
     */
     public function exactUpload($file, $path = "", $minWidth = null, $minHeight = null)
     {
         $image = $this->image->load($file)->setPath($path);

         if (!empty($minWidth)) $image->setSize($minWidth, $minHeight);

         return $image->exactResult();
     }

    /**
     * Method to crop image
     *
     * @param int $left
     * @param int $top
     * @param int $width
     * @param int $height
     * @param boolean $resize
     * @param int $rWidth
     * @param int $rHeight
     * @return \iDocrea8\Image\ImageProcessor
     */
    public function cropImage($file,$path = "", $left = 0, $top = 0, $width = 0, $height = 0, $resize = true, $rWidth = 0, $rHeight = 0)
    {

        return $this->image->load($file, true)->setPath($path)->crop($left, $top, $width, $height, $resize, $rWidth, $rHeight);
    }

    /**
     * Works for multi-upload of images
     * @param $images
     * @return boolean
     */
    public function imagesMetSizes($images) {
        $images = (is_array($images)) ? $images : array($images);

        $allowSize = \Config::get('image-max-size');
        foreach($images as $image) {
            if ($image->getSize() > $allowSize) return false;
        }

        return true;
    }

    /**
     * @param $image
     * @param $userid
     * @param $slug
     * @param $privacy
     * @param $pageId
     * @return mixed
     */

    public function add($image, $userid, $slug, $privacy = 1, $pageId = 0)
    {
        if (empty($userid)) return $image;
        $photo = $this->model->newInstance();
        $photo->user_id = $userid;
        $photo->slug = $slug;
        $photo->path = $image;
        $photo->privacy = $privacy;
        $photo->page_id = $pageId;
        $photo->save();

        \Cache::forget('all-photos');

        return $photo->path;
    }

    public function existsInDB($image)
    {

        if (\Cache::has('all-photos')) {

            $photos = \Cache::get('all-photos');

            if (in_array($image, $photos))return true;

            return false;
        } else {

            $photos = $this->model->lists('path');

            \Cache::forever('all-photos', $photos);

            if (in_array($image, $photos)) return true;
            return false;
        }
    }

    public function getByLink($photo)
    {
        $photo = str_replace(['_600_', '_original_', '_960_'], '_%d_', $photo);


        $photo = str_replace([\URL::to('/').'/'], [''], $photo);

        $CDNRepository = app('App\\Repositories\\CDNRepository');

        $photo = $CDNRepository->convertToPath($photo);


        $photo = $this->model->with(['likes', 'comments'])->where('path', '=', $photo)->first();
        return $photo;
    }

    public function get($id)
    {
        return $this->model->where('id', '=', $id)->orWhere('path', '=', $id)->first();
    }

    public function delete($path)
    {
        return \Image::delete($path);
    }

    public function deleteAllByUser($userid)
    {
        return $this->model->where('user_id', '=', $userid)->delete();
    }

    public function deleteAllByPage($id)
    {
        return $this->model->where('slug', '=', 'page-'.$id)->delete();
    }

    public function deletePhoto($id)
    {
        $photo = $this->get($id);

        if ($photo) {

            if ($photo->user_id != \Auth::user()->id) {
                if (!\Auth::user()->isAdmin()) return false;
            }
            $photo->deleteMe();
        }


        return true;
    }

    public function lists($id, $limit = 12)
    {
        return $this->model->where('slug', '=', $id)->where('privacy', '!=', 5)->orderBy('id', 'desc')->paginate($limit);
    }

    public function listPages($id, $type = '', $limit = 12)
    {
        return $this->model->where('page_id', '=', $id)->orderBy('id', 'desc')->paginate($limit);
    }

    public function lastPhoto($id)
    {
        return $this->model->where('slug', '=', $id)->orderBy('id', 'desc')->first();
    }
    public function listAll($slug)
    {
        return $this->model->where('slug', '=', $slug)->get();
    }

    public function deletePhotos($slug)
    {
        $photos = $this->listAll($slug);
        foreach($photos as $photo) {
            $photo->deleteMe();
        }

        return true;
    }
}
 