<?php

namespace App\Controllers;

use App\Controllers\Base\UserBaseController;
use App\Repositories\InvitedMemberRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class InviteController extends UserBaseController
{
    public function __construct(InvitedMemberRepository $invitedMemberRepository)
    {
        $this->inviteRepository = $invitedMemberRepository;
        parent::__construct();
    }

    public function invite($type, $typeId, $userid)
    {
        $this->inviteRepository->add($type, $typeId, $userid);

        return '1';
    }

    public function index()
    {
        $message = null;

        if ($val = \Input::get('val')) {
            $this->inviteRepository->inviteMember($val);
            $message = trans('invite.success');
        }
        return $this->render('invite.send', ['user' => \Auth::user(), 'message' => $message], [
            'title' => $this->setTitle(trans('invite.invite-friends'))
        ]);
    }
}