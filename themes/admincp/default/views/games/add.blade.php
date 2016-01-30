<div class="box">
    <div class="box-title">Add Games</div>
    <div class="box-content">

        @if($message)
        <div class="alert alert-danger">{{$message}}</div>
        @endif

        <form enctype="multipart/form-data" class="form-horizontal" method="post" action="">
            <div class="alert alert-info">Add Your games with there full details from here</div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Title</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" placeholder="Category title" name="val[title]"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">About</label>
                <div class="col-sm-7">
                    <textarea class="form-control" name="val[description]"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Game Category</label>
                <div class="col-sm-7">
                    <select class="form-control" name="val[category]">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->title}}</option>
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
                        <input  id="" multiple  class="" type="file" name="file">
                    </a>
                    </span>

                    <br/>

                    @if(!Config::get('disable-game-embed-code', true) or (Config::get('allow-admin-embed-game-code', false) and Auth::user()->isAdmin()))
                        <label class=" control-label">{{trans('game.or')}} {{trans('game.embed-code')}}</label><br/><br/>
                        <div class="">
                            <textarea data-height="80" placeholder="{{trans('game.embed-code')}}" style="height: 100px" class="form-control" name="val[content]"></textarea>
                        </div>
                    @endif


                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('game.dimensions')}}</label>
                <div class="col-sm-7">
                    <p class="help-block"><strong>{{trans('game.dimension-note')}}</strong> </p>
                    <label>{{trans('game.width')}}</label>
                    <input class="form-control" type="text" name="val[width]" value="100%" placeholder="{{trans('game.width')}}"/>
                    <label>{{trans('game.height')}}</label>
                    <input class="form-control" type="text" name="val[height]" value="450" placeholder="{{trans('game.height')}}"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('game.game-icon')}}</label>
                <div class="col-sm-7">
                    <span   class=" fileupload fileupload-exists" data-provides="fileupload">
                    <a title="{{trans('post.attach-photos')}}"  class="btn-file btn btn-success">
                        <span class="fileupload-new">{{trans('game.select-icon')}}</span>


                        <input  id="" multiple  class="" type="file" name="icon">
                    </a>
                    </span>


                </div>
            </div>

            <!---Admin features--->
            <div class="form-group">
                <label class="col-sm-3 control-label">Verified</label>
                <div class="col-sm-7">
                    <select class="form-control" name="val[verified]">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>



            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Add Game"/>
            </div>
        </form>
    </div>
</div>