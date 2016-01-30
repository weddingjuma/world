<?php

namespace App\Controllers\Base;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class SearchBaseController extends UserBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->searchRepository = app('App\\Repositories\\SearchRepository');

        $term = \Input::get('term');
        $term = preg_replace('![^\pL\pN\s]+!u', '', mb_strtolower($term));

        $this->searchRepository->term = $term;
        $this->setType();
        $this->theme->share('searchRepository', $this->searchRepository);
    }

    public function render($path, $param = [], $setting = [])
    {
        return parent::render('search.layout', [
            'content' => $this->theme->section($path, $param)
        ], $setting);
    }

    public function setType($type = 'people')
    {
        $this->searchRepository->type = $type;
    }
}