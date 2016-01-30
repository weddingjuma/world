<div class="box">
    <div class="box-title">{{trans('user.custom-fields')}} - <a href="{{URL::route('admincp-user-custom-field-add')}}">{{trans('user.add-custom-field')}}</a> </div>

    <div class="box-content">
        <ul class="nav nav-tabs">
          <li class="{{($type == 'profile') ? 'active' : null}}"><a href="{{URL::route('admincp-user-custom-field')}}?type=profile">Profile fields</a></li>
          <li class="{{($type == 'page') ? 'active' : null}}"><a href="{{URL::route('admincp-user-custom-field')}}?type=page">Pages</a></li>
          <li class="{{($type == 'community') ? 'active' : null}}"><a href="{{URL::route('admincp-user-custom-field')}}?type=community">Communities</a></li>
        </ul>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Field type</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($fields as $field)
                    <tr>
                        <td>{{$field->name}}</td>
                        <td>{{$field->description}}</td>
                        <td>{{$field->field_type}}</td>
                        <td>
                            <a href="{{URL::route('admincp-user-custom-field-delete', ['id' => $field->id])}}">Delete</a>
                            <a href="{{URL::route('admincp-user-custom-field-edit', ['id' => $field->id])}}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>
</div>