<div class="box">
    <div class="box-title">{{trans('user.edit-custom-field')}}</div>

    <div class="box-content">

        <form class="form-horizontal" action="" method="post">


                        <div class="form-group">
                                <label class="col-sm-4">{{trans('user.type')}}</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="val[type]">
                                        <option {{($field->type == 'profile') ? 'selected' : null}} value="profile">Profile</option>
                                        <option {{($field->type == 'page') ? 'selected' : null}} value="page" >Page</option>
                                        <option {{($field->type == 'community') ? 'selected' : null}} value="community">Community</option>
                                    </select>
                                </div>
                        </div>

                        <div class="form-group">
                                <label class="col-sm-4">{{trans('user.custom-field-name')}}</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" value="{{$field->name}}" name="val[name]"/>
                                </div>
                        </div>

                        <div class="form-group">
                                <label class="col-sm-4">{{trans('user.custom-field-description')}}</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" value="{{$field->description}}" name="val[description]"/>
                                </div>
                        </div>

                        <div class="form-group">
                                <label class="col-sm-4">{{trans('user.custom-field-type')}}</label>
                                <div class="col-sm-7">
                                    <select id="custom-field-type" class="form-control" name="val[field_type]">
                                        <option {{($field->field_type == 'text') ? 'selected' : null}} value="text">Text Field</option>
                                        <option {{($field->field_type == 'textarea') ? 'selected' : null}} value="textarea" >Textarea (long text)</option>
                                        <option {{($field->field_type == 'selection') ? 'selected' : null}} value="selection">Selections</option>
                                    </select>

                                    <div style="{{($field->field_type == 'selection') ? 'display: block' : 'display:none'}}" class="" id="selection-field-container">
                                        <label >{{trans('user.custom-field-options')}}</label>

                                        @if ($field->field_type == 'selection')
                                            <?php $options = unserialize($field->data)?>
                                            @foreach($options as $option)
                                                <input style="margin: 10px 0" class="form-control" type="text" name="val[options][]" value="{{$option}}" placeholder="Enter option"/>
                                            @endforeach
                                        @else
                                            <input style="margin: 10px 0" class="form-control" type="text" name="val[options][]" placeholder="Enter option"/>
                                        @endif

                                        <a style="margin-top: 20px;top: 20px" href=""> <i class="icon ion-android-add"></i> Add more options</a>
                                    </div>
                                </div>
                        </div>



            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Add Field"/>
            </div>

        </form>
    </div>
</div>