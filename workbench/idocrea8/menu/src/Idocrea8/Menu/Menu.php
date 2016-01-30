<?php

namespace Idocrea8\Menu;

use Idocrea8\Menu\MenuContainer;

/**
*Menu class
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Menu
{
    protected $menus = [];

    /**
     * Method to add menus
     *
     * @param string $container
     * @param string $id
     * @param array $details
     * @param boolean $override
     * @return \iDocrea8\MenuContainer
     */
    public function add($container, $id, $details, $override = false)
    {
        if (!isset($this->menus[$container])) $this->menus[$container] = [];

        if(isset($this->menus[$container][$id]) and !$override) return $this;

        $this->menus[$container][$id] = new MenuContainer($id, $details);

        return $this->menus[$container][$id];
    }

    /**
     * Method to list menus under a container
     *
     * @param string $container
     * @return array
     */
    public function lists($container)
    {
        if (!isset($this->menus[$container])) return [];

        return $this->menus[$container];
    }

    /**
     * Check if a container has menus
     *
     * @param string $container
     * @return bool
     */
    public function hasMenu($container)
    {
        if (!isset($this->menus[$container])) return false;

        return count($this->menus[$container]);
    }

}
 