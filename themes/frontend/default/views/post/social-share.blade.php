@if($post->privacy == 1 or $post->privacy == 3 or $post->privacy == 4)
<li class="divider"></li>
<li><a href="javascript:void(0)" onclick="return window.open(
                             'http://www.facebook.com/sharer.php?u={{route('post-page', ['id' => $post->id])}}'
                             , 'targetWindow', 'width=600,height=400')"
        ><i style="display: inline-block;width: 15%;text-align: center" class="icon ion-social-facebook"></i> {{trans('post.share-on-facebook')}}</a> </li>

<li><a href="javascript:void(0)" onclick="return window.open(
                             'http://twitter.com/share?url={{route('post-page', ['id' => $post->id])}}'
                             , 'targetWindow', 'toolbar=no,location=no,status=no,scrollbar=yes,resizable=no,width=600,height=400')"
        ><i style="display: inline-block;width: 15%;text-align: center" class="icon ion-social-twitter"></i> {{trans('post.share-on-twitter')}}</a> </li>

<li><a href="javascript:void(0)" onclick="return window.open(
                             'https://plus.google.com/share?url={{route('post-page', ['id' => $post->id])}}'
                             , 'targetWindow', 'toolbar=no,location=no,status=no,scrollbar=yes,resizable=no,width=600,height=400')"
        ><i style="display: inline-block;width: 15%;text-align: center" class="icon ion-social-googleplus"></i> {{trans('post.share-on-g+')}}</a> </li>


<li><a href="javascript:void(0)" onclick="return window.open(
                             'http://www.linkedin.com/shareArticle?mini=true&url={{route('post-page', ['id' => $post->id])}}'
                             , 'targetWindow', 'toolbar=no,location=no,status=no,scrollbar=yes,resizable=no,width=600,height=400')"
        ><i style="display: inline-block;width: 15%;text-align: center" class="icon ion-social-linkedin-outline"></i> {{trans('post.share-on-linkedin')}}</a> </li>

@endif