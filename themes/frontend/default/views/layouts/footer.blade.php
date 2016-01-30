<div id="footer">
    <div class="container">
        <ul class="nav">
            <li><a href="{{URL::route('terms-and-condition')}}">{{trans('home.terms-condition')}}</a> </li>
            <li><a href="{{URL::route('privacy')}}">{{trans('home.privacy-policy')}}</a> </li>
            <li><a href="{{URL::route('disclaimer')}}">{{trans('home.disclaimer')}}</a> </li>
            <li><a href="{{URL::route('about-us')}}">{{trans('home.about-us')}}</a> </li>
            <li><a href="{{URL::route('developers')}}">{{trans('home.developers')}}</a> </li>
            <!--<li><a href="">Contact</a> </li>-->
        </ul>

        <div class="footer-info">
                {{trans('home.footer-site-info')}}

        </div>
    </div>
</div>

<script>
    paceOptions = {
        initialRate: 2
    }
</script>
@if(Auth::check())
    {{Theme::section('messages.chatbox')}}
@endif
{{Theme::asset()->scripts()}}


<audio id="update-sound" preload="auto">
    <source src="{{Theme::asset()->img('theme/sounds/notification.ogg')}}" type="audio/ogg">
    <source src="{{Theme::asset()->img('theme/sounds/notification.mp3')}}" type="audio/mpeg">
    <source src="{{Theme::asset()->img('theme/sounds/notification.wav')}}" type="audio/wav">
</audio>

</body>
</html>

<?php //print_r(DB::getQueryLog())?>