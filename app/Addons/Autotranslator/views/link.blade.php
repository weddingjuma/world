<span id="to-translate-{{$post->id}}" style="display: none">{{$post->text}}</span>

<a style="display: block;margin-top:10px" href="" data-id="{{$post->id}}" class="translate-it">
    {{trans('autotranslator::global.translate')}}
    <img src="{{Theme::asset()->img('theme/images/loading.gif')}}" style="display: none" width="12" height="12"/>
</a>
<div class="translated-container" id="this-translated-container-{{$post->id}}"></div>