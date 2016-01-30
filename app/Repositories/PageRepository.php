<?php

namespace App\Repositories;

use App\Models\Page;
use Illuminate\Events\Dispatcher;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class PageRepository
{
    public function __construct(Page $page,
                                PhotoRepository $photoRepository,
                                LikeRepository $likeRepository,
                                MustAvoidUserRepository $mustAvoidUserRepository,
                                Dispatcher $event
    )
    {
        $this->model = $page;
        $this->photoRepository = $photoRepository;
        $this->likeRepository = $likeRepository;
        $this->mustAvoidUserRepository = $mustAvoidUserRepository;
        $this->event = $event;
    }

    /**
     * Create page
     *
     * @param array $val
     * @return \App\Models\Page
     */
    public function create($val)
    {
        $expected = [
            'title',
            'description',
            'website',
            'url' => '',
            'category'
        ];

        /**
         * @var $title
         * @var $description
         * @var $website
         * @var $category
         * @var $url
         */
        extract(array_merge($expected, $val));


        if (!empty($title) and !empty($category)) {
            $page = $this->model->newInstance();
            $page->title = sanitizeText($title, 130);
            $page->slug = sanitizeText($url);
            $page->user_id = \Auth::user()->id;
            $page->description = \Hook::fire('filter-text', sanitizeText($description));
            $page->category_id = sanitizeText($category);
            $page->website = sanitizeText($website);
            $page->save();

            $page->save();

            $this->event->fire('page.add', [$page]);
            return $page;
        }

        return false;
    }

    /**
     * @param $val
     * @param $page
     * @return bool
     */
    public function save($val, $page)
    {
        $expected = [
            'title',
            'description',
            'website',
            'category',
            'url' => '',
            'title' => '',
            'info' => []
        ];

        /**
         * @var $title
         * @var $description
         * @var $website
         * @var $category
         * @var $info
         * @var $url
         */
        extract(array_merge($expected, $val));

        $page->description = sanitizeText($description);
        $page->website = sanitizeText($website);
        $page->title = sanitizeText($title, 130);
        $page->category_id = sanitizeText($category);
        $page->info = serialize($info);
        $page->save();

        return true;
    }

    public function adminEdit($val, $page)
    {
        $expected = [
            'description' => '',
            'verified' => 0
        ];

        /**
         * @var $description
         * @var $verified
         */
        extract(array_merge($expected, $val));

        $page->description = $description;
        $page->verified = $verified;
        $page->save();

        return true;
    }

    public function exists($title)
    {
        return $this->model->where('title', '=', $title)->first();
    }

    public function get($id)
    {
        return $this->model->with('likes')
            ->where('title', '=', $id)
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get())
            ->orWhere('slug', '=', $id)->orWhere('id', '=', $id)->first();
    }

    public function changePhoto($image, $page)
    {
        $user = (empty($user)) ? \Auth::user() : $user;
        $image = $this->photoRepository->upload($image, [
            'path' => 'users/'.$user->id,
            'slug' => 'page-'.$page->id,
            'userid' => $user->id
        ]);

        /**
         * Now save user avatar
         */
        $page->logo = $image;
        $page->save();

        return $image;
    }

    public function updateCover($id, $image)
    {
        return $this->model->where('id', '=', $id)->update(['cover' => sanitizeText($image)]);
    }

    public function search($term, $limit = 10)
    {
        return $this->model->with('likes')
            ->where('title', 'LIKE', '%'.$term.'%')
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get())
            ->orWhere('description', 'LIKE', '%'.$term.'%')->paginate($limit);
    }

    public function suggest($limit = 3)
    {
        $userid = \Auth::user()->id;
        $likes = $this->likeRepository->getLikesId('page', $userid);
        /**$connectionRepository = app('App\Repositories\\ConnectionRepository');
        $friendsId = $connectionRepository->getAllFriendConnectionIds($userid);

        $friendsLiked = ['00'];

        foreach($friendsId as $uid) {
            $fLikes = $this->likeRepository->getLikesId('page', $uid);

            if (is_array($fLikes)) $friendsLiked = array_merge($friendsLiked, $fLikes);
        }
        ->where(function($query) use($friendsLiked) {
        $query->whereIn('id', $friendsLiked)
        ->orWhere('id', '!=', '');
        })
         **/

        $query = $this->model->with('likes')
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get())

            ->whereNotIn('id', $likes)
            ->where('user_id', '!=', \Auth::user()->id)
            ->orderBy(\DB::raw('rand()'));

        if (\Config::get('enable-query-cache')) {
            return $query = $query->remember(\Config::get('query-cache-time-out', 5), 'page-suggestions-'.\Auth::user()->id)->take($limit)->get();
        } else {
            return $query = $query->paginate($limit);
        }

    }

    public function suggestAdmin($term, $page)
    {
        $userRepository = app('App\Repositories\UserRepository');

        $userIds = ['none'];
        //people who like this page
        $likesId = $this->likeRepository->getIds('page', $page->id);
        $userIds = array_merge($userIds, $likesId);

        //also friends only
        $friendsId = app('App\Repositories\ConnectionRepository')->getFriendsId();
        $userIds = array_merge($userIds, $friendsId);

        return $userRepository->searchByIds($term, $userIds);
    }

    public function friendsToLike($pageId, $limit = 5, $offset = 0, $term = '')
    {

        $userRepository = app('App\Repositories\UserRepository');
        $userIds = ['none'];
        //people who like this page
        $likesId = $this->likeRepository->getIds('page', $pageId);
        $userIds = array_merge($userIds, $likesId);

        //also friends only
        $friendsId = app('App\Repositories\ConnectionRepository')->getFriendsId();
        $friendsId[] = 0;

        return $userRepository->listByIds($friendsId, [0], $limit, $offset, $term);
    }

    public function lists($category = null, $limit = 10, $term = null)
    {
        $query =  $this->model->orderBy('id', 'desc')
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get());

        if(!empty($category)) {
            $query = $query->where('category_id', '=', $category);
        }

        if ($term) {
            $query = $query->where('title', 'LIKE', '%'.$term.'%');
        }

        return $query = $query->paginate($limit);
    }

    public function myLists($limit = 10)
    {
        return $this->model->with('likes')->where('user_id', '=', \Auth::user()->id)->orderBy('id', 'desc')->paginate($limit);
    }

    public function myListsId()
    {
        return $this->model->with('likes')->where('user_id', '=', \Auth::user()->id)->lists('id');
    }

    public function delete($id)
    {
        $page = $this->get($id);


        if ($page) {

            if ($page->user_id != \Auth::user()->id) {
                if (!\Auth::user()->isAdmin()) return false;
            }

            $page->delete();

            foreach([
                        'App\\Repositories\\PostRepository',
                        'App\\Repositories\\LikeRepository',
                        'App\\Repositories\\PhotoRepository',
                    ] as $object) {
                app($object)->deleteAllByPage($id);
            }
        }

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