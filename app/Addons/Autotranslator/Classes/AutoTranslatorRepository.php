<?php
namespace App\Addons\Autotranslator\Classes;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class AutoTranslatorRepository
{
    public function __construct(AutoTranslatorModel $model)
    {
        $this->model = $model;
    }

    public function findById($id)
    {
        return $this->model->where('hash_id', '=', $id)->first();
    }

    public function add($id, $result)
    {
        $translate = $this->model->newInstance();
        $translate->hash_id = $id;
        $translate->result = $result;
        $translate->save();
    }
}