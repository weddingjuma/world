<?php

namespace App\Controllers\Base;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class DiscoverBaseController extends UserBaseController
{
    public function render($path, $param = [], $setting = [])
    {
        return parent::render('discover.layout', [
            'content' => $this->theme->section($path, $param)
        ], $setting);
    }
}