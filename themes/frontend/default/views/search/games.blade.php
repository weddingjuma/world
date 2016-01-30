@if(!count($games))
<div class="box">
    <div class="box-title">{{trans('search.games-results')}}</div>
    <div class="box-content">
        <div class="alert alert-danger">
            {{trans('search.no-games', ['term' => $searchRepository->term])}}
            </div>
    </div>
</div>
@endif

<div class="communities-container">
    @foreach($games as $game)
    {{Theme::section('game.display', ['game' => $game])}}
    @endforeach
</div>

{{$games->appends(['term' => Input::get('term')])->links()}}
