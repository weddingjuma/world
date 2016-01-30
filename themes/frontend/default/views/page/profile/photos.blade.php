<div class="box photos-header">
    <h4 class="title"><i class="icon ion-image"></i> {{trans('photo.photos')}}</h4>

    <div class="photo-action-button">

        @if($page->present()->isAdmin() or $page->present()->isEditor())
            <form id="page-add-photos-form"  action="" method="post" enctype="multipart/form-data">
                <input type="hidden" value="{{$page->id}}" name="val[id]"/>
                <span style="display:inline-block;overflow: hidden;max-width: 300px;height: 40px;padding-top:5px"   class=" fileupload fileupload-exists" data-provides="fileupload">

                <a  data-url="{{$page->present()->url('photos')}}?type=p" data-current="{{$current}}" class="btn-file btn-danger btn-sm">
                    <img  style="width: 20px;display: none" src="{{Theme::asset()->img('theme/images/loading.gif')}}"/>
                    <span class="fileupload-new">{{trans('photo.add-photos')}}</span>
                    <span class="fileupload-exists">{{trans('photo.add-photos')}}</span>

                    <input data-current="{{$current}}" data-page-id="{{$page->id}}"  id="page-add-image-input" multiple  class="" type="file" name="image[]">
                </a>
            </span>
            </form>
        @endif

    </div>
    <div id="page-add-photo-error" class="alert alert-danger alert-dismissable" style="margin-bottom: 0;display: none">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{trans('photo.error', ['size' => formatBytes()])}}
    </div>


</div>


<div class="box" style="margin-top: 0px">

    <div id="page-photos-container" class="photo-albums clearfix">
        @if(count($photos))
            @foreach($photos as $photo)
            {{Theme::section('photo.display-photo', ['photo' => $photo])}}
            @endforeach
        @endif
    </div>

    {{$photos->links()}}

</div>
