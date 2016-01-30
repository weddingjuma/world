<?php

namespace App\Repositories;
use App\Repositories\Models\MustAvoidUser;
use Illuminate\Cache\Repository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class MustAvoidUserRepository
{
    public function __construct(MustAvoidUser $mustAvoidUser, Repository $cache)
    {
        $this->model = $mustAvoidUser;
        $this->cache = $cache;
    }

    /**
     * Method to add
     *
     * @param int $userid
     * @return boolean
     */
    public function add($userid)
    {
        if (!$this->exists($userid)) {
            $user = $this->model->newInstance();
            $user->user_id = $userid;
            $user->save();

            $this->cache->forget('must-avoid-users');
            return true;
        }

        return true;
    }

    public function remove($userid)
    {
        $this->model->where('user_id', '=', $userid)->delete();
        $this->cache->forget('must-avoid-users');
    }

    public function get()
    {
        if ($this->cache->has('must-avoid-users')) {
            return $this->cache->get('must-avoid-users');
        } else {
            $list = $this->model->lists('user_id');
            $list[] = 'nothing';
            $this->cache->forever('must-avoid-users', $list);
            return $list;
        }
    }

    public function exists($userid)
    {
        $users = $this->get();

        if (in_array($userid, $users)) return true;
        return false;
    }
}