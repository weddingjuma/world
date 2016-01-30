<?php

namespace App\Repositories;

use App\Models\AdditionalPage;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class AdditionalPageRepository
{
    public function __construct(AdditionalPage $additionalPage)
    {
        $this->model = $additionalPage;
    }

    /**
     * Method to add page
     *
     * @param string $slug
     * @param string $title
     * @param string $content
     * @return boolean
     */
    public function add($slug, $title, $content)
    {
        //if (\Auth::user()->id != 1) return false;
        if (!$this->exists($slug)) {
            $page = $this->model->newInstance();
            $page->slug = $slug;
            $page->title = $title;
            $page->content = $content;

            $page->save();

            return true;
        }

        return false;
    }

    public function exists($slug)
    {
        return $this->model->where('slug', '=', $slug)->first();
    }

    public function get($slug)
    {
        return $this->model->where('slug', '=', $slug)->first();
    }
    /**
     * Method to save page from editing
     *
     * @param string $slug
     * @param array $array
     * @return boolean
     */
    public function save($slug, $val)
    {
        $expected = [
            'title' => '',
            'content' => ''
        ];

        /**
         * @var $title
         * @var $content
         */
        extract(array_merge($expected, $val));

        $page = $this->get($slug);

        if (!$page) return false;

        $page->title = $title;
        $page->content = $content;
        $page->save();

        return true;
    }
}