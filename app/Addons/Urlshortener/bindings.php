<?php

Event::listen('post.add', function($post) {

   if (!empty($post->text)) {
       $text = app('App\\Addons\\Urlshortener\\Classes\\ShortenedRepository')->process($post->text);
       $post->text = $text;
       $post->save();
   }
});

Event::listen('post.edit', function($post) {

    if (!empty($post->text)) {
        $text = app('App\\Addons\\Urlshortener\\Classes\\ShortenedRepository')->process($post->text);
        $post->text = $text;
        $post->save();
    }
});

Event::listen('message.send', function($message) {

    if (!empty($message->text)) {
        $text = app('App\\Addons\\Urlshortener\\Classes\\ShortenedRepository')->process($message->text);
        $message->text = $text;
        $message->save();
    }
});

Event::listen('comment.add', function($text, $userid, $type, $typeId, $comment) {
   if (!empty($text)) {
       $text = app('App\\Addons\\Urlshortener\\Classes\\ShortenedRepository')->process($text);
       $comment->text = $text;
       $comment->save();
   }
});