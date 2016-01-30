<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class MessageConversation extends Model
{
    use PresentableTrait;

    protected $table = "message_conversation";

    protected $presenter = "App\\Presenters\\MessageConversationPresenter";

    public function userOne()
    {
        return $this->belongsTo('App\\Models\\User', 'user1');
    }

    public function userTwo()
    {
        return $this->belongsTo('App\\Models\\User', 'user2');
    }

    public function messages()
    {
        return $this->hasMany('App\\Models\\Message', 'conversation_id')->orderBy('id', 'desc');
    }
}