<div class="box" style="margin-top: 20px">
    <div class="box-title">Photo Albums</div>
    <div class="photo-albums box-content clearfix">
        @if ($profileUser->isOwner())
            <div class="album album-create">
                <span>
                    <a class="album-create-button" href=""><i class="icon ion-plus"></i></a>
                    <p>{{trans('photo.add-album')}}</p>
                </span>
                <form action="" method="post">
                    <div class="form-group">
                        <label class="control-label">{{trans('photo.album-name')}}:</label>
                        <input class="form-control input-sm" type="text" />
                        <button class="btn btn-success btn-sm">{{trans('global.add')}}</button>
                    </div>
                </form>
            </div>
        @endif

        @foreach($albums as $album)
            {{Theme::section('photo.display-album', ['album' => $album])}}
        @endforeach
    </div>
</div>