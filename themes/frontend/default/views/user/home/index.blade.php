<div class="container page-content clearfix">

        <div class="left-column">
            {{Theme::extend('timeline-before-post-editor')}}

            {{Theme::section('post.editor.main')}}

            {{Theme::extend('timeline-after-post-editor')}}
            <?php Theme::widget()->add('user.home.feeds', ['user-feeds'] )?>
            {{Theme::widget()->get('user-feeds')}}
        </div>

        <div class="right-column">
            {{Theme::section('user.side-info')}}
            {{Theme::widget()->get('user-home')}}
        </div>
    </div>