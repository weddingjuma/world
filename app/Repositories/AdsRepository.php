<?php

namespace App\Repositories;
use Illuminate\Filesystem\Filesystem;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class AdsRepository
{
    public function __construct(Filesystem $filesystem)
    {
        $this->file = $filesystem;
    }

    /**
     * Method to save
     *
     * @param array $val
     * @return bool
     */
    public function save($val)
    {
        //if (\Auth::user()->id != 1) return false;
        $expected = [
            'header',
            'side'
        ];

        /**
         * @var $header
         * @var $side
         */
        extract(array_merge($expected, $val));

        $dir  = storage_path().'/ads/';

        if (!$this->file->isDirectory($dir)) $this->file->makeDirectory($dir);

        $headerPath = $dir.'header.php';
        $sidePath = $dir.'side.php';

        $this->file->put($headerPath, $header);
        $this->file->put($sidePath, $side);

        return true;
    }

    public function getHeader()
    {
        return $this->file->get(storage_path().'/ads/header.php');
    }

    public function getSide()
    {
        return $this->file->get(storage_path().'/ads/side.php');
    }
}