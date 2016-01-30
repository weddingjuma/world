<div class="box" style="margin-top: 20px">
    <div class="box-title">{{trans('photo.photos')}}</div>

    <div id="album-add-photo-error" class="alert alert-danger alert-dismissable" style="margin-bottom: 0;display: none">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{trans('photo.error', ['size' => formatBytes()])}}
    </div>
    <div class="box-content">
        <div class="photo-albums clearfix">
            @if ($profileUser->isOwner() and !in_array($album->slug, ['posts','profile-photos']))
                    <div class="album album-create photo-add photo">

                        <form id="photos-upload-form" action="" enctype="multipart/form-data" method="post">
                            <span class=" fileupload fileupload-exists" data-provides="fileupload">

                                       <a style="display: block" title="Add photos"  class="btn-file">
                                             <span class="fileupload-new"><i class="icon ion-plus"></i></span>
                                             <span class="fileupload-exists"><i class="icon ion-plus"></i></span>
                                             <input data-album-id="{{$album->id}}"  id="album-image-input" multiple class="" type="file" name="image[]">
                                       </a>
                             </span>

                            <p>{{trans('photo.add-photos')}}</p>
                        </form>

                    </div>
                @endif

            @foreach($photos as $photo)
                {{Theme::section('photo.display-photo', ['photo' => $photo])}}
            @endforeach
        </div>

        {{$photos->links()}}
    </div>
</div>