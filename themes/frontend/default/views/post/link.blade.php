<div class="media post-link-detail">
    <?php $link = $post->present()->getLinkDetail()?>

    @if (!empty($link['image']))
    <div class="media-object pull-left">

        <a href="javascript:void(0)" onclick="window.open('{{$link['link']}}')"><img class="main-preview-image" src="{{$link['image']}}"/></a>
    </div>
    @endif
    <div class="media-body">
        <a href="javascript:void(0)" onclick="window.open('{{$link['link']}}')"><h4 class="media-heading">{{$link['title']}}</h4></a>
        <p>{{$link['description']}}</p>


    </div>
</div>