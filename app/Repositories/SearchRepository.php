<?php

namespace App\Repositories;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class SearchRepository
{
    public $term;

    public $type = 'people';

    public function url($type)
    {
        $slug = "/".(($type != 'people') ? $type : '');
        $slug .= (!empty($this->term)) ? '?term='.$this->term : null;
        return \URL::route('search').$slug;
    }
}