<div class="container page-content">



    <div class="left-column">
        <div class="content-topography community-topography">
            <h2 class="title">{{trans('community.communities')}}</h2>
            <p class="description">{{trans('community.community-info')}}</p>

            <ul class="nav menu">
                <li ><a data-ajaxify="true" href="{{URL::route('community-create')}}">{{trans('community.create')}}</a> </li>
                <li ><a data-ajaxify="true" href="{{URL::route('discover-communities')}}">{{trans('community.discover')}}</a> </li>

            </ul>

        </div>

        {{$content}}
    </div>
    <div class="right-column">

        <ul class="nav user-action-buttons">
            <li><a href="{{URL::route('community-create')}}" data-ajaxify="true"><i class="icon ion-ios7-personadd-outline"></i> <span>{{trans('community.create')}}</span></a> </li>
            <li><a href="{{URL::route('communities')}}" data-ajaxify="true"><i class="icon ion-disc"></i> <span>{{trans('community.my-communities')}}</span></a> </li>
            <li><a href="{{URL::route('communities-joined')}}" data-ajaxify="true"><i class="icon ion-ios7-people-outline"></i> <span>{{trans('community.joined')}}</span></a> </li>
            <!--<li><a href="{{URL::route('discover-communities')}}" data-ajaxify="true"><i class="icon ion-disc"></i> <span>Discover Communities</span></a> </li>-->
        </ul>

        {{Theme::widget()->get('user-community')}}
    </div>
</div>