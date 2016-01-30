<div class="container page-content">
    <div class="left-column">
        {{$content}}
    </div>

    <div class="right-column">
        {{Theme::section('user.side-info')}}
        <div class="box nav-box">
                        <div class="box-title">{{trans('user.suggestions')}}</div>
                        <ul class="nav">
                            <li><a data-ajaxify="true" href="{{URL::route('suggestion', ['type' => 'people'])}}">{{trans('user.people-you-know')}}</a> </li>

                        </ul>
                </div>
        {{Theme::widget()->get('user-suggestion')}}
    </div>
</div>