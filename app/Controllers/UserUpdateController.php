<?php

namespace App\Controllers;
use App\Repositories\ConnectionRepository;
use App\Repositories\MessageRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\PostRepository;
use App\Repositories\RealTimeRepository;
use App\Repositories\UserRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class UserUpdateController extends \BaseController
{
    public function __construct(
        NotificationRepository $notificationRepository,
        MessageRepository $messageRepository,
        ConnectionRepository $connectionRepository,
        UserRepository $userRepository,
        PostRepository $postRepository,
        RealTimeRepository $realTimeRepository
    )
    {
        parent::__construct();
        $this->notificationRepository = $notificationRepository;
        $this->messageRepository = $messageRepository;
        $this->connectionRepository = $connectionRepository;
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->realTimeRepository = $realTimeRepository;
    }
    public function process()
    {
        usleep(2000000);
        $results = [
            'status' => 'login'
        ];

        if (!\Auth::check()) {
            return json_encode($results);
        }

        $this->realTimeRepository->setType('update');
        $results['status'] = true;
        $results['lastaccess'] = $this->realTimeRepository->getLastAccess(\Auth::user()->id);
        $messageUserId = \Input::get('messageuserid');
        $postType = \Input::get('postType');
        $currentTime = date('Y-m-d H:i:s', time());
        $lastaccess = \Input::get('lastaccess');

        $continue = true;

        $results['lastaccess'] = $this->realTimeRepository->getLastAccess(\Auth::user()->id);


        if ($this->realTimeRepository->has(\Auth::user()->id, 'notification', $lastaccess)) {
            $results['notifications'] = $this->notificationRepository->count();
            $this->realTimeRepository->remove(\Auth::user()->id, 'notification');
            $continue = false;
        }

        if ($this->realTimeRepository->has(\Auth::user()->id, 'friend-request', $lastaccess)) {
            $results['requests'] = $this->connectionRepository->countFriendRequest();
            $this->realTimeRepository->remove(\Auth::user()->id, 'friend-request');
            $continue = false;
        }

        if ($this->realTimeRepository->has(\Auth::user()->id, 'message', $lastaccess)) {
            if ($messageUserId) {
                $lastime = \Input::get('messagelastcheck');

                if($lastime) {

                    $messages = $this->messageRepository->getList($messageUserId, 10, 0, $lastime);
                    $results['messages'] = (string) $this->theme->section('messages.paginate', ['messages' => $messages]);

                }
            }
            $this->realTimeRepository->remove(\Auth::user()->id, 'message');
            $continue = false;
        }


        clearstatcache(storage_path('realtime/'));

        $results['messagelastcheck'] = $currentTime;
        $results['postlastcheck'] = $currentTime;
        $results['onlines'] = $this->userRepository->countFriendsOnline();



        $results['unreadmessage'] = $this->messageRepository->countNew();

        if ($postType) {
            $lastime = \Input::get('postlastcheck');
            if ($lastime) {
                $results['posts'] = (String) $this->theme->section('post.paginate', [
                    'posts' => $this->postRepository->lists($postType, 0, \Input::get('postUserid'), $lastime)
                ]);
            }
        }
        return json_encode($results);
    }
}