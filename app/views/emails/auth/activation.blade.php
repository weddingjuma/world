<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Account Activation</h2>

		<div>
			To activate your account please kindly click this link and follow further instructions :
            <a href="{{URL::route('user-activation')}}?email={{$email_address}}&code={{$hash}}">{{URL::route('user-activation')}}?email={{$email_address}}&code={{$hash}}</a>
		</div>
	</body>
</html>
