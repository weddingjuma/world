<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Events\Dispatcher;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class NotificationRepository
{
    public function __construct(
        Notification $notification,
        Dispatcher $event,
        NotificationReceiverRepository $notificationReceiverRepository,
        RealTimeRepository $realTimeRepository
    )
    {
        $this->model = $notification;
        $this->event = $event;
        $this->notificationReceiver = $notificationReceiverRepository;
        $this->realTimeRepository = $realTimeRepository;
    }

    public function count($userid = null)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        return $this->model->where('to_user_id', '=', $userid)->where('seen', '=', 0)->count();
    }

    public function get($userid = null, $limit = 15)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        return $this->model->with('user')->where('to_user_id', '=', $userid)->orderBy('id', 'desc')->paginate($limit);
    }

    /**
     * Method to send notification
     *
     * @param int $toUserid
     * @param array $details
     * @param int $userid
     * @param bool $withPrivacy
     * @param string $privacyId
     * @param \App\\Models\\User $privacyUser
     * @return boolean
     */
    public function send($toUserid, $details = [], $userid = null, $withPrivacy = false, $privacyId = null, $privacyUser = null)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;

        if ($withPrivacy) {
            //check if the notification receiver has disabled receiving from this notification type
            if (!$privacyUser->present()->privacy($privacyId, 1)) return false;
        }

        $expectedDetails = [
            'title' => '',
            'content' => '',
            'seen' => 0,
            'data' => []
        ];

        /**
         * @var $title
         * @var $content
         * @var $seen
         * @var $data
         */
        extract($allDetails = array_merge($expectedDetails, $details));

        $notification = $this->model->newInstance();
        $notification->user_id = $userid;
        $notification->to_user_id = $toUserid;
        $notification->title = sanitizeText($title);
        $notification->content = $content;
        $notification->data = (empty($details)) ? '' : perfectSerialize($details);
        $notification->save();

        $this->realTimeRepository->add($toUserid, 'notification');

        $this->event->fire('notification.send', [$notification, $details]);

        return $notification;
    }

    /**
     * Method to send notificatin to receivers
     *
     * @param string $type
     * @param int $typeId
     * @param array $details
     * @param int $userid
     * @param array $ignoreIds
     * @return boolean
     */
    public function sendToReceivers($type, $typeId, $details, $userid = null, $ignoreIds = [])
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;

        $receivers = $this->notificationReceiver->getAll($type, $typeId);

        foreach($receivers as $receiver) {
            if ($userid != $receiver->user_id and !in_array($receiver->user_id, $ignoreIds)) {

                $this->send($receiver->user_id, $details, $userid, true, 'notify-comment-post', $receiver->user);
            }
        }

        return true;
    }

    public function markAll()
    {
        $this->model->where('to_user_id', '=', \Auth::user()->id)->update(['seen' => 1]);
    }

    public function clearAll()
    {
        $this->model->where('to_user_id', '=', \Auth::user()->id)->delete();
    }

    public function findById($id)
    {
        return $this->model->where('id', '=', $id)->first();
    }

    /**
     * Method to delete notification
     *
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $notification = $this->findById($id);
        if(!$notification or !\Auth::check()) return false;
        if ($notification != \Auth::user()->id) return false;

        return $this->model->where('id', '=', $id)->delete();
    }

    public function deleteAllByUser($userid)
    {
        return $this->model->where('user_id', '=', $userid)->orWhere('to_user_id', '=', $userid)->delete();
    }
}