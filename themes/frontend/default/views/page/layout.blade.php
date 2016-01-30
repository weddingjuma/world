<div class="container page-content">



    <div class="left-column">
        <div class="content-topography page-topography">
            <h2 class="title">{{trans('page.pages')}}</h2>


            <ul class="nav menu">
                <li ><a data-ajaxify="true" href="{{URL::route('pages-create')}}">{{trans('page.create-a-page')}}</a> </li>
                <li ><a data-ajaxify="true" href="{{URL::route('my-pages')}}">{{trans('page.my-pages')}}</a> </li>

            </ul>

        </div>
        {{$content}}
    </div>
    <div class="right-column">

        <ul class="nav user-action-buttons">
            <li><a href="{{URL::route('pages-create')}}" data-ajaxify="true"><i class="icon ion-ios7-personadd-outline"></i> <span>{{trans('page.create-a-page')}}</span></a> </li>
            <li><a href="{{URL::route('my-pages')}}" data-ajaxify="true"><i class="icon ion-disc"></i> <span>{{trans('page.my-pages')}}</span></a> </li>

            <!--<li><a href="{{URL::route('discover-communities')}}" data-ajaxify="true"><i class="icon ion-disc"></i> <span>Discover Communities</span></a> </li>-->
        </ul>

        <div class="box nav-box">
            <div class="box-title">{{trans('page.filter-category')}}</div>
            <ul class="nav">
                @foreach(app('App\\Repositories\\PageCategoryRepository')->listAll() as $category)
                    <li><a href="{{URL::route('pages')}}?category={{$category->id}}">{{$category->title}}</a> </li>
                @endforeach
            </ul>
        </div>


        {{Theme::widget()->get('user-pages')}}
    </div>
</div>