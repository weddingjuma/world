<div class="box">
    <div class="box-title">
        {{trans('page.design-page')}}
    </div>

    <div class="box-content">
        @if(!empty($message))
            <div class="alert alert-info">{{$message}}</div>
        @endif
        {{Theme::section('page-design.form', ['user' => $page->user, 'type' => 'page-'.$page->id])}}
    </div>
</div>