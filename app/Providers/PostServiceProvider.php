<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class PostServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $postRepository = $this->app->make('App\\Repositories\\PostRepository');
        $this->app['events']->listen('user.avatar', function($user, $image) use($postRepository){
            if ($user->fully_started) {
                $postRepository->add([
                    'content_type' => 'auto-post',
                    'type_content' => [
                        'type' => 'change-avatar',
                        'avatar' => $image
                    ],
                    'privacy' => 1,
                    'type' => 'user-timeline'
                ]);
            }
        });

        $this->app['events']->listen('add-album-photos', function($id, $photos) use($postRepository) {
            $albumRepository = $this->app->make('App\\Repositories\\PhotoAlbumRepository');
            $album = $albumRepository->get($id);
            $postRepository->add([
                'content_type' => 'auto-post',
                'type_content' => [
                    'type' => 'add-photos',
                    'photos' => $photos,
                    'photo-count' => count($photos),
                    'album' => $album
                ],
                'privacy' => 1,
                'type' => 'user-timeline'
            ]);
        });

        $this->app['events']->listen('like.add', function($userid, $type, $typeId) use($postRepository) {
            if ($type == 'page') {
               if (\Auth::check()) {
                   $page = app('App\\Repositories\\PageRepository')->get($typeId);

                   $postRepository->add([
                       'content_type' => 'auto-post',
                       'type_content' => [
                           'type' => 'like-page',
                           'page' => $page
                       ],
                       'privacy' => 1,
                       'type' => 'user-timeline',
                       'auto_like_id' => 'page-'.$page->id
                   ]);

                   \Cache::forget('page-suggestions-'.\Auth::user()->id);
               }
            } elseif($type == 'game') {
                $game = app('App\\Repositories\\GameRepository')->get($typeId);

                $postRepository->add([
                    'content_type' => 'auto-post',
                    'type_content' => [
                        'type' => 'like-game',
                        'game' => $game
                    ],
                    'privacy' => 1,
                    'type' => 'user-timeline',
                    'auto_like_id' => 'page-'.$game->id
                ]);
            }elseif($type == 'post') {
                $post = $postRepository->findById($typeId);
                $post->touch();//method to update this post
                \Cache::forget('post-'.$post->id);
            }
        });

    }

}