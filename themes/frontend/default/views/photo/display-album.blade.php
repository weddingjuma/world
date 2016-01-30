<div class="album">
    <a data-ajaxify="true" href="{{$album->user->present()->url('album/'.$album->slug)}}" class="default-photo">
        <img src="{{$album->defaultPhoto()}}"/>
    </a>
    <div class="info">
        <h3 class="" id="album-title-{{$album->id}}">{{Str::limit(ucwords($album->title), 23)}}</h3>
        <form style="display: none" data-album-id="{{$album->id}}" method="post" action="" class="photo-album-edit-form" id="photo-album-edit-form-{{$album->id}}">
            <input style="margin-bottom:3px" type="text" class="form-control input-sm" value="{{$album->title}}"/>
            <button class="btn btn-xs btn-default">{{trans('global.save')}}</button>
            <button  data-album-id="{{$album->id}}" class="btn photo-album-edit-cancel-button btn-xs btn-danger">{{trans('global.cancel')}}</button>
        </form>
        <i class="icon ion-ios7-photos-outline"></i> {{$album->countPhotos()}} {{trans('photo.photos')}}

        @if ( (Auth::check() and $album->user->id == \Auth::user()->id) or (\Auth::check() and \Auth::user()->isAdmin()))
            @if($album->slug != 'profile-photos' and $album->slug != 'posts')
                | <a data-message="{{trans('global.confirm-delete')}}" class="confirm-delete" href="{{URL::route('delete-album', ['id' => $album->id])}}">{{trans('global.delete')}}</a>
                | <a href="" data-album-id="{{$album->id}}" class="photo-album-edit-button">{{trans('photo.edit-album')}}</a>
            @else

            @endif
        @endif
    </div>

</div>