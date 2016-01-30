<div class="box">
    <div class="box-title">{{trans('community.about')}}</div>
    <div class="box-content">

        <table class="table table-striped">
            <h4>Author</h4>
            {{Theme::section('user.display', ['user' => $community->user])}}


            <tbody>

                <tr>
                    <td><strong>{{trans('user.date-created')}} :</strong></td>
                    <td><span class="post-time" ><span title="{{$community->present()->createdOn()}}">{{$community->created_at}}</span></span> </td>
                </tr>

                <tr>
                    <td><strong>{{trans('community.description')}}:</strong></td>
                    <td>{{$community->description}}</td>
                </tr>

                @foreach($community->present()->fields() as $field)
                    <tr>
                        <td><strong>{{$field->name}}</strong></td>
                        <td>{{$community->present()->field($field->id)}}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>


    </div>
</div>