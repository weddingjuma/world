<?php

namespace App\Repositories;

use App\Models\Community;
use Illuminate\Events\Dispatcher;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class CommunityRepository
{
    public function __construct(
        Community $community,
        CommunityMemberRepository $communityMemberRepository,
        NotificationReceiverRepository $notificationReceiverRepository,
        MustAvoidUserRepository $mustAvoidUserRepository,
        Dispatcher $event
    )
    {
        $this->model = $community;
        $this->memberRepository = $communityMemberRepository;
        $this->notificationReceiver = $notificationReceiverRepository;
        $this->mustAvoidUserRepository = $mustAvoidUserRepository;
        $this->event = $event;
    }

    /**
     * Method to create a community
     *
     * @param array $val
     * @return boolean
     */
    public function create($val)
    {
        $expected = [
            'public_name' => '',
            'private_name' => '',
            'can_post' => '',
            'searchable' => '',
            'privacy' => 1,
            'public_url',
            'private_url'
        ];

        /**
         * @var $public_name
         * @var $private_name
         * @var $can_post
         * @var $searchable
         * @var $privacy
         * @var $public_url
         * @var $private_url
         */
        extract(array_merge($expected, $val));

        $name = ($privacy == 1) ? $public_name : $private_name;

        if (empty($name)) return false;

        $community = $this->model->newInstance();
        $community->title = sanitizeText($name, 100);
        $community->user_id = \Auth::user()->id;
        $community->privacy = sanitizeText($privacy);

        if ($privacy == 1) {
            $community->can_post = sanitizeText($can_post);
            $community->can_join = 1;
            $community->searchable = 1;
            $community->slug = $public_url;
        } else {
            $community->can_post = 1;
            $community->can_join = 0;
            $community->searchable = sanitizeText($searchable);
            $community->slug = $private_url;
        }
        $community->save();

        /**
         * Add this user to the notification receivers for this community
         */
        $this->notificationReceiver->add(\Auth::user()->id, 'community', $community->id);

        $this->event->fire('community.add', [$community]);
        return $community;
    }

    /**
     * Method to save community
     *
     * @param array $val
     * @param \App\Models\Community $community
     * @return boolean
     */
    public function save($val, $community)
    {
        $expected = [
            'description' => '',
            'title' => '',
            'can_post' => '',
            'searchable' => '',
            'info' => []
        ];

        /**
         * @var $description
         * @var $title
         * @var $can_post
         * @var $searchable
         * @var $info
         */
        extract(array_merge($expected, $val));

        $community->description = \Hook::fire('filter-text', sanitizeText($description));
        $community->can_post = sanitizeText($can_post);
        $community->searchable = sanitizeText($searchable);
        $community->info = perfectSerialize($info);
        $community->title = sanitizeText($title);
        $community->save();

        return true;
    }

    public function adminSave($val, $community)
    {
        //if (\Auth::user()->id != 1) return false;
        $expected = [
            'description' => '',
            'title' => '',
            'type' => '',
            'searchable' => '',

        ];

        /**
         * @var $description
         * @var $title
         * @var $can_post
         * @var $searchable
         * @var $info
         */
        extract(array_merge($expected, $val));

        $community->description = \Hook::fire('filter-text', sanitizeText($description));
        $community->searchable = $searchable;
        $community->title = $title;
        $community->save();

        return true;
    }

    /**
     * Make to check if a community exists through the slug
     *
     * @param string $slug
     * @return boolean
     */
    public function exists($title)
    {
        return $this->model->where('title', '=', $title)->first();
    }

    public function seperateExists($title, $id)
    {
        return $this->model->where('title', '=', $title)->where('id', '!=', $id)->first();
    }

    public function get($slug)
    {
        return $this->model->with(['categories', 'members'])
            ->whereNotIn('user_id',  $this->mustAvoidUserRepository->get())
            ->where('slug', '=', $slug)->orWhere('id','=', $slug)->first();
    }

    public function join($id, $userid = null)
    {
        return $this->memberRepository->add($id, $userid);
    }

    public function getMyCommunities($userid = null, $limit = 9)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;

        return $this->model->with(['members', 'posts'])->where('user_id', '=', $userid)->orderBy('created_at', 'desc')->paginate($limit);
    }

    public function getJoinedCommunities($userid = null, $limit = 9)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        return $this->memberRepository->getCommunities($userid, $limit);
    }

    public function search($term, $limit = 10)
    {
        return $this->model->with(['members', 'posts'])
            ->where('searchable', '!=', 0)
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get())
            ->where(function($query) use($term) {
                    $query->where('title', 'LIKE', '%'.$term.'%')
                    ->orWhere('description', 'LIKE', '%'.$term.'%');
            } )->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    public function discover($limit = 9)
    {
        return $this->model->with(['members', 'posts'])->where('searchable', '!=', 0)->orderBy('created_at', 'desc')->paginate($limit);
    }

    public function suggest($limit = 5)
    {
        $ids = $this->memberRepository->getIds(\Auth::user()->id);

        $ids = array_merge([0], $ids);

        $query = $this->model->with(['members', 'posts'])
            ->whereNotIn('id', $ids)
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get())
            ->where('searchable', '!=', '0')
            ->where('user_id', '!=', \Auth::user()->id)
            ->orderBy(\DB::raw('rand()'));

        if (\Config::get('enable-query-cache')) {
            return $query = $query->remember(\Config::get('query-cache-time-out', 5), 'community-suggestions-'.\Auth::user()->id)->take($limit)->get();
        } else {
            return $query = $query->paginate($limit);
        }
    }

    /**
     * Method to update logo
     *
     * @param int $id
     * @param string $logo
     * @return boolean
     */
    public function updateLogo($id, $logo)
    {
        return $this->model->where('id', '=', $id)->update(['logo' => sanitizeText($logo)]);
    }

    /**
     * Method to leave community
     *
     * @param int $id
     * @param int $userid
     * @return boolean
     */
    public function leave($id, $userid = null)
    {
        return $this->memberRepository->delete($id, $userid);
    }

    public function assignModerator($id, $userid)
    {
        $community = $this->get($id);

        if ($community and $community->isOwner()) {
            $moderators = $community->getModerators();

            if (!in_array($userid, $moderators)) {
                $moderators[] = $userid;
                $community->moderators = perfectSerialize($moderators);
                $community->save();
            }

        }

        return false;
    }

    public function removeModerator($id, $userid)
    {
        $community = $this->get($id);

        if ($community and $community->isOwner()) {
            $moderators = $community->getModerators();
            $newModerators = [];
            foreach($moderators as $m) {
                if ($m != $userid) $newModerators[] = $m;
            }

            $community->moderators = perfectSerialize($newModerators);
            $community->save();

        }

        return false;
    }

    public function getAll($term = null, $limit = 10)
    {
        $communities = $this->model;

        if ($term) {
            $communities = $communities->where('title', 'LIKE', '%'.$term.'%');
        }
        return $communities = $communities->paginate($limit);
    }

    public function delete($id)
    {

        $community = $this->get($id);

        if ($community) {
            if ($community->user_id != \Auth::user()->id) {
                if (!\Auth::user()->isAdmin()) return false;
            }
            foreach([
                'App\\Repositories\\PostRepository',
                'App\\Repositories\\CommunityMemberRepository',
                'App\\Repositories\\CommunityCategoryRepository',
                'App\\Repositories\\InvitedMemberRepository'
            ] as $repository) {
                app($repository)->deleteAllByCommunity($id);
            }

            $community->delete();
        }

        return false;
    }

    public function deleteAllByUser($userid)
    {
        return $this->model->where('user_id', '=', $userid)->delete();
    }

    public function total()
    {
        return $this->model->count();
    }
}