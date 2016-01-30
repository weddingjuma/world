<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Like extends Model
{
    protected $table = "likes";

    public function user()
    {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }
}