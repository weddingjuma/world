<div class="box">
    <div class="box-title">Manage Helps <a href="{{URL::route('admincp-helps-add')}}">Create New</a> </div>
    <div class="box-content">
        <table class="table table-bordered">
              <thead>
                <tr>
                    <th style="width: 40%">Title</th>
                    <th style="width: 50%">Content</th>
                    <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($helps as $help)
                    <tr>
                        <td>{{$help->title}}</td>
                        <td>{{Str::limit($help->content, 200)}}</td>
                        <td>
                            <a href="{{URL::route('admincp-helps-edit', ['id' => $help->id])}}">Edit</a> |
                            <a href="{{URL::route('admincp-helps-delete', ['id' => $help->id])}}">Delete</a>
                        </td>
                    </tr>
                @endforeach
              </tbody>
        </table>

        {{$helps->links()}}
    </div>
</div>