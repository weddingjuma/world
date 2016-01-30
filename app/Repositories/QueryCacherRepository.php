<?php
namespace App\Repositories;
use Illuminate\Cache\Repository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class QueryCacherRepository
{
    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * public function to add a query cache
     *
     * @param string $id
     * @param mixed $content
     * @param int $minute
     * @return mixed
     */
    public function add($id, $content, $minute = false)
    {
        if($this->cache->has($id)) {
            return $this->cache->get($id);
        }

        //do the caching now
        if (!$minute) {
            $this->cache->forever($id, $content);
        } else {
            $this->cache->add($id, $content, $minute);
        }

        return $content;
    }

    /**
     * Method to remove the cached query
     *
     * @param string $id
     * @return boolean
     */
    public function forget($id)
    {
        return $this->cache->forget($id);
    }
}