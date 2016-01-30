<div class="box">
    <div class="box-title">{{trans('user.edit-profile')}}</div>
    <div class="box-content">
        <form class="form-horizontal" role="form" action="" method="post">

                @foreach($fields as $field)
                    <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans($field->name)}}</label>
                    <div class="col-sm-6 ">

                        @if($field->field_type == 'text')
                            <input type="text" class="form-control" value="{{$user->present()->profile($field->id)}}" name="val[{{$field->id}}]"/>
                        @elseif($field->field_type == 'textarea')
                            <textarea class="form-control" name="val[{{$field->id}}]">{{$user->present()->profile($field->id)}}</textarea>
                        @elseif($field->field_type == 'selection')
                            <select class="form-control" name="val[{{$field->id}}]">
                                <?php $options = unserialize($field->data)?>
                                @foreach($options as $option)
                                    @if($option != '')
                                        <option {{($user->present()->profile($field->id) == $option) ? 'selected' : null}} value="{{$option}}">{{$option}}</option>
                                    @endif
                                @endforeach
                            </select>
                        @endif
                        <p class="help-block">{{trans($field->description)}}</p>
                    </div>

                </div>
                @endforeach
                      <div class="divider"></div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-sm btn-danger">{{trans('global.save')}}</button>

                        </div>
                      </div>

        </form>
    </div>
</div>