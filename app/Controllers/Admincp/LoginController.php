<?php

namespace App\Controllers\Admincp;

use App\Controllers\Admincp\AdmincpController;
use App\Repositories\UserRepository;
/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class LoginController extends AdmincpController
{
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->user = $userRepository;
        $this->theme->layout('layouts.login');
        $this->theme->asset()->add('login-css', 'theme/css/login.css');
    }


    public function index()
    {
        $this->setTitle(trans('global.login'));

        $message = null;
        if ($val = \Input::get('val')) {

            $validator = \Validator::make($val, [
                'username' => 'required',
                'password' => 'required'
            ]);

            if ($validator->passes()) {
                if ($this->user->login($val)) {

                    return \Redirect::route('admincp');
                }

                $message = trans('user.failed-login');
            } else {
                $message = $validator->messages()->first();
            }
        }
        return $this->theme->view('login.index', ['message' => $message])->render();
    }
}
 