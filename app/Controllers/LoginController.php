<?php

namespace App\Controllers;

use App\Repositories\UserRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class LoginController extends \BaseController
{
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->user = $userRepository;
        parent::__construct();
    }

    public function logout()
    {
        \Auth::logout();
        \Session::flush();
        //\Session::forget();

        sleep(1);
        \Auth::logout();
        if (\Auth::check()) {
            $user = \Auth::user();
            $user->last_active_time = time() - 3600;
            $user->updateStatus(0);
            $user->save();
        }

        return \Redirect::to('/');
    }

    public function login()
    {
        $message = null;
        if ($val = \Input::get('val')) {

            if ($login = $this->user->login($val)) {

                $desUrl = \URL::route('user-home');

                if ($login == 'activate') {
                    $desUrl = \URL::route('user-resend-activation');
                } elseif($login == 'banned') {
                    return json_encode([
                        'response' => 0,
                        'message' => trans('user.user-banned'),
                    ]);
                }

                if (\Request::ajax()) {

                    return json_encode([
                        'response' => 1,
                        'url' => $desUrl,
                    ]);
                } else {
                    return \Redirect::to($desUrl);
                }

            } else {

                $message = trans('user.failed-login');
                return json_encode([
                    'response' => 0,
                    'message' => $message,
                ]);
            }

        }
    }

    public function forgotPassword()
    {
        $this->setTitle(trans('user.forgot-password'));
        $message = null;

        if ($email = \Input::get('email')) {
            if ($this->user->retrievePassword($email)) {
                $message = trans('user.forgot-password-success');
            } else {
                $message = trans('user.forgot-password-error');
            }
        }

        return $this->theme->view('user.login.forgot-password', ['message' => $message])->render();
    }

    public function retrievePassword()
    {
        $hash = \Input::get('hash');

        if (empty($hash)) return \Redirect::to('/');

        if($user = $this->user->findByHash(['code' => $hash], false)) {

            $message = null;
            if ($val = \Input::get('val')) {
                $validator = \Validator::make($val, [
                    'password' => 'required|confirmed'
                ]);

                if ($validator->fails()) {
                    $message = $validator->messages()->first();

                } else {
                    $this->user->changePassword($val, $user);
                    \Auth::login($user);
                    return \Redirect::route('user-home');
                }
            }
            return $this->theme->view('user.login.change-password', ['user' => $user, 'message' => $message])->render();
        }

    }
}
 