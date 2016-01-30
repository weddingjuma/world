<?php

namespace App\Repositories;

use Illuminate\Cache\Repository;
use Illuminate\Filesystem\Filesystem;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ThemeRepository
{
    protected $supportedType = [];

    /**
     * @var string $activeCacheName
     */
    protected $activeCacheName = "active-themes";

    /**
     * Active themes
     *
     * @var array $activeThemes
     */
    protected $activeThemes = [
        'frontend' => 'default',
        'admincp'  => 'default'
    ];

    /**
     * @param ThemeReader $themeReader
     * @param \Illuminate\Cache\Repository
     */
    public function __construct(
        ThemeReader $themeReader,
        Repository $cache,
        Filesystem $filesystem
    )
    {
        $this->reader = $themeReader;
        $this->cache = $cache;
        $this->file = $filesystem;

        $this->setSupportedTypes();

        if ($this->cache->has($this->activeCacheName)) {
            $this->activeThemes = $this->cache->get($this->activeCacheName);
        }
    }

    /**
     * Get theme supported types
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->supportedType;
    }

    /**
     * Set theme supported types
     *
     * @return boolean
     */
    public function setSupportedTypes()
    {
        $this->supportedType = $this->reader->types();
    }
    /**
     * Get themes in a theme type
     *
     * @param string $type
     * @return array
     */
    public function getThemes($type)
    {
        return $this->reader->find($type);
    }

    /**
     * check if a theme is the active for a type
     *
     * @param string $type
     * @param string $theme
     * @return boolean
     */
    public function isActive($type, $theme)
    {
        if(!isset($this->activeThemes[$type])) {
            if($theme == 'default') return true;
            return false;
        }

        return ($this->activeThemes[$type] == $theme);
    }

    /**
     * Set Active theme for a type
     *
     * @param string $type
     * @param string $theme
     * @return void
     */
    public function setActive($type, $theme)
    {
        $this->activeThemes[$type] = $theme;

        /**
         * First clear cache incase of existence
         */
        $this->clearCache();

        $this->cache->forever($this->activeCacheName, $this->activeThemes);
    }

    /**
     * Get Active
     *
     * @param string $type
     * @return string
     */
    public function getActive($type)
    {
        $theme = 'default';

        if(isset($this->activeThemes[$type])) {
           $theme = $this->activeThemes[$type];
        }

        return $theme;
    }

    public function clearCache()
    {
        return $this->cache->forget($this->activeCacheName);
    }
}