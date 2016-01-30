<div class="container page-content">
    <div class="left-column">
        {{nl2br($content)}}
    </div>

    <div class="right-column">
        <div class="box nav-box">
            <div class="box-title">Help Center</div>
            <ul class="nav">
                @foreach($helps as $help)
                    <li><a href="{{URL::route('help-page', ['id' => $help->slug])}}">{{$help->title}}</a> </li>
                @endforeach


            </ul>
        </div>
    </div>
</div>