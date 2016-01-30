<?php

namespace App\Controllers\Base;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class UserBaseController extends \BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->userRepository = app('App\Repositories\UserRepository');

    }

    public function render($path, $param = [], $setting = [])
    {
        $predefinedSettings = [

        ];

        if (\Auth::check()) {
            /**
             * If there is login we help this user to design his page
             */
            $predefinedSettings = array_merge($predefinedSettings, ['design' => \Auth::user()->present()->readDesign()]);
        }

        $settings = array_merge($predefinedSettings, $setting);

        if (\Config::get('page-design') and isset($settings['design'])) {
            extract($settings['design']);

            $bgImage = (!empty($bg_image)) ? 'background-image:url('.\Image::url($bg_image).');' : 'background-image : none';
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

        return parent::render($path, $param, $settings);
    }
}