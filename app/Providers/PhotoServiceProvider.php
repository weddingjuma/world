<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class PhotoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Interfaces\PhotoRepositoryInterface', 'App\Repositories\PhotoRepository');

    }

    public function boot()
    {
        $this->app['events']->listen('comment.add', function($text, $userid, $type, $typeId) {
            if($type == 'photo') {
                $photo = app('App\\Repositories\\PhotoRepository')->get($typeId);
                if ($photo) {
                    $receiver = app('App\\Repositories\\NotificationReceiverRepository');
                    if (!$receiver->exists($photo->user_id, $type, $typeId)) {
                        //lets send the photo owner
                        $notification = app('App\\Repositories\\NotificationRepository');
                        $notification->send($photo->user_id, [
                            'path' => 'notification.comment.add-comment',
                            'type' => $type,
                            'typeId' => $typeId,
                            'text' => $text
                        ], $userid);
                    }
                }
            }
        });
    }
}