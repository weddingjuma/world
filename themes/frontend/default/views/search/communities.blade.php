@if(!count($communities))
    <div class="box">
        <div class="box-title">{{trans('search.community-results')}}</div>
        <div class="box-content">
            <div class="alert alert-danger">
                {{trans('search.no-community', ['term' => $searchRepository->term])}}
               </div>
        </div>
    </div>
@endif

<div class="communities-container">
    @foreach($communities as $community)
        {{Theme::section('community.display', ['community' => $community])}}
    @endforeach
</div>

{{$communities->appends(['term' => Input::get('term')])->links()}}
