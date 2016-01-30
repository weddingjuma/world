<div class="box">
    <div class="box-title">
        {{trans('admincp.dashboard')}}
    </div>

    <div class="box-content">
        <h2>Statistics</h2>
        <table class=" table">
            <thead>
                <tr>
                    <th style="width: 50%"></th>
                    <th style="width: 50%"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                            Total Users :

                    </td>

                    <td>
                        <span>{{app('App\\Repositories\\UserRepository')->total()}}</span>

                    </td>
                </tr>

                <tr>
                    <td>
                        Online Users :
                    </td>
                    <td>
                        <span>{{app('App\\Repositories\\UserRepository')->totalOnline()}}
                    </td>
                </tr>

                <tr>
                    <td>
                        Total Pages :
                    </td>
                    <td>
                        <span>{{app('App\\Repositories\\PageRepository')->total()}}
                    </td>
                </tr>

                <tr>
                    <td>
                        Total Posts :
                    </td>
                    <td>
                        <span>{{app('App\\Repositories\\PostRepository')->total()}}
                    </td>
                </tr>

                <tr>
                    <td>
                        Total Communities :
                    </td>
                    <td>
                        <span>{{app('App\\Repositories\\CommunityRepository')->total()}}
                    </td>
                </tr>

                <tr>
                    <td>
                        Total Games :
                    </td>
                    <td>
                        <span>{{app('App\\Repositories\\GameRepository')->total()}}
                    </td>
                </tr>

                <tr>
                    <td>
                        Total Reports :
                    </td>
                    <td>
                        <span>{{app('App\\Repositories\\ReportRepository')->total()}}
                    </td>
                </tr>

                <tr>
                    <td>
                        Total Comments :
                    </td>
                    <td>
                        <span>{{app('App\\Repositories\\CommentRepository')->total()}}
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>