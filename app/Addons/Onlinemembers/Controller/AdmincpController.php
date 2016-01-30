<?php

namespace App\Addons\Onlinemembers\Controller;
use App\Addons\Onlinemembers\Repository\OnlineRepository;


/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class AdmincpController extends \App\Controllers\Admincp\AdmincpController
{
    public function __construct(OnlineRepository $onlineRepository)
    {
        parent::__construct();
        $this->onlineRepository = $onlineRepository;
    }

    public function index()
    {
        return $this->theme->view('onlinemembers::admincp.index', [
            'users' => $this->onlineRepository->getLists(20, null, true)
        ])->render();
    }
}