@if(Auth::check())
    <?php $friendsLikes = $page->friendsLiked()?>
    @if(count($friendsLikes) > 0)
    <div class="box">
        <div class="box-title">{{trans('user.friend-like-this')}}</div>
        <div class="box-content">
            <div class="user-tile-list">

                @foreach($friendsLikes as $like)

                    @if($like->user)
                        <a data-ajaxify="true" href="{{$like->user->present()->url()}}"><img src="{{$like->user->present()->getAvatar(100)}}"/> </a>
                    @endif

                @endforeach

            </div>
        </div>
    </div>
    @endif

@endif

@if(count($page->likes) > 0)
    <div class="box">
        <div class="box-title">{{trans('user.people-like-this')}}</div>
        <div class="box-content">
            <div class="user-tile-list">
                @foreach($page->likes->take(12) as $like)

                    @if($like->user)
                            <a data-ajaxify="true" href="{{$like->user->present()->url()}}"><img src="{{$like->user->present()->getAvatar(100)}}"/> </a>
                    @endif

                @endforeach
            </div>
        </div>
    </div>
@endif