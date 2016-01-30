<?php

namespace Idocrea8\Menu;

/**
*Menu Container class
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class MenuContainer
{
    /**
     * Menu name
     */
    public $name;

    /**
     * Menu id
     * @var string $id
     */
    public $id;

    /**
     * Menu link
     *
     * @var string $link
     */
    public $link;

    /**
     * Menu icon
     *
     * @var string $icon
     */
    public $icon;

    /**
     * sub menu prefix
     *
     * @var string $subMenuPrefix
     */
    protected $subMenuPrefix = 'sub-menu-';


    /**
     * Initialize menu
     *
     * @param string $id
     * @param array $details
     */
    public function __construct($id, $details)
    {
        $this->id = $id;
        $this->setProperties($details);
    }

    /**
     * Set menu properties
     *
     * @param array $details
     * @return void
     */
    protected function setProperties($details)
    {
        if (!empty($details)) {
            foreach($details as $key => $value) {
                $this->$key = $value;
            }
        }

    }

    /**
     * Check if a menu has sub-menus
     *
     * @return boolean
     */
    public function hasMenus()
    {
        return \Menu::hasMenu($this->subMenuPrefix.$this->id);
    }

    /**
     * Method to get menu sub menus
     *
     * @return array
     */
    public function listMenus()
    {
        return \Menu::lists($this->subMenuPrefix.$this->id);
    }

    /**
     * Method to add subMenus to this menu
     *
     * @param  $function
     * @return void
     */
    public function subMenu($function)
    {
        call_user_func($function, $this);
    }

    /**
     * Add submenus
     *
     * @param string $id
     * @param array $details
     * @return \iDocrea8\Menu\MenuContainer
     */
    public function add($id, $details)
    {
       return \Menu::add($this->subMenuPrefix.$this->id, $id, $details);

    }
}