<div class="box">
    <div class="box-title">Custom Pages <a href="{{URL::route('admincp-custom-pages-add')}}">Add New Page</a> </div>

    <div class="box-content">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Title</th>
                <th>Created</th>
                <th>Active</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>
                @foreach($lists as $page)
                    <tr>
                        <td><a href="{{$page->url()}}">{{$page->title}}</a> </td>
                        <td>{{$page->created_at}}</td>
                        <td>
                            {{($page->active == 1) ? 'Yes' : 'No'}}
                        </td>
                        <td>
                            <a href="{{URL::route('admincp-custom-pages-edit', ['slug' => $page->slug])}}">Edit</a>
                            <a href="{{URL::route('admincp-custom-pages-delete', ['slug' => $page->slug])}}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>