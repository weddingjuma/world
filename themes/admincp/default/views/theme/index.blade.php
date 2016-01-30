<div class="box">
    <div class="box-title">{{trans('admincp.theme-management')}}</div>
    <div class="box-content">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="width: 20%">{{trans('theme.theme-preview')}}</th>
                    <th>{{trans('theme.theme-details')}}</th>
                    <th>{{trans('global.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($themes as $theme)

                    <tr>
                        <td>
                            <img style="width: 70%" src="{{URL::to($theme['dir'])}}/preview.png"/>
                        </td>
                        <td>
                            <ul>
                                <li>
                                    <strong>{{trans('global.name')}}:</strong>
                                    {{$theme['manifest']->name}}
                                </li>
                                <li>
                                    <strong>{{trans('theme.author-name')}}:</strong>
                                    {{$theme['manifest']->author}}
                                </li>
                            </ul>
                        </td>
                        <td>
                            @if ($themeRepository->isActive($currentType, $theme['name']))
                                <a href="#">{{trans('global.active')}}</a>
                            @else
                                <a href="{{URL::route('admincp-theme-activate')}}?type={{$currentType}}&theme={{$theme['name']}}">{{trans('global.enable')}}</a>


                            @endif

                            | <a href="{{URL::route('admincp-theme-configurations')}}?type={{$currentType}}&theme={{$theme['name']}}">{{trans('admincp.configurations')}}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>
</div>


