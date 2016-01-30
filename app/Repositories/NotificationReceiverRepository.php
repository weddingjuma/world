<?php

namespace App\Repositories;

use App\Models\NotificationReceiver;
use Illuminate\Cache\Repository;
use Illuminate\Events\Dispatcher;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class NotificationReceiverRepository
{
    public function __construct(
        NotificationReceiver $notificationReceiver,
        Dispatcher $event,
        Repository $cache
    )
    {
        $this->model = $notificationReceiver;
        $this->event = $event;
        $this->cache = $cache;
    }

    /**
     * Method to add user
     * @param int $userid
     * @param string $type
     * @param int $typeId
     * @return boolean
     */
    public function add($userid, $type, $typeId)
    {
        if (!$this->exists($userid, $type, $typeId)) {
            $user = $this->model->newInstance();
            $user->user_id = $userid;
            $user->type = $type;
            $user->type_id = $typeId;
            $user->save();

            $this->event->fire('add.user.get.notification', [$userid, $type, $typeId]);

            $this->cache->forget('notification-receivers-'.$type.$typeId);
            return true;
        }

        return false;
    }

    /**
     * Method to remove a receiver
     *
     * @param int $userid
     * @param string $type
     * @param int $typeId
     * @return boolean
     */
    public function remove($userid, $type, $typeId)
    {
        $this->model
            ->where('user_id', '=', $userid)
            ->where('type', '=', $type)
            ->where('type_id', '=', $typeId)
            ->delete();

        return $this->cache->forget('notification-receivers-'.$type.$typeId);
    }

    /**
     * Method to check if a user is already listed
     *
     * @param int $userid
     * @param string $type
     * @param int $typeId
     * @return boolean
     */
    public function exists($userid, $type, $typeId)
    {
        $userIds = $this->getIds($type, $typeId);
        return in_array($userid, $userIds);
    }

    /**
     * Method to get all receivers ids
     *
     * @param string $type
     * @param int $typeId
     * @param array
     */
    public function getIds($type, $typeId)
    {
        if ($this->cache->has('notification-receivers-'.$type.$typeId)) {
            return $this->cache->get('notification-receivers-'.$type.$typeId);
        }

        $list = $this->model
            ->where('type', '=', $type)
            ->where('type_id', '=', $typeId)
            ->lists('user_id');

        $this->cache->forever('notification-receivers-'.$type.$typeId, $list);

        return $list;
    }

    public function getAll($type, $typeId)
    {
        return $this->model->with('user')
            ->where('type', '=', $type)
            ->where('type_id', '=', $typeId)
            ->get();

    }

    public function deleteAllByUser($userid)
    {
        return $this->model->where('user_id', '=', $userid)->delete();
    }
}