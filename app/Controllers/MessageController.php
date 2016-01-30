<?php

namespace App\Controllers;
use App\Controllers\Base\UserBaseController;
use App\Repositories\MessageConversationRepository;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class MessageController extends UserBaseController
{
    public function __construct(MessageRepository $messageRepository,
                                MessageConversationRepository $messageConversationRepository,
                                UserRepository $userRepository
    )
    {
        $this->messageRepository = $messageRepository;
        $this->conversationRepository = $messageConversationRepository;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    public function send()
    {
        $userid = \Input::get('userid');
        $text = \Input::get('text');
        $type = \Input::get('type');

        $message = $this->messageRepository->send($userid, $text, \Input::file('image'));

        if ($type == 'alert') {
            return trans('message.sent');
        } else {

            if ($message) {
                return $this->theme->section('messages.display', ['message' => $message]);

            }

            return 0;
        }
    }

    public function dropdown()
    {
        return $this->theme->section('messages.dropdown', ['messages' => $this->messageRepository->getUnread(5)]);
    }

    public function online()
    {
        return $this->theme->section('messages.online', [
            'users' => $this->userRepository->listOnlineUsers()
        ]);
    }

    public function more()
    {
        $limit = 5;
        $userid = \Input::get('userid');
        $offset = \Input::get('offset');
        $offset = (empty($offset)) ? $limit : $offset;
        $newOffset = $offset  +  $limit;


        return json_encode([
            'offset' =>$newOffset,
            'content' => (String)  $this->theme->section('messages.paginate', ['messages' => $this->messageRepository->getList($userid, $limit, $offset)])
        ]);
    }

    public function index()
    {
        $userid = \Input::get('user');

        if ($userid) {

            $user = $this->userRepository->findByUsername($userid);

            $userid = ($user) ? $user->id : null;
            if (!$userid) return \Redirect::route('messages');

        }

        if ($userid == \Auth::user()->id ) return \Redirect::route('messages');

        if (empty($userid)) {
            $lastConversation = $this->conversationRepository->lastConversation();
            if ($lastConversation) {
                $userid = $lastConversation->present()->theUser()->id;

                $this->messageRepository->markAllByUser($userid);
            }
        }
        return $this->render('messages.index', [
            'conversations' => $this->conversationRepository->listAll(),
            'userid' => $userid,
            'messages' => ($userid) ? $this->messageRepository->getList($userid) : []
        ], ['title' => $this->setTitle(trans('message.messages'))]);
    }

    public function setOnlineStatus()
    {
        $status = \Input::get('status');
        if (\Auth::check()) \Auth::user()->updateStatus($status, true);
    }

    public function delete()
    {
        $id = \Input::get('id');
        $this->messageRepository->delete($id);
    }
}