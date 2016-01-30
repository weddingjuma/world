<?php

namespace App\SetupDatabase;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema;

/**
*System setup installer
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Install
{
    public function database()
    {

        if (!\Schema::hasTable('addons')) {

            \Schema::create('addons', function(Blueprint $table)
            {
                $table->increments('id');
                $table->string('name');
                $table->integer('active')->default(0);
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE addons ADD INDEX `name`(`name`)");
        }catch (\Exception $e){}


        /**
         * settings table
         */
        if (!\Schema::hasTable('configurations')) {

            \Schema::create('configurations', function(Blueprint $table) {
                $table->increments('id');
                $table->string('slug');
                $table->text('value');

                $table->timestamps();
            });
        }

        try{
            //@\DB::statement("ALTER TABLE configurations ADD INDEX `id`(`id`)");
            @\DB::statement("ALTER TABLE configurations ADD INDEX `slug`(`slug`)");
        }catch (\Exception $e){}

        //update some configurations
        app('App\\Repositories\\ConfigurationRepository')->save(['enable-bigpipe' => 0]);
        app('App\\Repositories\\ConfigurationRepository')->save(['ajaxify_frontend' => 0]);
        app('App\\Repositories\\ConfigurationRepository')->save(['enable-realtime-activity' => 0]);
        app('App\\Repositories\\ConfigurationRepository')->save(['allow-files-types' => 'exe,txt,zip,rar,doc,mp3,jpg,png,css,psd,pdf,ppt,pptx,xls,xlsx,html,docx,fla,avi,mp4']);

        //let remove any php file already uploaded to the size
        $basePath = base_path('uploads/files/');

        if (is_dir($basePath)) {
            $fileSystem = app('files');

            $files = $fileSystem->allFiles($basePath);

            foreach($files as $file) {
                $ext = $ext = pathinfo($file, PATHINFO_EXTENSION);

                if (strtolower($ext) == 'exe') {
                    @unlink($file);

                }
            }
        }

        /**
         * Languages table
         */
        if (!\Schema::hasTable('languages')) {
            \Schema::create("languages", function(Blueprint $table) {
               $table->increments('id');
               $table->string('var');
               $table->string("name");
               $table->integer('active')->default(0);
               $table->timestamps();
            });

            \DB::table('languages')->insert([
               'var' => 'en',
                'name' => 'English Language',
                'active' => 1
            ]);
        }

        /**
         * User tables
         */

        if(!\Schema::hasTable('users')) {
            \Schema::create('users', function(Blueprint $table)
            {
                $table->increments('id');
                $table->string('fullname');
                $table->string('username');
                $table->string('email_address');
                $table->string('password');
                $table->string('genre')->default('male');
                $table->text('bio')->nullable();
                $table->text('profile_details')->nullable();
                $table->text('privacy_info')->nullable();
                $table->text('design_info')->nullable();
                $table->text('cover')->nullable();
                $table->string('country')->nullable();
                $table->integer('fully_started')->default(0);
                $table->text('avatar')->nullable();
                $table->string('auth')->default('');
                $table->string('auth_id')->default('');
                $table->integer('verified')->default(0);
                $table->integer('admin')->default(0);
                $table->integer('active')->default(0);
                $table->integer('activated')->default(0);
                $table->string('hash', 300)->default('');
                $table->string('remember_token', 300)->default('');
                $table->integer('last_active_time')->nullable();
                $table->timestamps();
            });
        }


        try{
            @\DB::statement("ALTER TABLE users ADD INDEX `fullname`(`fullname`)");
            @\DB::statement("ALTER TABLE users ADD INDEX `username`(`username`)");
            @\DB::statement("ALTER TABLE users ADD INDEX `email_address`(`email_address`)");
            @\DB::statement("ALTER TABLE users ADD INDEX `genre`(`genre`)");
            @\DB::statement("ALTER TABLE users ADD INDEX `password`(`password`)");
            @\DB::statement("ALTER TABLE users ADD INDEX `country`(`country`)");
            @\DB::statement("ALTER TABLE users ADD INDEX `last_active_time`(`last_active_time`)");
        }catch (\Exception $e){}



        /**custom fields***/
        if (!\Schema::hasTable('custom_fields')) {
            \Schema::create('custom_fields', function(Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->text('description');
                $table->string('field_type')->default('text');
                $table->string('type')->default('profile');
                $table->text('data')->nullable();
                $table->timestamps();
            });
        }

        //help add some fields to the site
        $this->addCustomFields();

        /**Post table**/
        if (!\Schema::hasTable('posts')) {
            \Schema::create('posts', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->integer('to_user_id')->nullable();
                $table->text('text')->nullable();
                $table->text('content_type')->nullable();
                $table->text('type_content')->nullable();
                $table->text('type')->nullable();
                $table->string('type_id')->default('');
                $table->integer('community_id')->nullable();
                $table->integer('page_id')->nullable();
                $table->text('link')->nullable();
                $table->text('tags')->nullable();
                $table->integer('privacy')->default(1);
                $table->integer('shared')->nullable();
                $table->integer('shared_id')->nullable();
                $table->integer('shared_from')->nullable();
                $table->integer('shared_count')->nullable();
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE posts ADD INDEX `user_id`(`user_id`)");
            @\DB::statement("ALTER TABLE posts ADD INDEX `to_user_id`(`to_user_id`)");
            @\DB::statement("ALTER TABLE posts ADD INDEX `content_type`(`content_type`)");
            @\DB::statement("ALTER TABLE posts ADD INDEX `type`(`type`)");
            @\DB::statement("ALTER TABLE posts ADD INDEX `type_id`(`type_id`)");
            @\DB::statement("ALTER TABLE posts ADD INDEX `community_id`(`community_id`)");
            @\DB::statement("ALTER TABLE posts ADD INDEX `page_id`(`page_id`)");
            @\DB::statement("ALTER TABLE posts ADD INDEX `privacy`(`privacy`)");
            @\DB::statement("ALTER TABLE posts ADD INDEX `created_at`(`created_at`)");
            @\DB::statement("ALTER TABLE posts ADD INDEX `updated_at`(`updated_at`)");
        }catch (\Exception $e){}

        /***coments***/
        if(!\Schema::hasTable('comments')) {
            \Schema::create('comments', function(Blueprint $table) {
               $table->increments('id');
                $table->integer('user_id');
                $table->text('text');
                $table->string('type');
                $table->string('type_id');
                $table->text('img_path')->nullable();
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE comments ADD INDEX `user_id`(`user_id`)");
            @\DB::statement("ALTER TABLE comments ADD INDEX `type`(`type`)");
            @\DB::statement("ALTER TABLE comments ADD INDEX `type_id`(`type_id`)");
            @\DB::statement("ALTER TABLE comments ADD INDEX `created_at`(`created_at`)");
        }catch (\Exception $e){}

        /***likes table**/
        if(!\Schema::hasTable('likes')) {
            \Schema::create('likes', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('type');
                $table->integer('type_id');
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE likes ADD INDEX `user_id`(`user_id`)");
            @\DB::statement("ALTER TABLE likes ADD INDEX `type`(`type`)");
            @\DB::statement("ALTER TABLE likes ADD INDEX `type_id`(`type_id`)");
        }catch (\Exception $e){}

        /**connection table**/
        if (!\Schema::hasTable('connections')) {
            \Schema::create('connections', function(Blueprint $table) {
               $table->increments('id');
                $table->integer('user_id');
                $table->integer('to_user_id');
                $table->integer('way')->default(2);
                $table->integer('confirmed')->default(0);
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE connections ADD INDEX `user_id`(`user_id`)");
            @\DB::statement("ALTER TABLE connections ADD INDEX `to_user_id`(`to_user_id`)");
            @\DB::statement("ALTER TABLE connections ADD INDEX `way`(`way`)");
        }catch (\Exception $e){}

        /**Notification table**/
        if (!\Schema::hasTable('notifications')) {
            \Schema::create('notifications', function(Blueprint $table) {
               $table->increments('id');
                $table->integer('user_id');
                $table->integer('to_user_id');
                $table->text('title')->nullable();
                $table->text('content')->nullable();
                $table->text('data')->nullable();
                $table->integer('seen')->default(0);
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE notifications ADD INDEX `user_id`(`user_id`)");
            @\DB::statement("ALTER TABLE notifications ADD INDEX `to_user_id`(`to_user_id`)");
        }catch (\Exception $e){}

        /**Blocked users**/
        if (!\Schema::hasTable('blocked_users')) {
            \Schema::create('blocked_users', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->integer('block_id');
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE blocked_users ADD INDEX `user_id`(`user_id`)");
        }catch (\Exception $e){}


        /**User get notifications table**/
        if (!\Schema::hasTable('notification_receivers')) {
            \Schema::create('notification_receivers', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('type');
                $table->integer('type_id');
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE notification_receivers ADD INDEX `user_id`(`user_id`)");
            @\DB::statement("ALTER TABLE notification_receivers ADD INDEX `type`(`type`)");
            @\DB::statement("ALTER TABLE notification_receivers ADD INDEX `type_id`(`type_id`)");
        }catch (\Exception $e){}


        /**Hashtags table**/
        if (!\Schema::hasTable('hashtags')) {
            \Schema::create('hashtags', function(Blueprint $table) {
                $table->increments('id');
                $table->string('hash');
                $table->integer('count');
                $table->timestamps();
            });
        }

        /**Reports table**/
        if (!\Schema::hasTable('reports')) {
            \Schema::create('reports', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->text('url');
                $table->string('type');
                $table->text('reason');
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE reports ADD INDEX `type`(`type`)");
        }catch (\Exception $e){}

        /**Additional pages table***/
        if (!\Schema::hasTable('additional_pages')) {
            \Schema::create('additional_pages', function(Blueprint $table) {
                $table->increments('id');
                $table->string('slug');
                $table->string('title');
                $table->text('content');
                $table->timestamps();
            });
        }

        $this->addAdditionalPages();

        /**Help system***/
        if (!\Schema::hasTable('helps')) {
            \Schema::create('helps', function(Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('slug');
                $table->text('content');
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE helps ADD INDEX `slug`(`slug`)");
        }catch (\Exception $e){}

        $this->addHelps();

        /***Communities***/
        if (!\Schema::hasTable('communities')) {
                \Schema::create('communities', function(Blueprint $table) {
                    $table->increments('id');
                    $table->integer('user_id');
                    $table->string('title');
                    $table->string('slug');
                    $table->text('description')->nullable();
                    $table->text('info')->nullable();
                    $table->text('moderators')->nullable();
                    $table->integer('privacy')->default(0);
                    $table->integer('can_join')->default(1);
                    $table->integer('can_post')->default(1);
                    $table->integer('can_invite')->default(1);
                    //$table->integer('can_create_category');
                    $table->integer('searchable')->default(1);
                    $table->string('logo')->nullable();
                    $table->timestamps();
                });
        }

        try{
            @\DB::statement("ALTER TABLE communities ADD INDEX `user_id`(`user_id`)");
            @\DB::statement("ALTER TABLE communities ADD INDEX `slug`(`slug`)");
            @\DB::statement("ALTER TABLE communities ADD INDEX `created_at`(`created_at`)");
            @\DB::statement("ALTER TABLE communities ADD INDEX `privacy`(`privacy`)");
            @\DB::statement("ALTER TABLE communities ADD INDEX `searchable`(`searchable`)");
        }catch (\Exception $e){}

        //communities members
        if (!\Schema::hasTable('community_members')) {
            \Schema::create('community_members', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('community_id');
                $table->integer('user_id');
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE community_members ADD INDEX `community_id`(`community_id`)");
        }catch (\Exception $e){}

        //community categories
        if (!\Schema::hasTable('community_categories')) {
            \Schema::create('community_categories', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('community_id');
                $table->string('title');
                $table->string('slug');
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE community_categories ADD INDEX `community_id`(`community_id`)");
            @\DB::statement("ALTER TABLE community_categories ADD INDEX `slug`(`slug`)");
        }catch (\Exception $e){}

        //invited members
        if (!\Schema::hasTable('invited_members')) {
            \Schema::create('invited_members', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->integer('type_id');
                $table->string('type');
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE invited_members ADD INDEX `user_id`(`user_id`)");
            @\DB::statement("ALTER TABLE invited_members ADD INDEX `type_id`(`type_id`)");
            @\DB::statement("ALTER TABLE invited_members ADD INDEX `type`(`type`)");
        }catch (\Exception $e){}

        //photos
        if (!\Schema::hasTable('photos')) {
                \schema::create('photos', function(Blueprint $table) {
                    $table->increments('id');
                    $table->integer('user_id');
                    $table->string('slug');
                    $table->string('path');
                    $table->timestamps();
                });
        }

        try{
            @\DB::statement("ALTER TABLE photos ADD INDEX `user_id`(`user_id`)");
            @\DB::statement("ALTER TABLE photos ADD INDEX `slug`(`slug`)");
            @\DB::statement("ALTER TABLE photos ADD INDEX `path`(`path`)");
        }catch (\Exception $e){}

        if (!\Schema::hasTable('photo_albums')) {
            \Schema::create('photo_albums', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('title');
                $table->string('slug');
                $table->string('default_photo')->nullable();
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE photo_albums ADD INDEX `user_id`(`user_id`)");
            @\DB::statement("ALTER TABLE photo_albums ADD INDEX `slug`(`slug`)");
        }catch (\Exception $e){}


        //pages
        if (!\Schema::hasTable('pages')) {
            \Schema::create('pages', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->integer('category_id');
                $table->string('title');
                $table->string('slug');
                $table->string('description')->nullable();
                $table->text('info')->nullable();
                $table->string('website')->nullable();
                $table->string('logo')->nullable();
                $table->string('cover')->nullable();
                $table->integer('verified')->default(0);
                //$table->integer('can_post')->default(0);
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE pages ADD INDEX `user_id`(`user_id`)");
            @\DB::statement("ALTER TABLE pages ADD INDEX `slug`(`slug`)");
            @\DB::statement("ALTER TABLE pages ADD INDEX `title`(`title`)");
            @\DB::statement("ALTER TABLE pages ADD INDEX `description`(`description`)");
        }catch (\Exception $e){}

        if (!\Schema::hasTable('page_categories')) {
            \Schema::create('page_categories', function(Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('slug');
                $table->string('description');
                $table->timestamps();
            });
        }

        $this->addPagesCategories();


        /***Games**/
        if(!\Schema::hasTable('games')) {
            \Schema::create('games', function(Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('slug');
                $table->integer('user_id');
                $table->integer('category');
                $table->string('description')->nullable();
                $table->string('logo')->nullable();
                $table->text('iframe_content')->nullable();
                $table->integer('approved')->default(1);
                $table->integer('verified')->default(0);
                $table->text('info')->nullable();
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE games ADD INDEX `user_id`(`user_id`)");
            @\DB::statement("ALTER TABLE games ADD INDEX `slug`(`slug`)");
            @\DB::statement("ALTER TABLE games ADD INDEX `title`(`title`)");
            @\DB::statement("ALTER TABLE games ADD INDEX `description`(`description`)");
            @\DB::statement("ALTER TABLE games ADD INDEX `created_at`(`created_at`)");
        }catch (\Exception $e){}

        if (!\Schema::hasTable('game_categories')) {
            \Schema::create('game_categories', function(Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('slug');
                $table->string('description');
                $table->timestamps();
            });
        }

        $this->addGameCategories();



        //message table
        if(!\Schema::hasTable('messages')) {
            \Schema::create('messages', function(Blueprint $table) {
                $table->increments('id');
                $table->text('text');
                $table->integer('sender');
                $table->integer('receiver');
                $table->string('image')->nullable();
                $table->integer('conversation_id');
                $table->integer('sender_status')->default(0);
                $table->integer('receiver_status')->default(0);
                $table->integer('seen')->default(0);
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE messages ADD INDEX `sender`(`sender`)");
            @\DB::statement("ALTER TABLE message ADD INDEX `receiver`(`receiver`)");
            @\DB::statement("ALTER TABLE messages ADD INDEX `conversation_id`(`conversation_id`)");
            @\DB::statement("ALTER TABLE messages ADD INDEX `sender_status`(`sender_status`)");
            @\DB::statement("ALTER TABLE messages ADD INDEX `receiver_status`(`receiver_status`)");
            @\DB::statement("ALTER TABLE messages ADD INDEX `seen`(`seen`)");
            @\DB::statement("ALTER TABLE messages ADD INDEX `created_at`(`created_at`)");
        }catch (\Exception $e){}

        if (!\Schema::hasTable('message_conversation')) {
            \Schema::create('message_conversation', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user1');
                $table->integer('user2');
                $table->timestamps();
            });
        }

        //newsletter
        if (!\Schema::hasTable('newsletters')) {
            \Schema::create('newsletters', function(Blueprint $table) {
                $table->increments('id');
                $table->string('subject');
                $table->text('content');
                $table->string('to');
                $table->timestamps();
            });
        }

        //v1.3
        if (!\Schema::hasTable('post_hide')) {
            \Schema::create('post_hide', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('post_id');
                $table->integer('user_id');
                $table->timestamps();
            });
        }

        try{
            @\DB::statement("ALTER TABLE post_hide ADD INDEX `user_id`(`user_id`)");
            @\DB::statement("ALTER TABLE post_hide ADD INDEX `post_id`(`post_id`)");

        }catch (\Exception $e){}

        //v1.4
        if (!\Schema::hasTable('page_admins')) {
            \Schema::create('page_admins', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('page_id');
                $table->integer('user_id');
                $table->string('type');
                $table->integer('approved')->default(0);
                $table->timestamps();
            });
        }

        //v3.0
        if (!\Schema::hasTable('must_avoid_users')) {
            \Schema::create('must_avoid_users', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->timestamps();
            });
        }


        try{
            @\DB::statement("ALTER TABLE must_avoid_users ADD INDEX `user_id`(`user_id`)");
        }catch (\Exception $e){}


        //run updates for database
        $this->runUpdate();

    }

    /**
     * Add custum fields
     */
    public function addCustomFields()
    {
        if (\Config::get('system.installed')) return true;
        $customField = app('App\\Repositories\\CustomFieldRepository');

        //for profile
        $customField->add([
            'name' => 'Favourite Musics',
            'type' => 'profile',
            'field_type' => 'textarea',
            'description' => 'What\'s your favourite musics, seperate them with comma(,)'
        ]);
        $customField->add([
            'name' => 'Favourite Movies',
            'type' => 'profile',
            'field_type' => 'textarea',
            'description' => 'What\'s your favourite movies, seperate them with comma(,)'
        ]);

        $customField->add([
            'name' => 'Favourite Quotes',
            'type' => 'profile',
            'field_type' => 'textarea',
            'description' => 'What\'s your favourite quotes'
        ]);

        $customField->add([
            'name' => 'Personal Skills',
            'type' => 'profile',
            'field_type' => 'textarea',
            'description' => 'What are your personal skills',
        ]);

        $customField->add([
            'name' => 'Religious Views',
            'type' => 'profile',
            'field_type' => 'selection',
            'description' => 'What\'s your religion',
            'options' => [
                'Muslim',
                'Christian',
                'All',
                'None'
            ]
        ]);

        $customField->add([
            'name' => 'Interested In',
            'type' => 'profile',
            'field_type' => 'selection',
            'description' => 'What\'s your interest',
            'options' => [
                'Men',
                'Women',
                'Women and Men'
            ]
        ]);

        //for pages custom fields


    }

    public function addAdditionalPages()
    {
        if (\Config::get('system.installed')) return true;
        $pageRepository = app('App\\Repositories\\AdditionalPageRepository');

        $pageRepository->add('about-us', 'global.about-us', 'Your about us content goes here.....');
        $pageRepository->add('terms-and-condition', 'global.terms-and-condition', 'Your terms and condition content goes here.....');
        $pageRepository->add('privacy-policy', 'global.privacy-policy', 'Your private policy content goes here.....');
        $pageRepository->add('disclaimer', 'global.disclaimer', 'Your Disclaimer content goes here.....');
    }

    public function addHelps()
    {
        if (\Config::get('system.installed')) return true;
        $helpRepository = app('App\\Repositories\\HelpRepository');
        $helpRepository->add([
            'title' => 'How to Make Connections',
            'content' => 'How can you make connections is very easy just follow or if you know the person request for friend request'
        ]);

        $helpRepository->add([
            'title' => 'How to post',
            'content' => '
            You can post to your connections through our post editor. With it you can share images, videos, location,  e.t.c.
            Also if you are with one of your friends you can post with them.
            '
        ]);

        $helpRepository->add([
            'title' => 'How To design Your Profile',
            'content' => '
                Following this process to design your profile
                1. Go to your account settings
                2. On the right menu click design your page link
                3. From there follow other instructions to complete the deisng of your page
            '
        ]);
    }

    public function addPagesCategories()
    {
        if (\Config::get('system.installed')) return true;
        $category = app('App\\Repositories\\PageCategoryRepository');

        $category->add([
            'title' => 'Brand or Product',
        ]);
        $category->add([
            'title' => 'Company, Organization or Institution',
        ]);

        $category->add([
            'title' => 'Artist, Public figure ',
        ]);

        $category->add([
            'title' => 'Brand or Product',
        ]);

        $category->add([
            'title' => 'Entertainment',
        ]);

        $category->add([
            'title' => 'Cause or Community',
        ]);
    }

    public function addGameCategories()
    {
        if (\Config::get('system.installed')) return true;
        $category = app('App\\Repositories\\GameCategoryRepository');

        $category->add([
            'title' => 'Arcade & Action',
        ]);

        $category->add([
            'title' => 'Board & Card',
        ]);

        $category->add([
            'title' => 'Casino',
        ]);

        $category->add([
            'title' => 'Puzzle',
        ]);

        $category->add([
            'title' => 'Strategy & RGP',
        ]);
    }

    public function runUpdate()
    {
        $this->version11();

        $this->version21();

    }

    public function version11()
    {
        if (!\Schema::hasColumn('posts', 'edited')) {
            \Schema::table('posts', function($table) {
                $table->integer('edited')->default(0);
            });
        }

        if (!\Schema::hasColumn('communities', 'moderators')) {
            \Schema::table('communities', function($table) {
                $table->text('moderators')->nullable();
            });
        }

        if (!\Schema::hasColumn('users', 'city')) {
            \Schema::table('users', function($table) {
               $table->text('city')->nullable();
            });
        }

        if (!\Schema::hasColumn('users', 'original_cover')) {
            \Schema::table('users', function($table) {
                $table->text('original_cover')->nullable();
            });
        }

        if (!\Schema::hasColumn('pages', 'original_cover')) {
            \Schema::table('pages', function($table) {
                $table->text('original_cover')->nullable();
            });
        }

        try{
            @\DB::statement("ALTER TABLE users ADD INDEX `city`(`city`)");
        }catch (\Exception $e){}


        //version 1.4

        if (!\Schema::hasColumn('photos', 'page_id')) {
            \Schema::table('photos', function($table) {
                $table->integer('page_id')->default(0);
            });
        }

        if (!\Schema::hasColumn('photos', 'privacy')) {
            \Schema::table('photos', function($table) {
               $table->integer('privacy')->default(0);
            });
        }

        if (!\Schema::hasColumn('photos', 'post_id')) {
            \Schema::table('photos', function($table) {
                $table->integer('post_id')->default(0);
            });
        }

        try{
            @\DB::statement("ALTER TABLE photos ADD INDEX `page_id`(`page_id`)");
            @\DB::statement("ALTER TABLE photos ADD INDEX `post_id`(`post_id`)");
            @\DB::statement("ALTER TABLE photos ADD INDEX `privacy`(`privacy`)");
        }catch (\Exception $e){}


        if (!\Schema::hasColumn('page_admins', 'type')) {
            \Schema::table('page_admins', function($table) {
                $table->string('type');
            });
        }
    }

    public function version21()
    {
        if (!\Schema::hasColumn('games', 'game_path')) {
            \Schema::table('games', function($table) {
                $table->text('game_path');
            });
        }

        if (!\Schema::hasColumn('games', 'width')) {
            \Schema::table('games', function($table) {
                $table->string('width')->default('100%');
            });
        }

        if (!\Schema::hasColumn('games', 'height')) {
            \Schema::table('games', function($table) {
                $table->string('height')->default('450');
            });
        }

        if (!\Schema::hasColumn('posts', 'video_path')) {
            \Schema::table('posts', function($table) {
                $table->text('video_path');
            });
        }

        if (!\Schema::hasColumn('users', 'online_status')) {
            \Schema::table('users', function($table) {
                $table->integer('online_status')->default(1);
            });
        }

        try{
            @\DB::statement("ALTER TABLE users ADD INDEX `online_status`(`online_status`)");

        }catch (\Exception $e){}

        if (!\Schema::hasColumn('users', 'banned')) {
            \Schema::table('users', function($table) {
                $table->integer('banned')->default(0);
            });
        }



        //version 3.2.1
        if (!\Schema::hasColumn('posts', 'auto_like_id')) {
            \Schema::table('posts', function($table) {
                $table->string('auto_like_id');
            });
        }

        $this->version40();
    }

    public function version40()
    {
        if (!\Schema::hasColumn('users', 'birth_day')) {
            \Schema::table('users', function($table) {
                $table->integer('birth_day')->default(0);
            });
        }

        if (!\Schema::hasColumn('users', 'birth_month')) {
            \Schema::table('users', function($table) {
                $table->integer('birth_month')->default(0);
            });
        }

        if (!\Schema::hasColumn('users', 'birth_year')) {
            \Schema::table('users', function($table) {
                $table->integer('birth_year')->default(0);
            });
        }

        if (!\Schema::hasColumn('posts', 'file_path')) {
            \Schema::table('posts', function($table) {
                $table->text('file_path');
            });
        }



        if (!\Schema::hasColumn('posts', 'file_path_name')) {
            \Schema::table('posts', function($table) {
                $table->text('file_path_name');
            });
        }
    }
}