<?php

namespace App\Addons\Customwidget\SetupDatabase;

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
        if (!\Schema::hasTable('custom_widgets')) {
            \Schema::create('custom_widgets', function(Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->text('content');
                $table->integer('status')->default(1);
                $table->string('page');
                $table->timestamps();
            });
        }



    }
}