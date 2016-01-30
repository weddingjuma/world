<div class="general-listing media">

    <div class="media-object pull-left">
            <a data-ajaxify="true" class=" " href="{{$community->present()->url()}}"><img src="{{$community->present()->getLogo()}}"/> </a>
    </div>


    <div class="media-body">
        <h3 class="media-heading">
            <a data-ajaxify="true" class="cover" href="{{$community->present()->url()}}">{{Str::limit($community->title, 30)}} </a>

        </h3>
        {{Theme::extend('community-display', ['community' => $community])}}
        <a data-ajaxify="true" href="{{$community->present()->url('members')}}">{{count($community->members) + 1}} {{trans('community.members')}}</a> |
        <a  data-ajaxify="true" href="{{$community->present()->url()}}">{{count($community->posts)}} {{trans('post.posts')}}</a>

    </div>
</div>