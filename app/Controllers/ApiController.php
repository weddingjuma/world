<?php

namespace App\Controllers;
use App\Repositories\PageRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class ApiController extends \BaseController
{
    public function __construct(
        UserRepository $userRepository,
        PostRepository $postRepository,
        PageRepository $pageRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->pageRepository = $pageRepository;
    }

    public function get()
    {
        $type = \Input::get('type');
        $get = \Input::get('get');
        $id = \Input::get('id');

        $errorResult = json_encode([
            'status' => 'error'
        ]);
        $result = null;

        switch($type) {
            case 'user':
                    if ($get == 'profile') {
                        $user = $this->userRepository->findByUsername($id);
                        if ($user) {
                            $result = json_encode([
                                'fullname' => $user->fullname,
                                'username' => $user->username,
                                'genre' => $user->genre,
                                'bio' => $user->bio,
                                'country' => $user->country,
                                'avatar' => $user->present()->getAvatar(600),
                                'cover' => $user->present()->coverImage(),
                                'verified' => $user->verified,
                                'id' => $user->id
                            ]);
                        }
                    } elseif($get == 'posts') {
                        $posts = $this->postRepository->timeline($id);
                        $user = $this->userRepository->findByUsername($id);
                        if (!$user) return json_encode(['posts' => []]);
                            $result = json_encode([
                                'posts' => $posts->toArray()
                            ]);

                    }
                break;

            case 'page':
                    if ($get == 'profile') {
                        $page = $this->pageRepository->get($id);
                        $result = json_encode([
                            'title' => $page->title,
                            'category' => $page->category->title,
                            'slug' => $page->slug,
                            'description' => $page->description,
                            'website' => $page->website,
                            'verified' => $page->verified,
                            'cover' => $page->present()->coverImage(),
                            'logo' => $page->present()->getAvatar(600)
                        ]);
                    } elseif($get == 'posts') {
                        $posts = $this->postRepository->pageTimeline($id);
                        $page = $this->pageRepository->get($id);

                        if (!$page) return json_encode(['posts' => []]);
                        $result = json_encode([
                            'posts' => $posts->toArray()
                        ]);

                    }
                break;
        }

        return $result;
    }
}