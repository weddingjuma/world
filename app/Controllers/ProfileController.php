<?php

namespace App\Controllers;

use App\Controllers\Base\ProfileBaseController;
use App\Interfaces\PhotoRepositoryInterface;
use App\Repositories\BlockedUserRepository;
use App\Repositories\PhotoRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ProfileController extends ProfileBaseController
{
    public function __construct(
        PostRepository $postRepository,
        BlockedUserRepository $blockedUserRepository,
        PhotoRepositoryInterface $photoRepositoryInterface,
        UserRepository $userRepository
    )
    {
        parent::__construct();
        $this->postRepository = $postRepository;
        $this->blockUserRepository = $blockedUserRepository;
        $this->photo = $photoRepositoryInterface;
        $this->userRepository = $userRepository;

        //set neccessary meta data

        if ($this->profileUser) {
            $this->theme->share('site_description', ($this->profileUser and $this->profileUser->bio) ? $this->profileUser->bio : \Config::get('site_description'));
            $this->theme->share('ogSiteName', $this->profileUser->present()->fullName());
            $this->theme->share('ogUrl', $this->profileUser->present()->url());
            $this->theme->share('ogTitle', $this->profileUser->present()->fullName());
            $this->theme->share('ogImage', $this->profileUser->present()->getAvatar(150));
        }
    }
    public function index()
    {
        if (!$this->exists()) {
            return $this->profileNotFound();
        }

        if (\Auth::check() and $this->blockUserRepository->hasBlock(\Auth::user()->id, $this->profileUser->id)) {
            return \Redirect::route('user-home');
        }


        echo $this->render('profile.index', [],
            [
                'title' => $this->setTitle()
            ]
        );
    }

    public function uploadCover()
    {
        $failed = json_encode([
            'status' => 'error',
            'message' => trans('photo.error', ['size' => formatBytes()])
        ]);
        if (!\Input::hasFile('image')) return $failed;

        $file = \Input::file('image');

        if (!$this->photo->imagesMetSizes($file)) return $failed;

        $path = $file->getRealPath();

        list($width, $height) = getimagesize($path);

        $result = json_encode([
            'status' => 'error',
            'message' => 'Failed to process image, supported type jpg,jpeg,gif,png'
        ]);
        //let use direct upload like that
        $imageRepo = $this->photo->image;
        $image = $imageRepo->load($file)->setPath('temp/')->offCdn();
        $image = $image->resize(1000, null, 'fill', 'any');;

        if ($image->hasError()) return $result;

        $image = $image->result();
        $image = str_replace('%d', '1000', $image);

        if ($image) {

            list($width, $height) = getimagesize(base_path().'/'.$image);
            //delete old images
            $user = \Auth::user();
            if($user->original_cover) \Image::delete($user->original_cover);
            if($user->cover) \Image::delete($user->cover);
            $this->userRepository->updateCover($image, null, true);
            return json_encode([
                'status' => 'success',
                'url' => \URL::to($image),

            ]);
        }

        return $result;

    }

    public function removeCover()
    {
        $user = \Auth::user();
        $user->cover = '';
        $user->original_cover = '';
        if($user->original_cover) \Image::delete($user->original_cover);
        if($user->cover) \Image::delete($user->cover);
        $user->save();
    }

    public function cropCover()
    {
        $top = \Input::get('top');

        $image = $this->photo->cropImage(base_path(\Auth::getUser()->original_cover), 'cover/', 0, abs($top), 1000, 300, false);
        $image = str_replace('%d', 'original', $image->result());

        if (!empty($image)) {
            /**
             * Update user profile cover
             */
            $user = \Auth::user();
            if ($user->cover and $user->cover != $user->original_cover) {
                \Image::delete($user->cover);
            }
            $this->userRepository->updateCover($image);
            return json_encode([
                'status' => 'success',
                'url' => \Image::url($image),
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Error things',
            ]);
        }


    }
}
