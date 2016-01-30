<?php

namespace App\Repositories;

use App\Models\CommunityMember;
use App\Models\Notification;
use Illuminate\Cache\Repository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class CommunityMemberRepository
{
    public function __construct(
        CommunityMember $communityMember,
        Repository $cache,
        NotificationRepository $notificationRepository,
        MustAvoidUserRepository $mustAvoidUserRepository
    )
    {
        $this->model = $communityMember;
        $this->cache = $cache;
        $this->notification = $notificationRepository;
        $this->mustAvoidUserRepository = $mustAvoidUserRepository;
    }

    public function isMember($id, $userid = null)
    {
        if (!\Auth::check()) return false;

        $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        return $this->exists($id, $userid);
    }

    public function getUserIds($id)
    {
        if ($this->cache->has('community-members-'. $id)) {
            return $this->cache->get('community-members-'. $id);
        } else {
            $members = $this->model->where('community_id', '=', $id)->lists('user_id');

            $this->cache->forever('community-members-'. $id, $members);
            return $members;
        }
    }

    public function getIds($userid)
    {
        if ($this->cache->has('user-communities' . $userid)) {
            return $this->cache->get('user-communities' . $userid);
        } else {
            $members = $this->model->where('user_id', '=', $userid)->lists('community_id');
            $members[] = 0;

            $this->cache->forever('user-communities' . $userid, $members);
            return $members;
        }
    }

    public function getCommunities($userid, $limit)
    {
        return $this->model->with('community')->where('user_id', '=', $userid)->paginate($limit);
    }

    public function listUsers($id, $limit = 10)
    {
        return $this->model->with('user')
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get())
            ->where('community_id', '=', $id)->paginate($limit);
    }
    /**
     * MEthod to add member to a community
     *
     * @param int $id
     * @param int $userid
     * @return boolean
     */
    public function add($id, $userid = null)
    {
        if (!\Auth::check() and empty($userid)) return false;

        $userid = (empty($userid)) ? \Auth::user()->id : $userid;

        if (!$this->exists($id, $userid)) {
            $member = $this->model->newInstance();
            $member->community_id = sanitizeText($id);
            $member->user_id = sanitizeText($userid);
            $member->save();

            $this->cache->forget('community-members-' . $id);
            $this->cache->forget('user-communities' . $userid);
            $this->cache->forget('community-suggestions-' . $userid);

            //lets send a notification to the owner of this community
            $community = app('App\\Repositories\\CommunityRepository')->get($id);
            $this->notification->send($community->user_id, [
                'path' => 'notification.community.join',
                'community' => $community
            ], $userid);


            return  true;
        }

        return true;
    }

    public function exists($id, $userid)
    {
        $members = $this->getUserIds($id);

        return (in_array($userid, $members));
    }

    public function delete($id, $userid = null)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;

        $this->cache->forget('community-members-' . $id);
        $this->cache->forget('user-communities' . $userid);
        return $this->model->where('community_id', '=', $id)->where('user_id', '=', $userid)->delete();
    }

    public function deleteAllByCommunity($id)
    {
        $this->cache->forget('community-members-'.$id);
        return $this->model->where('community_id', '=', $id)->delete();
    }

    public function deleteAllByUser($userid)
    {
        return $this->model->where('user_id', '=', $userid)->delete();
    }
}