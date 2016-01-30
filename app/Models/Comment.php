<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Comment extends Model
{
    use PresentableTrait;

    protected $table = 'comments';

    protected $presenter = "App\\Presenters\\CommentPresenter";

    public function user()
    {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }

    public function post()
    {
        return $this->belongsTo('App\\Models\\Post', 'type_id');
    }

    public function likes()
    {
        return $this->hasMany('App\\Models\\Like', 'type_id')->where('type', '=', 'comment');
    }

    public function countLikes()
    {
        return count($this->likes);
        //return app('App\\Repositories\\LikeRepository')->count('post', $this->id);
    }

    public function hasLiked()
    {
        if (!\Auth::check()) return false;

        return app('App\\Repositories\\LikeRepository')->hasLiked('comment', $this->id, \Auth::user()->id);
    }
}