<?php

namespace App\Repositories;
use App\Models\GameCategory;
use Illuminate\Cache\Repository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class GameCategoryRepository
{
    public function __construct(GameCategory $gameCategory, Repository $cache)
    {
        $this->model = $gameCategory;
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
            $category->title = sanitizeText($title, 130);
            $category->slug = \Str::slug($title);
            $category->description = sanitizeText($description);
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

            $category->title = sanitizeText($title, 130);
            $category->slug = \Str::slug($title);
            $category->description = sanitizeText($description);
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
        return $this->model->where('title', '=', $id)->Where('id', '!=', $id)->first();
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