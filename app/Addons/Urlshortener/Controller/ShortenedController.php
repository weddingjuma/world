<?php

namespace App\Addons\Urlshortener\Controller;
use App\Addons\Urlshortener\Classes\ShortenedRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class ShortenedController extends \BaseController
{
    public function __construct(ShortenedRepository $shortenedRepository)
    {
        $this->repository = $shortenedRepository;
        parent::__construct();
    }

    public function redirect($hash)
    {
        $url = $this->repository->get($hash);
        if ($url) {
            $url->click_count += 1;
            $url->save();
            return \Redirect::to($url->link);
        } else {
            return $this->theme->section('error-page');
        }
    }
}