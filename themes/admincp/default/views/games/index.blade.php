<div class="box">
    <div class="box-title">Manage Games <a href="{{URL::route('admincp-games-add')}}">Add Game</a> </div>

    <div class="box-content">
        <div class="alert alert-info">Below contains the list of game both admin and members have added</div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 30%">Title</th>
                <th style="width: 30%">Description</th>
                <th style="width: 10%">Category</th>
                <th style="width: 5%">Approved</th>
                <th style="width: 5%">verified</th>
                <th style="width: 10%">By</th>
                <th style="width: 10%">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($games as $game)
            <tr>
                <td>{{$game->title}}</td>
                <td>{{$game->description}}</td>
                <td>
                    @if ($game->cat)
                        {{$game->cat->title}}
                    @endif
                </td>
                <td>{{($game->approved == 1 ) ? 'Yes' : 'No'}}</td>
                <td>{{($game->verified == 1 ) ? 'Yes' : 'No'}}</td>
                <td>
                    <a href="{{$game->user->present()->url()}} ">{{$game->user->fullname}}</a>
                </td>
                <td>
                    @if($game->approved == 0)
                    <a href="{{URL::route('admincp-games-approve', ['id' => $game->id])}}">Confirm</a> <br/>
                    @endif
                    <a href="{{$game->present()->url()}}">Visit</a><br/>
                    <a href="{{URL::route('admincp-games-edit', ['id' => $game->id])}}">Edit</a> <br/>
                    <a href="{{URL::route('games-delete', ['id' => $game->id])}}">Delete</a>

                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

        {{$games->links()}}
    </div>
</div>