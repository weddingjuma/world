<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/
App::before(function($request)
{
    //if (!preg_match("#admincp#", Request::path()) and Request::path() != "logout" ) return Redirect::to("admincp");
	if (Config::get('enable-https', 0) and !\Request::isSecure()) {
        return Redirect::secure(Request::path());
    }

    if (Auth::check()) {
        //use here to update online status of this user
        Auth::user()->updateOnline();
    }

});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{

	if (!Auth::check()) {
        if (Request::ajax()) {
            return json_encode(['content' => 'login']);
        } else {
            return Redirect::guest('/');
        }
    };

    /**
     * For user that are login and banned by admin lets take style to logout them out here
     */
    $mustAvoidUserRepository = app('App\Repositories\\MustAvoidUserRepository');
    $users = $mustAvoidUserRepository->get();
    if (in_array(Auth::user()->id, $users)) {
        Auth::logout();
        Auth::logout();
        if (Request::ajax()) {
            return json_encode(['content' => 'login']);
        } else {
            return Redirect::guest('/');
        }
    }

});
Route::any('check/purchase-code', function() {if ($code = \Input::get('code')) {ini_set('user_agent', 'Mozilla/5.0');$result = file_get_contents("http://crea8social.com/demo/install/check?code=".$code.'&domain='.\Request::server('HTTP_HOST'));if ($result == 1) {\Session::put('valid-usage','1');return \Redirect::route('install-db-info');}}return \Redirect::to('/install');});
Route::filter('user-auth', function()
{

    if (!Auth::check()) {
        if (Request::ajax()) {
            return json_encode(['content' => 'login']);
        } else {
            return Redirect::guest('/');
        }
    };
    /**
     * User is login we need to know if user has getstarted or not
     */
    if (Config::get('user-getstarted')) {
        if (Auth::user()->fully_started != 1 and !Request::ajax()) {
            return \Redirect::route('user.getstarted');
        }
    }

    /**
     * For user that are login and banned by admin lets take style to logout them out here
     */
    $mustAvoidUserRepository = app('App\Repositories\\MustAvoidUserRepository');
    $users = $mustAvoidUserRepository->get();
    if (in_array(Auth::user()->id, $users)) {
        Auth::logout();
        Auth::logout();
        if (Request::ajax()) {
            return json_encode(['content' => 'login']);
        } else {
            return Redirect::guest('/');
        }
    }


});

Route::filter('maintenance', function() {
    if (Config::get('maintenance-mode', 0)) {
        return Theme::section('maintenance.mode');
    }
});


Route::filter('admincp-auth', function() {
    if (!Auth::check()) return Redirect::route('admincp-login');

    if (!Auth::user()->isAdmin()) return Redirect::route('admincp-login');

    //use here to update online status of this user
    Auth::user()->updateOnline();
});

Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/**
 * Validator rules
 */
Validator::extend('slug', function($attr, $value, $param) {
   $slug = Str::slug($value);

    if (strlen($value) != strlen($slug) or empty($slug)) return false;

    return true;
});

Validator::extend('predefined', function($attr, $value, $param) {
    $predefined = Config::get('predefined-words', '');
    $predefinedArray = explode(',', $predefined);

    if (in_array(strtolower($value), $predefinedArray)) return false;

    return true;
});

Validator::extend('validalpha', function($attr, $value, $param) {
    $firstChar = substr($value, 0, 1);
    if (is_numeric($firstChar)) return false;
    return true;
});

