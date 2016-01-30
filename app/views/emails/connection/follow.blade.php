<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{$followerName}} started following you on {{Config::get('site_title')}}</h2>

		<div>
			You can view {{$followerName}} profile by following this link <a href="{{$followerProfileUrl}}">{{$followerName}} profile</a>
		</div>
	</body>
</html>
