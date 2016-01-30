@if(!count($requests))
    <div style="padding: 5px 0">{{trans('connection.no-requests')}}</div>
@endif

@foreach($requests as $request)
<div class="user media user-mini " id="friend-request-{{$request->id}}">
    <div class="media-object pull-left">
        <a href="{{$request->fromUser->present()->url()}}" data-ajaxify="true"><img src="{{$request->fromUser->present()->getAvatar(100)}}"/></a>
    </div>
    <div class="media-body">
        <h5  class="media-heading">
            <a data-ajaxify="true" href="{{$request->fromUser->present()->url()}}">{{$request->fromUser->fullname}}</a>
            <span>{{$request->fromUser->present()->atName()}}</span></h5>
        <div class="action-buttons">
            <a data-ajaxify="true" style="display: none" id="visit-profile-link" class="btn btn-default btn-xs " href="{{$request->fromUser->present()->url()}}">{{trans('user.visit-profile')}}</a>
            <a data-id="{{$request->id}}" data-type="reject" href="{{URL::route('connection-reject-friend', ['id' => $request->id])}}" class="btn btn-xs btn-danger response-friend-request">{{trans('connection.reject')}}</a>
            <a data-id="{{$request->id}}" data-type="confirm" href="{{URL::route('connection-confirm-friend', ['id' => $request->id])}}" class="btn btn-xs btn-success response-friend-request">{{trans('connection.confirm')}}</a>
        </div>
    </div>

</div>
@endforeach