<?php

namespace App\Controllers\Admincp;

use App\Repositories\PageCategoryRepository;
use App\Repositories\PageRepository;

class PageController extends AdmincpController
{
    public function __construct(PageCategoryRepository $categoryRepository, PageRepository $pageRepository)
    {
        parent::__construct();
        $this->activePage('page');
        $this->categoryRepository = $categoryRepository;
        $this->pageRepository = $pageRepository;
    }

    public function index()
    {
        return $this->theme->view('pages.list', ['pages' => $this->pageRepository->lists(null, 20, \Input::get('term'))])->render();
    }

    public function editPage($id)
    {
        $page = $this->pageRepository->get($id);

        $message = null;

        if ($val = \Input::get('val')) {
            $this->pageRepository->adminEdit($val, $page);
            $message = "Saved successfully";
        }
        return $this->theme->view('pages.edit', ['page' => $page, 'message' => $message])->render();
    }

    public function categories()
    {
       return  $this->theme->view('pages.categories', ['categories' => $this->categoryRepository->lists()])->render();
    }

    public function createCategory()
    {
        $this->setTitle('Create Page Category');
        $message = null;
        if ($val = \Input::get('val')) {
            $category = $this->categoryRepository->add($val);
            if($category) {
                $message = "Category added successfully";
            } else {
                $message = "Failed to add category due to existence or invalid details";
            }
        }
        return $this->theme->view('pages.create-category', ['message' => $message])->render();
    }

    public function editCategory($id)
    {
        $this->setTitle('Edit Page Category');
        $category = $this->categoryRepository->get($id);

        if (!$category) return \Redirect::to(\URL::previous());

        $message = null;

        if ($val = \Input::get('val')) {
            $s = $this->categoryRepository->save($val, $category);
            if($s) {
                $message = "Category save successfully";
            } else {
                $message = "Failed to add category due to existence or invalid details";
            }
        }
        return $this->theme->view('pages.edit-category', ['message' => $message, 'category' => $category])->render();

    }

    public function deleteCategory($id)
    {
        $this->categoryRepository->delete($id);
        return \Redirect::to(\URL::previous());
    }
}
