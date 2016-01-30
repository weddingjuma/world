<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Hashtag extends Model
{
    protected $table = "hashtags";

    public function url()
    {
         return \URL::to('search/hashtag?term='.ltrim(str_replace('#', '', $this->hash)));
    }
}