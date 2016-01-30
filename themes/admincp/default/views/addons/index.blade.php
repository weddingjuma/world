<div class="box">
    <div class="box-title">{{trans('admincp.addon-management')}}</div>
    <div class="box-content">
        <table class="table">
        <thead>
                <tr>
                    <th style="width: 50%">Name</th>
                    <th>Enable/Disabled</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($addons as $addon)

                    <tr>
                        <td>{{ucwords($addon['name'])}} <br/> {{$addon['description']}}</td>
                        <td>
                            @if ($addonRepository->isActive($addon['slug']))
                                Active
                                | <a href="{{URL::route('admincp-update-addon', ['id' => $addon['slug']])}}">Update</a>
                            @else
                                Not active
                            @endif
                        </td>
                        <td>
                            @if($addonRepository->isActive($addon['slug']))
                                <a href="{{URL::route('admincp-disable-addon', ['id' => $addon['slug']])}}">Disable</a>
                            @else
                                <a href="{{URL::route('admincp-enable-addon', ['id' => $addon['slug']])}}">Enable</a>
                            @endif
                            | <a href="{{URL::route('admincp-configuration-addon', ['id' => $addon['slug']])}}">Configurations</a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>