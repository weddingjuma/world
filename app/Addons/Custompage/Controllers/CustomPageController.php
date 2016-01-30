<?php
namespace App\Addons\Custompage\Controllers;
use App\Addons\Custompage\Classes\CustomPageRepository;
use App\Controllers\Base\UserBaseController;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class CustomPageController extends UserBaseController
{
    public function __construct(CustomPageRepository $customPageRepository)
    {
        parent::__construct();
        $this->customRepository = $customPageRepository;
        $this->theme->share('customRepository', $this->customRepository);
    }

    public function index($slug)
    {
        $page = $this->customRepository->findBySlug($slug);

        if (!$page or !$page->canView()) return $this->theme->section('error-page');

        $this->setTitle($page->title);
        $this->theme->share('site_description', $page->description);
        $this->theme->share('ogUrl', $page->url());
        $this->theme->share('ogTitle', $page->title);
        $this->theme->share('site_keywords', $page->keywords);

        return $this->preRender($this->theme->section('custompage::index', ['page' => $page]));
    }

    public function preRender($content)
    {
        return $this->render('custompage::layout', ['content' => $content]);
    }
}