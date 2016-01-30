<?php

namespace App\Controllers;

use App\Controllers\Base\CommunityPageBaseController;
use App\Interfaces\PhotoRepositoryInterface;
use App\Repositories\CommunityCategoryRepository;
use App\Repositories\CommunityMemberRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class CommunityPageController extends CommunityPageBaseController
{
    public function __construct(
        CustomFieldRepository $customFieldRepository,
        PostRepository $postRepository,
        CommunityCategoryRepository $categoryRepository,
        PhotoRepositoryInterface $photoRepositoryInterface,
        ConnectionRepository $connectionRepository,
        CommunityMemberRepository $communityMemberRepository,
        UserRepository $userRepository
    )
    {
        parent::__construct();
        $this->customFiedRepository = $customFieldRepository;
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
        $this->photo = $photoRepositoryInterface;
        $this->connectionRepository = $connectionRepository;
        $this->memberRepository = $communityMemberRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        if (!$this->exists()) {
            return $this->notFound();
        }
        return $this->render('community.page.index', [
            'posts' => $this->postRepository->lists('community-'.$this->community->id)
        ], [
            'title' => $this->setTitle(''),
        ]);
    }

    public function category($slug, $category)
    {
        if (!$this->exists()) {
            return $this->notFound();
        }

        $category = $this->categoryRepository->get($category, $this->community->id);

        if (empty($category)) return \Redirect::to($this->community->present()->url());

        return $this->render("community.page.category", [
            'posts' => $this->postRepository->lists('communitycategory-'.$category->id),
            'typeId' => $category->id
        ], ['title' => $this->setTitle($category->title)]);
    }

    public function edit()
    {
        if (!$this->exists()) {
            return $this->notFound();
        }

        if (!$this->community->present()->isAdmin()) return \Redirect::to($this->community->present()->url());

        $message = null;
        if ($val = \Input::get('val')) {

            $validator = \Validator::make($val, [
                'title' => 'required'
            ]);

            if (!$validator->fails()) {
                $save = $this->communityRepository->save($val, $this->community);

                if ($save) {
                    //send to this community home
                    return \Redirect::to($this->community->present()->url());
                } else {
                    $message = trans('community.save-error');
                }
            } else {
                $message = $validator->messages()->first();
            }
        }

        return $this->render('community.page.edit', ['fields' => $this->customFiedRepository->listAll('community'), 'message' => $message ], [
            'title' => $this->setTitle(trans('global.edit'))
        ]);
    }

    public function design()
    {
        if (!$this->exists() or !\Config::get('page-design')) {
            return $this->notFound();
        }

        if (!$this->community->present()->isAdmin()) return \Redirect::to($this->community->present()->url());

        $message = null;
        if ($val = \Input::get('val')) {
            $this->userRepository->saveDesign($val);
            $message = trans('community.design-save');
        }

        return $this->render('community.page.design', ['user' => $this->community->user, 'message' => $message ], [
            'title' => $this->setTitle(trans('community.design'))
        ]);
    }

    public function addCategory()
    {
        $id = \Input::get('id');
        $name = \Input::get('text');

        $category = $this->categoryRepository->add($id, $name);

        if ($category) {
            return json_encode([
                'title' => $category->title,
                'url' => $category->community->present()->url('category/'.$category->slug),
                'status' => 1
            ]);
        } else {
            return json_encode([
                'status' => 0,
                'message' => trans('community.category-create-error')
            ]);
        }


    }

    public function deleteCategory($id)
    {
        $this->categoryRepository->delete($id);

        return '1';
    }

    public function about()
    {
        if (!$this->exists()) {
            return $this->notFound();
        }

        return $this->render('community.page.about', [], ['title' => $this->setTitle(trans('global.about'))]);
    }

    public function invite()
    {
        if (!$this->exists()) {
            return $this->notFound();
        }

        if (!$this->community->present()->canInvite()) return \Redirect::to(\URL::previous());

        return $this->render('community.page.invite', ['connections' => $this->connectionRepository->getFriends()], ['title' => $this->setTitle('Invite Members')]);

    }

    public function members()
    {
        if (!$this->exists()) {
            return $this->notFound();
        }

        return $this->render('community.page.members', ['members' => $this->memberRepository->listUsers($this->community->id)], ['title' => $this->setTitle('Members')]);
    }

    public function join($id)
    {
        $this->communityRepository->join($id);

        return '1';
    }


    public function uploadCover()
    {
        $result = json_encode([
            'status' => 'error',
            'message' => 'Insufficient image width/Height, MinWidth : 100px and MinHeight :  100px'
        ]);

        if (!\Input::hasFile('img')) return json_encode([
            'status' => 'error',
            'message' => trans('photo.error', ['size' => formatBytes()])
        ]);

        $file = \Input::file('img');

        if (!$this->photo->imagesMetSizes($file)) return json_encode([
            'status' => 'error',
            'message' => trans('photo.error', ['size' => formatBytes()])
        ]);

        list($width, $height) = getimagesize($file->getRealPath());

        if ($width < 100 or $height < 100) {
            return $result;
        }
        if ($width < 1000) {
            //let use direct upload like that
            $imageRepo = $this->photo->image;
            $image = $imageRepo->load($file)->setPath('temp/')->offCdn();
            $image = $image->resize(800, 500, 'fill', 'up');;

            //if ($image->hasError()) return $result;

            $image = $image->result();
            $image = str_replace('%d', '800', $image);
        }  else {
            $image = $this->photo->upload($file, [
                'path' => 'temp/',
                'width' => 600,
                'fit' => 'inside',
                'scale' => 'down',
                'cdn' => false
            ]);

            if (!$image) return $result;
            $image = str_replace('_%d_', '_600_', $image);
        }




        if ($image) {

            list($width, $height) = getimagesize(base_path().'/'.$image);
            return json_encode([
                'status' => 'success',
                'url' => \URL::to($image),

            ]);
        }

        return $result;
    }

    public function cropCover()
    {
        $top = \Input::get('imgY1');
        $left = \Input::get('imgX1');
        $cWidth = \Input::get('cropW');
        $cHeight = \Input::get('cropH');
        $file = \Input::get('imgUrl');
        $file = str_replace( [\URL::to(''), '//'],[ '', '/'], $file);
        $id = \Input::get('id');

        $image = $this->photo->cropImage(base_path('').$file, 'cover/', $left, $top, $cWidth, $cHeight, false);
        $image = str_replace('%d', 'original', $image->result());

        /**make sure to delete the original image***/
        $this->photo->delete($file);

        if (!empty($image)) {
            /**
             * Update user profile cover
             */
            $this->communityRepository->updateLogo($id, $image);
            return json_encode([
                'status' => 'success',
                'url' => \Image::url($image),
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Error ',
            ]);
        }


    }
}