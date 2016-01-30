<?php

namespace App\Controllers\Admincp;

use App\Repositories\AdditionalPageRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class AdditionalPageController extends AdmincpController
{
    public function __construct(AdditionalPageRepository $additionalPageRepository)
    {
        parent::__construct();
        $this->additionalPageRepository = $additionalPageRepository;
    }

    public function edit()
    {
        $slug = \Input::get('slug');

        $page = $this->additionalPageRepository->get($slug);

        if (empty($page)) return \Redirect::to(\URL::previous());

        if ($val = \Input::get('val')) {
            $this->additionalPageRepository->save($slug, $val);
        }

        return $this->theme->view('additional-page.edit', ['page' => $this->additionalPageRepository->get($slug)])->render();
    }
}