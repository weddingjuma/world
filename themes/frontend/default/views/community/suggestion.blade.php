<?php $communities = app('App\\Repositories\\CommunityRepository')->suggest(2)?>

@if(count($communities))

    <div class="box">
        <div class="box-title">{{trans('community.you-may-join')}}</div>
        <div class="box-content">

            @foreach($communities as $community)

                <div class=" user media media">
                      <div class="media-object pull-left">
                           <a   href="{{$community->present()->url()}}" data-ajaxify="true"><img src="{{$community->present()->getlogo()}}"/></a>
                      </div>
                      <div class="media-body">
                          <h5 class="media-heading">{{$community->title}} </h5>
                          <p>{{$community->countMembers()}} {{trans('community.members')}}</p>
                          <p>{{$community->countPosts()}} {{trans('post.posts')}}</p>
                      </div>
                </div>

            @endforeach

        </div>
    </div>
@endif