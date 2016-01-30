<?php

namespace App\Controllers;
use App\Controllers\Base\PageBaseController;
use App\Repositories\CustomFieldRepository;
use App\Repositories\PageAdminRepository;
use App\Repositories\PageCategoryRepository;
use App\Repositories\PhotoRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class PageProfileController extends PageBaseController
{
    public function __construct(
        CustomFieldRepository $customFieldRepository,
        PageCategoryRepository $categoryRepository,
        PostRepository $postRepository,
        UserRepository $userRepository,
        PageAdminRepository $adminRepository,
        PhotoRepository $photoRepository,
        PostRepository $postRepository
    )
    {
        parent::__construct();
        $this->customFiedRepository = $customFieldRepository;
        $this->categoryRepository = $categoryRepository;
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->adminRepository = $adminRepository;
        $this->photoRepository = $photoRepository;
        $this->postRepository = $postRepository;

        if ($this->page) {
            $this->theme->share('site_description', $this->page->description);
            $this->theme->share('ogSiteName', $this->page->title);
            $this->theme->share('ogUrl', $this->page->present()->url());
            $this->theme->share('ogTitle', $this->page->title);
            $this->theme->share('ogImage', $this->page->present()->getAvatar(150));
        }
    }

    public function index()
    {
        if(!$this->exists()) {
            return $this->notFound();
        }

        return $this->render('page.profile.index', []);
    }

    public function photos()
    {
        if(!$this->exists()) {
            return $this->notFound();
        }

        $current = \Input::get('type', 'posts');

        $this->theme->share('singleColumn', 'true');

        return $this->render('page.profile.photos', [
            'current' => $current,
            'photos' => $this->photoRepository->listPages($this->page->id, $current)]);
    }

    public function addPhotos()
    {

        if (\Input::hasFile('image')) {

            $images = \Input::file('image');

            if (!$this->photoRepository->imagesMetSizes($images)) return 0; //confirm if one of the size is more than admin set value

            $page = $this->pageRepository->get(\Input::get('val.id'));

            $param = [
                'path' => 'pages/'.$page->id.'/posts',
                'slug' => 'photos',
                'userid' => $page->user->id,
                'page_id' => $page->id,
                'privacy' => 5
            ];

            $photos = [];
            $paths = [];
            foreach($images as $im) {
                $i = $this->photoRepository->upload($im, $param);
                $paths[] = $i;
                $photos[] = $this->photoRepository->getByLink($i);
            }

            //help this page to post to its timeline
            $this->postRepository->add([
                'type' => 'page',
                'content_type' => 'image',
                'type_content' => $paths,
                'page_id' => $page->id,
                'privacy' => 1,
            ]);


            $content = "";
            foreach($photos as $photo) {
                if ($photo) {
                    $content .= (String) $this->theme->section('photo.display-photo', ['photo' => $photo]);
                }
            }

            return $content;
        }

        return '0';
    }

    public function edit()
    {
        if(!$this->exists()) {
            return $this->notFound();
        }

        if (!$this->page->present()->isAdmin() and !$this->page->present()->isEditor()) return \Redirect::to($this->page->present()->url());

        $message = null;

        if ($val = \Input::get('val')) {

            $validator = \Validator::make($val, [
                'title' => 'required',
            ]);

            if (!$validator->fails()) {
                $save = $this->pageRepository->save($val, $this->page);
                if($save) {
                    $message = trans('page.success');
                } else {
                    $message = trans('page.save-error');
                }
            } else {
                $message = $validator->messages()->first();
            }
        }

        return $this->render('page.profile.edit', [
            'message' => $message,
            'fields' => $this->customFiedRepository->listAll('page'),
            'categories' => $this->categoryRepository->listAll()
        ]);
    }

    public function manageAdmins()
    {
        if(!$this->exists()) {
            return $this->notFound();
        }

        if (!$this->page->present()->isAdmin()) return \Redirect::to($this->page->present()->url());

        return $this->render('page.profile.admins', [
            'admins' => $this->adminRepository->getList($this->page->id)
        ]);
    }

    public function design()
    {
        if(!$this->exists() or !\Config::get('page-design')) {
            return $this->notFound();
        }

        if (!$this->page->present()->isAdmin() and !$this->page->present()->isEditor()) return \Redirect::to($this->page->present()->url());

        $message = null;
        if ($val = \Input::get('val')) {
            $this->userRepository->saveDesign($val);
            $message = 'Design saved';
        }

        return $this->render('page.profile.design', [
            'message' => $message,

        ]);
    }
}