<?php

namespace App\Abstracts;

use App\Addonmanager\Repository\Settings;
/**
*Abstract for addon installer
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
abstract class SetupAbstract
{
    /**
     * Run migration for the addon
     *
     * @return void
     */
    public  function migration(){}

    /**
     * Run settings installer
     */
    public function settings(){}

    /**
     * Run the addon installer
     */
    public function run()
    {
        $this->settings = app('App\Addonmanager\Repository\Settings');
        $this->migration();

        $this->settings();
    }
}
 