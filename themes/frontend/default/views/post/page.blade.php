<div class="container page-content clearfix">

        <div class="" id="post-page" style="">


              @if(empty($post))
                <div class="box">
                    <div class="box-title">{{trans('post.post-error')}}</div>
                    <div class="box-content">
                        <div class="alert alert-danger">
                            {{trans('post.post-error-note')}}
                        </div>
                    </div>
                </div>
              @else
                    {{Theme::section('post.media', ['post' => $post, 'commentsLimit' => 10, 'paginate' => true])}}
              @endif



        </div>


    </div>