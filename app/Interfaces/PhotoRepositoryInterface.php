<?php

namespace App\Interfaces;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
interface PhotoRepositoryInterface
{
    public function upload($file, $settings = []);

    public function add($image, $userid, $slug);

    public function delete($path);
}