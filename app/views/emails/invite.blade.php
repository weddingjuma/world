<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>New Invitation from {{$fromName}}</h2>

		<div>
			@if ($type == 'community')
			    <?php $community = app('App\\Repositories\\CommunityRepository')->get($typeId)?>
			    @if ($community)
			        {{$fromName}} invited you join {{$community->title}} community <a href="{{$community->present()->url()}}">click here</a> to visit the community
			    @endif
			@endif
		</div>
	</body>
</html>
