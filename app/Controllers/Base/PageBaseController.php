<?php

namespace App\Controllers\Base;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class PageBaseController extends \BaseController
{
    public $page;

    public function __construct()
    {
        parent::__construct();
        $this->pageRepository = app('App\\Repositories\\PageRepository');
        $slug = \Request::segment(2);

        $this->page = $this->pageRepository->get($slug);

        $this->theme->share('page', $this->page);
    }

    public function exists()
    {
        return ($this->page);
    }

    public function render($path, $param = [], $setting = [])
    {
        $predefinedSettings = [
            'title' => $this->setTitle()
        ];

        if ($this->exists()) {

            $predefinedSettings = array_merge($predefinedSettings, ['design' => $this->page->present()->readDesign()]);
        }

        $settings = array_merge($predefinedSettings, $setting);

        if (\Config::get('page-design') and isset($settings['design'])) {
            extract($settings['design']);

            $bgImage = (!empty($bg_image)) ? 'background-image:url('.\Image::url($bg_image).');' : null;
            $this->theme->asset()->afterStyleContent("
                body{
                    ".$bgImage."
                    background-position: ".$bg_position.";
                    background-color: ".$bg_color.";
                    background-repeat: ".$bg_repeat.";
                    background-attachment : ".$bg_attachment.";
                }

                a {
                    color : ".$link_color.";
                }

                .page-content{
                    background-color: ".$content_bg_color.";
                }
            ");
        }

        return parent::render('page.profile.layout', ['content' => $this->theme->section($path, $param)], $settings);

    }

    public function notFound()
    {
        //if ($this->page and !$this->community->present()->canView()) return \Redirect::route('communities');
        return $this->theme->section('error-page');
    }

    public function setTitle($title = null)
    {
        if (!$this->exists()) return parent::setTitle($title);
        $title = $this->page->title.((!empty($title)) ? ' - '.$title : null);
        return parent::setTitle($title);
    }

}