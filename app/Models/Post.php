<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Post extends Model
{
    use PresentableTrait;

    protected $table = "posts";

    protected $presenter = "App\\Presenters\\PostPresenter";

    public function user()
    {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }



    public function page()
    {
        return $this->belongsTo('App\\Models\\Page', 'page_id');
    }

    public function toUser()
    {
        return $this->belongsTo('App\\Models\\User', 'to_user_id');
    }

    public function sharedUser()
    {
        return $this->belongsTo('App\\Models\\User', 'shared_from');
    }

    public function comments()
    {
        $comments = $this->hasMany('App\\Models\\Comment', 'type_id')->with('user')->where('type', '=', 'post')->orderBy('id', 'desc');

        return $comments;
    }

    public function likes()
    {
        return $this->hasMany('App\\Models\\Like', 'type_id')->where('type', '=', 'post');
    }

    public function countLikes()
    {
        return count($this->likes);
        //return app('App\\Repositories\\LikeRepository')->count('post', $this->id);
    }

    public function hasLiked()
    {
        if (!\Auth::check()) return false;

        return app('App\\Repositories\\LikeRepository')->hasLiked('post', $this->id, \Auth::user()->id);
    }

    public function getComments()
    {
        $comments =  $this->comments()->with('user')->where('type', '=', 'post');

        if (\Auth::check()) {
            $blockedUsers = app('App\\Repositories\\blockedUserRepository')->listIds(\Auth::user()->id);
            $comments = $comments->whereNotIn('user_id', $blockedUsers);
        }
        return $comments = $comments->orderBy('id', 'desc')->paginate(\Config::get('post-per-page'));
    }

    public function countComments()
    {
        return $this->comments->count();
    }

    public function community()
    {
        return $this->belongsTo('App\\Models\\Community', 'community_id');
    }

    public function communityCategory()
    {
        return $this->belongsTo('App\\Models\\CommunityCategory', 'type_id');
    }

    public function isOwner()
    {
        if (!\Auth::check()) return false;
        if (\Auth::user()->id == $this->user_id) return true;
        return false;
    }
}