<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class PhotoAlbum extends Model
{
    protected $table = "photo_albums";

    public function defaultPhoto()
    {
        if (empty($this->default_photo)) return \Theme::asset()->img('theme/images/photo/default-album.png');

        return \Image::url($this->default_photo, 600);
    }

    public function countPhotos()
    {
        return count(app('App\\Repositories\\PhotoRepository')->listAll('album-'.$this->id));
    }

    public function user()
    {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }
}