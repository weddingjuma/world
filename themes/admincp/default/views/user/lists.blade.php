<div class="box">
    <div class="box-title">User Lists</div>
    <div class="box-content">
        <form action="" method="get">
            <input name="term" type="text" class="form-control" placeholder="Search for users by fullname or username or email address"/>
            <br/>
            <button class="btn btn-primary btm-sm">Search</button><br/><br/>
        </form>
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
                        <td><img style="width: 30px" src="{{$user->present()->getAvatar(30)}}"/> </td>
                        <td>{{$user->present()->fullName()}}</td>
                        <td>{{$user->username}}</td>
                        <td>{{$user->email_address}}</td>
                        <td>{{$user->created_at}}</td>
                        <td>{{$user->updated_at}}</td>
                        <td>
                            <a href="{{URL::route('admincp-user-edit', ['id' => $user->id])}}">Edit</a> <br/>
                            @if($user->banned == 0)
                                <a href="{{URL::route('admincp-user-ban')}}?action=ban&userid={{$user->id}}">Ban</a> <br/>
                            @else
                                <a href="{{URL::route('admincp-user-ban')}}?action=unban&userid={{$user->id}}">UnBan</a> <br/>
                            @endif
                            <a href="{{URL::route('delete-account')}}?userid={{$user->id}}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{$users->links()}}
    </div>
</div>