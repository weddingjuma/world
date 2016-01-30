<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Photos extends Model
{
    protected  $table = "photos";

    public function user()
    {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }

    public function time()
    {
        return str_replace(' ', 'T', $this->created_at).'Z';
    }

    public function page()
    {
        return $this->belongsTo('App\\Models\\Page', 'page_id');
    }

    public function post()
    {
        return $this->belongsTo('App\\Models\\Post', 'post_id');
    }

    public function deleteMe()
    {
        $path = $this->path;
        $slug = $this->slug;
        \Image::delete($path);
        //lets take our time to delete likes and comments
        app('App\\Repositories\\CommentRepository')->deleteByType('photo', $this->id);
        app('App\\Repositories\\LikeRepository')->deleteByType('photo', $this->id);

        //if post is attached lets delete the pic
        if ($this->post_id) {
            app('App\\Repositories\\PostRepository')->delete($this->post_id);
        }
        $this->delete();

        $split = explode('-', $slug);
        if (count($split) > 1) {
            list($name, $id) = $split;

            if (isset($id)) {
                $album = app('App\\Repositories\\PhotoAlbumRepository')->get($id);
                if(!empty($album)) {
                    if ($album->default_photo == $path) {
                        $lastPhoto = app('App\\Repositories\\PhotoRepository')->lastPhoto($slug);
                        if ($lastPhoto) {
                            $album->default_photo = $lastPhoto->path;
                        } else {
                            $album->default_photo = '';
                        }
                        $album->save();
                    }
                }
            }

        }
    }

    public function isOwner()
    {
        if (!\Auth::check()) return false;

        return ($this->user_id == \Auth::user()->id);
    }

    public function likes()
    {
        return $this->hasMany('App\\Models\\Like', 'type_id')->where('type', '=', 'photo');
    }

    public function hasLiked()
    {
        if (!\Auth::check()) return false;

        return app('App\\Repositories\\LikeRepository')->hasLiked('photo', $this->id, \Auth::user()->id);
    }

    public function comments()
    {
        return $this->hasMany('App\\Models\\Comment', 'type_id')->where('type', '=', 'photo')->orderBy('id', 'desc');
    }


    public function countLikes()
    {
        return count($this->likes);
    }

    public function countComments()
    {
        return count($this->comments)   ;
    }
}