<?php

namespace App\Repositories;

use App\Interfaces\PhotoRepositoryInterface;
use App\Models\PhotoAlbum;
use Illuminate\Events\Dispatcher;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class PhotoAlbumRepository
{
    public function __construct(
        PhotoAlbum $photoAlbum,
        PhotoRepositoryInterface $photoRepositoryInterface,
        Dispatcher $event
    )
    {
        $this->model = $photoAlbum;
        $this->photoRepository = $photoRepositoryInterface;
        $this->event = $event;
    }

    /**
     * Method to add album
     *
     * @param string $title
     * @param int $userid
     * @param bool $needAlbum
     * @return boolean
     */
    public function add($title, $userid = null, $needAlbum = false)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        if (!$this->exists($title, $userid)) {
            $album = $this->model->newInstance();
            $album->title = sanitizeText($title, 100);
            $album->slug = \Str::slug(sanitizeText($title));
            $album->user_id = $userid;
            $album->default_photo = '';
            $album->save();

            //resave to make sure the slug is not empty
            $album->slug = $album->id.((!empty($album->slug)) ? '-'.$album->slug : '');
            $album->save();

            $this->event->fire('album.add', [$album]);
            return $album;
        }

        if ($needAlbum) {
            return $this->exists($title, $userid);
        }

        return false;
    }

    public function exists($title, $userid)
    {
        return $this->model
            ->where('title', '=', $title)
            ->where('user_id', '=', $userid)
            ->first();
    }

    public function save($title, $album)
    {
        $album->title = sanitizeText($title);
        $slug = toAscii($title);
        $album->slug = $album->id.((!empty($slug)) ? '-'.$slug : '');
        $album->save();

        return $album;
    }

    public function get($id, $userid = null)
    {
        if ($userid) {
            return $this->model
                ->where('user_id', '=', $userid)
                ->where(function($query) use($id) {
                    $query->where('id', '=', $id)
                    ->orWhere('slug', '=', $id);
                })->first();
        }

        return $this->model
            ->where('id', '=', $id)
            ->orWhere('slug', '=', $id)
            ->first();
    }

    public function lists($userid, $limit = 10)
    {
        return $this->model->with('user')->where('user_id', '=', $userid)->paginate($limit);
    }

    public function photos($id)
    {
        if ($id == 'post') {
            $album = 'post';
        } elseif ($id == 'avatar') {
            $album = "avatar";
        } else {
            $album = 'album-'.$id;
        }
        return $this->photoRepository->lists($album);
    }

    public function remove($id, $userid)
    {
        return $this->model
            ->where('id', '=', $id)
            ->where('user_id', '=', $userid)
            ->delete();
    }

    public function upload($file, $id)
    {
        if (in_array($id, ['post', 'avatar'])) return false;
        $album = $this->get($id);

        $image = $this->photoRepository->upload($file, [
            'path' => 'users/'.$album->user_id.'/photos',
            'slug' => 'album-'.$album->id,
            'userid' => $album->user_id
        ]);

        if (!$album->default_photo) {
            $album->default_photo = $image;
            $album->save();
        }
        return $this->photoRepository->getByLink($image);
    }

    public function delete($id)
    {
        $album = $this->get($id);

        if ($album and ($album->slug != 'profile-photos' or $album->slug != 'posts')) {
            if ($album->user_id != \Auth::user()->id) {
                if (!\Auth::user()->isAdmin()) return false;
            }
            $album->delete();
            $this->photoRepository->deletePhotos('album-'.$album->id);

            return true;
        }
        return false;
    }
}