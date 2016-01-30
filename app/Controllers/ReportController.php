<?php

namespace App\Controllers;

use App\Controllers\Base\UserBaseController;
use App\Repositories\ReportRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ReportController extends UserBaseController
{
    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
        parent::__construct();
    }

    public function index()
    {
        $url = \Input::get('url');
        $type = \Request::segment(2);
        $message = null;

        if ($val = \Input::get('val'))
        {
            if ($this->reportRepository->add($val)) {
                return \Redirect::route('user-home');
            } else {
                $message = trans('report.error');
            }
        }

        return $this->render('report.form', ['url' => $url, 'type' => $type, 'message' => $message], [
            'title' => $this->setTitle(trans('report.report-content'))
        ]);
    }
}