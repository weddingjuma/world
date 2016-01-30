<?php namespace Idocrea8\Menu;

use Illuminate\Support\ServiceProvider;
use Idocrea8\Menu\Menu;

class MenuServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['menu'] = $this->app->share(function() {
            return new Menu();
        });
	}

    public function boot()
    {
        $this->package('idocrea8/menu', 'menu', realpath(dirname(__DIR__).'/../../'));
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
