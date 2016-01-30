<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class AddonServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app['addon'] = $this->app->share(function() {
            return app('App\\Repositories\\AddonRepository');
        });
    }

    public function boot()
    {
        $this->config = $this->app->make('config');

        $this->enabledAddons();

    }

    public function enabledAddons()
    {
        if (\Config::get('system.installed')) {
            $manager = app('App\Repositories\AddonRepository');
            $addons = $manager->getEnabled();


            $modules = $this->config->get('system.coreAddons');

            foreach($addons as $addon) {
                $modules[][$addon->name] = ['enabled' => true, 'group' => 'addons'];
            }


            //listen to when theme is booted to allow addons to add there assets
            $this->app['events']->listen('theme.booted', function($theme) use($addons) {
                $path = str_replace('providers', '', dirname(__FILE__));

                foreach($addons as $addon) {
                    $realPath = app_path('/').'Addons/'.ucwords($addon->name).'/addassets.php';
                    //$realPath = str_replace('/', '\\', $realPath);

                    if (file_exists($realPath)) {
                        include($realPath);
                    }
                }
            });

            $this->app['config']->set('modules::modules', $modules);

            try
            {
                // Auto scan if specified
                $this->app['modules']->start();

                // And finally register all modules
                $this->app['modules']->register();
                /**
                 * Let restart the modules and re-register them
                 */

            }
            catch (\Exception $e)
            {
                $this->app['modules']->logError("There was an error when starting modules: [".$e->getMessage()."]");
            }
        }
    }

}
 