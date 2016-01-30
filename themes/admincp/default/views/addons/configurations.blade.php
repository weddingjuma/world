<div class="box">
    <div class="box-title">{{trans('admincp.configurations')}}</div>
    <div class="box-content">

        <form class="form-horizontal" action="{{URL::route('admincp-save-configuration')}}" method="post">

                    @foreach($configurations as $slug => $details)

                            <div class="form-group">
                                <label class="col-sm-4">{{$details['title']}}</label>
                                <div class="col-sm-7">
                                    {{Theme::section('configuration.format', compact(['slug', 'details', 'configurationRepository']))}}
                                    <p class="help-block">{{$details['description']}}</p>
                                </div>
                            </div>
                        @endforeach

            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Save"/>
            </div>

        </form>
    </div>
</div>