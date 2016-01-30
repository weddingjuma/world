<?php

namespace Idocrea8\Hook;

use Illuminate\Support\Facades\Facade;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class HookFacade extends Facade
{
    public static function getFacadeAccessor(){return 'hook';}
}