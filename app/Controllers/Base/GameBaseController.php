<?php

namespace App\Controllers\Base;

/**
 *
 *@author: Tiamiyu waliu kola
 *@website : www.crea8social.com
 */
class GameBaseController extends \BaseController
{
    public $game;

    public function __construct()
    {
        parent::__construct();
        $this->gameRepository = app('App\\Repositories\\GameRepository');
        $slug = \Request::segment(2);

        $this->game = $this->gameRepository->getProfile($slug);

        $this->theme->share('game', $this->game);
    }

    public function exists()
    {
        return $this->game;
    }

    public function render($path, $param = [], $setting = [])
    {
        $predefinedSettings = [
            'title' => $this->setTitle()
        ];

        if ($this->exists()) {

            $predefinedSettings = array_merge($predefinedSettings, ['design' => $this->game->present()->readDesign()]);
        }

        $settings = array_merge($predefinedSettings, $setting);



        return parent::render('game.profile.layout', ['content' => $this->theme->section($path, $param)], $settings);

    }

    public function notFound()
    {
        //if ($this->page and !$this->community->present()->canView()) return \Redirect::route('communities');
        return $this->theme->section('error-page');
    }

    public function setTitle($title = null)
    {
        if (!$this->exists()) return parent::setTitle($title);
        $title = $this->game->title.((!empty($title)) ? ' - '.$title : null);
        return parent::setTitle($title);
    }

}