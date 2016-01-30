<?php

namespace App\Repositories;

use App\Models\Like;
use Illuminate\Cache\Repository;
use Illuminate\Events\Dispatcher;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class LikeRepository
{
    public function __construct(
        Like $like,
        Dispatcher $event,
        NotificationRepository $notification,
        Repository $cache,
        ConnectionRepository $connectionRepository
    )
    {
        $this->model = $like;
        $this->event = $event;
        $this->notification = $notification;
        $this->cache = $cache;
        $this->connectionRepository = $connectionRepository;
    }

    /**
     * Method to check if user has liked a post
     *
     * @param string $type
     * @param int $id
     * @param int $userid
     * @return boolean
     */
    public function hasLiked($type, $id, $userid = null)
    {
        if (!\Auth::check()) return false;
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;

        $likes = $this->getIds($type, $id);
        return in_array($userid, $likes);
    }

    /**
     * Method to count likes
     *
     * @param string $type
     * @param int $id
     * @return int
     */
    public function count($type, $id)
    {
        return count($this->getIds($type, $id));
    }

    /**
     * Method to add like
     *
     * @param string $type
     * @param int $id
     * @param int $userid
     * @return int
     */
    public function add($type, $id, $userid = null)
    {
        $type = sanitizeText($type);
        $id = sanitizeText($id);
        $userid = sanitizeText($userid);
        if (!is_numeric($id)) return false;
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;

        if ($this->hasLiked($type, $id, $userid)) return $this->count($type, $id);
        $like = $this->model->newInstance();
        $like->user_id = $userid;
        $like->type = $type;
        $like->type_id = $id;
        $like->save();

        $this->event->fire('like.add', [$userid, $type, $id]);

        $this->cache->forget('likes-'.$type.$id);
        $this->cache->forget($type.'-likes-'.$userid);

        return $this->count($type, $id);
    }

    /**
     * Method to remove like
     *
     * @param string $type
     * @param int $id
     * @param int $userid
     * @return int
     */
    public function remove($type, $id, $userid = null)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        $this->model
            ->where('type', '=', $type)
            ->where('type_id', '=', $id)
            ->where('user_id', '=', $userid)
            ->delete();

        $this->cache->forget('likes-'.$type.$id);
        $this->cache->forget($type.'-likes-'.$userid);
        return $this->count($type, $id);
    }

    /**
     * Method to get all likers of a like type
     *
     * @param string $type
     * @param int $id
     * @return boolean
     */
    public function getIds($type, $id)
    {
        if ($this->cache->has('likes-'.$type.$id)) return $this->cache->get('likes-'.$type.$id);

        $likes = $this->model
            ->where('type', '=', $type)->where('type_id', '=', $id)->lists('user_id');

        $this->cache->forever('likes-'.$type.$id, $likes);

        return $likes;
    }

    public function getLikesId($type, $userid)
    {
        $cacheName = $type.'-likes-'.$userid;
        if ($this->cache->has($cacheName)) {
           return $this->cache->get($cacheName);
        } else {
            $likes = ['nill'];
            $lik = $this->model->where('type', '=', $type)->where('user_id', '=', $userid)->lists('type_id');
            $likes = array_merge($likes, $lik);

            $this->cache->forever($cacheName, $likes);

            return $likes;
        }
    }

    /**
     * @param     $type
     * @param     $id
     * @param int $limit
     * @return mixed
     */
    public function getLikes($type, $id, $limit = 10)
    {
        return $this->model->with('user')
            ->where('type', '=', $type)->where('type_id', '=', $id)->paginate($limit);
    }

    /**
     * Method to get list of friends who like this type and its id
     *
     * @param string $type
     * @param int $typeId
     * @param int $limit
     * @return array
     */
    public function friendsLike($type, $typeId, $limit = 10)
    {
        $userids = [0];

        $followings = $this->connectionRepository->getFollowingId();
        $friends = $this->connectionRepository->getFriendsId();

        $userids = array_merge($userids, $followings);
        $userids = array_merge($userids, $friends);


        return $this->model->with('user')
            ->where('type', '=', $type)
            ->where('type_id', '=', $typeId)
            ->whereIn('user_id', $userids)
            ->paginate($limit);
    }

    /**
     * @param $userid
     * @return mixed
     */
    public function deleteAllByUser($userid)
    {
        return $this->model->where('user_id', '=', $userid)->delete();
    }

    public function deleteByType($type, $typeId)
    {
        $this->cache->forget('likes-'.$type.$typeId);
        return $this->model->where('type', '=', $type)->where('type_id', '=', $typeId)->delete();
    }

    public function deleteAllByPage($id)
    {
        return $this->deleteByType('page', $id);
    }

    public function deleteAllByGame($id)
    {
        return $this->deleteByType('game', $id);
    }
}
 