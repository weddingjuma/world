<?php

namespace App\Addons\Custompage\Controllers;
use App\Addons\Custompage\Classes\CustomPageRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class AdmincpController extends \App\Controllers\Admincp\AdmincpController
{
    public function __construct(CustomPageRepository $customPageRepository)
    {
        parent::__construct();
        $this->customRepository = $customPageRepository;

        $this->activePage('custom-page');
    }

    public function index()
    {
        $this->setTitle('Custom Pages');
        return $this->theme->view('custompage::admincp.index', [
            'lists' => $this->customRepository->lists()
        ])->render();
    }

    public function delete($slug)
    {
        $page = $this->customRepository->findBySlug($slug);

        if ($page) {
            $page->deleteIt();
        }

        return \Redirect::route('admincp-custom-pages');
    }

    public function edit($slug)
    {
        $page = $this->customRepository->findBySlug($slug);
        $message = null;

        if (!$page) return \Redirect::route('admincp-custom-pages');

        if ($val = \Input::get('val')) {

            $validate = \Validator::make($val, [
                'title' => 'required',
                'content' => 'required'
            ]);

            if (!$validate->fails()) {
                $page = $this->customRepository->save($val, $page);
                if ($page) {
                    return \Redirect::route('admincp-custom-pages');
                } else {
                    $message = "Failed to add page: maybe page already exists";
                }
            } else {
                $message = $validate->messages()->first();
            }

        }

        return $this->theme->view('custompage::admincp.edit', [
            'message' => $message,
            'page' => $page
        ])->render();
    }

    public function add()
    {
        $message = null;

        $this->setTitle('Add New Page');

        if ($val = \Input::get('val')) {

            $validate = \Validator::make($val, [
                'title' => 'required',
                'content' => 'required'
            ]);

            if (!$validate->fails()) {
                $page = $this->customRepository->add($val);
                if ($page) {
                    return \Redirect::route('admincp-custom-pages');
                } else {
                    $message = "Failed to add page: maybe page already exists";
                }
            } else {
                $message = $validate->messages()->first();
            }

        }

        return $this->theme->view('custompage::admincp.add', [
            'message' => $message
        ])->render();
    }
}