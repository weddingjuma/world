<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class EmoticonServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        if (\Config::get('enable-emoticon', 1)) {

            $this->app['hook']->listen('post-text',function($text) {
                $emoticons = \Theme::option()->get('emoticons');

                foreach($emoticons as $code => $details) {
                    $text = str_replace($code, "<img src='".$details['image']."' title='".$details['title']."'/>", $text);
                }
                return $text;
            });

        }
    }
}