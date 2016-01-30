<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class Game extends Model
{
    use PresentableTrait;

    protected $presenter = "App\\Presenters\\GamePresenter";

    protected $table = "games";

    public function user()
    {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }

    public function cat()
    {
        return $this->belongsTo('App\\Models\\GameCategory', 'category');
    }

    public function isOwner()
    {
        if (!\Auth::check()) return false;
        return (\Auth::user()->id == $this->user_id);
    }

    public function likes()
    {
        return $this->hasMany('App\\Models\\Like', 'type_id')->where('type', '=', 'game');
    }

    public function countLikes()
    {
        return count($this->likes);
        //return app('App\\Repositories\\LikeRepository')->count('page', $this->id);
    }

    public function friendsLiked()
    {
        return app('App\\Repositories\\LikeRepository')->friendsLike('game', $this->id, 12);
    }

    public function hasLiked()
    {
        if (!\Auth::check()) return false;

        return app('App\\Repositories\\LikeRepository')->hasLiked('game', $this->id, \Auth::user()->id);
    }

    public function comments()
    {
        return $this->hasMany('App\\Models\\Comment', 'type_id')->where('type', '=', 'game')->orderBy('id', 'desc');
    }


    public function countComments()
    {
        return count($this->comments)   ;
    }
}