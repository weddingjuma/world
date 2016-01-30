<?php

namespace App\Controllers;


class HomeController extends \BaseController {

    public function  __construct()
    {
        if (\Config::get('system.installed')) {
            parent::__construct();
        }
    }
	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|``
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function index()
	{
        //echo \Hash::make('123456');

        if (!\Config::get('system.installed')) return \Redirect::to('/install');


        if (\Auth::check()) return \Redirect::route('user-home');
        $this->setTitle(trans('home.welcome-to-our-social-network'));

        return $this->theme->view('home.index', ['users' => app('App\\Repositories\\UserRepository')->latestUsers(8)])->render();
	}
}
