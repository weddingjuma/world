<div class=" box">
    <div class="box-title">Blocked Members</div>
    <div class="box-content">
        @foreach($users as $block)
             <div id="blocked-{{$block->id}}" class="user media user-mini">
                  <div class="media-object pull-left">
                       <a href="javascript:void(0)" data-ajaxify="true"><img src="{{$block->user->present()->getAvatar(100)}}"/></a>
                  </div>
                  <div class="media-body">
                      <h5 class="media-heading">{{$block->user->fullname}} <span>@ {{$block->user->username}}</span></h5>
                       <div class="action-buttons">
                           <a class="btn btn-danger btn-sm unblock-button" data-id="{{$block->id}}"  href="">Unblock</a>
                       </div>
                  </div>
            </div>
        @endforeach
    </div>
</div>