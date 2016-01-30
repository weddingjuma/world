<?php

namespace App\Addons\Urlshortener\SetupDatabase;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class Install
{
    public function database()
    {
        if (!\Schema::hasTable('shortened_urls')) {
            \Schema::create('shortened_urls', function(Blueprint $table) {
                $table->increments('id');
                $table->string('hash');
                $table->longText('link');
                $table->integer('click_count');
                $table->timestamps();
            });
        }
    }
}