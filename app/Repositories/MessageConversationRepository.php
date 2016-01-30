<?php

namespace App\Repositories;
use App\Models\MessageConversation;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class MessageConversationRepository
{
    public function __construct(MessageConversation $messageConversation)
    {
        $this->model = $messageConversation;
    }

    /**
     * Method to ensure connection between two users
     *
     * @param int $user1
     * @param int $user2
     * @return bool
     */
    public function ensureConnection($user1, $user2)
    {
        $conversation = $this->exists($user1, $user2);
        if(!$conversation) {
            $conversation = $this->model->newInstance();
            $conversation->user1 = $user1;
            $conversation->user2  = $user2;
            $conversation->save();
        } else {
            $conversation->updated_at = time();
            $conversation->save();//just to update the last time
        }

        return $conversation;
    }

    public function exists($user1, $user2)
    {
        return $this->model
            ->where(function($query) use($user1, $user2) {
                $query->where('user1', '=', $user1)
                    ->where('user2', '=', $user2);
            })->orWhere(function($query) use($user1, $user2) {
                $query->where('user1', '=', $user2)
                    ->where('user2', '=', $user1);
            })->first();
    }

    public function lastConversation()
    {
        $conversations = $this->listAll(1);

        foreach($conversations as $conversation) {
            return $conversation;
        }

        return false;
    }

    public function listAll($limit = null)
    {
        $userid = \Auth::user()->id;
        $query =  $this->model->with('userOne', 'userTwo')
            ->where('user1', '=', $userid)
            ->orWhere('user2', '=', $userid)->orderBy('updated_at', 'desc');

        if (!$limit) return $query = $query->get();

        return $query = $query->paginate($limit);

    }

    public function deleteAllByUser($userid)
    {
        return $this->model->where('user1', '=', $userid)
            ->orWhere('user2', '=', $userid)
            ->delete();
    }
}