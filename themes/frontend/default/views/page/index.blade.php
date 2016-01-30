@if(count($pages) < 1)
    <div class="box">
        <div class="box-title">{{trans('page.pages')}}</div>
        <div class="box-content">
            <div class="alert alert-info">{{trans('page.no-page')}}</div>
        </div>
    </div>
@endif

@foreach($pages as $page)
    {{Theme::section('page.display', ['page' => $page])}}
@endforeach

{{$pages->links()}}