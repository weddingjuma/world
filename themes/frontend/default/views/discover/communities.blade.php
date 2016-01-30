@if(!count($communities))
    <div class="box">
        <div class="box-title">Communities results</div>
        <div class="box-content">
            <div class="alert alert-info">No Community is found  ...try again later</div>
        </div>
    </div>
@endif

<div class="communities-container">
    @foreach($communities as $community)
        {{Theme::section('community.display', ['community' => $community])}}
    @endforeach
</div>

{{$communities->links()}}
