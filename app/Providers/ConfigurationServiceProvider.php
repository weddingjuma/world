<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Configuration service provider
 *
 * @author : Tiamiyu waliu kola
 * @webiste: procrea8.com
 */
class ConfigurationServiceProvider extends ServiceProvider
{
    public function register(){

    }

    public function boot(){
        if (\Config::get('system.installed')) {
            $repository = app('App\Repositories\ConfigurationRepository');

            foreach($repository->getAll() as $configuration) {
                \Config::set($configuration['slug'], $configuration['value']);
            }

            /**
             * set image configuration
             */
            \Config::set('image::max-size', \Config::get('image-max-size'));
            \Config::set('image::save-original', true);
            \Config::set('image::allow-animated-gif', \Config::get('allow-animated-gif'));
            \Config::set('image::ext-allowed', \Config::get('image-allow-type', 'gif,png,jpg'));
            \Config::set('mail.from', ['address' => \Config::get('site_email', ''), 'name' => \Config::get('site_title', 'crea8SOCIAL')]);
            \Config::set('app.debug', \Config::get('enable-debug', 0));

            $timezone = \Config::get('timezone', 'UTC');

            if ($timezone) date_default_timezone_set($timezone);
            \Config::set('app.timezone', \Config::get('timezone', 'UTC'));

            //exit(\Config::get('app.timezone', 'UTC'));
            $driver = \Config::get('site-mail-driver', 'mail');
            if (!empty($driver)) {
                \Config::set('mail.driver', $driver);
            }

            \Config::set('mail.host', \Config::get('mail-driver-host', ''));
            \Config::set('mail.username', \Config::get('mail-driver-username', ''));
            \Config::set('mail.password', \Config::get('mail-driver-username', ''));

            $from = array('address' => \Config::get('mail-from-address', ''), 'name' => \Config::get('mail-from-name', ''));

            \Config::set('mail.from', $from);

            \Config::set('mail.encryption', \Config::get('site-mail-encryption', ''));

            \Config::set('mail.port', \Config::get('mail-driver-port', '587'));
            /**Assets***/
            \Config::set('theme::minifyAssets', \Config::get('minify-assets'));
        }
    }
}