<div class="container page-content clearfix">

        <div class="left-column">

            <div class="box">
                <div class="box-title">{{trans('connection.friend-requests')}}</div>
                <div class="box-content">
                    @if($requests->getTotal() < 1)
                        <div class="alert alert-info">{{trans('connection.no-requests')}}</div>
                    @endif

                        @foreach($requests as $request)
                            <div class="user media" id="friend-request-{{$request->id}}">
                                  <div class="media-object pull-left">
                                       <a href="{{$request->fromUser->present()->url()}}" data-ajaxify="true"><img src="{{$request->fromUser->present()->getAvatar(100)}}"/></a>
                                  </div>
                                  <div class="media-body">
                                      <h5 class="media-heading">{{$request->fromUser->fullname}} <span>{{$request->fromUser->present()->atName()}}</span></h5>
                                       <div class="action-buttons">
                                           <a data-ajaxify="true" style="display: none" id="visit-profile-link" class="btn btn-default btn-xs " href="{{$request->fromUser->present()->url()}}">{{trans('user.visit-profile')}}</a>
                                           <a data-id="{{$request->id}}" data-type="reject" href="{{URL::route('connection-reject-friend', ['id' => $request->id])}}" class="btn btn-xs btn-danger response-friend-request">{{trans('connection.reject')}}</a>
                                           <a data-id="{{$request->id}}" data-type="confirm" href="{{URL::route('connection-confirm-friend', ['id' => $request->id])}}" class="btn btn-xs btn-success response-friend-request">{{trans('connection.confirm')}}</a>
                                       </div>
                                  </div>

                            </div>
                        @endforeach

                        {{$requests->links()}}

                </div>
            </div>

        </div>

        <div class="right-column">
            {{Theme::section('user.side-info')}}
            {{Theme::widget()->get('friend-requests')}}

        </div>
    </div>