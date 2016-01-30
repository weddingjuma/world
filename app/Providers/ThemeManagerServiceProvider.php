<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
/**
*ThemeManagerProvider
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ThemeManagerServiceProvider extends  ServiceProvider
{
    public function boot(){
        /**Register suggestion widget for psges**/
        if (\Config::get('system.installed') and \Auth::check()) {
           $this->app['widget']->add('user.suggestion', [
                'user-home',
                'user-search',
                'user-discover',
                'notifications',
                'user-community'
            ]);



            $this->app['widget']->add('page.suggestion', [
               'user-home',
                'user-search',
                'user-discover',
                'notifications'
            ]);

            $this->app['widget']->add('community.suggestion', [
                'user-home',
                'user-search',
                'user-discover',
                'notifications'
            ]);

            if (!\Config::get('disable-game', 0)) {
                $this->app['widget']->add('game.suggestion', [
                    //'user-home',
                    'user-search',
                    'user-discover',
                    'notifications'
                ]);

            }


            $this->app['widget']->add('ads.side', [
                'user-home',
                'user-search',
                'user-discover',
                'notifications',
                'user-community'
            ]);
        }
    }

    public function register()
    {
        $this->app->singleton('ThemeManager', function() {
           return app('App\Repositories\ThemeRepository');
        });


    }
}

