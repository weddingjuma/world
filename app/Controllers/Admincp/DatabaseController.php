<?php

namespace App\Controllers\Admincp;

use App\Repositories\DatabaseRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class DatabaseController extends AdmincpController
{
    public function __construct(DatabaseRepository $databaseRepository)
    {
        parent::__construct();
        $this->databaseRepository = $databaseRepository;
    }

    public function update()
    {
        $this->databaseRepository->update();
        return \Redirect::to(\URl::previous())->with('message', trans('admincp.success-saved'));
    }
}