<?php

namespace App\Controllers;

use App\Controllers\Base\ProfileBaseController;
use App\Interfaces\PhotoRepositoryInterface;
use App\Repositories\PhotoAlbumRepository;
use Illuminate\Events\Dispatcher;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class PhotoController extends ProfileBaseController
{
    public function __construct(
        PhotoRepositoryInterface $photoRepository,
        PhotoAlbumRepository $photoAlbumRepository,
        Dispatcher $event
    )
    {
        parent::__construct();
        $this->photoRepository = $photoRepository;
        $this->albumRepository = $photoAlbumRepository;
        $this->event = $event;
    }
    public function details()
    {
        $photo = \Input::get('photo');
        $photo = $this->photoRepository->getByLink($photo);
        if (!empty($photo)) {
            return $this->theme->section('photo.detail', ['photo' => $photo]);
        }
    }

    public function profile()
    {
        if (!$this->exists()) {
            return $this->profileNotFound();
        }

        $this->theme->share('singleColumn', true);

        return $this->render('photo.profile.albums', [
            'albums' => $this->albumRepository->lists($this->profileUser->id)
        ], ['title' => $this->setTitle('Photos')]);
    }

    public function albumPhotos($id, $slug)
    {
        if (!$this->exists()) {
            return $this->profileNotFound();
        }

        $this->theme->share('singleColumn', true);


        $album = $album = $this->albumRepository->get($slug, $this->profileUser->id);

        $photos = $this->albumRepository->photos($album->id);
        //$album = $album->id;


        if (!$album) return  \Redirect::to($this->profileUser->present()->url('photos'));

        return $this->render('photo.profile.photos', [
            'photos' => $photos,
            'album' => $album,
        ], ['title' => $this->setTitle('Photos')]);

    }

    public function createAlbum()
    {
        $name = \Input::get('name');

        $results = [
            'response' => 0,
            'message' => trans('photo.album-create-error')
        ];

        $validator = \Validator::make(
            [
                'album' => $name
            ], [
            'album' => 'required'
        ]);

        if (!$validator->fails()) {
            $album = $this->albumRepository->add($name);

            if ($album) {
                $results['album'] = (String) $this->theme->section('photo.display-album', ['album' => $album]);
                $results['response'] = 1;
            }
        } else {
            $results['message'] = $validator->messages()->first();
        }
        return json_encode($results);
    }

    public function editAlbum()
    {
        $id = \Input::get('id');
        $title = \Input::get('text');

        $album = $this->albumRepository->get($id);

        if (!$album) return '0';
        if (empty($title)) return \Str::limit(ucwords($album->title), 30);

        $album = $this->albumRepository->save($title, $album);
        return \Str::limit(ucwords($album->title), 30);
    }

    public function deleteAlbum($id)
    {
        $this->albumRepository->delete($id);

        return \Redirect::to(\URL::previous());
    }

    public function deletePhoto($id)
    {
        $this->photoRepository->deletePhoto($id);
        return \Redirect::to(\URL::previous());
    }

    public function upload()
    {

        if (!\Input::hasFile('image')) return '';
        $files = \Input::file('image');

        $photos = [];
        $id = \Input::get('album');

        if (!$this->photoRepository->imagesMetSizes($files)) return '';

        foreach($files as $file) {
            $photo = $this->albumRepository->upload($file, $id);
            if($photo) $photos[] = $photo;
        }

        $this->event->fire('add-album-photos', [$id, $photos]);

        $content = "";
        foreach($photos as $photo) {
            if ($photo) {
                $content .= $this->theme->section('photo.display-photo', ['photo' => $photo]);
            }
        }

        return $content;
    }
}
 