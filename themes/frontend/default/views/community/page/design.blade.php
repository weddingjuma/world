<div class="box">
    <div class="box-title">
        {{trans('community.design')}}
    </div>

    <div class="box-content">
        @if(!empty($message))
            <div class="alert alert-info">{{$message}}</div>
        @endif
        {{Theme::section('page-design.form', ['user' => $user, 'type' => 'community-'.$community->id])}}
    </div>
</div>