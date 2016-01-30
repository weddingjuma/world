<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConnectionServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->app['events']->listen('user.register', function($user) {
            $email = $user->email_address;
            $inviteRepo = app('App\\Repositories\\InvitedMemberRepository');
            $invite = $inviteRepo->getUserInvited($email);

            if ($invite) {
                /**
                 * That means we are making them friend,
                 * A notification to this user
                 *
                 */
                $connectionRepo = app('App\\Repositories\\ConnectionRepository');
                $connectionRepo->autoConnectUser($user->id, $invite->user_id);

                $notification = app('App\\Repositories\\NotificationRepository');
                $notification->send($invite->user_id, [
                    'path' => 'notification.user.join',
                    'user' => $user
                ], $user->id);

                //finally delete the invitation
                $invite->delete();
            }
        });
    }
}