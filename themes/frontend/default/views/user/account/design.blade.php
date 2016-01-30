<div class="box">
    <div class="box-title">
        {{trans('user.design-your-page')}}
    </div>

    <div class="box-content">
        {{Theme::section('page-design.form', ['user' => $user, 'type' => 'profile'])}}
    </div>
</div>