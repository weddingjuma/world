<?php

namespace App\Models;

use Illuminate\Database\Eloquent;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Configuration extends Eloquent\Model
{
    protected $table = "configurations";

    public function findBySlug($slug)
    {
        return $this->where('slug', '=', $slug)->first();
    }
}