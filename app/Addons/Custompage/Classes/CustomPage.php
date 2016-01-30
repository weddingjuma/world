<?php
namespace App\Addons\Custompage\Classes;
use Illuminate\Database\Eloquent\Model;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class CustomPage extends Model
{
    protected $table = "custom_pages";

    public function deleteIt()
    {
        $this->delete();
    }

    public function url($slug = null)
    {
        return \URL::route('custom-page', ['slug' => $this->slug]).'/'.$slug;
    }

    public function canView()
    {
        if ($this->privacy == 0) return true;
        if ($this->privacy == 1 and \Auth::check()) return true;
        if ($this->privacy == 2 and (\Auth::check() and \Auth::user()->isAdmin())) return true;
        return false;
    }



    public function comments()
    {
        $comments = $this->hasMany('App\\Models\\Comment', 'type_id')->with('user')->where('type', '=', 'custompage')->orderBy('id', 'desc');

        return $comments;
    }

    public function likes()
    {
        return $this->hasMany('App\\Models\\Like', 'type_id')->where('type', '=', 'custompage');
    }

    public function countLikes()
    {
        return count($this->likes);
        //return app('App\\Repositories\\LikeRepository')->count('post', $this->id);
    }

    public function hasLiked()
    {
        if (!\Auth::check()) return false;

        return app('App\\Repositories\\LikeRepository')->hasLiked('custompage', $this->id, \Auth::user()->id);
    }

    public function getComments()
    {
        $comments =  $this->comments()->with('user')->where('type', '=', 'custompage');

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
}