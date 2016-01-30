<div class="box" style="margin-top: 20px">
    <div class="box-title">{{trans('game.edit-game')}}</div>
    <div class="box-content">

        @if($message)
        <div class="alert alert-danger">{{$message}}</div>
        @endif

        <form data-id="{{$game->id}}" id="game-edit-form" enctype="multipart/form-data" class="form-horizontal" method="post" action="">
            <div class="alert alert-info">{{trans('game.edit-info')}}</div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('game.title')}}</label>
                <div class="col-sm-7">
                    <input type="text" value="{{$game->title}}" class="form-control" placeholder="" name="val[title]"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('game.about')}}</label>
                <div class="col-sm-7">
                    <textarea class="form-control" name="val[description]">{{$game->description}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('game.category')}}</label>
                <div class="col-sm-7">
                    <select class="form-control" name="val[category]">
                        @foreach($categories as $category)
                        <option {{($category->id == $game->category) ? 'selected' : null}} value="{{$category->id}}">{{$category->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('game.game-file')}}</label>
                <div class="col-sm-7">
                    <span   class=" fileupload fileupload-exists" data-provides="fileupload">
                    <a title="{{trans('post.attach-photos')}}"  class="btn-file btn btn-success">
                        <span class="fileupload-new">{{trans('game.select-game')}}</span>
                        <span class="fileupload-exists">{{trans('game.select-game')}}</span>

                        <input  id="" multiple  class="" type="file" name="file">
                    </a>
                    </span>

                    <br/>

                    @if(!Config::get('disable-game-embed-code', true) or (Config::get('allow-admin-embed-game-code', false) and Auth::user()->isAdmin()))
                    <label class=" control-label">{{trans('game.or')}} {{trans('game.embed-code')}}</label><br/><br/>
                    <div class="">
                        <textarea data-height="80" placeholder="{{trans('game.embed-code')}}" style="height: 100px" class="form-control" name="val[content]">{{$game->iframe_content}}</textarea>
                    </div>
                    @endif
                </div>
            </div>

            <div class="form-group">

                <label class="col-sm-3 control-label">{{trans('game.dimensions')}}</label>
                <div class="col-sm-7">
                    <p class="help-block"><strong>{{trans('game.dimension-note')}}</strong> </p>
                    <label>{{trans('game.width')}}</label>
                    <input class="form-control" type="text" name="val[width]" value="{{$game->width}}" placeholder="{{trans('game.width')}}"/>
                    <label>{{trans('game.height')}}</label>
                    <input class="form-control" type="text" name="val[height]" value="{{$game->height}}" placeholder="{{trans('game.height')}}"/>
                </div>
            </div>


            <div class="form-group">

                <label class="control-label col-sm-3">{{trans('game.change-icon')}}:</label>
                <div class="col-sm-7">
                    <div class="alert alert-danger" style="display: none" id="game-image-error" >{{trans('photo.error', ['size' => formatBytes()])}}</div>
                    <div class="media">
                        <div style="width: 70px;height: 70px;overflow: hidden" class="media-object pull-left">
                            <img style="width: 100%" id="game-logo-image" src="{{$game->present()->getAvatar(150)}}"/>
                        </div>
                        <div class="media-body">

                                                <span style=""  class=" fileupload fileupload-exists" data-provides="fileupload">

                                                     <a     class=" btn btn-xs btn-success btn-file">
                                                         <span class="fileupload-new">{{trans('user.change-photo')}}</span>
                                                         <span class="fileupload-exists">{{trans('user.change-photo')}}</span>
                                                         <input title="" id="game-image-input" class="" type="file" name="image">
                                                     </a>


                                                 </span>


                        </div>
                    </div>
                </div>
            </div>



            @foreach($game->present()->fields() as $field)
            <div class="form-group">
                <label class="col-sm-4 control-label">{{trans($field->name)}}</label>
                <div class="col-sm-6 ">

                    @if($field->field_type == 'text')
                    <input type="text" class="form-control" value="{{$game->present()->field($field->id)}}" name="val[info][{{$field->id}}]"/>
                    @elseif($field->field_type == 'textarea')
                    <textarea class="form-control" name="val[info][{{$field->id}}]">{{$game->present()->field($field->id)}}</textarea>
                    @elseif($field->field_type == 'selection')
                    <select class="form-control" name="val[info][{{$field->id}}]">
                        <?php $options = unserialize($field->data)?>
                        @foreach($options as $option)
                        @if($option != '')
                        <option {{($game->present()->field($field->id) == $option) ? 'selected' : null}} value="{{$option}}">{{$option}}</option>
                        @endif
                        @endforeach
                    </select>
                    @endif
                    <p class="help-block">{{trans($field->description)}}</p>
                </div>

            </div>
            @endforeach




            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="{{trans('game.save-game')}}"/>
            </div>
        </form>
    </div>
</div>