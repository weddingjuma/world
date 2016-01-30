<?php

namespace App\Repositories\Models;
use Illuminate\Database\Eloquent\Model;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class PageAdmin extends Model
{
    protected $table = "page_admins";

    public function user()
    {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }

    public function page()
    {
        return $this->belongsTo('App\\Models\\Page', 'page_id');
    }
}
 