<div class="box">
    <div class="box-title">
        {{trans('custompage::global.recent-pages')}}

        <a href="" class="pull-right">{{trans('global.more')}}</a>
    </div>

    <div class="box-content">
        <div class="recent-custom-pages-list">
            @foreach(app('App\\Addons\\Custompage\\Classes\\CustomPageRepository')->getList(10, (!isset($all)) ? true : false, false) as $page)
            <a href="{{$page->url()}}">{{Str::limit($page->title, 100)}}</a>
            @endforeach
        </div>
    </div>
</div>