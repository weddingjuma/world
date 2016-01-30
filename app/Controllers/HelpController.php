<?php

namespace App\Controllers;

use App\Repositories\HelpRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class HelpController extends \BaseController
{
    public function __construct(HelpRepository $helpRepository)
    {
        parent::__construct();
        $this->helpRepository =$helpRepository;
    }

    public function index()
    {
        $this->setTitle('Help Center');
        return $this->formatRender($this->theme->section('helps.welcome'));
    }

    public function page($slug)
    {
        $help = $this->helpRepository->get($slug);

        if (empty($help)) return \Redirect::route('helps');

        $this->setTitle($help->title);

        return $this->formatRender($this->theme->section('helps.page', ['help' => $help]));
    }

    public function formatRender($content)
    {
        return $this->render('helps.layout', ['content' => $content, 'helps' => $this->helpRepository->getAll(30)]);
    }
}