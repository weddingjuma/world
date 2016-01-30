<?php

Event::listen('inline-post-text', function($post) {

   if (!empty($post->text) and !isEnglish($post->text)) {
       echo Theme::section('autotranslator::link', ['post' => $post]);
   }
});