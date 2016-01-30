@if(empty($medias))
    <strong>{{trans('post.no-result-found')}}</strong>
@endif

@foreach($medias as $media)
    <a href="{{$media['link']}}" data-image="{{$media['image']}}" data-title="{{$media['title']}}">
        <div class="media">
            <div class="media-object pull-left">
                <img src="{{$media['image']}}" width="30" height="30"/>
            </div>
            <div class="media-body">
                <h3 class="media-heading">{{$media['title']}}</h3>
            </div>
        </div>
    </a>
@endforeach