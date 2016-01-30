<div class="box" style="margin-top: 20px">
    <div class="box-title">{{$game->title}}</div>
    <div class="box-content">
        <div class="game-panel">
            @if($game->game_path)

            <script type="text/javascript">
                $(function()) {
                    swfobject.registerObject("game, "9.0.115", "expressInstall.swf");
                }
            </script>
            <object id="game" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{{$game->width}}" height="{{$game->height}}">

                <param name="movie" value="{{URL::to($game->game_path)}}" />
                <!--[if !IE]>-->
                <object type="application/x-shockwave-flash" data="{{URL::to($game->game_path)}}" width="{{$game->width}}" height="{{$game->height}}">
                    <!--<![endif]-->
                    <p></p>
                    <!--[if !IE]>-->
                </object>
                <!--<![endif]-->
            </object>
            @else
                {{$game->iframe_content}}
            @endif
        </div>


    </div>
</div>

<div class="left-column">
    <div class="box">
        <div class="box-content">
            <div class="game-stats clearfix">
                <div class="pull-left">
                    <h4><span>{{$game->countComments()}}</span> Comments</h4>
                </div>

                <div class="pull-right">
                    <a href="javascript:void(0)" onclick="return window.open(
                             'http://www.facebook.com/sharer.php?u={{route('game', ['id' => $game->id])}}'
                             , 'targetWindow', 'toolbar=no,location=no,status=no,scrollbar=yes,resizable=no,width=600,height=400')"
                        ><i style="display: inline-block;width: 15%;text-align: center" class="icon ion-social-facebook"></i> </a>

                    <a href="javascript:void(0)" onclick="return window.open(
                             'http://twitter.com/share?url={{route('game', ['id' => $game->id])}}'
                             , 'targetWindow', 'toolbar=no,location=no,status=no,scrollbar=yes,resizable=no,width=600,height=400')"
                        ><i style="display: inline-block;width: 15%;text-align: center" class="icon ion-social-twitter"></i> </a>

                    <a href="javascript:void(0)" onclick="return window.open(
                             'https://plus.google.com/share?url={{route('game', ['id' => $game->id])}}'
                             , 'targetWindow', 'toolbar=no,location=no,status=no,scrollbar=yes,resizable=no,width=600,height=400')"
                        ><i style="display: inline-block;width: 15%;text-align: center" class="icon ion-social-googleplus"></i></a>


                    <a href="javascript:void(0)" onclick="return window.open(
                             'http://www.linkedin.com/shareArticle?mini=true&url={{route('game', ['id' => $game->id])}}'
                             , 'targetWindow', 'toolbar=no,location=no,status=no,scrollbar=yes,resizable=no,width=600,height=400')"
                        ><i style="display: inline-block;width: 15%;text-align: center" class="icon ion-social-linkedin-outline"></i></a>

                </div>
            </div>

            <div class="post-replies" id="game-post-replies" data-limit="10" data-offset="0" data-type="game" data-type-id="{{$game->id}}">
                @if(Auth::check())

                {{Theme::section('comment.form', ['typeId' => $game->id, 'type' => 'game'])}}
                @endif

                @if($game->countComments() > 10)
                <a href="" class="load-more-comment" data-target="#game-post-replies"><i class="icon ion-more"></i> View more comments <img class="indicator" src="{{Theme::asset()->img('theme/images/loading.gif')}}"/></a>
                @endif
                <div id="game-{{$game->id}}-reply-lists" class="replies-list">

                    @foreach($game->comments->take(10)->reverse() as $comment)

                    {{Theme::section('comment.display', ['comment' => $comment])}}

                    @endforeach
                </div>


            </div>
        </div>
    </div>
</div>

<div class="right-column">
    {{Theme::section('game.profile.widget')}}
    {{Theme::widget()->get('game-profile')}}
</div>