<?php

namespace App\Repositories;

use App\Models\CommunityCategory;
use Illuminate\Cache\Repository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class CommunityCategoryRepository
{
    public function __construct(CommunityCategory $communityCategory, Repository $cache)
    {
        $this->model = $communityCategory;
        $this->cache = $cache;
    }

    /**
     * Method to add category
     *
     * @param int $id
     * @param string $title
     * @return boolean
     */
    public function add($id, $title)
    {
        if (!is_numeric($id)) return false;

        if (!$this->exists($id, $title)) {
            $category = $this->model->newInstance();
            $category->community_id = $id;
            $category->title = sanitizeText($title, 100);
            $category->slug = hash('crc32', $title.time());
            $category->save();

            $this->cache->forget('community-categoies-'.$id);
            return $category;
        }

        return false;
    }

    public function exists($id, $title)
    {
        return $this->model->where('community_id', '=', $id)->where('title', '=', $title)->first();
    }

    public function get($id, $communityId = null)
    {
        if ($communityId) {
            return $this->model->where('community_id', '=', $communityId)
                ->where(function($query) use($id) {
                   $query->where('id', '=', $id)->orWhere('slug', '=', $id);
                })->first();
        }
        return $this->model->where('id', '=', $id)->orWhere('slug', '=', $id)->first();
    }

    public function delete($id)
    {
        $category = $this->get($id);

        $this->cache->forget('community-categories-'.$category->community->id);

        return $category->delete();
    }

    public function deleteAllByCommunity($id)
    {
        $this->cache->forget('community-categories-'.$id);
        return $this->model->where('community_id', '=', $id)->delete();
    }
}
 