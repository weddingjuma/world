<?php

namespace Idocrea8\Theme;

use Idocrea8\Theme\Asset\AssetContainer;
use Illuminate\Events\Dispatcher;

/**
*Asset class
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Asset
{
    /**
     * Containers
     */
    protected static  $containers = [];

    /**
     * Event dispatcher
     *
     * @var \Illuminate\Events\Dispatcher
     */
    protected $event;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->event = $dispatcher;
    }

    public function container($container = 'default')
    {
        if (!isset(static::$containers[$container])) {
            static::$containers[$container] = new AssetContainer();
        }

        return static::$containers[$container];
    }

    /**
     * Add css content
     */

    /**
     * Magic call that route to our AssetContainer
     */
    public function __call($method, $args)
    {
        $container = $this->container();
        return call_user_func_array([$container, $method], $args);
    }

    /**
     * Method to reset assets
     *
     * @return boolean
     */
    public function reset()
    {
        static::$containers = [];
        return true;
    }
}

 