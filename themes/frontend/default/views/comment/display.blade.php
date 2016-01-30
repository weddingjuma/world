@if($comment->user)
<div id="reply-{{$comment->id}}" class="reply media reply-{{$comment->id}}">
    <div class="media-object pull-left">
        <a href="{{$comment->user->present()->url()}}" data-ajaxify="true"><img src="{{$comment->user->present()->getAvatar(50)}}"/></a>
    </div>
    <div class="media-body">
        <h5 class="media-heading">
            <a href="{{$comment->user->present()->url()}}" data-ajaxify="true">{{$comment->user->present()->fullName()}}</a>


            <span>{{$comment->user->present()->atName()}} {{Theme::section('user.verified', ['user' => $comment->user])}}</span></h5>

        @if ($comment->present()->canDelete())
        <a  data-type="{{$comment->type}}" data-type-id="{{$comment->type_id}}" data-warning="{{trans('comment.confirm-delete')}}" class="delete-button" data-id="{{$comment->id}}" href=""><i class="icon ion-close"></i></a>
        @endif

        <span class="comment-text" id="{{$comment->id}}-comment-text">{{$comment->present()->text()}}</span><br/>
        @if ($comment->present()->canDelete())
            <div style="display:none" id="comment-inline-editor-{{$comment->id}}" class="comment-inline-editor clearfix">
                <form  action="" method="post">
                    <textarea name="text" >{{$comment->text}}</textarea>
                    <br/><br/>
                    <div class="pull-right">
                        <a href="" data-text="{{$comment->text}}" data-id="{{$comment->id}}" class="comment-edit-save-button btn btn-success btn-xs">{{trans('post.done-editing')}}</a>
                        <a data-id="{{$comment->id}}" class="cancel-inline-editor btn btn-default btn-xs">{{trans('post.cancel')}}</a>
                    </div>
                </form>
            </div>
        @endif

        @if($image = $comment->present()->getImage())
        <a class="reply-image preview-image" href="{{Image::url($image, '600')}}"><img src="{{Image::url($image, 600)}}"/> </a>

        @endif
        <span class="post-time"><i class="icon ion-ios7-time-outline"></i> <span title="{{$comment->present()->time()}}">{{formatDTime($comment->created_at)}}</span></span>
        <i style="font-size: 15px;color: #828282" class="icon ion-thumbsup"></i>
        <span id="comment-like-count-{{$comment->id}}">
            <?php $countLikes = $comment->countLikes()?>
            @if($countLikes)
                {{$countLikes}}
            @endif
        </span>
        <?php $hasLike = $comment->hasLiked()?>

        <a data-target="#comment-like-count-{{$comment->id}}" data-is-login="{{Auth::check()}}" data-status="{{($hasLike) ? '1' : 0}}" class="like-button" data-like="{{trans('like.like')}}" data-unlike="{{trans('like.unlike')}}" data-id="{{$comment->id}}" data-type="comment" href=""> <span>{{($hasLike) ? trans('like.unlike') : trans('like.like')}}</span></a>
        <!--<a href="">Like</a>-->
        @if ($comment->present()->canDelete())
            <a data-comment-id="{{$comment->id}}" class="comment-edit-button" href=""><i class="icon ion-edit"></i> {{trans('global.edit')}}</a>
        @endif

    </div>
</div>
@else
    <?php $comment->delete()?>
@endif