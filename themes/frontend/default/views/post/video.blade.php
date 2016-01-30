<html>
    <head>
        <link href="{{Theme::asset()->get('theme/css/video-js.min.css')}}" type="text/css" rel="stylesheet"/>
        <script type="text/javascript" src="{{Theme::asset()->get('theme/js/video.js')}}"></script>

        <script>
            videojs.options.flash.swf = "{{Theme::asset()->img('theme/js/video-js.swf')}}";
        </script>
    </head>
    <body>
        <video id="p" class="video-js vjs-default-skin vjs-big-play-centered"
               controls preload="auto" width="100%" height="300"

               data-setup='{"example_option":true}'>
            <?php

                $link = URL::to($path);
                $CDNRepository = app('App\\Repositories\\CDNRepository');
                if ($CDNRepository->has($path)) {
                    $link = $CDNRepository->getLink($path);
                }

            ?>
            <source src="{{$link}}" type='video/mp4' />

            <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
        </video>
    </body>
</html>