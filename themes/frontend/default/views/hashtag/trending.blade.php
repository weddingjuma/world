<div class="box">
    <div class="box-title">{{trans('hashtag.trending-hashtag')}}</div>
    <div class="box-content">
        @foreach(app('App\\Repositories\\HashtagRepository')->trending(5) as $hashtag)
            <a style="display: inline-block;width: 40%;margin-right: 1%;margin-bottom: 10px" data-ajaxify="true" href="{{$hashtag->url()}}">{{$hashtag->hash}}</a>
        @endforeach
    </div>
</div>