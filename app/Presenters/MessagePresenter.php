<?php

namespace App\Presenters;
use Laracasts\Presenter\Presenter;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class MessagePresenter extends Presenter
{
    public function canView()
    {

        $userid = \Auth::user()->id;

        if ($userid == $this->entity->receiver and $this->entity->seen == 0) {
            $this->entity->seen = 1;
            $this->entity->save();
        }
        if ($this->entity->sender_status == 1 and $this->entity->receiver_status == 1) {
            //return false and delete the message
            $this->entity->delete();
            return false;
        }

        if ($userid == $this->entity->sender) {

            if ($this->entity->sender_status == 0) return true;
        } else {
            if ($this->entity->receiver_status == 0) return true;
        }

        return false;
    }

    public function time()
    {
        return str_replace(' ', 'T', $this->entity->created_at).'Z';
    }

    public function text()
    {
        $text = nl2br($this->entity->text);

        //turn links to clickable
        $text = app('App\\Repositories\\PostRepository')->turnLinks($text);

        return \Hook::fire('post-text', $text);
    }


}