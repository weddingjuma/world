<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Configuration service provider
 *
 * @author : Tiamiyu waliu kola
 * @webiste: procrea8.com
 */
class NotificationServiceProvider extends ServiceProvider
{
    public function register(){

    }

    public function boot(){
        $this->app['events']->listen('like.add', function($userid, $type, $typeId) {
            $notification = app('App\\Repositories\\NotificationRepository');
            if ($type == 'post') {
                $post = app('App\\Repositories\\PostRepository')->findById($typeId);

                if (!empty($post) and $post->user_id != \Auth::user()->id) {
                    $notification->send($post->user_id, [
                        'path' => 'notification.like.post',
                        'post' => $post
                    ], null, true, 'notify-like-post', $post->user);
                }
            } elseif ($type == 'photo') {
                $photo = app('App\\Repositories\\PhotoRepository')->get($typeId);

                if (!empty($photo) and $photo->user_id != \Auth::user()->id) {
                    $notification->send($photo->user_id, [
                        'path' => 'notification.like.photo',
                        'photo' => $photo
                    ]);
                }
            } elseif($type == 'page') {
                $page = app('App\\Repositories\\PageRepository')->get($typeId);

                if (\Auth::check()) {
                    if ($page and $page->user_id != \Auth::user()->id) {
                        $notification->send($page->user_id, [
                            'path' => 'notification.like.page',
                            'page' => $page
                        ]);
                    }
                }
            } elseif($type == 'game') {
                $game = app('App\\Repositories\\GameRepository')->get($typeId);

                if ($game and $game->user_id != \Auth::user()->id) {
                    $notification->send($game->user_id, [
                        'path' => 'notification.like.game',
                        'game' => $game
                    ]);
                }
            } elseif ($type == 'comment') {
                $comment = app('App\\Repositories\\CommentRepository')->getInfo($typeId);
                if ($comment and $comment->user_id != \Auth::user()->id) {
                    $notification->send($comment->user_id, [
                        'path' => 'notification.like.comment',
                        'comment' => $comment
                    ]);
                }

            }
        });
    }
}