@if($page->verified == 1)
<img src="{{Theme::asset()->img('theme/images/verified.png')}}" title="{{$page->title}} {{trans('global.is-verified')}}"/>
@endif