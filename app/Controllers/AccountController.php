<?php

namespace App\Controllers;

use App\Controllers\Base\UserBaseController;
use App\Interfaces\PhotoRepositoryInterface;
use App\Repositories\BlockedUserRepository;
use App\Repositories\CustomFieldRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class AccountController extends UserBaseController
{
    public function __construct(
        BlockedUserRepository $blockedUserRepository,
        CustomFieldRepository $customFieldRepository,
        PhotoRepositoryInterface $photoRepositoryInterface
    )
    {
        parent::__construct();
        $this->blockedUserRepository = $blockedUserRepository;
        $this->customFieldRepository = $customFieldRepository;
        $this->photoRepository = $photoRepositoryInterface;
        //$this->theme->layout('user.account.layout');
    }

    public function index()
    {
        $message = null;
        if ($val = \Input::get('val')) {
            $message = $this->userRepository->saveSettings(\Auth::user(), $val);
        }
        return $this->render('user.account.index', ['message' => $message, 'user' => $this->userRepository->findById(\Auth::user()->id)], [
            'title' => $this->setTitle(trans('user.account-settings'))
        ]);
    }

    public function privacy()
    {
        if ($val = \Input::get('val')) {
            $this->userRepository->savePrivacy($val);
        }

        return $this->render('user.account.privacy', ['user' => $this->userRepository->findById(\Auth::user()->id)], [
            'title' => $this->setTitle(trans('user.security-privacy'))
        ]);
    }

    public function deactivate()
    {
        if ($val = \Input::get('val')) {

            $this->userRepository->deactivate($val);
            return \Redirect::to('/');
        }

        return $this->render('user.account.deactivate', [], [
            'title' => $this->setTitle(trans('user.deactivate-account'))
        ]);
    }

    public function profile()
    {
        if ($val = \Input::get('val')) {
            $this->userRepository->updateProfile($val);
        }

        return $this->render('user.account.profile', [
            'user' => $this->userRepository->findById(\Auth::user()->id),
            'fields' => $this->customFieldRepository->listAll('profile')
        ], [
            'title' => $this->setTitle(trans('user.edit-profile'))
        ]);
    }

    public function updatePostPrivacy()
    {
        $v = \Input::get('v');

        $this->userRepository->savePrivacy([
            'post-privacy-default' => $v
        ]);
    }

    public function notifications()
    {
        if ($val = \Input::get('val')) {
            $this->userRepository->savePrivacy($val);
        }

        return $this->render('user.account.notifications', [
            'user' => $this->userRepository->findById(\Auth::user()->id),
        ], [
            'title' => $this->setTitle(trans('user.notification-privacy'))
        ]);
    }

    public function designPage()
    {
        $message = '';
        if (!\Config::get('page-design')) {
            return \Redirect::route('user-home');
        }
        if ($val = \Input::get('val')) {
            $this->userRepository->saveDesign($val);
            $message = 'Design saved';
        }
        return $this->render('user.account.design', ['user' => $this->userRepository->findById(\Auth::user()->id), 'message' => $message], [
            'title' => $this->setTitle(trans('user.design-your-page'))
        ]);
    }

    /**
     * Ajax serving
     */
    public function uploadBg()
    {
        $error = json_encode([
            'response' => 0,
            'message' => trans('photo.error', ['size' => formatBytes()])
        ]);
        if (\Request::hasFile('image')) {

            if (!$this->photoRepository->imagesMetSizes(\Input::file('image'))) return $error;
            if ($image = $this->userRepository->changeDesignBg(\Input::file('image'), \Input::get('val.bg_image'))) {
                return json_encode([
                    'response' => 1,
                    'image' => \Image::url($image),
                    'imagePath' => $image
                ]);
            } else {
                return $error;
            }

        }

        return $error;

    }

    /**
     * Controller to block user
     *
     * @param int $userid
     */
    public function blockUser($userid)
    {
        $this->blockedUserRepository->block($userid, \Auth::user()->id);
    }

    public function unBlock($id)
    {
        $this->blockedUserRepository->unBlock($id);
    }

    public function listBlockUsers()
    {
        return $this->render('user.account.block-users', ['users' => $this->blockedUserRepository->lists(\Auth::user()->id),], [
            'title' => $this->setTitle(trans('user.blocked-users'))
        ]);
    }

    public function delete()
    {
        $userid = \Input::get('userid');
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;

        $delete = $this->userRepository->delete($userid);

        return \Redirect::to(\URL::previous());
    }

    /**
     * @param string $path
     * @param array $param
     * @param array $settings
     * @return string
     */

    public function render($path, $param = [], $settings = [])
    {

        return parent::render('user.account.layout', ['content' => $this->theme->section($path, $param)], $settings);
    }

}