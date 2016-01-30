<?php

namespace App\Controllers;

use App\Controllers\Base\UserBaseController;
use App\Repositories\HashtagRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class UserHomeController extends UserBaseController
{
    public function __construct(
        PostRepository $postRepository,
        UserRepository $userRepository,
        HashtagRepository $hashtagRepository
    )
    {
        parent::__construct();
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->hashtagRepository = $hashtagRepository;
    }

    public function index()
    {
        return $this->render('user.home.index', [],
            ['title' => $this->setTitle(trans('global.home'))]
        );
    }

    public function tagSuggestion()
    {
       return $this->theme->section('user.tag.suggestion', ['users' => $this->userRepository->friendsSuggest(\Input::get('text'), 5)]);
    }

    public function tagUsername()
    {
        return $this->theme->section('user.tag.username', ['users' => $this->userRepository->searchByUsername(\Input::get('text'), 5)]);
    }

    public function hashTag()
    {
        return $this->theme->section('user.tag.hashtag', ['hashtags' => $this->hashtagRepository->search(\Input::get('text'), 5)]);
    }

    public function suggestion($type)
    {
        $content = null;
        $title = null;
        switch($type)
        {
            default:
                $content = $this->theme->section('user.suggestion.people', ['users' => $this->userRepository->suggest(10, null, true)]);
                $title =  $this->setTitle(trans('user.people-you-know'));
                break;
        }

        return $this->render('user.suggestion.layout', ['content' => $content, 'title' => $title]);
    }

    public function popover($id)
    {
        $user = $this->userRepository->findById($id);

        if (!empty($user)) {
            return $this->theme->section('user.popover', ['user' => $user]);
        }
    }
}