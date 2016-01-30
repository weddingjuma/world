<?php

namespace App\Repositories;

use App\Models\Report;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ReportRepository
{
    public function __construct(Report $report)
    {
        $this->model = $report;
    }

    /**
     * Method to add a report
     *
     * @param array $val
     * @return boolean
     */
    public function add($val)
    {
        $expected = [
            'type' => '',
            'url' => '',
            'reason' => ''
        ];

        /**
         * @var $type
         * @var $url
         * @var $reason
         */
        extract($val = array_merge($expected, $val));

        if (empty($reason)) return false;

        $report = $this->model->newInstance();
        $report->url = sanitizeText($url);
        $report->user_id = \Auth::user()->id;
        $report->type = sanitizeText($type, 50);
        $report->reason = \Hook::fire('filter-text', sanitizeText($reason));
        $report->save();

        return true;

    }

    public function lists($type = 'post')
    {
        return $this->model->with('user')->where('type', '=', $type)->paginate(20);
    }

    public function getAll($limit = 20)
    {
        return $this->model->with('user')->paginate($limit);
    }

    public function delete($id)
    {
        
        return $this->model->where('id', '=', $id)->delete();
    }


    public function total()
    {
        return $this->model->count();
    }

    public function deleteAllByUser($userid)
    {
        $this->model->where('user_id', '=', $userid)->delete();

    }

}