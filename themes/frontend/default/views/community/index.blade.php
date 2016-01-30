@if(!count($communities))
    <div class="box">
        <div class="box-title">{{trans('community.my-communities')}}</div>
        <div class="box-content">
            <div class="alert alert-info">{{trans('community.no-community')}}  <a href="{{URL::route('community-create')}}" class="alert-link">{{trans('community.create')}}</a> </div>
        </div>
    </div>
@endif

<div class="communities-container">
    @foreach($communities as $community)
        {{Theme::section('community.display', ['community' => $community])}}
    @endforeach
</div>

{{$communities->links()}}