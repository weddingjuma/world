<?php
/**
* Theme configuration
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
return array(

    /**
     * Asset url
     */
    'assetUrl' => URL::to('/'),

    /**
     * Minify assets css and js files
     */
    'minifyAssets' => false,

    /**
     * minify assets dir
     */
    'minifyDir' => 'app/storage/assets',

    /**
     * Asset dir
     */
    'assetDir' => 'assets',

    /**
     * theme path
     */
    'themePath' => public_path('themes'),

    /**
     * Theme dir
     */
    'themeDir' => 'themes',

    /**
     * Package name
     *
     * From here we can rename our package name to anything like addon, modules
     */
    'packageName' => 'app/Addons',

    /**
     * Default theme type
     * With this  we can have different theme type like Mobile|Admin e.t.c
     *
     * When no default theme type is using Theme::type() the value below is used
     */
    'defaultType' => 'frontend',

    /**
     * Default theme name in any of the theme types
     *
     * When no default theme name is set using Theme::current() below value will be used
     */
    'defaultTheme' => 'default',

    /**
     * Override for views and assets
     *
     * Setting this value to true make the view finder check for view or assets existence
     * from the current theme directory before going elsewhere
     *
     * But value can be override at the run time by using this Theme::override(true|false)
     */
    'override' => true,

    /**
     * Default layout
     *
     * When no layout is set using Theme::layout() below value will be use
     * Note: Layout can be disabled by passing false to the layout method
     * For Example Theme::layout(false)->view()->render()
     */
    'defaultLayout' => 'theme/layouts.default',

    /**
     * Theme autoload files
     *
     * List here are the default files to automatically included when the theme is booted.
     * In every theme the boot file must be present, thats why the boot.php is included as default
     */
    'autoload-files' => [
        'boot.php'
    ]
);
 