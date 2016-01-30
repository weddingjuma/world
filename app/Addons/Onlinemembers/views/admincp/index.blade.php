<div class="box">
    <div class="box-title">Online Members</div>
    <div class="box-content">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 3%">Avatar</th>
                <th style="width: 10%">Fullname</th>
                <th style="width: 10%">Username</th>
                <th style="width: 10%">Email Address</th>
                <th style="width: 5%">Date Joined</th>
                <th style="width: 5%">Last Login</th>
                <th style="width: 5%">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
            <tr>
                <td><a href="{{$user->present()->url()}}"><img style="width: 30px" src="{{$user->present()->getAvatar(30)}}"/> </a></td>
                <td><a href="{{$user->present()->url()}}">{{$user->present()->fullName()}}</a> </td>
                <td>{{$user->username}}</td>
                <td>{{$user->email_address}}</td>
                <td>{{$user->created_at}}</td>
                <td>{{$user->update_at}}</td>
                <td>
                    <a href="{{URL::route('admincp-user-edit', ['id' => $user->id])}}">Edit</a> <br/>

                    <a href="{{URL::route('delete-account')}}?userid={{$user->id}}">Delete</a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

        {{$users->links()}}
    </div>
</div>