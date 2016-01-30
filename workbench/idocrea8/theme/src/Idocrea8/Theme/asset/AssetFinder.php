<?php

namespace Idocrea8\Theme\Asset;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Idocrea8\Theme\Theme;

/**
*Favourite Asset Finder
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class AssetFinder
{
    /**
     * Config
     */
    protected $config;

    /**
     * @var \iDocrea8\Theme\Theme $theme
     */
    protected $theme;

    /**
     * Assetsd dir
     */
    protected $assetDir;

    /**
     * Package alias name
     */
    protected $packageName;

    /**
     * Constructor
     */
    public function __construct(
        Filesystem $filesystem,
        Repository $config
    )
    {
        $this->file = $filesystem;
        $this->config = $config;
        $this->theme = app('theme');
        $this->assetDir = $this->config->get('theme::assetDir');
        $this->packageName = $this->config->get('theme::packageName');

    }

    public function find($path)
    {
        if (strtolower(substr($path, 0, 6)) == 'theme/') {

            return $this->getFromTheme($path);

        } elseif(stripos($path, '::') !== false) {
            /**
             * Asset will be gotten from package
             */
            return $this->getFromPackage($path);
        } else {

            $relativePath = $this->assetDir.'/'.$path;

            return $relativePath;

        }
    }

    /**
     * Get asset from theme
     *
     * @param string $path
     * @return string
     */
    protected function getFromTheme($path)
    {
        $themeDir = $this->generateThemeDir();

        $path = substr($path, 6);
        $assetDir = $themeDir.$this->assetDir.'/';

        $file = $assetDir.$path;
        if ($this->file->exists($file)) {
            return $file;
        } else {
            //force to return to default theme
            $themeDir = $this->generateThemeDir('default');
            $assetDir = $themeDir.$this->assetDir.'/';
            return $assetDir.$path;
        }
    }

    /**
     * Generate theme directory
     *
     * @return string
     */
    protected function generateThemeDir($theme = "")
    {
        $themePath = $this->config->get('theme::themeDir');

        $theme = (empty($theme)) ? $this->theme->getCurrent() : $theme;

        return $themePath.'/'.$this->theme->getType().'/'.$theme.'/';
    }

    /**
     * Get from package
     *
     * @param string $path
     * @return string
     */
    protected function getFromPackage($path)
    {
        list($package, $path) = explode('::', $path);

        $relativePath = $this->packageName.'/'.ucwords($package).'/'.$this->assetDir.'/'.$path;

        return $this->detectPath($relativePath);
    }

    /**
     * Detect path by check for theme first if in override and fallback
     *
     * @param string $relativePath
     * @param string $themeRelativePath
     * @return string
     */
    public function detectPath($relativePath)
    {
        if ($this->config->get('theme::override')) {
            $themeDir = $this->generateThemeDir();
            /**
             * From here check if the assets exist in the theme path {themes/default/addons/assets/...}
             */
            if ($this->file->exists(base_path().'/'.$themeDir.$relativePath)) {
                return $themeDir.$relativePath;
            } else {
                return $relativePath;
            }
        } else {
            return $relativePath;
        }
    }

}
 