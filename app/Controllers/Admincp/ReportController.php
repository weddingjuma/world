<?php

namespace App\Controllers\Admincp;

use App\Repositories\ReportRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ReportController extends AdmincpController
{
    public function __construct(ReportRepository $reportRepository)
    {
        parent::__construct();
        $this->activePage('report');
        $this->reportRepository = $reportRepository;

    }

    public function lists()
    {
        $type = \Input::get('type', 'post');

        return $this->theme->view('report.lists', ['reports' => $this->reportRepository->lists($type), 'type' => $type])->render();
    }

    public function delete($id)
    {
        $this->reportRepository->delete($id);

        return \Redirect::to(\URL::previous());
    }
}