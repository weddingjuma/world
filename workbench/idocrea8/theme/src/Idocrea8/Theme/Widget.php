<?php

namespace Idocrea8\Theme;

use Idocrea8\Theme\Theme;
/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Widget
{
    protected static  $widgets;
    /**
     * Add a widget base
     *
     * @param string $base
     * @param boolean $force
     * @return \iDocrea8\Theme\Widget
     */
    public function registerBase($base, $force = false)
    {
        //if (isset(static::$widgets[$base]) and !$force) return $this;

        static::$widgets[$base] = [];

        return $this;
    }

    /**
     * Add widgets to a widget base
     *
     * @param string $view
     * @param string|array $base
     * @param array $data
     * @param boolean $force //incase the base doesnot exist
     * @return \iDocrea8\Theme\Widget
     */
    public function add($view, $base, $data = [], $force = false)
    {
        if (is_array($base)) {
            foreach($base as $baseName) {
                $this->add($view, $baseName,$data, $force);
            }
            return true;
        }
        //if (!isset(static::$widgets[$base]) and !$force) return $this;

        if (!isset(static::$widgets[$base])) {
            $this->registerBase($base);
        }

        static::$widgets[$base][] = ['view' => $view, 'data' =>  $data];
        return $this;
    }

    /**
     * Get widgets for a base
     *
     * @param string $base
     * @return string
     */
    public function get($base)
    {
        if (!isset(static::$widgets[$base])) return false;

        $paglets = [];
        $content = '';


        foreach(static::$widgets[$base] as $widget) {
            $paglets[] = $widget;
            if (!\Config::get('enable-bigpipe')) {

                $content .=\Theme::section($widget['view'], $widget['data']);
            }

        }


        if (\Config::get('enable-bigpipe')) {
            echo "<div class='pagelets' data-id='".$base."' data-content='".perfectSerialize($paglets)."'></div>";
        }

        return $content;
    }
}
 