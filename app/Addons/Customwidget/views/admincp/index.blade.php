<div class="box">
    <div class="box-title">Custom Widgets <a href="{{URL::route('admincp-custom-widgets-add')}}">Add New Widgets</a> </div>
    <div class="box-content">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($widgets as $widget)
                    <tr>
                        <td>{{$widget->title}}</td>
                        <td>
                            {{($widget->status == 1) ? 'Enabled' : 'Disabled'}}
                        </td>
                        <td>
                            <a href="{{URL::route('admincp-custom-widgets-delete', ['id' => $widget->id])}}">Delete</a>
                            <a href="{{URL::route('admincp-custom-widgets-edit', ['id' => $widget->id])}}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>