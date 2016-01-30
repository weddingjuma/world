<?php

namespace Idocrea8\Theme;

use Closure;
use Illuminate\View\Environment;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\View;
use Idocrea8\Theme\Asset;
use Idocrea8\Theme\Widget;
use Idocrea8\Theme\ThemeOption;
use Whoops\Example\Exception;
use Illuminate\Events\Dispatcher;

/**
* Theme Object
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Theme
{
    /*
     * Static namespace
     */
    protected static $namespace = "theme";

    /**
     * View Environment
     *
     * @var \Illuminate\View\Environment
     */
    protected $view;

    /**
     * Config
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Filesystem
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $file;

    /**
     * Event
     *
     * @var \Iluminate\Event\Dispatcher
     */
    protected $event;

    /**
     * asset
     *
     * @var \iDocrea8\Theme\Asset
     */
    protected $asset;

    /**
     * asset
     *
     * @var \iDocrea8\Theme\ThemeOption
     */
    protected $themeOption;

    /**
     * @var \iDocrea8\Theme\Widget
     */
    protected $widget;

    /**
     * Current theme name
     */
    protected $theme;

    /**
     * current theme path
     */
    protected $currentThemePath;

    /**
     * Theme path
     */
    protected $themePath;

    /**
     * Includes when theme is booted
     */
    protected $includes;

    /**
     * Override views or assets by theme
     */
    protected $override;

    /**
     * theme type
     */
    protected $type;

    /**
     * Package alias name
     */
    protected $packageName;

    /**
     * View content
     */
    protected $content;

    /**
     * Layout
     */
    protected $layout;

    /**
     * Prepare our dependency class
     *
     * @param \Illuminate\View\Environment $view
     * @param \Illuminate\Config\Repository $config
     * @param \Illuminate\Filesystem\Filesystem $file
     * @param \iDocrea8\Theme\Asset $asset
     * @param \Illuminate\Events\Dispatcher $dispatcher
     */
    public function __construct(
        Environment $view,
        Repository $config,
        Filesystem $file,
        Asset $asset,
        ThemeOption $themeOption,
        Dispatcher $dispatcher,
        Widget $widget
    )
    {
        $this->file = $file;
        $this->view = $view;
        $this->config = $config;
        $this->asset = $asset;
        $this->themeOption = $themeOption;
        $this->event = $dispatcher;
        $this->widget = $widget;

        $this->setConfig();

    }

    /**
     * Function to set the default config settings
     */
    private function setConfig()
    {
        $this->theme = $this->config->get('theme::defaultTheme');
        $this->type = $this->config->get('theme::defaultType');
        $this->override = $this->config->get('theme::override');
        $this->themePath = $this->config->get('theme::themePath');
        $this->layout = $this->config->get('theme::defaultLayout');
        $this->includes = $this->config->get('theme::autoload-files');
        $this->packageName = $this->config->get('theme::packageName');

    }

    /**
     * Set the current theme
     *
     * @param string $name
     * @return \iDocrea8\Theme\Theme
     */
    public function current($name)
    {
        if ($name) $this->theme = $name;

        $this->updateConfig('theme::defaultTheme', $name);


        return $this;
    }

    /**
     * Get current theme
     *
     * @return string
     */
    public function getCurrent()
    {
        return $this->theme;
    }

    /**
     * Set the theme type
     *
     * @param string $type
     * @return \iDocrea8\Theme\Theme
     */
    public function type($type)
    {
        if ($type) $this->type = $type;

        $this->updateConfig('theme::defaultType', $type);

        return $this;
    }

    /**
     * Get theme type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Set override rule
     *
     * @param boolean $override
     * @return \iDocrea8\Theme\Theme
     */
    public function override($override)
    {
        $this->override = $override;

        $this->updateConfig('theme::override', $override);

        return $this;
    }

    /**
     * Update config
     *
     * @param string $key
     * @param mixed $value
     * @return \iDocrea8\Theme\Theme
     */
    protected function updateConfig($key, $value)
    {
        $this->config->set($key, $value);

        return $this;
    }

    /**
     * Get the asset object
     *
     * @return \iDocrea8\Theme\Asset
     */
    public function asset()
    {
        return $this->asset;
    }

    /**
     * get widget
     */
    public function widget()
    {
        return $this->widget;
    }

    /**
     * Method to extend theme views
     *
     * @param string $name
     * @param array $param
     * @return \Illuminate\Events\Dispatcher
     */
    public function extend($name, $param = [])
    {
        $this->event->fire($name, $param);
    }

    /**
     * Method to listen to theme extends
     *
     * @param string $name
     * @param mixed $closure
     * @return \Illuminate\Events\Dispatcher
     */
    public function listen($name, $closure = null)
    {
        return $this->event->listen($name, $closure);
    }

    /**
     * Theme option
     *
     * @param string $path
     * @return string|\iDocrea8\Theme\ThemeOption
     */
    public function option($path = null)
    {
        if (!empty($path)) return $this->themeOption->get($path);

        return $this->themeOption;
    }

    /**
     * Method to share data across all views
     *
     * @param string $key
     * @param mixed $value
     * @return \iDocrea8\Theme\Theme
     */
    public function share($key, $value)
    {
        $this->view->share($key, $value);
        return $this;
    }

    /**
     * Set Page title
     *
     * @param string $title
     * @return \iDocrea8\Theme\Theme
     */
    public function setTitle($title)
    {
        return $this->share('title', $title);
    }

    /**
     * Set layout
     *
     * @param mixed $layout
     * @return \iDocrea8\Theme\Theme
     */
    public function layout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     *Load a view
     *
     * @param string $path
     * @param array $param
     * @return \iDocrea8\Theme\Theme
     */
    public function view($path, $param = array())
    {
        $this->content =  $this->loadView($path, $param);

        return $this;
    }

    /**
     * Load a section of a view
     *
     * @param string $path
     * @param array $param
     * @return string
     */
    public function section($path, $param = array())
    {
        return $this->loadView($path, $param);
    }

    /**
     * Load view
     *
     * @param string $path
     * @param array $param
     * @return string
     */
    public function loadView($path, $param = array())
    {
        if (strpos($path, 'theme/') !== false) {
            //load the view from the theme itself
            return $this->loadViewFromTheme($path, $param);

        } else {
            /**
             * This view can be from app/ or from any package view
             * From here we take care of that
             */
            if ($this->override) {
                if (strpos($path, '::') !== false) {

                    list($package, $newPath) = explode('::', $path);
                    $themePath = $this->packageName.'.'.$package.'.views.'.$newPath;

                } else {

                    $themePath = 'views.'.$path;
                }

                $namespace = $this->generateNamespace($themePath);

                try {

                    return $this->view->make($namespace, $param);

                } catch(\InvalidArgumentException $e) {

                    //lets try the default theme also
                    try{
                        $namespace = $this->generateNamespace($themePath, 'default');
                        return $this->view->make($namespace, $param);

                    } catch(\Exception $e) {
                        return $this->view->make($path, $param);
                    }

                }
            } else {
                /**
                 * Since we are not overriding just load the view from the origin
                 */
                return $this->view->make($path, $param);
            }
        }
    }

    /**
     * Helper load view directory from the theme directory
     *
     * @param string $path
     * @param array $param
     * @return string
     */
    private function loadViewFromTheme($path, $param)
    {
        list($theme, $path) = explode('/', $path);

        $namespace = $this->generateNamespace('views.'. $path);

        try{
            return $this->view->make($namespace, $param);
        } catch(\Exception $e) {
            //lets fall back to default files
            $namespace = $this->generateNamespace('views.'.$path, 'default');
            return $this->view->make($namespace, $param);
        }
    }

    /**
     * Generate the view namespace and return the true path to a theme view
     *
     * @param string $path
     * @return string
     */
    private function generateNamespace($path = null, $theme = false)
    {
        $namespace = (empty($theme)) ? static::$namespace : static::$namespace.'-'.$theme;
        $this->view->addNamespace($namespace, $this->getCurrentPath($theme));

        return $namespace.'::'.$path;
    }

    /**
     * Get the current theme path
     *
     * @return string
     */
    public function getCurrentPath($theme = '')
    {
        if ($this->currentThemePath and !$theme) return $this->currentThemePath;

        $theme = (empty($theme)) ? $this->theme : $theme;

        return $this->currentThemePath = $this->themePath.'/'.$this->type.'/'.$theme.'/';
    }

    /**
     * Boot current theme
     *
     * @return void
     */
    public  function bootTheme()
    {
        $this->event->fire('about.boot.theme', [$this]);

        $path = $this->getCurrentPath();

        foreach($this->includes as $file)
        {
            if ($this->file->exists($path.$file))
            {
                require($path.$file);
            }
        }

        $this->event->fire('theme.booted', [$this]);
    }

    /**
     * Method to reboot theme
     *
     * @return \iDocrea8\Theme\Theme
     */
    public function reBoot()
    {
        $this->asset()->reset();
        $this->currentThemePath = '';

        $this->bootTheme();

        return $this;
    }

    /**
     * Rencder theme content
     *
     * @return string
     */
    public function render()
    {

        return $this->loadView($this->layout, ['content' => $this->content]);
    }
}
 