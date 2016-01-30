<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ConnectionPresenter extends Presenter
{
    public function getFriend($userid)
    {
        if ($this->entity->user_id == $userid) {
            return $this->entity->toUser;
        } else {
            return $this->entity->fromUser;
        }
    }
}