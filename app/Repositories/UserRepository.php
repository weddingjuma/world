<?php

namespace App\Repositories;

use App\Interfaces\PhotoRepositoryInterface;
use App\Models\User;
use Illuminate\Events\Dispatcher;
use Illuminate\Config\Repository;
use Illuminate\Mail\Mailer;

/**
*User Repository
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class UserRepository
{
    public  $model;

    public function __construct(
        User $user,
        Dispatcher $dispatcher,
        Repository $config,
        PhotoRepositoryInterface $photoRepository,
        BlockedUserRepository $blockedUserRepository,
        Mailer $mailer,
        PhotoAlbumRepository $photoAlbumRepository,
        \Illuminate\Cache\Repository $cache,
        MustAvoidUserRepository $mustAvoidUserRepository
    )
    {
        $this->model = $user;
        $this->event = $dispatcher;
        $this->config =  $config;
        $this->photoRepository = $photoRepository;
        $this->blockedUserRepository = $blockedUserRepository;
        $this->mailer = $mailer;
        $this->photoAlbum = $photoAlbumRepository;
        $this->cache = $cache;
        $this->mustAvoidUserRepository = $mustAvoidUserRepository;
    }

    /**
     * @param int $id
     * @param array $column
     * @return mixed
     */
    public function findById($id, $column = ['*'])
    {
        return $this->model->find($id, $column);
    }

    public function findByIds($ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    /**
     * Find user by there username
     *
     * @param string $username
     * @return \App\Models\User
     */
    public function findByUsername($username)
    {
        return $this->model->where('username', '=', $username)->first();
    }

    /**
     * Find user by its email
     *
     * @param string $email
     * @return boolean
     */
    public function findByEmail($email)
    {
        return $this->model->where('email_address', '=', $email)->first();
    }

    /**
     * Find user by both id and username
     *
     * @param mixed $id
     * @return mixed
     */
    public function findByIdUsername($id)
    {
        return $this->model->where('id', '=', $id)->orWhere('username', '=', $id)->orWhere('email_address', '=', $id)->first();
    }

    /**
     * Dedicated method to get user for a profile
     *
     * @param mixed $id
     * @return \\App\\models\\User
     */
    public function getProfileUser($id)
    {

        return $this->model->where('username', '=', $id)
            ->whereNotIn('id', $this->mustAvoidUserRepository->get())
            ->where("activated", 1)
            ->where("active", 1)
            ->first();
    }

    /**
     * Method to suggest members
     *
     * @param int $limit
     * @param int $userid
     * @return array
     */
    public function suggest($limit = 3, $userid = null, $paginate = false)
    {

        $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        $connectionRepository = app('App\Repositories\\ConnectionRepository');
        $friendsId = $connectionRepository->getAllFriendConnectionIds($userid);

        $friendsId = (empty($friendsId)) ? ['sdds'] : $friendsId;
        $followingsId = $connectionRepository->getFollowingId($userid);
        $followingsId = (empty($followingsId)) ? ['dsfdf'] : $followingsId;

        $blockedUsers = $this->blockedUserRepository->listIds($userid);
        $blockedUsers = (empty($blockedUsers)) ? ['sfsfs'] : $blockedUsers;

        $users = array_merge($friendsId, $followingsId);
        $users = array_merge($users, $blockedUsers);
        $users[] = \Auth::user()->id;

        $user = (\Auth::user()->id == $userid) ? \Auth::user() : $this->findById($userid);

        $query = $this->model
            ->where('activated', 1)
            ->where('active', 1)
            ->whereNotIn('id', $users)
            ->whereNotIn('id', $this->mustAvoidUserRepository->get())
            ->where(function($query) use($user) {
                $query->Where('city', '=', $user->city)
                    ->orWhere('country', '=', $user->country)
                    ->orWhere('id', '!=', '');
            })
            ->orderBy(\DB::raw('rand()'));

        return $query = $query->paginate($limit);


    }

    public function gatherFriendsOfFriend($userid)
    {

            $connectionRepository = app('App\Repositories\\ConnectionRepository');
            $friendsId = $connectionRepository->getAllFriendConnectionIds($userid);
            $users = ['empty'];

            foreach($friendsId as $friendId) {
                $thisUserFriends = $connectionRepository->getFriendsId($friendId);
                foreach($thisUserFriends as $f) {
                    $users[] = $friendId;
                }
            }

            $this->cache->put('user-friends-of-friends-'.$userid, $users, 3600);

            return $users;
    }

    /**
     * Search users with a term
     *
     * @param string $term
     * @param int $limit
     * @return array
     */
    public function search($term = '', $limit = null)
    {
        $limit = (empty($limit)) ? $this->config->get('user-listing') : $limit;
        $term = str_replace('@','', $term);
        $users =  $this->model
            ->where('activated', 1)
            ->where('active', 1)
            ->where(function($users) use($term) {
                $users->where('username', 'LIKE', '%'.$term.'%')
                    ->orWhere('fullname', 'LIKE', '%'.$term.'%')
                    ->orWhere('email_address', '=', $term);
            })
            ->whereNotIn('id', $this->mustAvoidUserRepository->get());
        $country = \Input::get('country');
        $gender = \Input::get('gender');
        $city = \Input::get('city');

        if ($country and $country != 'all') $users = $users->where('country', '=', $country);
        if ($gender and $gender != 'both') $users = $users->where('genre', '=', $gender);
        if ($city) $users = $users->where('city', '=', $city);

        if (\Auth::check()) {
            $blockedUsers = $this->blockedUserRepository->listIds(\Auth::user()->id);
            $users->whereNotIn('id', $blockedUsers);
        }
            return $users = $users->paginate($limit);
    }

    public function searchByIds($term, $userIds, $limit = 5)
    {
        $users =  $this->model
            ->where('activated', 1)
            ->where('active', 1)
            ->whereIn('id', $userIds)
            ->whereNotIn('id', $this->mustAvoidUserRepository->get())
            ->where(function($users) use($term) {
                $users->where('username', 'LIKE', '%'.$term.'%')
                    ->orWhere('fullname', 'LIKE', '%'.$term.'%')
                    ->orWhere('email_address', '=', $term);
            });

        if (\Auth::check()) {
            $blockedUsers = $this->blockedUserRepository->listIds(\Auth::user()->id);
            $users->whereNotIn('id', $blockedUsers);
        }
        return $users = $users->paginate($limit);
    }

    public function listByIds($onlyIds = ['0'], $skipIds = [0], $limit = 10, $offset = 0, $term = '')
    {
        $users = $this->model
            ->where('activated', 1)
            ->where('active', 1)
            ->whereIn('id', $onlyIds)
            ->whereNotIn('id', $skipIds)
        ->whereNotIn('id', $this->mustAvoidUserRepository->get());

        if (!empty($term)) {
            $users->where(function($users) use($term) {
                $users->where('username', 'LIKE', '%'.$term.'%')
                    ->orWhere('fullname', 'LIKE', '%'.$term.'%')
                    ->orWhere('email_address', '=', $term);
            });
        }

        if (\Auth::check()) {
            $blockedUsers = $this->blockedUserRepository->listIds(\Auth::user()->id);
            $users->whereNotIn('id', $blockedUsers);//always ignore is blocked users
        }

        return $users->skip($offset)
            ->take($limit)
            ->get();

    }

    public function friendsSuggest($term, $limit = 5)
    {
        $friendsId = app('App\\Repositories\\ConnectionRepository')->getFriendsId();

        $users =  $this->model
            ->where('activated', 1)
            ->where('active', 1)
            ->whereIn('id', $friendsId)
            ->whereNotIn('id', $this->mustAvoidUserRepository->get())
            ->where(function($users) use($term) {
                $users->where('username', 'LIKE', '%'.$term.'%')
                    ->orWhere('fullname', 'LIKE', '%'.$term.'%')
                    ->orWhere('email_address', '=', $term);
            });

        if (\Auth::check()) {
            $blockedUsers = $this->blockedUserRepository->listIds(\Auth::user()->id);
            $users->whereNotIn('id', $blockedUsers);
        }
        return $users = $users->paginate($limit);
    }

    public function latestUsers($limit = 10)
    {
        return $this->model->where("activated", 1)->where("active", 1)->where('avatar', '!=', '')->orderBy('id', 'desc')->paginate($limit);
    }

    /**
     * Search users base on on there username
     *
     * @param string $username
     * @param int $Limit
     * @return array
     */
    public function searchByUsername($term, $limit = null)
    {
        $limit = (empty($limit)) ? $this->config->get('user-listing') : $limit;
        $users =  $this->model
            ->whereNotIn('id', $this->mustAvoidUserRepository->get())
            ->where(function($users) use($term) {
                $users->where('username', 'LIKE', '%'.$term.'%');
            });

        if (\Auth::check()) {
            $blockedUsers = $this->blockedUserRepository->listIds(\Auth::user()->id);
            $users->whereNotIn('id', $blockedUsers);
        }
        return $users = $users->paginate($limit);
    }

    /**
     * Getstarted members suggestion
     *
     * @return array
     */
    public function getstartedMembers()
    {
        return $this->suggest(20);
    }
    /**
     * Get User limit per page
     *
     * @param int $limit
     * @return array
     */
    public function getPerPage($limit = null)
    {
        $limit = (empty($limit)) ? $this->config->get('user-per-page') : $limit;
        return $this->model->paginate($limit);
    }

    /**
     * @param $val
     * @return mixed
     */
    public function login($val)
    {
        $credential = [
            'username' => '',
            'password' => '',
            'keep' => '',

        ];

        /**
         * @var $username
         * @var $password
         * @var $keep
         */
        extract($credential = array_merge($credential, $val));

        $keep = (empty($keep)) ? false : true;
        unset($credential['keep']);
        $loggedin = false;
        /**
         * first try using username
         */
        if ($loggedin = \Auth::attempt($credential, $keep)) {
            $loggedin = true;
        } else {
            $credential = [
                'email_address' => $username,
                'password' => $password
            ];
            $loggedin = \Auth::attempt($credential, $keep);
        }

        if ($loggedin) {
            $user = \Auth::user();
            if ($user->banned == 1) {
                \Auth::logout();
                return 'banned';
            } elseif($user->activated == 0) {
                \Auth::logout();
                return 'activate';
            } else {
                $user->online_status = 1;
                $this->savePrivacy(['self-offline' => 0], $user);
                $user->save();
                return 'valid';
            }
        }

        return $loggedin;
    }

    /**
     * Register new member
     *
     * @param array $val
     * @param boolean $active
     * @return boolean
     */
    public function signup($val, $active = false)
    {
        $credential = [
            'username' => '',
            'password' => '',
            'email_address' => '',
            'fullname' => '',
            'genre' => '',
            'country' => '',
            'birth_day' => 0,
            'birth_month' => 0,
            'birth_year' => 0
        ];

        /**
         * @var $username
         * @var $password
         * @var $email_address
         * @var $fullname
         * @var $genre
         * @var $country
         * @var $birth_day
         * @var $birth_month
         * @var $birth_year
         */
        extract($credential = array_merge($credential, $val));

        $user = $this->model->newInstance();
        $user->username = sanitizeText($username, 100);
        $user->email_address = $email_address;
        $user->fullname = sanitizeText($fullname, 100);
        $user->genre = sanitizeText($genre);
        $user->country = sanitizeText($country);
        $user->password = \Hash::make($password);
        $user->online_status = 1;
        $user->last_active_time = time();
        $user->birth_day = sanitizeText($birth_day);
        $user->birth_month = sanitizeText($birth_month);
        $user->birth_year = sanitizeText($birth_year);

        if ($active or !\Config::get('user-activation')) {
            $user->active = 1;
            $user->activated = 1;
        }

        if (!\Config::get('user-getstarted')) {
            $user->fully_started = 1;
        }


        $user->save();

        /***
         * Send a welcome email to our new user
         */
        try {
            $this->mailer->queue('emails.auth.welcome', [
                'username' =>  $user->username,
                'fullname' => $user->fullname,
                'email_address' => $user->email_address,
                'profileUrl' => $user->present()->url(),
                'site_name' => \Config::get('site_title')
            ], function($mail) use($user) {
                $mail->to($user->email_address, $user->fullname)
                    ->subject(trans('mail.welcome-mail-subject'));
            });
        } catch(\Exception $e) {

        }


        $this->event->fire('user.register', [$user, $val]);
        return $user;
    }

    /**
     * Send Activation code
     *
     * @param \App\Models\User $user
     * @return boolean
     */
    public function sendActivation($user)
    {
        $hash = md5($user->username.$user->password);
        $user->hash = $hash;
        $user->save();

        try{
            \Mail::queue('emails.auth.activation', [
                'hash' => $hash,
                'site_name' => \Config::get('site_title'),
                'username' =>  $user->username,
                'fullname' => $user->fullname,
                'email_address' => $user->email_address,
                'profileUrl' => $user->present()->url(),
            ], function($mail) use($user) {
                $mail->to($user->email_address, $user->fullname)
                    ->subject(trans('mail.activation-mail-subject', [
                        'name' => $user->username,
                        'site_title' => \Config::get('site_title')
                    ]));
            });
        } catch(\Exception $e) {

        }

        return true;
    }

    /**
     * Find user by its activation hash
     *
     * @param array $val
     * @return \App\Models\User
     */
    public function findByHash($val, $withEmail = true)
    {
        /**
         * @var $email
         * @var $code
         */
        extract($val);

        if (!$withEmail) {
            return $this->model->where('hash', '=', $code)->first();
        }
        return $this->model->where('hash', '=', $code)->where('email_address', '=', $email)->first();
    }

    /**
     * Activate account
     *
     * @param array $val
     * @return boolean
     */
    public function activate($val)
    {


        $user = $this->findByHash($val);

        if (!$user) return false;

        \Auth::login($user);

        if ($user->activated) return $user;
        $user->activated = 1;
        $user->active = 1;
        $user->save();

        $this->mustAvoidUserRepository->remove($user->id); //remove from avoided users

        return $user;
    }

    /**
     * Send password retrieval message
     *
     * @param string $email
     * @return boolean
     */
    public function retrievePassword($email)
    {
        $user = $this->findByEmail($email);

        if (!$user) return false;

        $hash = md5($user->username.$user->password);
        $user->hash = $hash;
        $user->save();

        try {
            $this->mailer->queue('emails.auth.password', [
                'hash' => $hash,
                'username' =>  $user->username,
                'fullname' => $user->fullname,
                'email_address' => $user->email_address,
                'site_name' => \Config::get('site_title'),
                'profileUrl' => $user->present()->url(),
            ], function($mail) use($user) {
                $mail->to($user->email_address, $user->fullname)
                    ->subject(trans('mail.forgot-pass-mail-subject'));
            });
        } catch(\Exception $e) {

        }

        return true;
    }

    /**
     * Save user settings
     *
     * @param $user
     * @param array $val
     * @param boolean $isAdmin
     * @return string
     */
    public function saveSettings($user, $val, $isAdmin = false)
    {
        $expected = [
            'username' => '',
            'fullname' => '',
            'currentpassword' => '',
            'newpassword' => '',
            'genre' => '',
            'activated' => '',
            'active' => '',
            'group' => '',
            'country' => '',
            'city' => '',
            'email' =>  '',
            'bio' => 'bio',
            'avatar_type' => 0,
            'language' => '',
            'birth_day' => 0,
            'birth_month' => 0,
            'birth_year' => 0
        ];

        $val = array_merge($expected, $val);

        /**
         * @var $username
         * @var $fullname
         * @var $currentpassword
         * @var $newpassword
         * @var $genre
         * @var $activated
         * @var $active
         * @var $group
         * @var $country
         * @var $city
         * @var $email
         * @var $bio
         * @var $avatar_type
         * @var $language
         * @var $birth_day
         * @var $birth_month
         * @var $birth_year
         */
        extract($val);

        $message = null;

        $user->fullname = sanitizeText($fullname, 100);
        if (!empty($genre)) $user->genre = sanitizeText($genre);
        if ($isAdmin) {
            if (\Auth::user()->id != 1) return false;
            $user->activated = $activated;
            $user->active = $active;
            $user->user_group = $group;
            $user->country = sanitizeText($country);
            $user->email_address = $email;
        } else {
            $user->bio = sanitizeText($bio);
            $user->country = sanitizeText($country);
            $user->city = sanitizeText($city, 70);
            $user->birth_day = sanitizeText($birth_day);
            $user->birth_month = sanitizeText($birth_month);
            $user->birth_year = sanitizeText($birth_year);

            $this->savePrivacy(['lang' => $language], $user);
        }

        $validatorRules = [];

        if (!empty($username) and $username != $user->username) {
            $validatorRules['username'] = 'required|alpha_num|slug|unique:users,username,'.$user->id;
        }

        if (!$isAdmin and $email != $user->email_address) {
            $validatorRules['email'] = 'required|email|unique:users,email_address,'.$user->id;
        }

        /**
         * Check for new password
         */
        if (!empty($newpassword)) {
            $validatorRules['currentpassword'] = 'required';
            $validatorRules['newpassword'] = 'required|min:6';

            if (!\Hash::check($currentpassword, $user->password)) {
                $message = trans('user.password-does-not-match');
            } else {
                $user->password = \Hash::make($newpassword);
            }

        }

        //update user avatar type
        $this->savePrivacy(['avatar_type' => $avatar_type]);

        if (!empty($validatorRules)) {

            $validator = \Validator::make($val, $validatorRules);

            if ($validator->fails()) {
                $message = $validator->messages()->first();
            }

            /**
             * Saving username from here if it doesnot have the error message
             */
            if (\Config::get('can-change-username') and !$validator->messages()->has('username') and $username != $user->username) {
                $user->username = sanitizeText($username, 100);

                if ($user->verified == 1 and \Config::get('remove-verify-badge-username')) {
                    $user->verified = 0;
                }
            }

            /**
             * Checking for validity of emails
             */
            if (!$isAdmin and !$validator->messages()->has('email')) {
                $user->email_address = $email;
            }
        }

        /**
         * Save and update user details
         */
        $user->save();

        $this->event->fire('user.setting.update', [$user, $val]);

        return $message;
    }

    /**
     * Change user avatar
     *
     * @param string $file
     * @param \App\Models\$user
     * @return \iDocrea8\Image\ImageProcessor
     */
    public function changeAvatar($file, $user = null)
    {
        $user = (empty($user)) ? \Auth::user() : $user;
        $album = $this->photoAlbum->add('profile photos', $user->id, true);
        $image = $this->photoRepository->upload($file, [
            'path' => 'users/'.$user->id,
            'slug' => 'album-'.$album->id,
            'userid' => $user->id
        ]);


        if (!$image) return false;

        if ($image and $album) {
            if (isset($album->default_photo) and empty($album->default_photo)) {
                $album->default_photo = $image;
                $album->save();
            }
        }


        /**
         * Now save user avatar
         */
        $user->avatar = $image;

        if ($user->cover == '') {
            $cover = $this->photoRepository->upload($file, [
                'path' => 'users/'.$user->id,
                'slug' => 'cover',
                'userid' => $user->id,
                'width' => 1000,
                'height' => 400
            ]);
            $user->cover = str_replace('_%d_', '_1000_', $cover);
        }
        $user->save();

        //automatically update this user avatar type
        $this->savePrivacy(['avatar_type' => 1]);

        $this->event->fire('user.avatar', [$user, $image]);

        return $user;

    }

    /**
     * Method to update user cover
     *
     * @param string $image
     * @param \App\Models\User $user
     * @return boolean
     */
    public function updateCover($image, $user = null, $original = false)
    {
        $user = (empty($user)) ? \Auth::user() : $user;
        $user->cover = sanitizeText($image);
        if ($original) $user->original_cover = $image;
        $user->save();

        /**
         * Let help user to keep record of profile covers upload
         */
        if($original) $this->photoRepository->add($image, $user->id, 'cover');

        if ($original) $this->event->fire('user.update.cover', [$user, $image]);

        return true;
    }

    /**
     * Method to update user profile details
     *
     * @param array $val
     * @param \App\Models\User $user
     * @return boolean
     */
    public function updateProfile($val, $user = null)
    {
        $user = (empty($user)) ? \Auth::user() : $user;

        $newVal = [];

        foreach($val as $id => $value) {
            $newVal[$id] = sanitizeText($value);
        }
        $user->profile_details = perfectSerialize($newVal);
        $user->save();

        $this->event->fire('user.update.profile', [$user, $val]);

        return true;
    }

    /**
     * @param        $file
     * @param string $current
     * @param null   $user
     * @return mixed
     */
    public function changeDesignBg($file, $current = '', $user = null)
    {
        $user = (empty($user)) ? \Auth::user() : $user;
        $image = $this->photoRepository->upload($file, [
            'path' => 'users/'.$user->id.'/design',
            'slug' => 'design',
            'userid' => $user->id,
            'resize' => false
        ]);

        if (!$image) return false;
        if ($current) $this->photoRepository->delete($current);
        return $image;

    }

    /**
     * @param      $val
     * @param null $user
     * @return bool
     */
    public function saveDesign($val, $user = null)
    {
        $user = (empty($user)) ? \Auth::user() : $user;

        $expected = [
            'type' => 'profile',
            'theme' => 'default',
            'enable' => false,
            'bg_image' => '',
            'bg_color' => '',
            'bg_position' => '',
            'bg_attachment' => '',
            'bg_repeat' => '',
            'link_color' => '',
            'content_bg_color' => ''
        ];

        /**
         * @var $type,
         */
        extract($val = array_merge($expected, $val));

        $designs = perfectUnserialize($user->design_info);
        $designs[$type] = sanitizeUserInfo($val);
        $user->design_info = perfectSerialize($designs);
        $user->save();

        $this->event->fire('user.update.design', [$user, $val]);

        return true;
    }

    /**
     * Change password
     *
     * @param array $val
     * @param \App\Models\User $user
     * @return boolean
     */
    public function changePassword($val, $user = null)
    {
        $user = (empty($user)) ? \Auth::user() : $user;

        /*
         * @var $password
         */
        extract($val);

        $user->password = \Hash::make($password);

        /**
         * Once user can use his/her email to retrieve password means the email is verified
         * to check if account is activated or not
         */
        $user->activated = 1;

        $user->save();

        $this->event->fire('retrieve-password', [$user]);
        return true;
    }

    /**
     * Save user bio
     *
     * @param string $bio
     * @param \App\Models\User $user
     * @return boolean
     */
    public function saveBio($bio, $user = null, $city = null)
    {
        $user = (empty($user)) ? \Auth::user() : $user;
        $user->bio = sanitizeText($bio);
        if ($city) {
            $user->city = sanitizeText($city);
        }
        $user->save();

        return $user;
    }

    /**
     * save user privacy info
     *
     * @param array $val
     * @param \App\Models\User $user
     * @return boolean
     */
    public function savePrivacy($val, $user = null)
    {
        $user = (empty($user)) ? \Auth::user() : $user;

        $privacy = (empty($user->privacy_info)) ? [] : perfectUnserialize($user->privacy_info);
        $privacy = array_merge($privacy, $val);

        $user->privacy_info = perfectSerialize(sanitizeUserInfo($privacy));
        $user->save();

        $this->event->fire('user.save.privacy', [$user]);

        return true;
    }

    /**
     * Finish getstarted
     *
     * @param \App\Models\User $user
     * @return \App\Models\User
     */
    public function finishGetstarted($user = null)
    {
        $user = (empty($user)) ? \Auth::user() : $user;
        $user->fully_started = 1;
        $user->save();

        return $user;

    }

    /**
     * Method to list all users neccessary on admincp
     *
     * @return array
     */
    public function listAll($term = null)
    {
        $users = $this->model
            ->where('active', '!=', 0)
            ->where('banned', '!=', 1)
            ->where('activated', '!=', 0);

        if ($term) {
            $users = $users->where('fullname', 'LIKE', '%'.$term.'%')
                ->orWhere('username', 'LIKE', '%'.$term.'%')
            ->orWhere('email_address', 'LIKE', '%'.$term.'%');
        }

        return $users = $users->orderBy('id', 'desc')->paginate(20);
    }

    public function listBanned($term = null)
    {
        $users = $this->model
            ->where('banned', '=', 1);

        if ($term) {
            $users = $users->where('fullname', 'LIKE', '%'.$term.'%')
                ->orWhere('username', 'LIKE', '%'.$term.'%')
                ->orWhere('email_address', 'LIKE', '%'.$term.'%');
        }

        return $users = $users->orderBy('id', 'desc')->paginate(20);
    }

    public function getAll()
    {
        return $this->model->orderBy('id', 'desc')->get();
    }

    public function listOnlineUsers()
    {
        $offset = time() - 1000;
        $userid = \Auth::user()->id;

        $friends = app('App\\Repositories\\ConnectionRepository')->getFriendsId();

        return $this->model
            ->where('activated', 1)
            ->where('active', 1)
            ->whereIn('id', $friends)
            ->whereNotIn('id', $this->mustAvoidUserRepository->get())
            ->where('last_active_time', '>', $offset)
            ->where('online_status', '!=', 0)
            ->where('id', '!=', $userid)
            ->get();
    }

    public function countFriendsOnline()
    {
        return count($this->listOnlineUsers());
    }

    public function listUnvalidatedUsers()
    {
        return $this->model->where('activated', '=', 0)->orderBy('id', 'desc')->paginate(20);
    }

    /**
     * Admincp update a user
     *
     * @param array $val
     * @param \App\Models\User $user
     * @return boolean
     */
    public function adminUpdate($val, $user)
    {
        //if (\Auth::user()->id != 1) return false;
        $expected = [
            'fullname' => '',
            'username' => '',
            'email' => '',
            'genre' => '',
            'verified' => '',
            'activated' => '',
            'admin' => 0,
            'password' => ''
        ];

        /**
         * @var $fullname
         * @var $username
         * @var $email
         * @var $genre
         * @var $verified
         * @var $activated
         * @var $admin
         * @var $password
         */
        extract($val = array_merge($expected, $val));

        $user->email_address = $email;
        $user->fullname = sanitizeText($fullname, 100);
        $user->username = sanitizeText($username);
        $user->genre = $genre;
        $user->verified = $verified;
        $user->activated = $activated;

        if ($password) {
            $user->password = \Hash::make($password);
        }

        if ($activated) {
            $user->active = 1;
            $this->mustAvoidUserRepository->remove($user->id);
        }
        $user->admin = $admin;
        $user->save();

        return true;
    }

    public function deactivate($val, $parseUser = null)
    {
        //if (\Auth::user()->id != 1) return false;
        $expected = [
            'userid' => '',
            'permanent' => 0
        ];

        /**
         * @var $userid
         * @var $permanent
         */
        extract(array_merge($expected, $val));


        $user = (empty($parseUser)) ? \Auth::user() : $parseUser;
        $user->activated = 0;
        $user->active = 0;
        $user->save();

        $this->mustAvoidUserRepository->add($user->id);

        if ($permanent) {
            $this->delete($user->id);//sorry we are deleting the user completely
        }

        if (!$parseUser) \Auth::logout();
        return true;
    }

    public function resendActivation($email)
    {
        //if (\Auth::user()->id != 1) return false;
        $user = $this->findByEmail($email);

        if (!$user or $user->activated == 1) return false;
        $this->sendActivation($user);
        return true;
    }

    public function ban($val, $user)
    {
        //if (\Auth::user()->id != 1) return false;
        $expected = [
            'action' => 'ban',
            'message' => ''
        ];

        /**
         * @var $action
         * @var $message
         */
        extract(array_merge($expected, $val));

        if ($action == 'unban') {
            $user->activated = 1;
            $user->active = 1;
            $user->banned = 0;
            $user->save();

            $this->mustAvoidUserRepository->remove($user->id); //remove from avoided users

            //send a mail to user
            try{
                \Mail::queue('emails.user.unban', [
                    'message' => $message,
                    'site_name' => \Config::get('site_title'),
                    'username' =>  $user->username,
                    'fullname' => $user->fullname,
                    'email_address' => $user->email_address,
                    'profileUrl' => $user->present()->url(),
                ], function($mail) use($user) {
                    $mail->to($user->email_address, $user->fullname)
                        ->subject(trans('mail.user-unban', [
                            'name' => $user->username,
                            'site_title' => \Config::get('site_title')
                        ]));
                });
            } catch(\Exception $e) {}
        } else {
            $this->deactivate([], $user);
            $user->banned = 1;
            $user->save();

            //send a mail to user
            try{
                \Mail::queue('emails.user.ban', [
                    'message' => $message,
                    'site_name' => \Config::get('site_title'),
                    'username' =>  $user->username,
                    'fullname' => $user->fullname,
                    'email_address' => $user->email_address,
                    'profileUrl' => $user->present()->url(),
                ], function($mail) use($user) {
                    $mail->to($user->email_address, $user->fullname)
                        ->subject(trans('mail.user-ban', [
                            'name' => $user->username,
                            'site_title' => \Config::get('site_title')
                        ]));
                });
            } catch(\Exception $e) {}
        }
    }

    /**
     * @param $userid
     * @return bool
     */

    public function delete($userid)
    {
        //if (\Auth::user()->id != 1) return false;
        $user = $this->findById($userid);
        $loggedUser = \Auth::user();

        if ($user and $user->admin == 1) return false;

        if ($user and ($loggedUser->id == $userid or $loggedUser->isAdmin())) {
            //this is when we call delete this user
            $user->delete();

            foreach([
                'App\\Repositories\\PostRepository',
                'App\\Repositories\\BlockedUserRepository',
                'App\\Repositories\\CommentRepository',
                'App\\Repositories\\LikeRepository',
                'App\\Repositories\\PhotoRepository',
                'App\\Repositories\\NotificationReceiverRepository',
                'App\\Repositories\\ConnectionRepository',
                'App\\Repositories\\NotificationRepository',
                'App\\Repositories\\CommunityRepository',
                'App\\Repositories\\CommunityMemberRepository',
                'App\\Repositories\\PageRepository',
                'App\\Repositories\\GameRepository',
                'App\\Repositories\\MessageRepository',
                'App\\Repositories\\ReportRepository',
                'App\\Repositories\\MessageConversationRepository',
            ] as $object) {
                app($object)->deleteAllByUser($userid);
            }
            return true;
        }
    }

    public function total()
    {
        return $this->model->count();
    }

    public function totalOnline()
    {
        return $this->model->where('last_active_time', '>', time() - 1000)->count();
    }

}
