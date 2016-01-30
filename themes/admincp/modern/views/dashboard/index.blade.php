<div class="box">
    <div class="box-title">
        {{trans('admincp.dashboard')}}
    </div>

    <div class="box-content">

        <ul class="dashboard-statistics nav">
            <li>
                <i class="icon ion-android-social"></i>
                <div class="stat">
                    <span class="count">{{app('App\\Repositories\\UserRepository')->total()}}</span>
                    <span>Users</span>
                </div>
                <a href="{{URL::to('admincp/user/list')}}">View all users</a>
            </li>

            <li style="background: #52B3D9">
                <i class="icon ion-clock"></i>
                <div class="stat">
                    <span class="count">{{app('App\\Repositories\\UserRepository')->totalOnline()}}</span>
                    <span>Online Members</span>
                </div>
                <a href="{{URL::to('admincp/user/list')}}">View all</a>
            </li>

            <li style="background: #336E7B" class="last">
                <i class="icon ion-clipboard"></i>
                <div class="stat">
                    <span class="count">{{app('App\\Repositories\\PageRepository')->total()}}</span>
                    <span>Pages</span>
                </div>
                <a href="{{URL::to('admincp/pages')}}">View all Pages</a>
            </li>



            <li style="background: black">
                <i class="icon ion-game-controller-a"></i>
                <div class="stat">
                    <span class="count">{{app('App\\Repositories\\GameRepository')->total()}}</span>
                    <span>Games</span>
                </div>
                <a href="{{URL::to('admincp/games')}}">View all Games</a>
            </li>



            <li style="background: #36D7B7" >
                <i class="icon ion-ios7-flag"></i>
                <div class="stat">
                    <span class="count">{{app('App\\Repositories\\ReportRepository')->total()}}</span>
                    <span>Reports</span>
                </div>
                <a href="{{URL::to('admincp/reports')}}">View  Reports</a>
            </li>

            <li style="background: #F7CA18" class="last">
                <i class="icon ion-chatbox-working"></i>
                <div class="stat">
                    <span class="count">{{app('App\\Repositories\\PostRepository')->total()}}</span>
                    <span>Posts</span>
                </div>

            </li>

            <li style="background: #F27935">
                <i class="icon ion-ios7-people-outline"></i>
                <div class="stat">
                    <span class="count">{{app('App\\Repositories\\CommunityRepository')->total()}}</span>
                    <span>Communities</span>
                </div>

            </li>
            <li style="background: #EB974E;width: 66.5%" class="last">
                <i class="icon ion-chatbox-working"></i>
                <div class="stat">
                    <span class="count">{{app('App\\Repositories\\CommentRepository')->total()}}</span>
                    <span>Comments</span>
                </div>

            </li>
        </ul>

    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="box" style="margin-top: 20px">
            <div class="box-title">New Reports <a href="{{URL::to('admincp/reports')}}">View All</a></div>
            <div class="box-content" style="padding: 5px">
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
                    @foreach(app('App\Repositories\ReportRepository')->getAll(5) as $report)
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
    </div>
    <div class="col-md-6">
        <div class="box" style="margin-top: 20px">
            <div class="box-title">Unvalidated Members <a href="{{URL::to('admincp/user/unvalidated')}}">View All</a> </div>
            <div class="box-content" style="padding: 5px">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 3%">Avatar</th>
                        <th style="width: 10%">Fullname</th>

                        <th style="width: 5%">Date Joined</th>

                        <th style="width: 5%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(app('App\Repositories\UserRepository')->listUnvalidatedUsers() as $user)
                    <tr>
                        <td><img style="width: 30px" src="{{$user->present()->getAvatar(30)}}"/> </td>
                        <td>{{$user->present()->fullName()}}</td>

                        <td>{{$user->created_at}}</td>

                        <td>
                            <a href="{{URL::route('admincp-user-edit', ['id' => $user->id])}}">Edit</a> <br/>
                            <a href="{{URL::route('delete-account')}}?userid={{$user->id}}">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>