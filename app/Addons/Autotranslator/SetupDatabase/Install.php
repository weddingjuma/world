<?php

namespace App\Addons\Autotranslator\SetupDatabase;

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
        if (!\Schema::hasTable('translated_text')) {
            \Schema::create('translated_text', function(Blueprint $table) {
                $table->increments('id');
                $table->string('hash_id');
                $table->longText('result');
                $table->timestamps();
            });
        }
    }
}