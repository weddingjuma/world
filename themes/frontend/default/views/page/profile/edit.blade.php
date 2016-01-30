<div class="box">
    <div class="box-title">{{trans('global.edit')}} {{$page->title}}

    </div>
    <div class="box-content">
        <form enctype="multipart/form-data" id="page-edit-form" data-id="{{$page->id}}" class="form-horizontal" role="form" action="" method="post">

            @if(!empty($message))
            <div class="alert alert-danger">{{$message}}</div>
            @endif
            <div class="form-group">
                <label class="col-sm-4 control-label">{{trans('page.name')}}:</label>
                <div class="col-sm-6 ">

                    <input  class="form-control" type="text" name="val[title]" value="{{$page->title}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">{{trans('page.short-description')}}:</label>
                <div class="col-sm-6 ">

                    <textarea class="form-control" name="val[description]">{{$page->description}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{{trans('page.website')}}:</label>
                <div class="col-sm-6 ">

                    <textarea class="form-control" name="val[website]">{{$page->website}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-4">{{trans('page.type-of-page')}}:</label>
                <div class="col-sm-6">
                    <select class="form-control" name="val[category]">
                        @foreach($categories as $category)
                        <option {{($category->id == $page->category_id) ? 'selected' : null}} value="{{$category->id}}">{{$category->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-4">{{trans('page.change-icon')}}:</label>
                <div class="col-sm-6">
                    <div class="alert alert-danger" style="display: none" id="page-image-error" >{{trans('photo.error', ['size' => formatBytes()])}}</div>
                    <div class="media">
                        <div style="width: 80px;height: 80px;overflow: hidden" class="media-object pull-left">
                            <img style="width: 100%" id="page-logo-image" src="{{$page->present()->getAvatar(100)}}"/>
                        </div>
                        <div class="media-body">
                                                <span style=""  class=" fileupload fileupload-exists" data-provides="fileupload">

                                                     <a     class=" btn btn-danger btn-file">
                                                         <span class="fileupload-new">{{trans('user.change-photo')}}</span>
                                                         <span class="fileupload-exists">{{trans('user.change-photo')}}</span>
                                                         <input title="" id="page-image-input" class="" type="file" name="image">
                                                     </a>


                                                 </span>


                        </div>
                    </div>
                </div>
            </div>



            @foreach($fields as $field)
            <div class="form-group">
                <label class="col-sm-4 control-label">{{trans($field->name)}}</label>
                <div class="col-sm-6 ">

                    @if($field->field_type == 'text')
                    <input type="text" class="form-control" value="{{$page->present()->field($field->id)}}" name="val[info][{{$field->id}}]"/>
                    @elseif($field->field_type == 'textarea')
                    <textarea class="form-control" name="val[info][{{$field->id}}]">{{$page->present()->field($field->id)}}</textarea>
                    @elseif($field->field_type == 'selection')
                    <select class="form-control" name="val[info][{{$field->id}}]">
                        <?php $options = unserialize($field->data)?>
                        @foreach($options as $option)
                        @if($option != '')
                        <option {{($page->present()->field($field->id) == $option) ? 'selected' : null}} value="{{$option}}">{{$option}}</option>
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
                    <button type="submit" class="btn btn-sm btn-success">{{trans('global.save')}}</button>

                    <a href="{{URL::route('pages-delete', ['id' => $page->id])}}" class=" btn btn-sm btn-danger pull-right">{{trans('page.delete-page')}}</a>
                </div>
            </div>

        </form>
    </div>
</div>