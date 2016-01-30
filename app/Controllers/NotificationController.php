<?php

namespace App\Controllers;

use App\Controllers\Base\UserBaseController;
use App\Repositories\NotificationReceiverRepository;
use App\Repositories\NotificationRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class NotificationController extends UserBaseController
{
    public function __construct(
        NotificationRepository $notificationRepository,
        NotificationReceiverRepository $notificationReceiverRepository
    )
    {
        $this->notificationRepository = $notificationRepository;
        $this->notificationReceiver = $notificationReceiverRepository;
        parent::__construct();
    }
    public function dropdown()
    {
        $this->notificationRepository->markAll();
        return $this->theme->section('notification.dropdown', ['notifications' => $this->notificationRepository->get(null, 4)]);
    }

    public function index()
    {

        return $this->render('notification.index', ['notifications' => $this->notificationRepository->get()], [
            'title' => $this->setTitle(trans('notification.notifications'))
        ]);
    }

    public function delete($id)
    {
        $this->notificationRepository->delete($id);
    }

    public function markAll()
    {
        $this->notificationRepository->markAll();

        if (!\Request::ajax()) {
            return \Redirect::to(\URL::previous());
        }
    }

    public function clearAll()
    {
        $this->notificationRepository->clearAll();

        if (!\Request::ajax()) {
            return \Redirect::to(\URL::previous());
        }
    }

    public function addReceiver($userid, $type, $typeId)
    {
        $this->notificationReceiver->add($userid, $type, $typeId);
    }

    public function removeReceiver($userid, $type, $typeId)
    {
        $this->notificationReceiver->remove($userid, $type, $typeId);
    }
}
 