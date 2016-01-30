@if(count($games) < 1)
<div class="box">
    <div class="box-title">{{trans('game.games')}}</div>
    <div class="box-content">
        <div class="alert alert-info">{{trans('game.no-game')}}</div>
    </div>
</div>
@endif

@foreach($games as $game)
{{Theme::section('game.display', ['game' => $game])}}
@endforeach

{{$games->links()}}