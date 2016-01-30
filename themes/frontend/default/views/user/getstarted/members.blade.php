@foreach($users as $user)
{{Theme::section('user.display', ['user' => $user])}}
@endforeach