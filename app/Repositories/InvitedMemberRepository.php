<?php

namespace App\Repositories;

use App\Models\InvitedMember;
use Illuminate\Cache\Repository;
use Illuminate\Mail\Mailer;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class InvitedMemberRepository
{
    public function __construct(
        InvitedMember $invitedMember,
        Repository $cache,
        NotificationRepository $notificationRepository,
        Mailer $mailer,
        UserRepository $userRepository
    )
    {
        $this->model = $invitedMember;
        $this->cache = $cache;
        $this->notification = $notificationRepository;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    /**
     * Method to add user
     *
     * @param string $type
     * @param int $typeId
     * @param int $userid
     * @return bool
     */
    public function add($type, $typeId, $userid)
    {
        if (!$this->exists($type, $typeId, $userid)) {
            $invite = $this->model->newInstance();
            $invite->type = $type;
            $invite->type_id = $typeId;
            $invite->user_id = $userid;
            $invite->save();

            if ($type != 'register') {
                /**
                 * Send a notification to user
                 */
                $this->notification->send($userid, [
                    'path' => 'notification.invite',
                    'type' => $type,
                    'typeId' => $typeId,
                ]);

                $fromUser = \Auth::user();
                $toUser = $this->userRepository->findById($userid);
                try{
                    $this->mailer->queue('emails.invite', [
                        'fullname' => $toUser->fullname,
                        'username' => $toUser->username,
                        'fromName' => $fromUser->fullname,
                        'fromUsername' => $fromUser->username,
                        'romProfileUrl' => $fromUser->present()->url(),
                        'fromAtName' => $fromUser->present()->atName(),
                        'type' => $type,
                        'typeId' => $typeId
                    ], function($mail) use($toUser, $fromUser) {
                        $mail->to($toUser->email_address, $toUser->fullname)
                            ->subject(trans('mail.invitation', ['fullname' => $fromUser->fullname]));
                    });
                } catch(\Exception $e) {

                }
            }
            $this->cache->forget($type.$typeId);
        }

        return false;
    }

    public function exists($type, $typeId, $userid)
    {
        return $this->model
            ->where('type', '=', $type)
            ->where('type_id', '=', $typeId)
            ->where('user_id', '=', $userid)
            ->first();
    }

    public function remove($type, $typeId, $userid)
    {
        $this->cache->forget($type.$typeId);
        return $this->model
            ->where('type', '=', $type)
            ->where('type_id', '=', $typeId)
            ->where('user_id', '=', $userid)
            ->delete();
    }

    public function usersId($type, $typeId)
    {
        if ($this->cache->has($type.$typeId)) {
            return $this->cache->get($type.$typeId);
        } else {
            $invites = $this->model
                ->where('type', '=', $type)
                ->where('type_id', '=', $typeId)
                ->lists('user_id');

            $this->cache->forever($type.$typeId, $invites);

            return $invites;
        }
    }

    public function inviteMember($val)
    {
        $expected = [
            'to' => ''
        ];

        /**
         * @var $to
         */
        extract(array_merge($expected, $val));

        $toArray = explode(',', $to);

        foreach($toArray as $to) {
            //we only invite if user is not a member yet
            $user = $this->userRepository->findByEmail($to);
            if (!$user) {
                $this->add('register', $to, \Auth::user()->id);
                //send a mail to this user
                $fromUser = \Auth::user();

                try{
                    $this->mailer->queue('emails.invite-new-user', [
                        'fromName' => $fromUser->fullname,
                        'fromUsername' => $fromUser->username,
                        'romProfileUrl' => $fromUser->present()->url(),
                        'fromAtName' => $fromUser->present()->atName(),
                    ], function($mail) use($to, $fromUser) {
                        $mail->to($to)
                            ->subject(trans('mail.invitation', ['fullname' => $fromUser->fullname]));
                    });
                } catch(\Exception $e) {

                }
            }
        }

        return true;
    }

    public function isInvited($type, $typeId, $userid)
    {
        $invites = $this->usersId($type, $typeId);

        return (in_array($userid, $invites));
    }

    /**
     * Method to get row if user is invited to register
     *
     * @param string $email
     * @return boolean
     */
    public function getUserInvited($email)
    {
        return $this->model->where('type', '=', 'register')->where('type_id', '=', $email)->first();
    }

    /**
     * @param $id
     * @return mixed
     */

    public function deleteAllByCommunity($id)
    {
        $this->cache->forget('community-categories-'.$id);
        return $this->model->where('type_id', '=', $id)->where('type', '=', 'community')->delete();
    }
}