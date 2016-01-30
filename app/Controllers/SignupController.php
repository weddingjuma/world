<?php

namespace App\Controllers;

use App\Repositories\UserRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class SignupController extends \BaseController
{
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->user = $userRepository;
    }

    public function index()
    {
        $response = [
            'response' => 0,
            'message' => '',
            'url' => ''
        ];


        if (!\Config::get('user-signup')) {
            if ($val = \Input::get('val')) {

                $errorMessage = null;

                $rules = [
                    'fullname' => 'required|predefined|validalpha|min:3',
                    'username' => 'required|predefined|validalpha|min:3|alpha_num|slug|unique:users',
                    'email_address' => 'required|email|unique:users',
                    'password' => 'required|min:6',
                    'genre' => 'required',
                    'country' => 'required',
                    'terms-and-condition' => 'required'
                ];

                if (\Config::get('user-enable-birth-date', 1)) {
                   $rules['birth_day'] = 'required';
                    $rules['birth_month'] = 'required';
                    $rules['birth_year'] = 'required';
                }

                $validator = \Validator::make($val, $rules);

                if (\Config::get('enable-captcha') and !$this->captchaValid()) {

                    $response['message'] = trans('user.captcha-error');

                } elseif(\Config::get('user-enable-birth-date', 1) and !$this->ageMet()) {
                    $response['message'] = trans('user.minimum-age', ['age' => \Config::get('user-minimum-age')]);
                }
                else  {
                    if (!$validator->fails()) {
                        $user = $this->user->signup($val);
                        $response['response'] = 1;
                        /**
                         * send to proper destination
                         */
                        if (\Config::get('user-activation')) {
                            $this->user->sendActivation($user);
                            $response['url'] = \URL::route('user-activation').'?email='.$user->email_address;

                        } else {
                            /**
                             * Login user
                             */
                            $this->user->login([
                                'username' => $val['username'],
                                'password' => $val['password']
                            ]);
                            $response['url'] = \URL::route('user-home');
                        }
                    } else {
                        $response['message'] = $validator->messages()->first();
                    }
                }

                return json_encode($response);
            }
        }
    }

    public function captchaValid()
    {
        session_start();

        return ($_SESSION['captcha'] == \Input::get('val.captcha'));
    }

    public function ageMet()
    {
        $mAge = \Config::get('user-minimum-age', false);

        if ($mAge == 0) return true;
        $eAge = \Input::get('val.birth_year');
        $thisYear = (int) date('Y');
        $dif = $thisYear - $eAge;
        if ($dif < $mAge) return false;

        //we check month and day if the age is equall to minimum age
        if ($dif == $mAge) {
            //lets check for month
            $eMonth = \Input::get('val.birth_month');
            $thisMonth = (int) Date('n');
            if ($thisMonth < $eMonth) return false;

            //now lets check the day if the the current month and the user is same
            if ($eMonth == $thisMonth) {

                $eDay = \Input::get('val.birth_day');
                $thisDay = (int) Date('j');
                if ($eDay > $thisDay) return false;
            }
        }

        return true;
    }

    public function activate()
    {
        $this->setTitle(trans('user.account-activation'));
        $email = \Input::get('email');
        $code = \Input::get('code');

        if ($val = \Input::get('val')) {

            if ($this->user->activate($val)) {
                return \Redirect::route('user-home');
            }
        }

        return $this->theme->view('user.activate', [
            'email' => $email,
            'code' => $code
        ])->render();
    }

    public function resendActivate()
    {
        $this->setTitle(trans('user.account-resend-activation'));
        $message = null;

        if ($email = \Input::get('email')) {
            $sent = $this->user->resendActivation($email);
            if (!$sent) {
                $message = trans('user.resend-activation-error');
            } else {
                $message = trans('user.resend-activation-success');
            }
        }

        return $this->theme->view('user.resend-activate', [
            'message' => $message
        ])->render();
    }
}