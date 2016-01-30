<?php

namespace App\Presenters;
use Laracasts\Presenter\Presenter;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class MessageConversationPresenter extends Presenter
{

    /**
     * Method to get the proper user
     */
    public function theUser()
    {

        if ($this->entity->user1 == \Auth::user()->id) {
            return $this->entity->userTwo;
        } else {

            return $this->entity->userOne;
        }
    }
}