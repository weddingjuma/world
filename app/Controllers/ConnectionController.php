<?php

namespace App\Controllers;

use App\Controllers\Base\ProfileBaseController;
use App\Repositories\ConnectionRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ConnectionController extends ProfileBaseController
{
    public function __construct(ConnectionRepository $connectionRepository)
    {
        parent::__construct();
        $this->connectionRepository = $connectionRepository;
    }

    public function lists($id, $type)
    {
        if (!$this->exists()) {
            return $this->profileNotFound();
        }
        $this->theme->share('singleColumn', true);

        switch($type) {
            case 'followers':
                $connections = $this->connectionRepository->followers($this->profileUser->id);
                break;
            case 'following':
                $connections = $this->connectionRepository->following($this->profileUser->id);
                break;
            default:
                $connections = $this->connectionRepository->getFriends($this->profileUser->id);
                break;
        }
        return $this->render('connection.profile.list', ['type' => $type, 'connections' => $connections], ['title' => $this->setTitle($type)]);
    }

    public function add($userid, $touserid, $way)
    {
        if ($this->connectionRepository->add($userid, $touserid, $way)) {
            return 1;
        }
        return 0;
    }

    public function dropdown() {
        return $this->theme->section('connection.friend.dropdown-requests', ['requests' => $this->connectionRepository->getFriendRequests(null, 5)]);
    }

    public function remove($userid, $touserid, $way)
    {
        if ($userid != \Auth::user()->id and $touserid != \Auth::user()->id) return 0;
        if ($this->connectionRepository->remove($userid, $touserid, $way)) {
            return 1;
        }
        return 0;
    }

    public function removeFriend($userid, $touserid)
    {
        $this->remove($userid, $touserid, 2);

        return \Redirect::to(\URL::previous());
    }

    public function friendRequest()
    {
        return $this->render('connection.friend.requests', ['requests' => $this->connectionRepository->getFriendRequests()], ['title' => $this->setTitle(trans('connection.friend-requests'))]);
    }

    public function rejectFriend($id)
    {
        $this->connectionRepository->rejectFriend($id);

        //return \Redirect::to(\URL::previous());
        $countRequest = $this->connectionRepository->countFriendRequest();

        return (String) $countRequest;
    }

    public function confirmFriend($id)
    {
        $connection = $this->connectionRepository->confirmFriend($id);

        //return \Redirect::to($connection->fromUser->present()->url());
        $countRequest = $this->connectionRepository->countFriendRequest();

        return (String) $countRequest;
    }

}