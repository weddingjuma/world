<div class="box">
    <div class="box-title">{{trans('user.add-custom-field')}}</div>

    <div class="box-content">
        @if (!empty($message))
            <div class="alert alert-success">{{$message}}</div>
        @endif
        <form class="form-horizontal" action="" method="post">


                        <div class="form-group">
                                <label class="col-sm-4">{{trans('user.type')}}</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="val[type]">
                                        <option value="profile">Profile</option>
                                        <option value="page" >Page</option>
                                        <option value="community">Community</option>
                                    </select>
                                </div>
                        </div>

                        <div class="form-group">
                                <label class="col-sm-4">{{trans('user.custom-field-name')}}</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="val[name]"/>
                                </div>
                        </div>

                        <div class="form-group">
                                <label class="col-sm-4">{{trans('user.custom-field-description')}}</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="val[description]"/>
                                </div>
                        </div>

                        <div class="form-group">
                                <label class="col-sm-4">{{trans('user.custom-field-type')}}</label>
                                <div class="col-sm-7">
                                    <select id="custom-field-type" class="form-control" name="val[field_type]">
                                        <option value="text">Text Field</option>
                                        <option value="textarea" >Textarea (long text)</option>
                                        <option value="selection">Selections</option>
                                    </select>

                                    <div style="display: none" class="" id="selection-field-container">
                                        <label >{{trans('user.custom-field-options')}}</label>
                                        <input style="margin: 10px 0" class="form-control" type="text" name="val[options][]" placeholder="Enter option"/>


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