<?php namespace Idocrea8\Image;

use Illuminate\Support\ServiceProvider;
use iDocrea8\Image\Image;

class ImageServiceProvider extends ServiceProvider {

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
		$this->app['image'] = $this->app->share(function() {
           return new Image;
        });
	}

    public function boot()
    {
        $this->package('idocrea8/image', 'image', realpath(dirname(__DIR__).'/../'));
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
