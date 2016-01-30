<?php

namespace App\Repositories;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\Repository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ThemeReader
{
    protected $file;

    protected $config;

    protected $themePath;

    public function __construct(
        Filesystem $filesystem,
        Repository $config
    )
    {
        $this->file = $filesystem;
        $this->config = $config;
        $this->themePath = $this->config->get('theme::themePath').'/';
        $this->themeDir = $this->config->get('theme::themeDir');
    }

    /**
     * Find themes in a type
     *
     * @param string $type
     * @return array
     */
    public function find($type)
    {
        $themes = [];
        $typePath = $this->themePath.$type.'/';

        if ($this->file->isDirectory($typePath)) {

            $directories = $this->file->directories($typePath);

            foreach($directories as $directory) {
                $name = $this->findDirectoryName($directory);
                $manifest = $this->getManifest($directory);

                $themes[$name] = [
                    'path' => $directory,
                    'name' => $name,
                    'dir' => $this->themeDir.'/'.$type.'/'.$name.'/',
                    'manifest' => $manifest
                ];
            }

        }

        return $themes;
    }

    public function getManifest($directory)
    {
        $manifest = $directory.'/manifest.xml';

        if ($this->file->exists($manifest)) {
            return simplexml_load_file($manifest);
        }

        return false;
    }

    /**
     * @param string $directoryPath
     * @return string
     */
    public function findDirectoryName($directoryPath)
    {
        if (empty($directoryPath)) return false;

        $e = explode(DIRECTORY_SEPARATOR, $directoryPath);

        return $e[count($e) - 1];
    }

    /**
     * Get themePath
     *
     * @return string
     */
    public function path()
    {
        return $this->themePath;
    }

    /*
     * Method to read theme types
     *
     * @return array
     */
    public function types()
    {
        $directories = $this->file->directories($this->themePath);
        $types = [];

        foreach($directories as $directory) {
            $types[] = $this->findDirectoryName($directory);
        }

        return $types;
    }
}
 