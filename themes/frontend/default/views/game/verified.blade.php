@if($game->verified == 1)
<img src="{{Theme::asset()->img('theme/images/verified.png')}}" title="{{$game->title}} is verified"/>
@endif