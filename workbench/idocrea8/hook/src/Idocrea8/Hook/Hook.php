<?php

namespace Idocrea8\Hook;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Hook
{
    /**
     * @var array $hooks
     */
    protected static $hooks = [];

    /**
     * Listen to a hooks
     *
     * @param string $hook
     * @param closure $closure
     * @return \iDocrea8\Hook\Hook
     */
    public function listen($hook, $closure = null)
    {
        if (!isset(static::$hooks[$hook])) static::$hooks[$hook] = [];

        static::$hooks[$hook][] = $closure;

        return $this;
    }

    /**
     * fire an hook
     *
     * @param string $hook
     * @param string $result
     * @return mixed
     */
    public function fire($hook, $result = '')
    {
        if (!isset(static::$hooks[$hook])) return $result;


        foreach(static::$hooks[$hook] as $closure) {

            $result = call_user_func($closure, $result);

        }

        return $result;
    }
}