<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

/**
 *
 *@author : Tiamiyu waliu
 *@website : http://www.iDocrea8.com
 */
class UserPresenter extends Presenter
{

    public function privacy($id, $default = '')
    {
        $user = $this->entity;
        if (empty($user->privacy_info)) return $default;

        if (!function_exists('perfectUnserialize')) {
            require app_path().'/functions.php';
        }

        $privacy = perfectUnserialize($this->privacy_info);
        if (empty($privacy)) {
            $user->privacy_info = '';
            $user->save();
        };

        if (isset($privacy[$id])) return $privacy[$id];
        return $default;
    }

    public function canSendMessage()
    {
        $privacy = $this->entity->present()->privacy('send-message', 'public');
        if (!\Auth::check()) return false;
        if(\Auth::user()->id == $this->entity->id) return false;
        $connection = app('App\\Repositories\\ConnectionRepository');

        if ($privacy == 'public') {
            return true;
        } elseif ($privacy == 'friends') {
            //only friends

            if (\Auth::check() and $connection->areFriends($this->entity->id, \Auth::user()->id)) return true;
            return false;

        } elseif ($privacy == 'friend-follower') {
            //only friends and followers
            if (\Auth::check() and $connection->areFriends($this->entity->id, \Auth::user()->id)) return true;

            //now check for follower
            if (\Auth::check() and $connection->isFollowing(\Auth::user()->id, $this->entity->id)) return true;
            return false;

        } elseif($privacy == 'nobody') {
            return false;
        }

        return false;
    }

    public function canPost()
    {
        $privacy = $this->entity->present()->privacy('timeline-post', 'public');
        if (!\Auth::check()) return false;
        if(\Auth::user()->id == $this->entity->id) return false;
        $connection = app('App\\Repositories\\ConnectionRepository');

        if ($privacy == 'public') {
            return true;
        } elseif ($privacy == 'friends') {
            //only friends

            if (\Auth::check() and $connection->areFriends($this->entity->id, \Auth::user()->id)) return true;
            return false;

        } elseif ($privacy == 'friend-follower') {
            //only friends and followers
            if (\Auth::check() and $connection->areFriends($this->entity->id, \Auth::user()->id)) return true;

            //now check for follower
            if (\Auth::check() and $connection->isFollowing(\Auth::user()->id, $this->entity->id)) return true;
            return false;

        } elseif($privacy == 'nobody') {
            return false;
        }

        return false;

    }

    public function postPrivacyValue()
    {
        return $privacy = $this->entity->present()->privacy('post-privacy-default', 2);
    }

    public function postPrivacyName()
    {
        $privacy = $this->entity->present()->privacy('post-privacy-default', 2);
        switch($privacy) {
            case 1:
                return trans('global.public');
                break;
            case 2:
                return trans('connection.friends');
                break;
            case 3:
                return trans('connection.followers');
                break;
            case 4:
                return trans('connection.friends-followers');
                break;
            case 5:
                return trans('connection.only-me');
                break;
        }
    }

    public function isOnline()
    {
        $offset = time() - 1000;
        $online =  ($this->entity->last_active_time > $offset);

        if (!$online) {
            if ($this->entity->online_status == 1) {
                //lets update it to offline
                $this->entity->updateStatus(0);
                return 0;
            }

        } else {
            $onlineStatus = $this->entity->online_status;

            if ($onlineStatus != 1) return $onlineStatus;
            return 1;
        }


    }

    /**
     * Format user fullname correctly
     *
     * @return string
     */
    public function fullName()
    {
        $name = (String) $this->entity->fullname;
        return ucwords($name);
    }

    /**
     * Format username
     *
     * @return string
     */
    public function atName()
    {
        return '@'.$this->username;
    }

    public function joinedOn()
    {
        return str_replace(' ', 'T', $this->entity->created_at).'Z';
    }

    public function lastLoginOn()
    {
        return str_replace(' ', 'T', $this->entity->updated_at).'Z';
    }

    /**
     * Privacy setting for profile page
     */
    public function canViewMe()
    {

        if (\Auth::check() and $this->entity->id == \Auth::user()->id) return true; //viewer is the owner

        //for admin to be able to view private profile
        if (\Auth::check() and \Auth::user()->isAdmin()) return true;

        $privacy = $this->privacy('view-profile', 'public');

        if ($privacy == 'public') {
            return true;
        } elseif($privacy == 'nobody') {
            return false;
        } else {
            $connection = app('App\\Repositories\\ConnectionRepository');

            if ($privacy == 'friends') {
                if (\Auth::check() and $connection->areFriends($this->entity->id, \Auth::user()->id)) return true;
                return false;
            } elseif ($privacy == "friend-follower") {
                if (\Auth::check() and $connection->areFriends($this->entity->id, \Auth::user()->id)) return true;

                //now check for follower
                if (\Auth::check() and $connection->isFollowing(\Auth::user()->id, $this->entity->id)) return true;
                return false;
            }
        }
    }

    public function canSeeBirth()
    {
        if (\Auth::check() and $this->entity->id == \Auth::user()->id) return true; //viewer is the owner

        //for admin to be able to view private profile
        if (\Auth::check() and \Auth::user()->isAdmin()) return true;

        $privacy = $this->privacy('view-birth', 'public');

        if ($privacy == 'public') {
            return true;
        } elseif($privacy == 'nobody') {
            return false;
        } else {
            $connection = app('App\\Repositories\\ConnectionRepository');

            if ($privacy == 'friends') {
                if (\Auth::check() and $connection->areFriends($this->entity->id, \Auth::user()->id)) return true;
                return false;
            } elseif ($privacy == "friend-follower") {
                if (\Auth::check() and $connection->areFriends($this->entity->id, \Auth::user()->id)) return true;

                //now check for follower
                if (\Auth::check() and $connection->isFollowing(\Auth::user()->id, $this->entity->id)) return true;
                return false;
            }
        }
    }

    /**
     * Method to get user profile details value
     *
     * @param string $name
     * @return mixed
     */
    public function profile($name = null)
    {
        $details = (!empty($this->entity->profile_details)) ? perfectUnserialize($this->entity->profile_details) : [];

        if (empty($name)) return $details;

        if (isset($details[$name])) return $details[$name];

        return null;
    }

    public function fields()
    {
        return app('App\\Repositories\\CustomFieldRepository')->listAll('profile');
    }
    /**
     * User avatar
     *
     * @param int $size
     * @return string
     */
    public function getAvatar($size = 100)
    {
        $avatar = $this->avatar;
        $avatarType = $this->privacy('avatar_type', 0);
        $realPathToAvatar = base_path(str_replace('%d', 200, $avatar));

        if ($avatarType == 0
            or empty($avatar)
            or (!preg_match('#http:\/\/|https:\/\/|amazon|cdnuploads#', $realPathToAvatar) and !file_exists($realPathToAvatar))) return $this->defaultAvatar($size);

        return \Image::url($avatar, $size);
    }

    /**
     * Get Default avatar for us
     */
    protected function defaultAvatar($size = 100)
    {
        $firstLetter = strtolower(substr($this->entity->fullname, 0, 1));

        if (in_array($firstLetter, [
            'a','b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
        ])) {
            return \URL::to(\Theme::asset()->img('theme/images/avatar/'.$firstLetter.'/'.$size.'.png'));
        } else {
            return \URL::to(\Theme::asset()->img('theme/images/avatar/default/'.$size.'.png'));
        }

    }

    /**
     * Get user cover image
     */
    public function getCover()
    {
        $default = \Theme::asset()->img("theme/images/profile-cover.jpg");
        if (!$this->entity->cover) return $default;
        return \Image::url($this->entity->cover);
    }

    public function coverImage()
    {
        return $this->getCover();
    }

    public function getOriginalCover()
    {
        if ($this->entity->original_cover) return \Image::url($this->entity->original_cover);
    }

    /**
     * @param        $key
     * @param string $type
     * @param string $default
     * @return null
     */
    public function design($key, $type = 'profile', $default = '')
    {
        $design = (!empty($this->entity->design_info)) ? perfectUnserialize($this->entity->design_info) : [];
        if (!$design) return null;
        if (isset($design[$type])) {
            $userDesign = $design[$type];
            return (isset($userDesign[$key])) ? $userDesign[$key] : null;
        }

        return null;
    }

    public function readDesign($type = 'profile')
    {
        $design = ['enable' => false];
        $userDesign = (!empty($this->entity->design_info)) ? perfectUnserialize($this->entity->design_info) : [];
        $userDesign = (!$userDesign) ? [] : $userDesign;
        $design = array_merge($design, $userDesign);

        if (isset($design[$type]))
        {
            $design = $design[$type];
        }


        /**
         * @var $enable
         * @var $theme
         */
        extract($design);

        if ($enable) $design['bg_image'] = \Image::url($design['bg_image']);

        if (!$enable) {

            $themeDesign = \Theme::option()->get('design-themes');
            $themeDesign = (empty($theme) or !isset($themeDesign[$theme])) ? $themeDesign['default'] : $themeDesign[$theme];

            /**
             * Reset the design to this selected one
             */
            $design['bg_image'] = $themeDesign['background-image'];
            $design['bg_color'] = $themeDesign['background-color'];
            $design['bg_attachment'] = $themeDesign['background-attachment'];
            $design['bg_position'] = $themeDesign['background-position'];
            $design['bg_repeat'] = $themeDesign['background-repeat'];
            $design['link_color'] = $themeDesign['link-color'];
            $design['content_bg_color'] = $themeDesign['page-content-bg-color'];
        }

        return $design;
    }

    /**
     * check if user is an admin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        $group = $this->group;

        if (strtolower($group->category) == 'admin') {
            return true;
        }
        return false;
    }

    /**
     * Check if a user is a moderator
     *
     * @return boolean
     */
    public function isModerator()
    {
        if ($this->group->category == 'moderator') return true;
        return false;
    }

    public function url($segment = null)
    {
        $url = \URL::route('profile', ['id' => (\Config::get('profile-url-format') == 1) ? $this->entity->id : $this->entity->username]).(($segment) ? '/'.$segment : null);
        return $url;
    }

    /**
     * Helper method to know if a user can follow this user
     *
     * @param \App\Models\User $user
     * @return bo
     */
    public function canFollowMe($user)
    {
        $privacy = $this->privacy('follow-me', 1);
        if ($privacy == 1) {
            return true;
        } elseif($privacy == 2) {
            $friend = app('App\\Repositories\\ConnectionRepository');
            if ($friend->areFriends($this->entity->id, $user->id)) return true;
        }

        return false;
    }

    public function popoverUrl()
    {
        return \URL::route('load-user-popover', ['id' => $this->entity->id]);
    }
}