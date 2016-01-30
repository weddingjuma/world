<?php

namespace App\Repositories;

use App\Repositories\PostHide;
use Illuminate\Cache\Repository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class HidePostRepository
{
    public function __construct(PostHide $postHide, Repository $cache)
    {
        $this->model = $postHide;
        $this->cache = $cache;
    }

    public function add($postId, $userid = null)
    {
        $userid = ($userid) ? $userid : \Auth::user()->id;

        $hide = $this->model->newInstance();
        $hide->post_id = $postId;
        $hide->user_id = $userid;
        $hide->save();

        return $this->cache->forget('post-hide-'.$userid);
    }

    public function remove($postId, $userid = null)
    {
        $userid = ($userid) ? $userid : \Auth::user()->id;

        $this->cache->forget('post-hide-'.$userid);
        return $this->model->where('post_id', '=', $postId)->where('user_id', '=', $userid)->delete();
    }

    public function lists($userid = null)
    {
        $userid = ($userid) ? $userid : \Auth::user()->id;
        $cacheName = 'post-hide-'.$userid;
        if ($this->cache->has($cacheName)) {
            return $this->cache->get($cacheName);
        } else {
            $list = $this->model->where('user_id', '=', $userid)->lists('post_id');

            if (empty($list)) return ['empty'];
            $this->cache->forever($cacheName, $list);

            return $list;
        }
    }
}