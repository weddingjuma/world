<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Community extends Model
{
    use PresentableTrait;

    protected $table = 'communities';

    protected $presenter = "App\\Presenters\\CommunityPresenter";

    /**public function getCreatedAtAttribute($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('H:i:s Y-m-d');
    }**/

    /***public function getUpdatedAtAttribute($date)
    {
        return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
    }**/

    public function user()
    {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }

    public function members()
    {
        return $this->hasMany('App\\Models\\CommunityMember', 'community_id')->with('user');
    }

    public function categories()
    {
        return $this->hasMany('App\\Models\\CommunityCategory', 'community_id');
    }

    public function posts()
    {
        return $this->hasMany('App\\Models\\Post', 'community_id');
    }

    public function countMembers()
    {
        return count($this->members) + 1;
    }

    public function countPosts()
    {
        return count($this->posts);
    }

    public function isOwner($userid = null)
    {
        if (!\Auth::check()) return false;

        $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        if ($this->user_id == $userid) return true;
        return false;
    }

    public function getModerators()
    {
        $moderators = (empty($this->moderators)) ? [] : perfectUnserialize($this->moderators);
        return (empty($moderators)) ? [] : $moderators;
    }
}