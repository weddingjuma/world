<?php

namespace App\Presenters;
use Laracasts\Presenter\Presenter;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class GamePresenter extends Presenter
{
    public function url($segment = null)
    {
        $segment = (empty($segment)) ? '' : '/'.$segment;
        return \URL::route('game', ['slug' => $this->entity->slug]).$segment;
    }

    public function readDesign()
    {
        return $this->entity->user->present()->readDesign('page-'.$this->entity->id);
    }

    public function getAvatar($size = 100)
    {
        if (empty($this->entity->logo)) return \Theme::asset()->img('theme/images/game/logo.jpg');

        return \Image::url($this->entity->logo, $size);
    }

    public function joinedOn()
    {
        return str_replace(' ', 'T', $this->entity->created_at).'Z';
    }


    public function coverImage()
    {
        if (!empty($this->entity->cover)) {
            return \URL::to($this->entity->cover);
        }
    }

    public function field($id = null)
    {
        $details = (!empty($this->entity->info)) ? perfectUnserialize($this->entity->info) : [];

        if (empty($id)) return $details;

        if (isset($details[$id])) return $details[$id];

        return 'Nill';
    }

    public function fields()
    {
        return app('App\\Repositories\\CustomFieldRepository')->listAll('game');
    }
}