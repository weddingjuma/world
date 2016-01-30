@if($details['type'] == 'text' or $details['type'] == 'int')

    <input class="form-control" type="text" name="val[{{$slug}}]" value="{{$configurationRepository->get($slug, $details['value'])}}"/>

@elseif($details['type'] == 'boolean')
    <select class="form-control" name="val[{{$slug}}]">
        <option {{($configurationRepository->get($slug, $details['value']) == 1) ? 'selected' :null }} value="1">Yes</option>
        <option {{($configurationRepository->get($slug, $details['value']) == 0) ? 'selected' :null }} value="0">No</option>
    </select>
@elseif($details['type'] == 'dropdown')
    <select class="form-control" name="val[{{$slug}}]">

        @foreach($configurationRepository->data($details['data']) as $value => $name)
            <option {{($configurationRepository->get($slug, $details['value']) == $value) ? 'selected' : null}} value="{{$value}}">{{$name}}</option>
        @endforeach
    </select>
@else
    <textarea class="form-control" name="val[{{$slug}}]">{{$configurationRepository->get($slug, $details['value'])}}</textarea>
@endif