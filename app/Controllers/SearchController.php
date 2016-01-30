<?php

namespace App\Controllers;

use App\Controllers\Base\SearchBaseController;
use App\Repositories\CommunityRepository;
use App\Repositories\GameRepository;
use App\Repositories\HashtagRepository;
use App\Repositories\PageRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class SearchController extends SearchBaseController
{
    public function __construct(
        PostRepository $postRepository,
        HashtagRepository $hashtagRepository,
        CommunityRepository $communityRepository,
        PageRepository $pageRepository,
        GameRepository $gameRepository
    )
    {
        parent::__construct();
        $this->postRepository = $postRepository;
        $this->hashtagRepository = $hashtagRepository;
        $this->communityRepository = $communityRepository;
        $this->pageRepository = $pageRepository;
        $this->gameRepository = $gameRepository;

        $this->setTitle(trans('search.search'));
    }

    public function index()
    {
        return $this->render('search.people', ['users' => $this->userRepository->search($this->searchRepository->term)]);
    }

    public function hashtag()
    {
        $this->setType('hashtag');

        return $this->render('search.hashtag', [
            'posts' => $this->postRepository->search('#'.$this->searchRepository->term),
            'hashtags' => $this->hashtagRepository->trending(10)
        ]);
    }
    public function posts()
    {
        $this->setType('posts');

        return $this->render('search.posts', ['posts' => $this->postRepository->search($this->searchRepository->term)]);

    }

    public function communities()
    {
        $this->setType('communities');

        return $this->render('search.communities', ['communities' => $this->communityRepository->search($this->searchRepository->term)]);
    }

    public function pages()
    {
        $this->setType('pages');
        return $this->render('search.pages', ['pages' => $this->pageRepository->search($this->searchRepository->term)]);
    }

    public function games()
    {
        $this->setType('games');
        return $this->render('search.games', ['games' => $this->gameRepository->search($this->searchRepository->term)]);
    }

    public function dropdown()
    {
        $hashtags = [];

        $pages = $this->pageRepository->search($this->searchRepository->term, 3);
        $communities = $this->communityRepository->search($this->searchRepository->term, 3);
        $games = $this->gameRepository->search($this->searchRepository->term, 3);

        $hashtags = $this->hashtagRepository->search($this->searchRepository->term, 5);

        return $this->theme->section('search.dropdown', [
            'users' => $this->userRepository->search($this->searchRepository->term, 3),
            'hashtags' => $hashtags,
            'pages' => $pages,
            'communities' => $communities,
            'games' => $games
        ]);
    }
}