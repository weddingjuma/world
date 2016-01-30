<?php

namespace App\Controllers\Base;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ProfileBaseController extends UserBaseController
{
    public $profileUser;

    public function __construct()
    {
        parent::__construct();
        $this->userid = \Request::segment(1);

        $this->profileUser = $this->userRepository->getProfileUser($this->userid);
        $this->theme->share('profileUser', $this->profileUser);

        if (\Auth::check()) {
            $this->theme->share('viewerUser', \Auth::user());
        }

        $this->setTitle();
    }

    public function render($path, $param = [], $setting = [])
    {
        $predefinedSettings = [
            'title' => ''
        ];

        if ($this->exists()) {

            $predefinedSettings = array_merge($predefinedSettings, ['design' => $this->profileUser->present()->readDesign()]);
        }

        $settings = array_merge($predefinedSettings, $setting);


        if (!$this->exists()) {
            return parent::render($path, $param, $settings);
        } elseif (!$this->profileUser->present()->canViewMe()) {
            return parent::render('profile.layout', ['content' => $this->theme->section('profile.not-viewable'), 'error' => true], $settings);
        } else {
            return parent::render('profile.layout', ['content' => $this->theme->section($path, $param)], $settings);
        }

    }

    public function profileNotFound()
    {
        if (\Config::get('disable-guest-access-profile') and !\Auth::check()) return \Redirect::to('');
        return $this->theme->section('error-page');
    }

    public function exists()
    {
        if (\Config::get('disable-guest-access-profile') and !\Auth::check()) return false;

        return $this->profileUser;
    }

    public function setTitle($title = null)
    {
        if (!$this->exists()) return parent::setTitle($title);
        $title = (!empty($title)) ? '-'.$title : null;
        $title = $this->profileUser->fullname.$title;
        return parent::setTitle($title);
    }
}
 