<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class PostPresenter extends Presenter
{
    public function canView()
    {

        if (\Auth::check() and \Auth::user()->isAdmin()) return true;
        $privacy = $this->entity->privacy;
        if ($privacy == 1 or $this->entity->isOwner()) return true;
        if ($privacy == 5 and $this->entity->community_id == 0 and $this->page_id == 0 and !$this->entity->isOwner()) return false;

        //for a post from community check if its a private and the viewer is a member
        if ($this->community_id != 0) {
            $community = $this->entity->community;
            if ($community->privacy == 1) return true;

            if (!\Auth::check()) return false;
            if (!$community->present()->isMember()) return false;
            return true;
        }

        //if the post is from a page, its public they can see it
        if ($this->page_id != 0) return true;

        if (!\Auth::check()) return false;

        $connectionRepository = app('App\\Repositories\\ConnectionRepository');

        //now lets check for other users privacy
        if ($privacy == 2) {
            if (!$connectionRepository->areFriends($this->entity->user_id, \Auth::user()->id)) return false;
        }

        if ($privacy == 3) {
            if (!Auth::check()) return true;
            if (!$connectionRepository->isFollowing(\Auth::user()->id, $this->entity->user_id)) return false;
        }

        if ($privacy == 4) {
            if (!Auth::check()) return true;
            if ($connectionRepository->areFriends($this->entity->user_id, \Auth::user()->id)
            or $connectionRepository->isFollowing(\Auth::user()->id, $this->entity->user_id)
            ) return true;
        }
        return true;
    }

    public function isGood()
    {

        if ($this->entity->page_id and !$this->entity->page) {
            $this->forceDeleteIt();
            return false;
        }

        //also for community
        if ($this->entity->community_id and !$this->entity->community) {
            $this->forceDeleteIt();
            return false;
        }

        if ($this->entity->content_type == 'image')
        {
            if ($this->hasValidImage()) {
                return true;
            } else {
                $this->forceDeleteIt();
                return false;
            }
        }

        if ($this->entity->content_type == 'auto-post') {

            $details = @$this->getAutoPost();


            if (!$details) {
                $this->forceDeleteIt();
                return false;
            }

            if (isset($details['type']) and $details['type'] == 'change-avatar') {
                $avatar = $details['avatar'];
                //$file = base_path(str_replace('%d', 600, $avatar));
                if (\Image::exists($avatar)) {
                    return true;
                } else {
                    $this->forceDeleteIt();
                    return false;
                }
            } elseif ($details['type'] == 'add-photos') {

                $photos = $this->getAutoPostPhotos();
                if (count($photos) > 0) {
                    return true;
                } else {
                    $this->forceDeleteIt();
                    return false;
                }
            } elseif ($details['type'] == 'like-page') {
                if ($details['page']) {
                    return true;
                } else {
                    $this->forceDeleteIt();
                    return false;
                }
            } elseif ($details['type'] == 'like-game') {

                if ($details['game'] and !\Config::get('disable-game', 0)) {
                    return true;
                } else {
                    $this->forceDeleteIt();
                    return false;
                }
            }
        }

        return true;
    }

    public function forceDeleteIt()
    {
        app('App\\Repositories\\PostRepository')->delete($this->entity->id);
    }

    /**
     * Present text
     *
     * @return string
     */
    public function text($text = null)
    {
        $text = (empty($text)) ? $this->entity->text : $text;
        //$text = htmlspecialchars($text);
        $text = nl2br($text);


        //turn links to clickable
        $text = app('App\\Repositories\\PostRepository')->turnLinks($text);


        return \Hook::fire('post-text', $text);
    }

    public function hasTooMuchText()
    {
        return (strlen($this->entity->text) > \Config::get('post-text-max-show'));
    }

    public function hasOneImage()
    {

        $images = $this->images();
        return (count($images) > 1) ? false : true;
    }

    public function hasValidImage()
    {
        $images = $this->images();
        if (count($images) > 0) return true;
        return false;
    }

    public function images()
    {

        $images = perfectUnserialize($this->entity->type_content);

        $photos = [];


        if (!$images) return $photos;
        foreach($images as $image) {

          if (!is_array($image)) {

              if(\Image::exists($image)) {
                  $photos[] = $image;
              }
          }
        }

        return $photos;
    }

    public function getVideoUrl()
    {
        $content = perfectUnserialize($this->entity->type_content);
        if (!empty($content)) {
            $link = $content[0];
            $youTube = parseYouTube($link);
            if (\Config::get('enable-https', false)) {
                $youTube = str_replace('http://', 'https://', $youTube);
            }
            if (!empty($youTube)) return $youTube;

            $vimeoURL = parseVimeo($link);
            if (\Config::get('enable-https', false)) {
                $vimeoURL = str_replace('http://', 'https://', $vimeoURL);
            }
            if (!empty($vimeoURL)) return $vimeoURL;
        }

        return false;
    }


    public function hasLink()
    {
        $link = (empty($this->entity->link)) ? [] : perfectUnserialize($this->entity->link);

        if (empty($link) or empty($link['title'])) return false;

        return true;
    }

    public function getLinkDetail()
    {
        $link = (empty($this->entity->link)) ? [] : perfectUnserialize($this->entity->link);

        return $link;
    }

    public function getSoundCloudUrl()
    {
        $content = perfectUnserialize($this->entity->type_content);
        if (!empty($content) and !empty($content[0])) {
            return 'https://w.soundcloud.com/player/?url='.$content[0];
        }
        return false;
    }

    public function getLocation()
    {
        $content = perfectUnserialize($this->entity->type_content);
        if (!empty($content)) {
            return $content[0];
        }

        return false;
    }

    public function getOEmbed()
    {
        $content = perfectUnserialize($this->entity->type_content);
        if (!empty($content)) {
            return $content[0];
        }

        return false;
    }

    public function generalMediaValue()
    {
        $content = perfectUnserialize($this->entity->type_content);
        if (!empty($content)) {
            return $content[0];
        }

        return false;
    }

    public function time()
    {
        return str_replace(' ', 'T', $this->entity->created_at).'Z';
    }

    public function canReceiveNotification()
    {
        $check = app('App\\Repositories\\NotificationReceiverRepository')->exists(\Auth::user()->id, 'post', $this->entity->id);

        return ($check) ? 1 : 0;
    }

    public function canDelete()
    {

        if (!\Auth::check()) return false;

        if (\Auth::user()->id == $this->entity->user_id or \Auth::user()->isAdmin()) return true;

        return false;
    }

    public function getPrivacy()
    {
        switch($this->entity->privacy) {
            case 1:
                return trans('global.public');
            break;
            case 2:
                return trans('connection.friends');
            break;
            case 3:
                return trans('connection.followers');
            break;
            case 4:
                return trans('connection.friends-followers');
            break;
            default:
                return trans('user.only-me');
        }
    }

    public function listTags()
    {

        $tags = (empty($this->entity->tags)) ? [0] : perfectUnserialize($this->entity->tags);

        if (empty($tags)) return [];

        return app('App\\Repositories\\UserRepository')->findByIds($tags);
    }

    public function moreTags()
    {

        $limit = \Config::get('post-tags-member-limit');

        return $this->listLimitedTags($limit);
    }

    public function url()
    {
        return \URL::route('post-page', ['id' => $this->entity->id]);
    }

    public function isAutoPost()
    {
        return ($this->entity->content_type == 'auto-post');
    }

    public function getAutoPost()
    {
        return $content = perfectUnserialize($this->entity->type_content);
    }

    public function getAutoPostPhotos()
    {
        $photos = [];
        $details = @$this->getAutoPost();

        if (!$details) {
            $this->forceDeleteIt();
            return $photos;
        }

        if ($details['type'] == 'add-photos') {

            foreach($details['photos'] as $photo) {
                //$file = base_path(str_replace('%d', 600, $photo->path));

                if(\Image::exists($photo->path)) {
                    $photos[] = $photo->path;
                }
            }
        }

        return $photos;
    }
}