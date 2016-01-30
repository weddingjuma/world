<div class="box">
    <div class="box-title">Communities</div>
    <div class="box-content">
        <form action="" method="get">
            <input name="term" type="text" class="form-control" placeholder="Search for community"/>
            <br/>
            <button class="btn btn-primary btm-sm">Search</button><br/><br/>
        </form>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width:40%">Title</th>
                <th style="width:25%">Description</th>
                <th style="width: 5%">Privacy</th>
                <th style="width: 20%">Creator</th>

                <th style="width: 10%">Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach($communities as $community)
                    <tr>
                        <td><a href="{{$community->present()->url()}}">{{$community->title}}</a> </td>
                        <td>{{$community->description}}</td>
                        <td>
                            @if($community->privacy == 1)
                                Public
                            @else
                                Private
                            @endif
                        </td>
                        <td>
                            <a href="{{$community->user->present()->url()}}">
                                <img src="{{$community->user->present()->getAvatar(30)}}" width="20" height="20"/>
                                <h5>{{$community->user->fullname}}</h5>
                            </a>
                        </td>
                        <td>
                            <a href="{{URL::route('admincp-community-edit', ['id' => $community->id])}}">Edit</a> | <a href="{{URL::route('community-delete', ['id' => $community->id])}}?ref=true">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{$communities->links()}}
    </div>
</div>