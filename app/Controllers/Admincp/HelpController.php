<?php

namespace App\Controllers\Admincp;

use App\Repositories\HelpRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class HelpController extends AdmincpController
{
    public function __construct(HelpRepository $helpRepository)
    {
        $this->helpRepository = $helpRepository;
        parent::__construct();
        $this->activePage('help');
    }

    public function index()
    {
        $this->setTitle('Manage Helps');

        return $this->theme->view('helps.lists', ['helps' => $this->helpRepository->getAll()])->render();
    }

    public function add()
    {
        $this->setTitle('Add Help');

        if ($val = \Input::get('val')) {
            $this->helpRepository->add($val);

            return \Redirect::route('admincp-helps');
        }

        return $this->theme->view('helps.add')->render();
    }

    public function edit($id)
    {
        $this->setTitle('Edit Help');

        $help = $this->helpRepository->get($id);

        if (empty($help)) return \Redirect::route('admincp-helps');

        if ($val = \Input::get('val')) {
            $this->helpRepository->save($help->slug, $val);

            return \Redirect::route('admincp-helps');
        }

        return $this->theme->view('helps.edit', ['help' => $help])->render();
    }

    public function delete($id)
    {
        $this->helpRepository->delete($id);

        return \Redirect::to(\URL::previous());
    }
}