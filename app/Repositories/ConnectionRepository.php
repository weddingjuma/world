<?php

namespace App\Repositories;

use App\Models\Connection;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use Illuminate\Cache\Repository;
use Illuminate\Events\Dispatcher;
use Illuminate\Mail\Mailer;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ConnectionRepository
{
    public function __construct(
        Connection $connection,
        Dispatcher $event,
        NotificationRepository $notificationRepository,
        Repository $cache,
        \Illuminate\Config\Repository $config,
        UserRepository $userRepository,
        Mailer $mailer,
        RealTimeRepository $realTimeRepository,
        MustAvoidUserRepository $mustAvoidUserRepository
    )
    {
        $this->model = $connection;
        $this->event = $event;
        $this->notification = $notificationRepository;
        $this->cache = $cache;
        $this->config = $config;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->realTimeRepository = $realTimeRepository;
        $this->mustAvoidUserRepository = $mustAvoidUserRepository;
    }

    /**
     * Method to get  following of a user
     *
     * @param int $userid
     * @return array
     */
    public function getFollowingId($userid = null)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        $cacheName = 'following-'.$userid;

        if ($this->cache->has($cacheName)) {
            return $this->cache->get($cacheName);
        } else {
            $result = $this->model->where('user_id', '=', $userid)
                ->where('way', '=', 1)
                ->lists('to_user_id');
            $result[] = 0;

            $this->cache->forever($cacheName, $result);
            return $result;
        }

    }

    /**
     * Method to get followers
     *
     * @param int $userid
     * @param int $limit
     * @return array
     */
    public function followers($userid, $limit = 10)
    {
        return $this->model->with('fromUser')
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get())
            ->where('to_user_id', '=', $userid)
            ->where('way', '=', 1)->paginate($limit);
    }

    /**
     * Method to count followers
     *
     * @return int
     */
    public function countFollowers($userid)
    {
        return $this->model->with('fromUser')
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get())
            ->where('to_user_id', '=', $userid)
            ->where('way', '=', 1)->count();
    }

    public function countFollowing($userid)
    {
        return $this->model
            ->whereNotIn('to_user_id', $this->mustAvoidUserRepository->get())
            ->where('user_id', '=', $userid)
            ->where('way', '=', 1)->count();
    }

    /**
     * Method to get following members
     *
     * @param int $userid
     * @param int $limit
     * @return array
     */
    public function following($userid, $limit = 10)
    {
        return $this->model->with('toUser')
            ->whereNotIn('to_user_id', $this->mustAvoidUserRepository->get())
            ->where('user_id', '=', $userid)
            ->where('way', '=', 1)->paginate($limit);
    }

    /***
     * Method to get friends id of a user
     *
     * @param int $userid
     * @return array
     */
    public function getFriendsId($userid = null)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        $cacheName = 'friends-'.$userid;

        if ($this->cache->has($cacheName)) {
            return $this->cache->get($cacheName);
        } else {
            $result = $this->model
                ->where('way', '=', 2)
                ->where('confirmed','=', 1)
                ->where(function($q) use($userid) {
                    $q->where('to_user_id', '=', $userid)
                        ->orWhere('user_id', '=', $userid);
                })
                ->get();

            $newResult = [0];

            foreach($result as $connection) {
                $newResult[] = ($connection->user_id == $userid) ? $connection->to_user_id : $connection->user_id;
            }

            $this->cache->forever($cacheName, $newResult);
            return $newResult;
        }

    }

    public function getFriends($userid = null, $limit = 10)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;

        return $result = $this->model->with(['fromUser', 'toUser'])
            ->where('way', '=', 2)
            ->where('confirmed','=', 1)
            ->where(function($q) use($userid) {
                $q->where('to_user_id', '=', $userid)
                    ->orWhere('user_id', '=', $userid);
            })
            ->paginate($limit);
    }

    /**
     * Method to add connection
     *
     * @param int $userid
     * @param int $toUserid
     * @param int $way
     * @param boolean $notify
     * @return boolean
     */
    public function add($userid, $toUserid, $way = 2, $notify = true)
    {
        $userid = sanitizeText($userid);
        $toUserid = sanitizeText($toUserid);
        $way = sanitizeText($way);
        //must not existed in this way of connection
        if (!is_numeric($userid) or !is_numeric($toUserid)) return false;

        if (!$this->exists($userid, $toUserid, $way)) {
            $user = $this->userRepository->findById($toUserid);
            if ($way == 2 and $user->present()->privacy('turnoff-friend-request', 0) == 1) return false;

            $connection = $this->model->newInstance();
            $connection->user_id = $userid;
            $connection->to_user_id = $toUserid;
            $connection->way = $way;
            $connection->confirmed = ($way == 2) ? 0 : 1;
            $connection->save();



            $this->cache->forget('user-suggestions-'.$userid);
            $this->cache->forget('user-suggestions-'.$toUserid);

            if ($way == 1) {
                if ($notify) {

                    $this->notification->send($toUserid, [
                        'title' => trans('connection.started-following')
                    ], $userid, true, 'notify-follow-me', $user);

                    if ($user->present()->privacy('email-notify-follow-me', 1)) {
                        $followerUser = app('App\\Repositories\\UserRepository')->findById($userid);
                        try{
                            $this->mailer->queue('emails.connection.follow', [
                                'fullname' => $user->fullname,
                                'username' => $user->username,
                                'followerName' => $followerUser->fullname,
                                'followerUsername' => $followerUser->username,
                                'followerProfileUrl' => $followerUser->present()->url(),
                                'followerAtName' => $followerUser->present()->atName()
                            ], function($mail) use($user, $followerUser) {
                                $mail->to($user->email_address, $user->fullname)
                                    ->subject(trans('mail.new-follower', ['fullname' => $followerUser->fullname]));
                            });
                        } catch(\Exception $e) {

                        }
                    }
                }

                /**
                 * Also delete this follower following cache list
                 */
                $this->cache->forget('following-'. $userid);
            } else {
                $this->realTimeRepository->add($user->id, 'friend-request');
                if ($user->present()->privacy("email-notify-friend-request", 1)) {
                    $followerUser = \Auth::user();
                    try {
                        $this->mailer->queue('emails.connection.friend', [
                            'fullname' => $user->fullname,
                            'username' => $user->username,
                            'friendName' => $followerUser->fullname,
                            'friendUsername' => $followerUser->username,
                            'friendProfileUrl' => $followerUser->present()->url(),
                            'friendAtName' => $followerUser->present()->atName()
                        ], function($mail) use($user, $followerUser) {
                            $mail->to($user->email_address, $user->fullname)
                                ->subject(trans('mail.new-friend-request', ['fullname' => $followerUser->fullname]));
                        });
                    } catch(\Exception $e){

                    }
                }

                //clear this both user friends list
                $this->cache->forget("all-friend-connection-".$userid);
                $this->cache->forget("all-friend-connection-".$toUserid);
                $this->cache->forget('friends-'.$userid);
                $this->cache->forget('friends-'.$toUserid);


            }

            $this->event->fire('connection.add', [$userid, $toUserid, $way]);
        }
        return false;
    }

    /**
     * Method to auto make two users connection without confirmation
     *
     * @param int $userid
     * @param int $toUserid
     * @return bool
     */
    public function autoConnectUser($userid, $toUserid)
    {
        $connection = $this->model->newInstance();
        $connection->user_id = $userid;
        $connection->to_user_id = $toUserid;
        $connection->way = 2;
        $connection->confirmed =  1;
        $connection->save();

        //clear this both user friends list
        $this->cache->forget("all-friend-connection-".$toUserid);
        $this->cache->forget('friends-'.$toUserid);

    }

    /**
     * Method to remove connection
     *
     * @param int $userid
     * @param int $toUserid
     * @param int $way
     */
    public function remove($userid, $toUserid, $way)
    {
        $this->event->fire('connection.remove', [$userid, $toUserid, $way]);
        $this->cache->forget('user-suggestions-'.$userid);
        $this->cache->forget('user-suggestions-'.$toUserid);

        if ($way == 1) {
            $this->cache->forget('following-'.$userid);
            return $this->model->where('user_id', '=', $userid)
                ->where('to_user_id', '=', $toUserid)
                ->where('way', '=', $way)
                ->delete();
        } else {
            //clear this both user friends list
            $this->cache->forget("all-friend-connection-".$userid);
            $this->cache->forget("all-friend-connection-".$toUserid);
            $this->cache->forget('friends-'.$userid);
            $this->cache->forget('friends-'.$toUserid);

            //yeah they need to unfollow each other too
            $this->remove($userid, $toUserid, 1);
            $this->remove($toUserid, $userid, 1);


            return $this->model
                ->where(function($query) use($userid, $toUserid, $way) {
                    $query->where('user_id', '=', $userid)
                        ->where('way', '=', $way)
                        ->where('to_user_id', '=', $toUserid);
                })->orWhere(function($query) use($userid, $toUserid, $way) {
                    $query->where('user_id', '=', $toUserid)
                        ->where('way', '=', $way)
                        ->where('to_user_id', '=', $userid);
                })
                ->delete();
        }


    }

    /**
     * Method to check existing connection
     *
     * @param int $userid
     * @param int $toUserid
     * @param int $way
     * @return boolean
     */
    public function exists($userid, $toUserid, $way)
    {
        if ($way == 1) {
            return $query = $this->model->where('user_id', '=', $userid)
                ->where('to_user_id', '=', $toUserid)
                ->where('way', '=', $way)
                ->where('confirmed', '=', 1)->first();

        }
        if ($way == 2) {
            $query = $this->model->where('user_id', '=', $userid)
                ->where('to_user_id', '=', $toUserid)
                ->where('way', '=', $way);
            $query = $query->orWhere(function($query) use($userid, $toUserid, $way) {
                $query->where('user_id', '=', $toUserid)
                    ->where('to_user_id', '=', $userid)
                    ->where('way', '=', $way);
            });
        }

        return $query = $query->first();

    }

    /**
     * Helper method to know if two users are friends
     *
     * @param int $userid
     * @param int $endUser
     * @return boolean
     */
    public function areFriends($userid, $endUser)
    {
        $friendsId = $this->getFriendsId($userid);
        return in_array($endUser, $friendsId);
    }

    /**
     * Method to check if user is following
     *
     * @param int $userid
     * @param int $toUserid
     * @return boolean
     */
    public function isFollowing($userid, $toUserid)
    {
        $userIds = $this->getFollowingId($userid);

        return in_array($toUserid, $userIds);
    }

    public function isConfirmedFriends($userid, $toUserid)
    {
        list($status, $con) = $this->friendStatus($userid, $toUserid);

        if ($status == 2) return true;

        return false;
    }
    /**
     * Friend status
     */
    public function friendStatus($userid, $toUserid)
    {
        $connection = $this->model
            ->where(function($query) use($userid, $toUserid) {
                $query->where('user_id', '=', $userid)
                    ->where('way', '=', 2)
                    ->where('to_user_id', '=', $toUserid);
            })->orWhere(function($query) use($userid, $toUserid) {
                $query->where('user_id', '=', $toUserid)
                    ->where('way', '=', 2)
                    ->where('to_user_id', '=', $userid);
            })->first();

        if (empty($connection)){
            return [0, $connection]; //no connection at all
        } elseif ($connection->confirmed == 0) {
            return [1, $connection]; //need confirmation
        } else {
            return [2, $connection]; //fully connected/friends
        }
    }

    /**
     * count friends notification
     *
     * @param int $userid
     * @return int
     */
    public function countFriendRequest($userid = null)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        return $this->model
            ->where('to_user_id', '=', $userid)
            ->where('way', '=', 2)
            ->where('confirmed', '=', 0)->count();
    }

    public function getFriendRequests($userid = null, $limit = 15)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        return $this->model
            ->where('to_user_id', '=', $userid)
            ->where('way', '=', 2)
            ->where('confirmed', '=', 0)->paginate($limit);
    }

    public function rejectFriend($id)
    {
        $this->event->fire('connection.reject-friend', [$id]);

        return $this->model->where('id', '=', $id)
            ->delete();
    }

    public function confirmFriend($id)
    {
        $connection = $this->model->find($id);
        $connection->confirmed = 1;
        $connection->save();

        //send user notification
        $this->notification->send($connection->user_id, [
            'title' => trans('connection.accepted-request')
        ]);

        /**
         * clear both entity cache friends list
         */
        $this->cache->forget('friends-'.$connection->user_id);
        $this->cache->forget('friends-'.$connection->to_user_id);

        $this->cache->forget('user-suggestions-'.$connection->user_id);
        $this->cache->forget('user-suggestions-'.$connection->to_user_id);

        //if auto follow is enabled
        if ($this->config->get('auto-follow-friends')) {

            $this->add($connection->user_id, $connection->to_user_id, 1, false);
            $this->add($connection->to_user_id, $connection->user_id, 1, false);
        }

        $this->event->fire('connection.confirm-friend', [$id, $connection]);

        return $connection;
    }

    /**
     * Method to get a user friends userid ignoring status
     *
     * @param int $userid
     * @return array
     */
    public function getAllFriendConnectionIds($userid)
    {

        if ($this->cache->has("all-friend-connection-".$userid)) return $this->cache->get("all-friend-connection-".$userid);
        $connections = $this->model->select('to_user_id', 'user_id')
            ->where('to_user_id', '=', $userid)
            ->orWhere('user_id', '=', $userid)
            ->get();

        $a = [];
        foreach($connections as $connection) {
            if ($userid == $connection->user_id) {
                $a[] = $connection->to_user_id;
            } else {
                $a[] = $connection->user_id;
            }
        }

        $this->cache->forever("all-friend-connection-".$userid, $a);
        return $a;

    }

    public function deleteAllByUser($userid)
    {
        return $this->model->where('user_id', '=', $userid)->orWhere('to_user_id', '=', $userid)->delete();
    }
}