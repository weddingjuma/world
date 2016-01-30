<?php
namespace App\Addons\Onlinemembers\Controller;
use App\Addons\Onlinemembers\Repository\OnlineRepository;
use App\Controllers\Base\UserBaseController;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class OnlineController extends UserBaseController
{
    public function __construct(OnlineRepository $onlineRepository)
    {
        $this->onlineRepository = $onlineRepository;
        parent::__construct();
    }

    public function index()
    {
        $gender = \Input::get('gender', 'all');

        return $this->render('onlinemembers::index', [
            'users' => $this->onlineRepository->getLists(\Config::get('onlinemember-per-page', 10), $gender),
            'gender' => $gender
        ], ['title' => $this->setTitle(trans('onlinemembers::global.online-members'))]);
    }
}