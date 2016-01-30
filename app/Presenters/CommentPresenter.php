<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class CommentPresenter extends Presenter
{
    public function time()
    {
        return str_replace(' ', 'T', $this->entity->created_at).'Z';
    }

    public function getImage()
    {
        if (!empty($this->entity->img_path)) {
            return $this->entity->img_path;
        }
    }

    public function text()
    {
        $text = $this->entity->text;
        $text = nl2br($text);

        //turn links to clickable
        $text = app('App\\Repositories\\PostRepository')->turnLinks($text);

        return \Hook::fire('post-text', $text);
    }

    public function canDelete()
    {
        if (!\Auth::check()) return false;

        if (\Auth::user()->id == $this->entity->user_id or \Auth::user()->isAdmin()) return true;

        return false;
    }
}