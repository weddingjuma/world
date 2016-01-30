<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class HashtagServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['widget']->add('hashtag.trending', [
            'user-home'
        ]);
    }

    public function register()
    {
        $this->app['events']->listen('post.add', function($post) {
            App('App\\Repositories\\HashtagRepository')->process($post->text);
        });

        $this->app['events']->listen('post.edit', function($post) {
            App('App\\Repositories\\HashtagRepository')->process($post->text, false);
        });

        $this->app['hook']->listen('post-text', function($text) {
            return App('App\\Repositories\\HashtagRepository')->transform($text);
        });
    }
}