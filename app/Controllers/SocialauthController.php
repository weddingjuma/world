<?php

namespace App\Controllers;
use App\Interfaces\PhotoRepositoryInterface;
use App\Repositories\SocialauthRepository;
use App\Repositories\UserRepository;
use Whoops\Example\Exception;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class SocialauthController extends \BaseController
{
    public function __construct(
        SocialauthRepository $socialauthRepository,
        UserRepository $userRepository,
        PhotoRepositoryInterface $photoRepositoryInterface
    )
    {
        $this->socialauthRepository = $socialauthRepository;
        $this->userRepository = $userRepository;
        $this->photo = $photoRepositoryInterface;
        parent::__construct();
    }

    public function facebook()
    {
        $facebook = $this->socialauthRepository->facebook();
        $user = $facebook->getUser();

        //try get the user profile

        if ($user) {
            try {
                $userProfile = $facebook->api('/me?fields=id,name,first_name,last_name,email,gender');
            } catch(\FacebookApiException $e) {
                $user = null;
            }
        }

        if ($user) {

            $needEdit = false;

            if (empty($userProfile['email'])) {
                $needEdit = true;
            }

            $username = \Str::slug($userProfile['name']);
            $username = str_replace([' ','.', '-'], ['','', ''], $username);

            $details = [
                'fullname' => $userProfile['first_name']. ' '. $userProfile['last_name'],
                'genre' => $userProfile['gender'],
                'country' => '',
                'email_address' => (isset($userProfile['email'])) ? $userProfile['email'] : '',
                'password' => time(),
                'username' => $username,
                'auth' => 'facebook',
                'authId' => $userProfile['id'],
                'avatar' => ''
            ];

            try{
                ini_set('user_agent', 'Mozilla/5.0');
                $avatar = json_decode(file_get_contents('https://graph.facebook.com/'.$userProfile['id'].'/picture?redirect=false&width=600&height=600'), true);

                if ($avatar and isset($avatar['data']['url'])) {
                    $avatar = $avatar['data']['url'];
                    $details['avatar'] = $avatar;
                }
            } catch(\Exception $e){}

            return $this->socialauthRepository->register($details);

        } else {
            $url = $facebook->getLoginUrl(['scope' => 'email']);

            return \Redirect::to($url);
        }
    }

    public function twitter()
    {
        $twitter = $this->socialauthRepository->twitter();



        //save some data to session
        try {
            $requestToken = $twitter->getRequestToken(\URL::route('twitter-auth-data'));
            \Session::put('oauth_token', $requestToken['oauth_token']);
            \Session::put('oauth_token_secret', $requestToken['oauth_token_secret']);

            if ($twitter->http_code != 200) {
                die('Something went wrong try again later');
            } else {
                return \Redirect::to($twitter->getAuthorizeURL($requestToken['oauth_token']));
            }

        } catch(\ErrorException $e) {
            die('Something went wrong try again later');
        }
    }

    public function twitterData()
    {
        $oauthVerifier = \Input::get('oauth_verifier');
        $oauthToken = \Session::get('oauth_token');
        $oauthTokenSecret = \Session::get('oauth_token_secret');

        if ($oauthVerifier and $oauthToken and $oauthTokenSecret) {
            $twitter = $this->socialauthRepository->twitter($oauthToken, $oauthTokenSecret);
            $accessToken = $twitter->getAccessToken($oauthVerifier);

            if ($twitter->http_code != 200) {
                die('Something went wrong, try again later');
            }

            \Session::forget('oauth_token');
            \Session::forget('oauth_token_secret');

            $twitter = $this->socialauthRepository->twitter($accessToken['oauth_token'], $accessToken['oauth_token_secret']);
            $userProfile = $twitter->get('account/verify_credentials');

            if ($twitter->http_code != 200) {
                die('Something went wrong, try again later');
            }

            if (isset($userProfile->error)) {
                return \Redirect::route('twitter-auth');
            } else {
                $details = [
                    'fullname' => $userProfile->name,
                    'genre' => '',
                    'country' => '',
                    'email_address' => '',
                    'password' => time(),
                    'username' => $userProfile->screen_name,
                    'auth' => 'twitter',
                    'authId' => $userProfile->id
                ];

                return $this->socialauthRepository->register($details);
            }
        } else {
            return \Redirect::route('twitter-auth');
        }
    }

    public function google()
    {
        $google = $this->socialauthRepository->google();

        if (!\Input::has('code')) {
            //redirect to get its login
            return \Redirect::to($google->createAuthUrl());
        }



        try{
            //yes we have the code good
            $google->authenticate(\Input::get('code'));

            $google->setAccessToken($google->getAccessToken());
            $outh2 = new \Google_Service_Oauth2($google);
            $userinfo = $outh2->userinfo->get();

            $username = \Str::slug($userinfo->givenName.$userinfo->familyName);
            $username = str_replace([' ','.', '-'], ['','', ''], $username);
            $details = [
                'fullname' => $userinfo->givenName. ' '. $userinfo->familyName,
                'genre' => ($userinfo->gender != null) ?  $userinfo->gender : '',
                'country' => '',
                'email_address' => $userinfo->email,
                'password' => time(),
                'username' => $username,
                'auth' => 'google',
                'authId' => $userinfo->id,
                'avatar' => $userinfo->picture,

            ];

            return $this->socialauthRepository->register($details);

        } catch( \Exception $e) {
            //return \Redirect::to($google->createAuthUrl());
        }


    }

    public function vk()
    {
        $vk = $this->socialauthRepository->vk();

        return \Redirect::to($vk->getAuthorizeUrl('photos,wall', \URL::route('vk-auth-data')));
    }

    public function vkData()
    {
        $vk = $this->socialauthRepository->vk();
        $callback = \URL::route('vk-auth-data');

        if ($code = \Input::get('code')) {

            if (\Session::has('vk_token')) {
                $accessToken = \Session::get('vk_token');
            } else {
                $vkToken = $vk->getAccessToken($code, $callback);
                $accessToken = $vkToken;
                \Session::put('vk_token', $accessToken);
            }

            $result = $vk->api('getProfiles', [
                'uids' => $accessToken['user_id'],
                'fields' => 'uid, first_name, last_name, nickname, screen_name, photo_big, gender',
            ]);

            $userProfile = $result['response'][0];

            /**
             * @var $first_name
             * @var $last_name
             * @var $screen_name
             * @var $uid
             * @var $gender
             */
            extract($userProfile);

            $details = [
                'fullname' => $first_name.' '.$last_name,
                'genre' => '',
                'country' => '',
                'email_address' => '',
                'password' => time(),
                'username' => $screen_name,
                'auth' => 'vk',
                'authId' => $uid
            ];

            return $this->socialauthRepository->register($details);

        } else {
            return \Redirect::route('vk-auth');
        }
    }

    public function complete($username)
    {
        $user = $this->userRepository->findByUsername($username);

        if (!$user) return \Redirect::to('/');

        $username = false;
        $email = false;
        $message = false;

        if ($this->socialauthRepository->userIsOk($user)) return \Redirect::to('/');


        if ($val = \Input::get('val')) {
            $completed = $this->socialauthRepository->complete($val, $user);

            if ($completed) {
                \Auth::login($user);
                return \Redirect::route('user-home');
            } else {
                $message = "Failed again, please try with different values";
            }
        }

        if(preg_match('#existed-#', $user->username)) {

            $username = str_replace('existed-', '', $user->username);

        }

        if (preg_match('#existed-#', $user->email_address) or empty($user->email_address)) {
            $email = str_replace('existed-', '', $user->email_address);
            $user->email_address = $email;
        }


        return $this->theme->view('socialauth.complete', [
            'user' => $user,
            'username' => $username,
            'email' => $email,
            'message' => $message
        ])->render();
    }
}