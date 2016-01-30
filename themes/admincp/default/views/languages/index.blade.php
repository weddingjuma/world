<div class="box">
    <div class="box-title">{{trans('admincp.languages')}} <a href="{{URL::route('admincp-languages-add')}}">{{trans('admincp.add-language')}}</a></div>

    <div class="box-content">


        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{{trans('global.var')}}</th>
                    <th>{{trans('global.name')}}</th>
                    <th>{{trans('global.status')}}</th>
                    <th>{{trans('global.action')}}</th>
                </tr>
            </thead>

            <tbody>
                @foreach($languages as $language)
                    <tr>
                        <td>{{$language->var}}</td>
                        <td>{{$language->name}}</td>
                        <td>
                            {{($language->active == 1) ? trans('global.active') : trans('global.not-active')}}
                        </td>

                        <td>
                            @if ($language->active == 0)
                                <a href="{{URL::route('admincp-languages-activate', ['var' => $language->var])}}">{{trans('global.activate')}}</a>
                            @endif

                            @if ($language->var != 'en')
                                <a href="{{URL::route('admincp-languages-delete', ['var' => $language->id])}}"><i class="icon ion-ios7-close-outline"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>