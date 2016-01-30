<div class="box">
    <div class="box-title">{{trans('game.add-games')}}</div>
    <div class="box-content">

        @if($message)
        <div class="alert alert-danger">{{$message}}</div>
        @endif

        <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
            <div class="alert alert-info">{{trans('game.add-game-info')}}</div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('game.title')}}</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" placeholder="{{trans('game.title')}}" name="val[title]"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('game.about')}}</label>
                <div class="col-sm-7">
                    <textarea data-height="50" placeholder="{{trans('game.about')}}" class="form-control" name="val[description]"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('game.category')}}</label>
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
                        <span class="fileupload-exists">{{trans('game.select-game')}}</span>

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
                        <span class="fileupload-exists">{{trans('game.select-icon')}}</span>

                        <input  id="" multiple  class="" type="file" name="icon">
                    </a>
                    </span>


                </div>
            </div>


            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="{{trans('game.add-game')}}"/>
            </div>
        </form>
    </div>
</div>