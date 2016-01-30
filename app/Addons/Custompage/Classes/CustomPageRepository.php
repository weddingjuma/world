<?php
namespace App\Addons\Custompage\Classes;
/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/

class CustomPageRepository
{
    public function __construct(CustomPage $customPage)
    {
        $this->model = $customPage;
    }

    public function add($val)
    {
        $expected = [
           'title' => '',
            'keywords' => '',
            'description' => '',
            'tags' => '',
            'privacy' => 0,
            'comments' => 1,
            'likes' => 1,
            'active' => 1,
            'show_menu' => 1,
            'content' => ''
        ];

        /**
         * @var $title
         * @var $keywords
         * @var $description
         * @var $tags
         * @var $privacy
         * @var $comments
         * @var $likes
         * @var $active
         * @var $show_menu
         * @var $content
         */
        extract(array_merge($expected, $val));

        $slug = hash('crc32', $title.time());
        $slug = $slug.'-'.\Str::slug($title);

        if (!$this->exists($title)) {
            $page = $this->model->newInstance();
            $page->title = $title;
            $page->description = sanitizeText($description);
            $page->keywords = sanitizeText($keywords);
            $page->tags = $tags;
            $page->privacy = $privacy;
            $page->show_menu = $show_menu;
            $page->content = lawedContent($content);
            $page->slug = $slug;
            $page->show_comments = $comments;
            $page->show_likes = $likes;
            $page->content = $content;
            $page->active = $active;
            $page->save();

            return true;
        }

        return false;
    }

    public function save($val, $page)
    {
        $expected = [
            'title' => '',
            'keywords' => '',
            'description' => '',
            'tags' => '',
            'privacy' => 0,
            'comments' => 1,
            'likes' => 1,
            'active' => 1,
            'show_menu' => 1,
            'content' => ''
        ];

        /**
         * @var $title
         * @var $keywords
         * @var $description
         * @var $tags
         * @var $privacy
         * @var $comments
         * @var $likes
         * @var $active
         * @var $show_menu
         * @var $content
         */
        extract(array_merge($expected, $val));

            $page->title = $title;
            $page->description = sanitizeText($description);
            $page->keywords = sanitizeText($keywords);
            $page->tags = $tags;
            $page->privacy = $privacy;
            $page->show_menu = $show_menu;
            $page->content = lawedContent($content);
            $page->show_comments = $comments;
            $page->show_likes = $likes;
            $page->content = $content;
            $page->active = $active;
            $page->save();

            return true;

    }

    public function exists($title)
    {
        return $this->model->where('title', $title)->first();
    }

    public function findBySlug($slug)
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function lists($limit = 20)
    {
        return $this->model->paginate($limit);
    }

    public function getList($limit = 20, $all = false, $menu = true)
    {
        $lists = $this->model->where('active', 1)
            ->where(function($lists) {
                $lists->where('privacy', 0);

                if (\Auth::check() and \Auth::user()->isAdmin()) {
                    $lists->orWhere('privacy', 1)->orWhere('privacy', 2);
                }

                if (\Auth::check()) {
                    $lists->orWhere('privacy', 1);
                }
            });

        if($menu) $lists = $lists->where('show_menu', 1);

        if ($all) return $lists = $lists->get();

        return $lists = $lists->paginate($limit);
    }
}
 