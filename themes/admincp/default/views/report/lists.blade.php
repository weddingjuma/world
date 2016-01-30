<div class="box">
    <div class="box-title">Reports</div>
    <div class="box-content">
        <ul class="nav nav-tabs">
          <li class="{{($type == 'post') ? 'active' : null}}"><a href="{{URL::route('admincp-reports')}}?type=post">Posts</a></li>
          <li class="{{($type == 'community') ? 'active' : null}}"><a href="{{URL::route('admincp-reports')}}?type=community">Communities</a></li>
          <li  class="{{($type == 'profile') ? 'active' : null}}"><a href="{{URL::route('admincp-reports')}}?type=profile">Profiles</a></li>
          <li  class="{{($type == 'page') ? 'active' : null}}"><a href="{{URL::route('admincp-reports')}}?type=page">Pages</a></li>
        </ul>

        <table class="table table-bordered">
              <thead>
                <tr>
                    <th style="width: 30%">Reporter</th>
                    <th style="width: 40%">Reason</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($reports as $report)
                    @if($report->user)
                        <tr>
                            <td><a href="{{$report->user->present()->url()}}"><img src="{{$report->user->present()->getAvatar(30)}}"/></a> <br/> {{$report->user->present()->fullName()}}</td>
                            <td>{{$report->reason}}</td>
                            <td>{{$report->type}}</td>
                            <td>
                                <a href="{{$report->url}}">Go Moderate</a> | <a href="{{URL::route('delete-report', ['id' => $report->id])}}">Delete</a>
                            </td>
                        </tr>
                    @endif
                @endforeach
              </tbody>
        </table>


    </div>
</div>