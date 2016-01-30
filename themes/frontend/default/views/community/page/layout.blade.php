<div class="container page-content clearfix">
    <div class="small-left-column">
        {{Theme::section('community.page.left')}}
    </div>
    <div class="large-right-column">
        @if (!$community->present()->isMember() and $community->present()->canJoin())
            <div class="community-join-panel box">
                <a class="btn btn-danger btn-sm join-community pull-right" data-id="{{$community->id}}" href="">{{trans('community.join')}}</a>
            </div>
        @endif
        {{$content}}

        {{Theme::section('community.page.history')}}
    </div>
</div>