<div class="container page-content clearfix">

    <div class="left-column">
        <div class="box">
            <div class="box-title">{{trans('onlinemembers::global.online-members')}}</div>
            <div class="box-content">
                <ul class="nav nav-tabs">
                    <li class="{{($gender == 'all') ? 'active' : null}}"><a href="{{URL::route('online-members')}}?gender=all">{{trans('onlinemembers::global.all')}}</a></li>
                    <li class="{{($gender == 'male') ? 'active' : null}}"><a href="{{URL::route('online-members')}}?gender=male">{{trans('onlinemembers::global.men')}}</a></li>
                    <li class="{{($gender == 'female') ? 'active' : null}}"><a href="{{URL::route('online-members')}}?gender=female">{{trans('onlinemembers::global.women')}}</a></li>
                </ul>

                @if (!count($users))
                <div class="alert alert-danger">{{trans('onlinemembers::global.no-member')}}</div>
                @endif
                @foreach($users as $user)
                {{Theme::section('user.display', ['user' => $user])}}
                @endforeach

                {{$users->appends(['gender' => Input::get('gender')])->links()}}
            </div>
        </div>
    </div>

    <div class="right-column">


        {{Theme::widget()->get('user-search')}}
    </div>
</div>