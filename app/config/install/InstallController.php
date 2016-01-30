<?php

namespace App\Install;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class InstallController extends \BaseController
{
    public function __construct(InstallRepository $installRepository)
    {
        $this->installRepository = $installRepository;
    }

    public function index()
    {
        if (\Config::get('system.installed')) return \Redirect::to('/');

        return $this->instalRender(\View::make('install.index'), '');
    }

    public function dbInfo()
    {
        if (\Config::get('system.installed')) return \Redirect::to('/');

        $message = null;
        if($val = \Input::get('val')) {
            $db = $this->installRepository->installDbinfo($val);

            if ($db) {
                //redirect
                return \Redirect::route('install-db');
            } else {

                $message = "Failed: Please confirm your details, unable to connect to database";
            }
        }
        return $this->instalRender(\View::make('install.db', ['message' => $message]), '');
    }

    public function db()
    {
        if (\Config::get('system.installed')) return \Redirect::to('/');

        $this->installRepository->installDB();
        return \Redirect::route('install-site-info');
    }

    public function site()
    {
        if (\Config::get('system.installed')) return \Redirect::to('/');

        $message = null;

        if ($val = \Input::get('val')) {
            $user = $this->installRepository->createAccount($val);

            if ($user) {
                return \Redirect::to('/');
            } else {
                $message = "Failed : Please check your details";
            }
        }
        return $this->instalRender(\View::make('install.site', ['message' => $message]), '');
    }

    public function instalRender($content, $title = null)
    {
        return \View::make('install.layout', ['content' => $content, 'title' => $title]);
    }
}