<?php

namespace App\Addons\Custompage\SetupDatabase;

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
        if (!\Schema::hasTable('custom_pages')) {
            \Schema::create('custom_pages', function(Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->text('content');
                $table->integer('privacy')->default(0);
                $table->integer('show_comments')->default(0);
                $table->integer('show_likes')->default(0);
                $table->string('slug');
                $table->integer('show_menu')->default(1);
                $table->integer('active')->default(1);
                $table->text('keywords');
                $table->text('description');
                $table->text('tags');
                $table->timestamps();
            });
        }


    }
}