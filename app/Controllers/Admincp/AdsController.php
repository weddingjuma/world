<?php

namespace App\Controllers\Admincp;
use App\Repositories\AdsRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class AdsController extends AdmincpController
{
    public function __construct(AdsRepository $adsRepository)
    {
        parent::__construct();
        $this->adsRepository = $adsRepository;
    }

    public function index()
    {
        $message = null;

        if ($val = \Input::get('val')) {
            $this->adsRepository->save($val);
            $message = "Successfully saved!";
        }
        return $this->theme->view('ads.index', [
            'message' => $message,
            'header' => $this->adsRepository->getHeader(),
            'side' => $this->adsRepository->getSide()
        ])->render();
    }
}