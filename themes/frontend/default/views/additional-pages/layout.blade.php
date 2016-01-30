<div class="container page-content">
    <div class="left-column">
        <div class="box">
            <div class="box-title">{{$title}}</div>
            <div class="box-content">
                {{$content}}
            </div>
        </div>
    </div>

    <div class="right-column">
        <div class="box nav-box">
            <div class="box-title">{{trans('global.menu')}}</div>
            <ul class="nav">
                <li><a href="{{URL::route('terms-and-condition')}}">{{trans('home.terms-condition')}}</a> </li>
                <li><a href="{{URL::route('privacy')}}">{{trans('home.privacy-policy')}}</a> </li>
                <li><a href="{{URL::route('disclaimer')}}">{{trans('home.disclaimer')}}</a> </li>
                <li><a href="{{URL::route('about-us')}}">{{trans('home.about-us')}}</a> </li>
                <li><a href="{{URL::route('developers')}}">{{trans('home.developers')}}</a> </li>
                <!--<li><a href="">Contact</a> </li>-->
            </ul>
        </div>
    </div>
</div>