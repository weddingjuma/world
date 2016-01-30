@if(!count($pages))
<div class="box">
    <div class="box-title">{{trans('search.page-results')}}</div>
    <div class="box-content">
        <div class="alert alert-danger">{{trans('search.no-page', ['term' => $searchRepository->term])}}</div>
    </div>
</div>
@endif

<div class="communities-container">
    @foreach($pages as $page)
    {{Theme::section('page.display', ['page' => $page])}}
    @endforeach
</div>

{{$pages->appends(['term' => Input::get('term')])->links()}}
