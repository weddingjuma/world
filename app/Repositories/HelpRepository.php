<?php

namespace App\Repositories;

use App\Models\Help;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class HelpRepository
{
    public function __construct(Help $help)
    {
        $this->model = $help;
    }

    /**
     * Method to add help
     *
     * @param array $val
     * @return boolean
     */
    public function add($val)
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

        $slug = \Str::slug($title);

        if ($this->exists($slug)) return false;

        $help = $this->model->newInstance();
        $help->title = $title;
        $help->content = $content;
        $help->slug = $slug;
        $help->save();

        return true;
    }

    public function exists($slug)
    {
        return $this->model->where('slug', '=', $slug)->first();
    }

    public function get($slug)
    {
        return $this->model->where('slug', '=', $slug)->orWhere('id', '=', $slug)->first();
    }

    public function getAll()
    {
        return $this->model->orderBy('id', 'desc')->paginate(15);
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
            'content' => '',
            'new_slug' => ''
        ];

        /**
         * @var $title
         * @var $content
         * @var $new_slug
         */
        extract(array_merge($expected, $val));

        $help = $this->get($slug);

        if (!$help) return false;

        if ($slug != $new_slug and $this->exists($new_slug)) return false;

        $help->title = $title;
        $help->slug = $new_slug;
        $help->content = $content;
        $help->save();

        return true;
    }

    public function delete($id)
    {
        return $this->model->where('id', '=', $id)->delete();
    }

    public function total()
    {
        return $this->model->count();
    }
}