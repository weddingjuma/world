<?php

namespace App\Repositories;

use App\Models\BlockedUser;
use Illuminate\Cache\Repository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class BlockedUserRepository
{

    public function __construct(BlockedUser $blockedUser, Repository $cache)
    {
        $this->model = $blockedUser;
        $this->cache = $cache;
    }

    /*
     * Method to block user
     *
     * @param int $userid
     * @param int $byUserid
     * @return boolean
     */
    public function block($userid, $byUserid)
    {
        if (!$this->hasBlock($byUserid, $userid)) {
            $block = $this->model->newInstance();
            $block->user_id = sanitizeText($byUserid);
            $block->block_id = sanitizeText($userid);
            $block->save();

            $this->cache->forget('blocked_users_'.$byUserid);
            return true;
        }

        return false;
    }

    /**
     * Method to unblock
     *
     * @param int $id
     * @return boolean
     */
    public function unBlock($id)
    {
        $this->model->where('id', '=', $id)->delete();
        $this->cache->forget('blocked_users_'.\Auth::user()->id);

        return true;
    }

    /**
     * Method to get user blocked users
     *
     * @param int $userid
     * @return array
     */
    public function lists($userid)
    {
        return $this->model->where('user_id', '=', $userid)->orderBy('id', 'desc')->paginate(10);
    }

    /**
     * Method to list the blocked users ids
     *
     * @param int $userid
     * @return array
     */
    public function listIds($userid)
    {
        if ($this->cache->has('blocked_users_'.$userid)) {
            return $this->cache->get('blocked_users_'.$userid);
        } else {
            $q = $this->model->where('user_id', '=', $userid)->orderBy('id', 'desc')->lists('block_id');

            $q[] = 0;//prevent query issues
            $this->cache->forever('blocked_users_'.$userid, $q);
            return $q;
        }
    }

    /**
     * Method to check if user has block another user
     *
     * @param int $byUserid
     * @param int $userid
     * @return boolean
     */
    public function hasBlock($byUserid, $userid)
    {
       $ids = $this->listIds($byUserid);
       if (in_array($userid, $ids)) return true;

        return false;
    }

    public function deleteAllByUser($userid)
    {
        return $this->model->where('user_id', '=', $userid)->delete();
    }
}