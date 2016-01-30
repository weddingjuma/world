<?php

namespace App\Controllers\Admincp;

use App\Repositories\ConfigurationRepository;

/**
*Admincp Base controller
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class AdmincpController extends \BaseController
{
    public function __construct(

    )
    {
        parent::__construct();

        $this->forgetBeforeFilter('maintenance');

        $this->theme = \Theme::type('admincp')
            ->current(\ThemeManager::getActive('admincp'))
            ->reBoot()
            ->layout('layouts.default');

        $this->activePage('dashboard');
    }

    public function activePage($page)
    {
        $this->theme->share('activePage', $page);
    }

    public function setTitle($title = null)
    {
        $this->theme->setTitle('Admincp >> '.$title);
    }

    public function dashboard()
    {
        return $this->theme->view('dashboard.index')->render();
    }


}
 