<?php

namespace App\Repositories;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class DatabaseRepository
{

    /**
     * Method to upadate the ddatabase
     *
     * @param string $type
     * @return boolean
     */
    public function update($type = 'core')
    {
        if ($type == 'core') {
            $installClassName = 'App\\SetupDatabase\\Install';
            $updateClassName = 'App\\SetupDatabase\\Upgrade';
        } else {
            $installClassName = 'App\\Addons\\'.ucwords($type).'\\SetupDatabase\\Install';
            $updateClassName = 'App\\Addons\\'.ucwords($type).'\\SetupDatabase\\Upgrade';
        }

        if (class_exists($installClassName) and method_exists($installClassName, 'database')) {
            @app($installClassName)->database();
        }
        if (class_exists($updateClassName) and method_exists($updateClassName, 'database')) {
            @app($updateClassName)->database();
        }

        return true;
    }
}