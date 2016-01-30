<?php

namespace App\Controllers\Base;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class CommunityPageBaseController extends \BaseController
{
    public $community;

    public function __construct()
    {
        parent::__construct();
        $this->communityRepository = app('App\\Repositories\\CommunityRepository');
        $slug = \Request::segment(2);

        $this->community = $this->communityRepository->get($slug);

        $this->theme->share('community', $this->community);
    }

    public function exists()
    {
        return ($this->community and $this->community->present()->canView());
    }

    public function render($path, $param = [], $setting = [])
    {
        $predefinedSettings = [
            'title' => $this->setTitle()
        ];

        if ($this->exists()) {

            $predefinedSettings = array_merge($predefinedSettings, ['design' => $this->community->present()->readDesign()]);
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

        return parent::render('community.page.layout', ['content' => $this->theme->section($path, $param)], $settings);

    }

    public function notFound()
    {
        if ($this->community and !$this->community->present()->canView()) return \Redirect::route('communities');
        return $this->theme->section('error-page');
    }

    public function setTitle($title = null)
    {
        if (!$this->exists()) return parent::setTitle($title);
        $title = $this->community->title.((!empty($title)) ? ' - '.$title : null);
        return parent::setTitle($title);
    }
}