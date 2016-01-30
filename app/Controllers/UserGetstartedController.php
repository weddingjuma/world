<?php

namespace App\Controllers;

use App\Controllers\Base\UserBaseController;
use App\Interfaces\PhotoRepositoryInterface;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class UserGetstartedController extends UserBaseController
{
    public function __construct(PhotoRepositoryInterface $photoRepositoryInterface)
    {
        parent::__construct();
        $this->photoRepository = $photoRepositoryInterface;
    }
    public function index()
    {
        $this->setTitle(trans('user.getstarted'));
        return $this->theme->view('user.getstarted.index')->render();
    }

    /**
     * From ajax
     */
    public function savePhoto()
    {
        $response = [
            'code' => 0,
            'message' => trans('photo.error', ['size' => formatBytes()]),
            'url' => ''
        ];

        if (\Request::hasFile('image')) {

            if (!$this->photoRepository->imagesMetSizes(\Input::file('image'))) return json_encode($response);

            $user = $this->userRepository->changeAvatar(\Input::file('image'));
            if ($user) {
                $response['code'] = 1;
                $response['url'] = $user->present()->getAvatar(100);
            }
        }

        return json_encode($response);
    }

    /**
     * from ajax
     */
    public function saveInfo()
    {
        echo \Input::get('bio');
        if ($bio = \Input::get('bio')) {
            $this->userRepository->saveBio($bio, null, \Input::get('city'));
        }
    }

    public function getMembers()
    {
        return $this->theme->section('user.getstarted.members', ['users' => $this->userRepository->getstartedMembers()]);
    }

    public function finish()
    {
        $this->userRepository->finishGetstarted();

        return \Redirect::route('user-home');
    }
}