<?php

namespace App\Repositories;
use App\Interfaces\PhotoRepositoryInterface;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class SocialauthRepository
{

    public function __construct(
        UserRepository $userRepository,
        PhotoRepositoryInterface $photoRepositoryInterface,
        PhotoAlbumRepository $photoAlbumRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->photo = $photoRepositoryInterface;
        $this->photoAlbum = $photoAlbumRepository;

        //include our social libraries
        require_once app_path().'/library/facebook/facebook.php';
        require_once app_path().'/library/twitter/twitteroauth.php';
        require_once app_path().'/library/vk/VK.php';
        require_once app_path().'/library/vk/VKException.php';
        require_once app_path().'/library/Google/autoload.php';
    }

    /**
     * Get Facebook object
     *
     * @return Facebook
     */
    public function facebook()
    {
        return new \Facebook([
            'appId' => \Config::get('facebook-id'),
            'secret' => \Config::get('facebook-secret')
        ]);
    }

    public function twitter($authToken = null, $tokenSecret = null)
    {
        return new \TwitterOAuth(
            \Config::get('twitter-id'),
            \Config::get('twitter-secret'),
            $authToken,
            $tokenSecret
        );
    }

    public function vk($token = null)
    {
        return new \VK(
            \Config::get('vk-id'),
            \Config::get('vk-secret'),
            $token
        );
    }

    public function google()
    {
        $client = new \Google_Client();
        $client->setClientId(\Config::get('google-id'));
        $client->setClientSecret(\Config::get('google-secret'));
        $client->setRedirectUri(\URL::route('google-auth'));
        $client->addScope('https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile');

        return $client;
    }
    /**
     * Method to to register user
     *
     * @param array $details
     * @return mixed
     */
    public function register($details)
    {
        $expected = [
            'username',
            'email_address',
            'fullname',
            'genre',
            'country',
            'auth',
            'authId',
            'password',
            'avatar' => ''
        ];

        /**
         * @var $username
         * @var $email_address
         * @var $fullname
         * @var $genre
         * @var $country
         * @var $auth
         * @var $authId
         * @var $password
         * @var $avatar
         */
        extract(array_merge($expected, $details));


        $results = null;

        if ($this->isAMember($auth, $authId)) {
            return $this->login($auth, $authId);
        }

        $validate = \Validator::make(['username' => $username], [
            'username' => 'required|alpha_num|slug|unique:users',
        ]);

        if ($validate->fails()) {
            $username = \Str::slug($username);
            $username = str_replace('-', '_', $username);
        }

        $usernameExists = $this->usernameExists($username);
        $emailExists = $this->emailExists($email_address);


        $user = $this->userRepository->model->newInstance();
        $user->fullname = $fullname;
        $user->password = $password;
        $user->username = ($usernameExists) ? 'existed-'.$username : $username;
        $user->email_address = ($emailExists) ? 'existed-'.$email_address : $email_address;
        $user->genre = $genre;
        $user->country = $country;
        $user->auth = $auth;
        $user->auth_id = $authId;
        if (!$usernameExists and !$emailExists) {
            $user->activated = 1;
            $user->active = 1;
        }
        $user->avatar = $avatar;
        $user->last_active_time = time();
        $user->save();

        if($avatar) {

            $this->userRepository->savePrivacy(['avatar_type' => 1], $user);
            $album = $this->photoAlbum->add('profile photos', $user->id, true);
            try {

                $photo = $this->photo->upload(file_get_contents($avatar), [
                    'path' => 'users/'.$user->id,
                    'slug' => 'album-'.$album->id,
                    'userid' => $user->id,
                    'url' => true
                ]);
                if ($photo) {
                    $user->avatar = $photo;

                    $user->save();
                } else {
                    $this->photo->add($avatar, $user->id, 'album-'.$album->id);
                }
            } catch(\Exception $e) {
                $this->photo->add($avatar, $user->id, 'album-'.$album->id);
            }
        }

        return $this->login($auth, $authId);
    }

    public function usernameExists($username)
    {
        return $this->userRepository->findByUsername($username);
    }

    public function emailExists($email)
    {
        return $this->userRepository->findByEmail($email);
    }

    public function isAMember($auth, $authId)
    {
        return $this->userRepository->model
            ->where('auth', '=', $auth)
            ->where('auth_id', '=', $authId)
            ->first();
    }

    public function login($auth, $authId)
    {
        $user = $this->isAMember($auth, $authId);

        if ($user) {
            if ($user->activated == 0 or $user->banned == 1) {
                if ($user->banned == 1) return \Redirect::to('');
                return \Redirect::route('user-resend-activation');
            }


            /**
             * We must consider where to send user to
             *
             * if user username column has existed value we sending user to edit
             * also email_address is also a case
             */


            if (preg_match('#existed-#', $user->username) or preg_match('#existed-#', $user->email_address) or empty($user->email_address) or empty($user->username)) {
                return \Redirect::route('auth-complete', ['username' => $user->username]);
            } else {
                \Auth::login($user);
                $user->online_status = 1;
                $user->save();
                return \Redirect::route('user-home');
            }
        }
    }

    public function userIsOk($user)
    {
        if (preg_match('#existed-#', $user->username) or preg_match('#existed-#', $user->email_address) or empty($user->email_address) or empty($user->username)) {
            return false;
        } else {
            //\Auth::login($user);
            return true;
        }
    }

    public function complete($val, $user)
    {
        $expected = [
            'username' => '',
            'email' => ''
        ];

        /**
         * @var $username
         * @var $email
         */
        extract(array_merge($expected, $val));


        $usernameFailed = false;
        $emailFailed = false;



        if (isset($username) and !empty($username)) {
            $validate = \Validator::make(['username' => $username], [
                'username' => 'required|alpha_num|slug|unique:users',
            ]);

            if ($validate->fails()) {
                $username = \Str::slug($username);
                $username = str_replace('-', '_', $username);
            }

            if (!empty($username) and !$this->userRepository->findByUsername($username)) {
                $user->username = $username;
                $user->save();
            } else {
                $usernameFailed = true;
            }

        }

        if (isset($email) and !empty($email)) {
            if (!$this->userRepository->findByEmail($email)) {
                $user->email_address = $email;
                $user->save();
            } else {
                $emailFailed = true;
            }

        }

        if ($usernameFailed or $emailFailed) {
            return false;
        } else {
            $user->activated = 1;
            $user->active = 1;
            $user->save();
        }

        return true;


    }
}