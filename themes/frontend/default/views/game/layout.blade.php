<div class="container page-content">




    <div class="left-column">
        <div class="content-topography game-topography">
            <h2 class="title">{{trans('game.games')}}</h2>


            <ul class="nav menu">
                @if(Config::get('game-create-allowed'))
                <li ><a data-ajaxify="true" href="{{URL::route('games-create')}}">{{trans('game.add-a-game')}}</a> </li>
                @endif

                <li ><a data-ajaxify="true" href="{{URL::route('my-games')}}">{{trans('game.my-games')}}</a> </li>

            </ul>

        </div>
        {{$content}}
    </div>
    <div class="right-column">

        <ul class="nav user-action-buttons">
            @if(Config::get('game-create-allowed'))
                <li><a href="{{URL::route('games-create')}}" data-ajaxify="true"><i class="icon ion-ios7-personadd-outline"></i> <span>{{trans('game.add-a-game')}}</span></a> </li>
            @endif

            <li><a href="{{URL::route('my-games')}}" data-ajaxify="true"><i class="icon ion-disc"></i> <span>{{trans('game.my-games')}}</span></a> </li>
            <li><a href="{{URL::route('games')}}" data-ajaxify="true"><i class="icon ion-disc"></i> <span>{{trans('game.find-games')}}</span></a> </li>
            <!--<li><a href="{{URL::route('discover-communities')}}" data-ajaxify="true"><i class="icon ion-disc"></i> <span>Discover Communities</span></a> </li>-->
        </ul>

        <div class="box nav-box">
            <div class="box-title">{{trans('game.filter-category')}}</div>
            <ul class="nav">
                @foreach(app('App\\Repositories\\GameCategoryRepository')->listAll() as $category)
                    <li><a href="{{URL::route('games')}}?category={{$category->id}}">{{$category->title}}</a> </li>
                @endforeach
            </ul>
        </div>


        {{Theme::widget()->get('user-pages')}}
    </div>
</div>