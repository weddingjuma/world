<?php

namespace iDocrea8\Image\Facade;

use Illuminate\Support\Facades\Facade;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Image extends Facade
{
    protected static function getFacadeAccessor(){return 'image';}
}