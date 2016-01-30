<div class="box">
    <div class="box-title">{{trans('global.edit')}}
        <span class="pull-right">
            <a data-ajaxify="true" href="{{$community->present()->url('members')}}">{{trans('community.manage-members')}}</a> |

             @if(Config::get('page-design'))
                <a data-ajaxify="true" href="{{$community->present()->url('design')}}">{{trans('community.design')}}</a>
             @endif
        </span>
    </div>
    <div class="box-content">
        <form class="form-horizontal" role="form" action="" method="post">

                  @if(!empty($message))
                    <div class="alert alert-danger">{{$message}}</div>
                  @endif
                    <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('community.name')}}:</label>
                    <div class="col-sm-6 ">

                        <input class="form-control" type="text" name="val[title]" value="{{$community->title}}"/>
                    </div>
                    </div>
                    <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('community.description')}}:</label>
                    <div class="col-sm-6 ">

                        <textarea class="form-control" name="val[description]">{{$community->description}}</textarea>
                    </div>
                    </div>

                @foreach($fields as $field)
                    <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans($field->name)}}</label>
                    <div class="col-sm-6 ">

                        @if($field->field_type == 'text')
                            <input type="text" class="form-control" value="{{$community->present()->field($field->id)}}" name="val[info][{{$field->id}}]"/>
                        @elseif($field->field_type == 'textarea')
                            <textarea class="form-control" name="val[info][{{$field->id}}]">{{$community->present()->field($field->id)}}</textarea>
                        @elseif($field->field_type == 'selection')
                            <select class="form-control" name="val[info][{{$field->id}}]">
                                <?php $options = unserialize($field->data)?>
                                @foreach($options as $option)
                                    @if($option != '')
                                        <option {{($community->present()->field($field->id) == $option) ? 'selected' : null}} value="{{$option}}">{{$option}}</option>
                                    @endif
                                @endforeach
                            </select>
                        @endif
                        <p class="help-block">{{trans($field->description)}}</p>
                    </div>

                </div>
                @endforeach



                <div class="box-title">{{trans('global.privacy')}}</div>

                    <div class="form-group">
                    <label class="col-sm-4 control-label">Who can post</label>
                    <div class="col-sm-6 ">

                        <select name="val[can_post]">
                            <option {{($community->can_post == 1) ? 'selected' : null}} value="1">All Members</option>
                            <option {{($community->can_post == 0) ? 'selected' : null}} value="0">Only you</option>
                        </select>

                    </div>
                    </div>

                    <div class="form-group">
                    <label class="col-sm-4 control-label">Can people search for this community</label>
                    <div class="col-sm-6 ">

                        <select name="val[searchable]">
                            <option {{($community->searchable == 1) ? 'selected' : null}} value="1">Yes</option>
                            <option {{($community->searchable == 0) ? 'selected' : null}} value="0">No</option>
                        </select>

                    </div>
                    </div>

                    <div class="form-group">
                    <label class="col-sm-4 control-label">Who can invite members</label>
                    <div class="col-sm-6 ">

                        <select name="val[searchable]">
                            <option {{($community->can_invite == 1) ? 'selected' : null}} value="1">All members</option>
                            <option {{($community->can_invite == 0) ? 'selected' : null}} value="0">Only you</option>
                        </select>

                    </div>
                    </div>





                              <div class="divider"></div>
                              <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                  <button type="submit" class="btn btn-sm btn-success">{{trans('global.save')}}</button>

                                  <a href="{{URL::route('community-delete', ['id' => $community->id])}}" class="delete-community btn btn-sm btn-danger pull-right">Delete community</a>
                                </div>
                              </div>

        </form>
    </div>
</div>