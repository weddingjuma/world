<?php

namespace App\Controllers\Admincp;

use App\Repositories\LanguageRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class LanguageController extends AdmincpController
{
    public function __construct(LanguageRepository $language)
    {
        parent::__construct();
        $this->language = $language;
    }

    public function index()
    {
        $this->setTitle(trans('admincp.languages'));

        return $this->theme->view("languages.index", [
            'languages' => $this->language->all()
        ])->render();
    }

    public function add()
    {
        $this->setTitle(trans('admincp.add-language'));

        if ($val = \Input::get('val')) {

            $this->language->add($val);

            return \Redirect::route('admincp-languages');
        }
        return $this->theme->view("languages.add")->render();
    }

    public function activate($var)
    {
        $this->language->activate($var);
        return \Redirect::route('admincp-languages');
    }

    public function delete($var)
    {
        $this->language->delete($var);
        return \Redirect::route('admincp-languages');
    }

    public function change($var)
    {
        \Session::put('current-language', $var);
        return \Redirect::to(\URL::previous());
    }
}