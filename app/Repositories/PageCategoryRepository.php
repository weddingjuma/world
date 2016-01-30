<?php

namespace App\Repositories;
use App\Models\PageCategory;
use Illuminate\Cache\Repository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class PageCategoryRepository
{
    public function __construct(PageCategory $pageCategory, Repository $cache)
    {
        $this->model = $pageCategory;
        $this->cache = $cache;
    }

    /**
     * Method to add category
     *
     * @param array $val
     * @return bool
     */
    public function add($val)
    {
        $expected = [
            'title',
            'description' => ''
        ];

        /**
         * @var $title
         * @var $description
         */
        extract(array_merge($expected, $val));

        if (!empty($title) and !$this->exists($title)) {
            $category = $this->model->newInstance();
            $category->title = sanitizeText($title, 100);
            $category->slug = \Str::slug($title);
            $category->description = $description;
            $category->save();

            return true;
        }

        return false;
    }

    public function save($val, $category)
    {
        $expected = [
            'title',
            'description'
        ];

        /**
         * @var $title
         * @var $description
         */
        extract(array_merge($expected, $val));

        if (!empty($title) and !$this->seperateExists($title, $category->id)) {

            $category->title = sanitizeText($title, 100);
            $category->slug = \Str::slug($title);
            $category->description = $description;
            $category->save();

            return true;
        }

        return true;
    }

    public function exists($title)
    {
        return $this->model->where('title', '=', $title)->orWhere('slug', '=', $title)->first();
    }

    public function seperateExists($title, $id)
    {
        return $this->model->where('title', '=', $title)->Where('id', '!=', $id)->first();
    }

    public function get($id)
    {
        return $this->model->where('title', '=', $id)->orWhere('slug', '=', $id)->orWhere('id', '=', $id)->first();
    }

    public function delete($id)
    {
        return $this->model->where('title', '=', $id)->orWhere('slug', '=', $id)->orWhere('id', '=', $id)->delete();
    }

    public function lists($limit = 10)
    {
        return $this->model->paginate($limit);
    }

    public function listAll()
    {
        return $this->model->get();
    }
}