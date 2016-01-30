<div class="media">
    @if (!empty($result['image']))
        <div class="media-object pull-left">
            @if(count($result['image']) > 1)
                <span class="navigator">
                    <a href="" class="navigate-left"><i class="icon ion-ios7-arrow-back"></i></a>
                    <a href="" class="navigate-right"><i class="icon ion-ios7-arrow-forward"></i></a>
                </span>
            @endif
            <a href="javascript:void(0)" onclick="window.open('{{$result['link']}}')"><img class="main-preview-image" src="{{$result['image'][0]}}"/></a>
        </div>
    @endif
    <div class="media-body">
        <a href="javascript:void(0)" onclick="window.open('{{$result['link']}}')"><h4 class="media-heading">{{$result['title']}}</h4></a>
        <p>{{$result['description']}}</p>

        <a href="" class="delete-link-preview"><i class="icon ion-close"></i> </a>
    </div>
</div>