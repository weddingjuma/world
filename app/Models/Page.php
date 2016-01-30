<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class Page extends  Model
{
    use PresentableTrait;

    protected $table = "pages";

    protected $presenter = "App\\Presenters\\PagePresenter";

    public function user()
    {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }

    public function isOwner()
    {
        if (!\Auth::check()) return false;
        return (\Auth::user()->id == $this->user_id);
    }

    public function likes()
    {
        return $this->hasMany('App\\Models\\Like', 'type_id')->where('type', '=', 'page');
    }

    public function countLikes()
    {
        return count($this->likes);
        //return app('App\\Repositories\\LikeRepository')->count('page', $this->id);
    }

    public function friendsLiked()
    {
        return app('App\\Repositories\\LikeRepository')->friendsLike('page', $this->id, 12);
    }

    public function hasLiked()
    {
        if (!\Auth::check()) return false;

        return app('App\\Repositories\\LikeRepository')->hasLiked('page', $this->id, \Auth::user()->id);
    }

    public function category()
    {
        return $this->belongsTo('App\\Models\\PageCategory', 'category_id');
    }
}