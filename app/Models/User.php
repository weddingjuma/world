<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Laracasts\Presenter\PresentableTrait;

class User extends Model implements UserInterface, RemindableInterface {

    use PresentableTrait;

    protected $presenter = "App\\Presenters\\UserPresenter";
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    protected $primaryKey = "id";

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');


	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

    /**
     * User posts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\\Models\\Post', 'user_id');
    }

    /**
     * User Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('Addon\\User\\Model\\Group', 'user_group');
    }

    public function friends($limit = 10)
    {
        return app('App\\Repositories\\ConnectionRepository')->getFriends($this->id, $limit);
    }

    public function countFriends()
    {
        $ids = app('App\\Repositories\\ConnectionRepository')->getFriendsId($this->id);
        if(isset($ids[0])) unset($ids[0]);

        return count($ids);
    }

    public function followers($limit = 10)
    {
        return app('App\\Repositories\\ConnectionRepository')->followers($this->id, $limit);
    }

    public function countFollowers()
    {
        return app('App\\Repositories\\ConnectionRepository')->countFollowers($this->id);
    }

    public function countFollowing()
    {
        return app('App\\Repositories\\ConnectionRepository')->countFollowing($this->id);
    }

    public function following($limit = 10)
    {
        return app('App\\Repositories\\ConnectionRepository')->following($this->id, $limit);
    }

    /**
     * Easy method to check if the login user is the owner of this user
     *
     * @return boolean
     */
    public function isOwner()
    {
        return (\Auth::check() and \Auth::user()->id == $this->id);
    }

    public function isAdmin()
    {
        return $this->admin;
    }

    public function updateOnline()
    {
        $this->last_active_time = time();
        if ($this->online_status == 0 or $this->online_status != 2) {
            $privacy = $this->present()->privacy('self-offline', 0);
            if ($privacy == 0) $this->online_status = 1;
        }
        $this->save();
    }

    public function updateStatus($s, $self = false)
    {
        if (!\Auth::user()) return false;
        $this->online_status = $s;
        $repository = app('App\\Repositories\\UserRepository');
        if ($self and $s == 0) {
            $repository->savePrivacy(['self-offline' => 1]);
        } else {
            if ($s == 0) {
               $repository->savePrivacy(['self-offline' => 0]);
            }
        }
        $this->save();
    }

    public function photos()
    {
        return $this->hasMany('App\\Models\\Photos', 'user_id')->where('slug', 'LIKE', '%album-%');
    }

    public function countPhotos()
    {
        return count($this->photos);
    }

    public function countPosts()
    {
        return count($this->posts);
    }
}
