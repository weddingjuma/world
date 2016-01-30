@if($user->verified == 1)
    <img src="{{Theme::asset()->img('theme/images/verified.png')}}" title="{{$user->fullname}} {{trans('global.is-verified')}}"/>
@endif

@if ($user->present()->isOnline())
    <?php $status = $user->present()->isOnline()?>
    @if($status == 1)
        <i title="{{$user->fullname}} {{trans('user.is-online')}}" class="icon ion-record" style="color: #26C281;font-size: 11px"></i>
    @else
        <i title="{{$user->fullname}} {{trans('message.is-busy')}}" class="icon ion-record" style="color: #E38826;font-size: 11px"></i>
    @endif

@endif