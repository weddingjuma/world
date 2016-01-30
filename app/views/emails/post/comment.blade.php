<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{$commentorName}} added a new comment to your post </h2>

		<div>
			Follow this link to see the post : <a href="{{URL::route('post-page', ['id' => $postId])}}">{{URL::route('post-page', ['id' => $postId])}}</a>
		</div>
	</body>
</html>
