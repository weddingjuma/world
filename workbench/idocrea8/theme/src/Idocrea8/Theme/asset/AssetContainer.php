<?php

namespace Idocrea8\Theme\Asset;

use Idocrea8\Theme\Theme;

/**
*Asset container
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class AssetContainer
{
    /**
     * Our favourite asset finder
     */
    protected $finder;

    /**
     * Hold assets headed
     */
    protected $assets = [];

    /**
     * Minify assets
     */
    protected $minify = false;

    /**
     * Config
     */
    protected $config;

    /**
     * File
     */
    protected $file;

    /**
     * after style contents
     */
    protected static $afterStyleContent = '';

    /**
     * Before style content
     */
    protected static $beforeStyleContent  = '';

    /**
     * after scripts contents
     */
    protected static $afterScriptContent = '';

    /**
     * Before scripts content
     */
    protected static $beforeScriptContent = '';

    /**
     * Constructor
     *
     * @param \iDocrea8\Theme\Asset\AssetFinder
     */
    public function __construct()
    {
        $this->finder = new AssetFinder(app('files'), app('config'));
        $this->config = app('config');
        $this->file = app('files');

        $this->minify = $this->config->get('theme::minifyAssets');
    }

    /**
     * Method to enable or disable minify of assets
     *
     * @param boolean $minify
     * @return \iDocrea8\Theme\Asset\AssetContainer
     */
    public function minify($minify = true)
    {
        $this->minify = $minify;
        return $this;
    }

    /**
     * Generate a asset url
     *
     * @param string $url
     * @return string
     */
    public function url($url)
    {
        return \URL::to($url);
    }

    /**
     * css contents
     *
     * @param string $content
     * @param string $position
     * @return \iDocrea8\Theme\Asset\AssetContainer
     */
    public function styleContent($content, $position = 'before')
    {
        switch($position) {
            case 'before':
                static::$beforeStyleContent .= $content;
                break;
            case 'after':
                static::$afterStyleContent .=$content;
        }
        return $this;
    }

    /**
     * Helper method for styleContent
     *
     * @param string $content
     *@return \iDocrea8\Theme\Asset\AssetContainer
     */
    public function afterStyleContent($content)
    {
        return $this->styleContent($content, 'after');
    }

    /**
     * Helper method for styleContent in case of before
     *
     * @param string $content
     * @return \iDocrea8\Theme\Asset\AssetContainer
     */
    public function beforeStyleContent($content)
    {
        return $this->styleContent($content, 'before');
    }

    /**
     * script content
     *
     * @param string $content
     * @param string $position
     * @return \iDocrea8\Theme\Asset\AssetContainer
     */
    public function jsContent($content, $position = 'before')
    {
        switch($position) {
            case 'before':
                static::$beforeScriptContent .= $content;
                break;
            case 'after':
                static::$afterScriptContent .=$content;
        }
        return $this;
    }

    /**
     * Helper method for jsContent
     *
     * @param string $content
     * @return \iDocrea8\Theme\Asset\AssetContainer
     */
    public function afterScriptContent($content)
    {
        return $this->jsContent($content, 'after');
    }

    public function beforeScriptContent($content)
    {
        return $this->jsContent($content, 'before');
    }

    /**
     * Get script or style content added
     *
     * @param string $type
     * @param string $position
     * @return string
     */
    protected function getAssetContent($type, $position = 'before')
    {
        $null = null;
        switch($type) {
            case 'style':
                    if (($position == "before" and empty(static::$beforeStyleContent)) or ($position == 'after' and empty(static::$afterStyleContent))) {
                        return $null;
                    }
                    $content = '<style>';
                    $content .= ($position == 'before') ? static::$beforeStyleContent : static::$afterStyleContent;
                    $content .= "</style>";
                    return $content;
                break;
            case 'script':
                    if (($position == "before" and empty(static::$beforeScriptContent)) or ($position == 'after' and empty(static::$afterScriptContent))) {
                        return $null;
                    }
                    $content = '<script>';
                    $content .= ($position == 'before') ? static::$beforeScriptContent : static::$afterScriptContent;
                    $content .= "</script>";
                    return $content;
                break;
        }

        return $null;
    }

    /**
     * Method to add assets
     *
     * @param string $name
     * @param string $path
     * @param array  $dependencies
     * @param array  $attributes
     * @param boolean $override
     * @return \iDocrea8\Theme\Asset\AssetContainer
     */
    public function add($name, $path, $dependencies = array(), $attributes = array(), $override = false)
    {
        $type = (pathinfo($path, PATHINFO_EXTENSION) == 'css') ? 'style' : 'script';

        return $this->addAsset($name, $path, $type, $dependencies, $attributes, $override);
    }

    /**
     * Add assets
     *
     * @param string $name
     * @param string $path
     * @param string $type
     * @param array $dependencies
     * @param array $attributes
     * @param boolean $override
     * @return \iDocrea8\Theme\Asset\AssetContainer
     */
    public function addAsset($name, $path, $type = 'style', $dependencies = array(), $attributes = array(), $override = false)
    {
        if(!isset($this->assets[$type])) $this->assets[$type] = array();

        if (isset($this->assets[$type][$name]) and !$override) return false;

        $this->assets[$type][$name] = compact('path', 'dependencies', 'attributes');

    }

    /**
     * Get styles
     *
     * @return mixed
     */
    public function styles()
    {
        return $this->getAssets('style');
    }

    /**
     * Get scripts
     *
     * @return mixed
     */
    public function scripts()
    {
        return $this->getAssets('script');
    }

    /**
     * Get Image for us
     *
     * @param string $path
     * @return string
     */
    public function img($path)
    {
        return $this->url($this->finder->find($path));
    }

    public function get($path)
    {
        return $this->url($this->finder->find($path));
    }
    /**
     * Get assets
     *
     * @param string $type
     * @return mixed
     */
    public function getAssets($type)
    {
        if (!isset($this->assets[$type])) return null;
        /**
         * Process each of assets dependencies
         */
        $this->validateDependencies($type);

        $assets = $this->assets[$type];

        $afterAssetContent = $this->getAssetContent($type, 'after');
        $beforeAssetContent = $this->getAssetContent($type, 'before');

        if ($this->minify) {
            /**
             * We are minifying the assets
             */
            $url = $this->minifyAsset($assets, $type);
            $html = $beforeAssetContent;
            $html .= ($type == 'style') ? \HTML::style($url) : \HTML::script($url);
            $html .= $afterAssetContent;
            return $html;

        } else {
            $html = $beforeAssetContent;
            foreach($assets as $name => $details)
            {
                $path = $this->evaluatePath($details['path']);
                $html .= ($type == 'style') ? \HTML::style($path, $details['attributes']) : \HTML::script($path, $details['attributes']);
            }
            $html .= $afterAssetContent;

            return $html;
        }

    }

    /**
     * Evaluate the path to correct path
     * of each of the assets
     *
     * @param array $path
     * @return array
     */
    protected function evaluatePath($path)
    {
        return $this->finder->find($path);
    }

    /**
     * Validate assets dependencies
     *
     * @param string $type
     * @return void
     */
    protected function validateDependencies($type)
    {
        $validAssets = [];
        $assets = $this->assets[$type];

        foreach($assets as $name => $value)
        {
            $dependencies = $value['dependencies'];
            /**
             * If its not dependent on any assets let continue
             */
            if (empty($dependencies)) {
                continue;
            } else {
                /**
                 *validate each dependency
                 */
                foreach ($dependencies as $dependent)
                {
                    if ($this->dependencyIsValid($dependent, $name, $assets)) continue;
                }
            }
        }
    }

    /**
     * Check if dependent is valid
     *
     * @param string $dependent
     * @param string $name
     * @param array $assets
     * @return void
     */
    protected function dependencyIsValid($dependent, $name, $assets)
    {
        if ($dependent == $name) {
            /**
             * Nope assets can depend on itself
             */
            throw new \Exception("Hooops!! Asset [$name] is dependent on itself ");
        } elseif (!in_array($dependent, $assets)) {
            /**
             * The asset need this
             */
            //return true;
        }
    }

    /**
     * Minify assets before output
     *
     * @param array $assets
     * @param string $type
     * @return string
     */
    protected function minifyAsset($assets, $type)
    {
        $evaluate = $this->evaluateAllPathAndCalculateLastAssessTime($assets);
        list($assets, $calculatedTime) = $evaluate;
        $ext = ($type == 'style') ? 'css' : 'js';

        $minifyDir = $this->config->get('theme::minifyDir');

        /**
         * Ensure existence of asset cache directory
         */
        if (!$this->file->isDirectory(base_path($minifyDir))) $this->file->makeDirectory(base_path($minifyDir));

        $minifyFile = $minifyDir.'/'.md5($calculatedTime.$type.\Request::root()).'.'.$ext;

        if ($this->file->exists(base_path($minifyFile))) {
            return $minifyFile;
        } else
        {
            $content = '';

            foreach($assets as $asset)
            {
                $a = ($ext == 'js') ? ';' : null;
                if ($this->file->exists(base_path($asset))) $content .= $a.$this->parseContent($asset, $type);
            }

            $this->file->put(base_path($minifyFile), $content);

            return $minifyFile;
        }

    }

    /**
     * Method to parse asset content and compress it
     *
     * @param string $path
     * @param string $type
     * @return string
     */
    protected function parseContent($path, $type)
    {
        $realPath = $path;
        $path = base_path($path);
        $content = $this->file->get($path);

        /**
         * Only compress styles
         */
        if ($type != 'style') return $content;

        $baseDir = dirname($path);

        // Split content line.
        $lines = preg_split("/\n/", $content);

        $content = $this->parseCssUrl($content, $realPath);

        // Remove comments.
        $content = preg_replace('!/\*.*?\*/!s', '', $content);
        $content = preg_replace('/^\s*\/\/(.+?)$/m', "\n", $content);

        // Remove tabs, spaces, newlines, etc.
        $content = str_replace(array("\r\n","\r","\n","\t"), '', $content);

        // Remove other spaces before/after.
        $content = preg_replace(array('(( )+{)','({( )+)'), '{', $content);
        $content = preg_replace(array('(( )+})','(}( )+)','(;( )*})'), '}', $content);
        $content = preg_replace(array('(;( )+)','(( )+;)'), ';', $content);

        return $content;

    }

    /**
     * Method to parse css rules url
     *
     * @param string $content
     * @param string $path
     * @return string
     */
    protected function parseCssUrl($content, $path)
    {
        /**
         * parse url with ../../
         */
        $content = str_replace('../../', \URL::to($this->stripSegment($path, 2)).'/', $content);

        /**
         * now do ../
         */
        $content = str_replace('../', \URL::to($this->stripSegment($path, 1)).'/', $content);

        return $content;
    }

    /**
     * Help function to strip segment from a path
     *
     * @param string $path
     * @param int $number
     * @return string
     */
    protected function stripSegment($path, $number)
    {
        $a = explode('/', $path);

        $i = count($a) - ($number +1 );

        $path = "";

        for( $y =0; $y < $i; $y++)
        {
            $path.= $a[$y].'/';
        }

        return $path;
    }
    /**
     * Evaluate the paths and calculate the last asset time
     *
     * @param array $assets
     * @return array
     */
    protected function evaluateAllPathAndCalculateLastAssessTime($assets)
    {
        $newAssets = [];
        $calculatedTime = 0;

        foreach($assets as $asset => $value)
        {
            $value['path'] = $this->evaluatePath($value['path']);
            $newAssets[] = $value['path'];
            $realPath = base_path($value['path']);

            if (file_exists($realPath)) {
                $calculatedTime += $this->file->lastModified($realPath);
            }
        }

         return [$newAssets, $calculatedTime];
    }
}
 