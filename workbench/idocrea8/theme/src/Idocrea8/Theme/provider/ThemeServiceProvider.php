<?php namespace Idocrea8\Theme\Provider;

use Idocrea8\Theme\Theme;
use Idocrea8\Theme\Asset;
use Idocrea8\Theme\Asset\AssetContainer;
use Idocrea8\Theme\ThemeOption;
use Idocrea8\Theme\Widget;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('idocrea8/theme', 'theme', realpath(dirname(__DIR__).'/../../'));

        $this->app->make('theme')->bootTheme();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->registerAsset();
        $this->registerTheme();
	}

    protected function registerAsset()
    {

        $this->app['asset'] = $this->app->share(function($app) {
            return new Asset($app['events']);
        });
    }

    protected function registerTheme()
    {
        $this->app['themeOption'] = $this->app->share(function($app) {
           return new ThemeOption($app['files'], $app['config']);
        });

        $this->app['widget'] = $this->app->share(function($app) {
            return new Widget();
        });

        $this->app->singleton('theme', function($app) {
            $theme=  new Theme(
                $app['view'],
                $app['config'],
                $app['files'],
                $app['asset'],
                $app['themeOption'],
                $app['events'],
                $app['widget']
            );
            return $theme;
        });




    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
