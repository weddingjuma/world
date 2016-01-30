<?php

namespace App\Repositories;

use App\Models\Configuration;
use Illuminate\Cache\Repository;
use Illuminate\Filesystem\Filesystem;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ConfigurationRepository
{
    protected $model;

    protected $cacheName = 'configurations';

    public function __construct(
        Configuration $configuration,
        Repository $cache,
        Filesystem $filesystem
    )
    {
        $this->model = $configuration;
        $this->cache = $cache;
        $this->file  = $filesystem;
        $this->basePath = base_path().'/';
    }

    /**
     * List configurations
     *
     * @param string $path
     * @return array
     */
    public function lists($path)
    {
        $fullPath = $this->basePath.$path;
        $file = $fullPath.'.php';

        if ($this->file->exists($file)) {
            return $this->file->getRequire($file);
        }

        return [];
    }

    /**
     * Get configuration name list from config directory
     *
     * @return array
     */
    public function getNameLists()
    {
        $path = $this->basePath.'app/config/site/';
        $list = [];

        if ($handle = opendir($path)) {
            while($file = readdir($handle)) {
                $file = str_replace(['.','php'], '', $file);
                if (!empty($file)) $list[] = $file;
            }
        }

        return $list;
    }

    /**
     * Get value for a configuration
     *
     * @param string $slug
     * @param mixed $default
     * @return mixed
     */
    public function get($slug, $default = null)
    {
        $configurations = $this->getAll();

        foreach($configurations as $configuration) {
            if ($configuration['slug'] == $slug) {
                return $configuration['value'];
            }
        }

        return $default;
    }

    /**
     * Get all db configurations
     *
     * @return array
     */
    public function getAll()
    {
        if ($this->cache->has($this->cacheName)) {
            return $this->cache->get($this->cacheName);
        } else {
            $all = $this->model->all()->toArray();

            $this->cache->forever($this->cacheName, $all);
            return $all;
        }
    }

    /**
     * Method to format some settings that require process to get configuration data
     *
     * @param mixed $data
     * @return array
     */
    public function data($data)
    {
        if (is_array($data)) {
            return $data;
        } elseif (is_callable($data)) {
            return call_user_func($data);
        } elseif (is_string($data)) {
            list($class, $func) = explode('@', $data);

            return call_user_func([$class, $func]);
        }

        return [];
    }

    /**
     * Save configurations
     *
     * @param array $val
     * @return boolean
     */
    public function save($val)
    {
        foreach($val as $slug => $value) {
            if (!$this->exists($slug)) {
                $model = $this->model->newInstance();
                $model->slug = $slug;
                $model->value = $value;
                $model->save();
            } else {
                $this->model->where('slug', '=', $slug)->update(['value' => $value]);
            }

        }

        $this->cache->forget($this->cacheName);
        return true;
    }

    /**
     * Method to update configurations
     *
     * @param string $path
     * @return boolean
     */
    public function update($path = null)
    {
        if (!$path) {
            $path = 'app/config/site/';
        }

        $fullPath = $this->basePath.$path;
        $configurations = [];



        if ($this->file->isDirectory($fullPath)) {

            $files = $this->file->allFiles($fullPath);

            foreach($files as $file) {
                if ($this->file->isFile($file)) {
                    $a = $this->file->getRequire($file);
                    if (is_array($a)) {
                        $configurations = array_merge($configurations, $a);
                    }

                }
            }
        }else {
            if ($this->file->isFile($this->basePath.$path.'.php')) $configurations = array_merge($configurations, $this->file->getRequire($this->basePath.$path.'.php'));
        }

        foreach($configurations as $slug => $details) {
            if (!$this->exists($slug)) {
                $model = $this->model->newInstance();
                $model->slug = $slug;
                $model->value = $details['value'];
                $model->save();
            }
        }

        $this->cache->forget($this->cacheName);

        return true;
    }

    public function exists($slug)
    {
        return $this->model->findBySlug($slug);
    }
}