<?php

namespace Idocrea8\Theme;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\Repository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ThemeOption
{
    protected static  $options;

    protected  $file;
    protected  $config;

    /**
     * @var string $themePath
     */
    protected $themePath;

    /**
     * @param Filesystem $file
     * @param Repository $config
     */
    public function __construct(
        Filesystem $file,
        Repository $config
    )
    {
        $this->file = $file;
        $this->config = $config;
        $this->themePath = $this->config->get('theme::themePath');
    }

    /**
     * Method to get a theme options
     * Example Theme::option()->get('frontend.default::slides')
     *
     * @param string $path
     * @return mixed
     */
    public function get($path)
    {
        if (empty($path)) return null;

        list($options, $key) = $this->parsePath($path);

        if (empty($options) or !isset($options[$key])) return null;

        return $options[$key];
    }

    /**
     * Get options and the option key
     *
     * @param string $path
     * @return array
     */
    public function parsePath($path)
    {
        $key = $path;
        if (preg_match('#::#', $path)) {
            $path = str_replace('.', '/', $path);
        } else {
            $path = \Theme::getType().'/'.\Theme::getCurrent();
        }

        $path = $this->themePath.'/'.$path.'/';
        $options =  (isset(static::$options[$path])) ? static::$options[$path] : [];

        if (empty($options) and $this->file->isDirectory($path) and $this->file->exists($path.'options.php')) {
            $options = $this->file->getRequire($path.'options.php');
        }

        static::$options[$path] = $options;
        return [$options, $key];
    }
}
