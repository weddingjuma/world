<?php

namespace App\Presenters;
use Laracasts\Presenter\Presenter;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class PagePresenter extends Presenter
{
    public function url($segment = null)
    {
        $segment = (empty($segment)) ? '' : '/'.$segment;
        return \URL::route('page', ['slug' => $this->entity->slug]).$segment;
    }

    public function readDesign()
    {
        return $this->entity->user->present()->readDesign('page-'.$this->entity->id);
    }

    public function getAvatar($size = 100)
    {
        if (empty($this->entity->logo)) return \Theme::asset()->img('theme/images/page/logo.jpg');

        return \Image::url($this->entity->logo, $size);
    }

    public function joinedOn()
    {
        return str_replace(' ', 'T', $this->entity->created_at).'Z';
    }

    public function isAdmin($ignoreGlobalAdmin = false)
    {
        if (!\Auth::check()) return false;

        if (!$ignoreGlobalAdmin and  \Auth::user()->isAdmin()) return true;
        if ($this->entity->isOwner()) return true;

        $userid = \Auth::user()->id;
        $adminRepository = app('App\Repositories\PageAdminRepository');
        if ($adminRepository->isAdmin($this->entity->id, $userid)) return true;
        return false;
    }

    public function isModerator()
    {
        if (!\Auth::check()) return false;

        $userid = \Auth::user()->id;
        $adminRepository = app('App\Repositories\PageAdminRepository');
        if ($adminRepository->isModerator($this->entity->id, $userid)) return true;
        return false;
    }

    public function isEditor()
    {
        if (!\Auth::check()) return false;

        $userid = \Auth::user()->id;
        $adminRepository = app('App\Repositories\PageAdminRepository');
        if ($adminRepository->isEditor($this->entity->id, $userid)) return true;
        return false;
    }

    public function coverImage()
    {
        if (!empty($this->entity->cover)) {
            return \Image::url($this->entity->cover);
        }
    }

    /**
     * Get user cover image
     */
    public function getCover()
    {
        $default = \Theme::asset()->img("theme/images/profile-cover.jpg");
        if (!$this->entity->cover) return $default;
        return \Image::url($this->entity->cover);
    }

    public function getOriginalCover()
    {
        if ($this->entity->original_cover) return \Image::url($this->entity->original_cover);
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
        return app('App\\Repositories\\CustomFieldRepository')->listAll('page');
    }

    public function likeStatus($userid)
    {
        $liked = app('App\\Repositories\\LikeRepository')->hasLiked('page', $this->entity->id, $userid);

        if ($liked) return 'liked';

        $invited = app('App\\Repositories\\InvitedMemberRepository')->isInvited('page', $this->entity->id, $userid);
        if ($invited) return 'invited';

        return false;
    }
}
 