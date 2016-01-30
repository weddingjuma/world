<?php

namespace App\Repositories;

use App\Models\Addon;
use App\Repositories\ConfigurationRepository;
use Illuminate\Cache\Repository;
use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Redis\Database;

/**
 *
 *@author : Tiamiyu waliu
 *@website : http://www.iDocrea8.com
 */
class AddonRepository
{
    protected $cacheName = 'addons';

    public function __construct(
        Addon $addon,
        Repository $cache,
        Config $config,
        Filesystem $filesystem,
        ConfigurationRepository $configurationRepository,
        DatabaseRepository $databaseRepository
    )
    {
        $this->model = $addon;
        $this->cache = $cache;
        $this->config = $config;
        $this->file = $filesystem;
        $this->addonPath = base_path().'/app/Addons/';
        $this->configurationRepository = $configurationRepository;
        $this->databaseRepository = $databaseRepository;
    }

    /**
     * Find an addon
     *
     * @param int $id
     * @param array $column
     * @return \App\Models\Addon
     */
    public function find($id, $column = ['*'])
    {
        return $this->model->find($id, $column);
    }

    /**
     * Find addon by its name
     *
     * @param string $name
     * @return \App\Models\Addon
     */
    public function findByName($name)
    {
        return $this->model->where('name', '=', $name)->first();
    }

    /**
     * Get all addons
     *
     * @param array $columns
     * @return array
     */
    public function all($columns = array('*'))
    {
        return $this->model->all($columns);
    }

    /**
     * List all addons in the addons directory
     *
     * @return array
     */
    public function listAddons()
    {
        $directories = $this->file->directories($this->addonPath);

        $addons = [];

        foreach($directories as $directory)
        {
            if ($this->file->exists($directory.'/info.php')) $addons[] = $this->file->getRequire($directory.'/info.php');
        }
        return $addons;
    }

    /**
     * Disable addon
     *
     * @param int $id
     * @return boolean
     */
    public function disable($id)
    {
        //if (\Auth::user()->id != 1) return false;
        $addon = $this->findByName($id);
        $addon->active = 0;
        $addon->save();

        \Event::fire('addon.disable', [$id, $addon]);
        $this->clearCache();

        return true;
    }

    /**
     * Enable addon
     *
     * @param int $id
     * @return boolean
     */
    public function enable($id)
    {
        //if (\Auth::user()->id != 1) return false;
        $addon = $this->findByName($id);

        $addon = (!$addon) ? $this->model->newInstance() : $addon;
        $addon->name = $id;
        $addon->active = 1;
        $addon->save();

        $this->update($id);
        \Event::fire('addon.enable', [$id, $addon]);
        $this->clearCache();

        return true;
    }

    /**
     * Update addon configurations and database
     *
     * @param string $id
     * @return boolean
     */
    public function update($id)
    {
        //if (\Auth::user()->id != 1) return false;
        $id = ucwords($id);
        $addonPath = $this->addonPath.$id.'/';

        $this->configurationRepository->update('app/Addons/'.$id.'/config');
        $this->databaseRepository->update($id);
        return true;
    }

    /**
     *
     */
    public function getEnabled()
    {
        if ($this->cache->has($this->cacheName)) {

            return $this->cache->get($this->cacheName);

        } else {

            $addons = $this->model->where('active', '=', 1)->get();

            /**
             * Cache the addon list for next request
             */
            $this->cache->forever($this->cacheName, $addons);
            return $addons;

        }
    }

    /**
     * Method to check if an addon is active
     *
     * @param string $name
     * @return boolean
     */
    public function exists($name)
    {
        $addons = $this->getEnabled();

        foreach($addons as $addon) {
            if ($addon->name == $name) return true;
        }

        return false;
    }

    /**
     * Alias for exists
     *
     * return boolean
     */
    public function isActive($name)
    {
        return $this->exists($name);
    }

    /**
     * @param string $addon
     * @return \App\Models\Addon
     */
    public function add($addon)
    {
        $model = $this->model->newInstance();
        $model->name = $addon;
        $model->save();

        \Event::fire('addon.add', [$addon, $model]);
        $this->clearCache();
        return $model;
    }

    /**
     * Clear addons cache incase there is an update
     *
     * @return void
     */
    protected function clearCache()
    {
        $this->cache->forget($this->cacheName);
    }
}
