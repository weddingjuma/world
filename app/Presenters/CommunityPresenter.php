<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class CommunityPresenter extends Presenter
{

    public function url($segment = null)
    {
        $segment = (empty($segment)) ? '' : '/'.$segment;
        return \URL::route('community-page', ['slug' => $this->entity->slug]).$segment;
    }

    public function readDesign()
    {
        return $this->entity->user->present()->readDesign('community-'.$this->entity->id);
    }

    public function getPrivacy()
    {
        return ($this->entity->privacy == 1) ? 'Public' : 'Private';
    }

    public function isAdmin()
    {
        if (!\Auth::check()) return false;

        return ($this->entity->user_id == \Auth::user()->id);
    }

    public function canReceiveNotification()
    {
        $check = app('App\\Repositories\\NotificationReceiverRepository')->exists(\Auth::user()->id, 'community', $this->entity->id);

        return ($check) ? 1 : 0;
    }

    public function field($id = null)
    {
        $details = (!empty($this->entity->info)) ? perfectUnserialize($this->entity->info) : [];

        if (empty($id)) return $details;

        if (isset($details[$id])) return $details[$id];

        return 'Nill';
    }

    public function fields()
    {
        return app('App\\Repositories\\CustomFieldRepository')->listAll('community');
    }

    public function createdOn()
    {
        return str_replace(' ', 'T', $this->entity->created_at).'Z';
    }

    public function canJoin()
    {
        if (!\Auth::check()) return false;

        if ($this->entity->privacy == 0 and !$this->isInvited()) return false;

        return true;
    }

    public function canView()
    {
        if ($this->privacy == 1 or $this->entity->isOwner() or $this->isMember() or $this->isInvited())    return true;

        return false;
    }

    public function isInvited()
    {
        if (!\Auth::check()) return false;
        $invited = app('App\\Repositories\\InvitedMemberRepository')->isInvited('community', $this->entity->id, \Auth::user()->id);
        if ($invited) return true;
        return false;
    }

    public function canPost()
    {
        if (!\Auth::check() or !$this->isMember()) return false;

        if ($this->entity->isOwner() or ($this->entity->can_post == 1)) return true;

        return false;
    }

    public function canManage()
    {
        if ($this->entity->isOwner()) return true;

        if ($this->isModerator()) return true;
        return false;
    }

    public function canInvite()
    {
        if (!\Auth::check()) return false;
        return (($this->isMember() and $this->entity->can_invite == 1) or $this->entity->isOwner());
    }

    public function isMember($userid = null)
    {
        if ($this->entity->isOwner($userid)) return true;

        return app('App\\Repositories\\CommunityMemberRepository')->isMember($this->entity->id, $userid);
    }

    public function isModerator($userid = null)
    {
        if (!\Auth::check()) return false;
        $moderators = $this->entity->getModerators();

        $userid = (empty($userid)) ? \Auth::user()->id : $userid;

        return (in_array($userid, $moderators));
    }

    public function getLogo()
    {
        if (empty($this->entity->logo)) return \Theme::asset()->img('theme/images/community/cover.jpg');

        return \Image::url($this->entity->logo);
    }

    public function memberStatus($userid)
    {
        if ($this->isMember($userid)) return 'member';

        $invited = app('App\\Repositories\\InvitedMemberRepository')->isInvited('community', $this->entity->id, $userid);
        if ($invited) return 'invited';

        return false;
    }
}