<?php

namespace App\Controllers;

use App\Controllers\Base\DiscoverBaseController;
use App\Repositories\CommunityRepository;
use App\Repositories\PostRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class DiscoverController extends DiscoverBaseController
{
    public function __construct(
        PostRepository $postRepository,
        CommunityRepository $communityRepository
    )
    {
        parent::__construct();
        $this->postRepository = $postRepository;
        $this->communityRepository = $communityRepository;
    }

    public function post()
    {
        return $this->render('discover.post', ['posts' => $this->postRepository->discover()], [
            'title' => $this->setTitle(trans('discover.discover-post'))
        ]);
    }

    public function mention()
    {
        return $this->render('discover.mention', [
            'posts' => $this->postRepository->search(\Auth::user()->present()->atName()),
            'username' => \Auth::user()->present()->atName()
        ], [
            'title' => $this->setTitle(trans('discover.@mention'))
        ]);
    }

    public function communities()
    {
        return $this->render('discover.communities', [
            'communities' => $this->communityRepository->discover()

        ], [
            'title' => $this->setTitle(trans('discover.discover-community'))
        ]);
    }
}