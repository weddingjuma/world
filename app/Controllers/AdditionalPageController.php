<?php

namespace App\Controllers;

use App\Repositories\AdditionalPageRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class AdditionalPageController extends \BaseController
{
    public function __construct(AdditionalPageRepository $additionalPageRepository)
    {
        parent::__construct();
        $this->additionalPageRepository = $additionalPageRepository;
    }

    public function about()
    {
        $page = $this->additionalPageRepository->get('about-us');

        return $this->formatRender(nl2br($page->content), $this->setTitle(trans($page->title)));
    }

    public function developers()
    {
        return $this->formatRender($this->theme->section('developers.index'), $this->setTitle('Developers'));
    }

    public function terms()
    {
        $page = $this->additionalPageRepository->get('terms-and-condition');

        return $this->formatRender(nl2br($page->content), $this->setTitle(trans($page->title)));

    }

    public function disclaimer()
    {
        $page = $this->additionalPageRepository->get('disclaimer');

        return $this->formatRender(nl2br($page->content), $this->setTitle(trans($page->title)));

    }

    public function privacy()
    {
        $page = $this->additionalPageRepository->get('privacy-policy');

        return $this->formatRender(nl2br($page->content), $this->setTitle(trans($page->title)));

    }
    public function formatRender($content, $title)
    {
        return $this->render('additional-pages.layout', ['content' => $content, 'title' => $title]);
    }
}