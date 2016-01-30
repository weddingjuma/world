<h4>{{trans('design.choose-from-theme')}}</h4>
        <div class="divider"></div>
<div class="themes" id="themes">
    @foreach(Theme::option()->get('design-themes') as $key => $design)
                <a
                    background-color="{{$design['background-color']}}"
                    background-attachment="{{$design['background-attachment']}}"
                    background-postion="{{$design['background-position']}}"
                    link-color="{{$design['link-color']}}"
                    page-content-bg-color="{{$design['page-content-bg-color']}}"
                    background-image="{{$design['background-image']}}"
                    background-repeat="{{$design['background-repeat']}}"
                    data-toggle="design"
                    data-key = "{{$key}}"
                    class = "{{($user->present()->design('theme', $type, 'default') == $key) ? 'selected' : null}}"
                    href=""><img class="img-responsive" src="{{$design['preview-image']}}"/> </a>
    @endforeach
</div>
        <div class="divider"></div>
        <h4>{{trans('design.customize-yours')}}</h4>
        <div class="divider"></div>
        <form enctype="multipart/form-data" id="design-form" class="form-horizontal" action="" method="post">
            <input type="hidden" value="{{$user->present()->design('theme', $type)}}" name="val[theme]" class="user-selected-theme"/>
            <input type="hidden" value="{{$type}}" name="val[type]"/>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('design.enable-design')}}</label>
                <div class="col-sm-7">
                    <input id="user-enable-design"  type="checkbox" {{($user->present()->design('enable', $type)) ? 'checked="checked"' : null}} name="val[enable]"> {{trans('design.enable-my-design')}}
                    <p class="help-block">{{trans('design.enable-design-note')}}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('design.background-image')}}</label>
                <div class="col-sm-7">
                        <div class="page-bg-upload-error alert alert-danger" style="display: none">{{trans('photo.error', ['size' => formatBytes()])}}</div>
                                <div class="media">
                                    <div class="media-object pull-left bg-preview-image-container">
                                        <?php $image = $user->present()->design('bg_image', $type)?>
                                        @if($image)
                                            <img class="bg-preview-image img-responsive" src="{{\Image::url($image)}}"/>
                                        @else
                                            <img class="bg-preview-image img-responsive" src="{{Theme::asset()->img('theme/images/design/previews/default.png')}}"/>
                                        @endif
                                    </div>
                                    <div class="media-body">
                                                <span style=""  class=" fileupload fileupload-exists" data-provides="fileupload">

                                                     <a    class=" btn  btn-file btn-sm btn-default">
                                                         <span class="fileupload-new">{{trans('design.choose-image')}}</span>
                                                         <span class="fileupload-exists">{{trans('design.choose-image')}}</span>
                                                         <input data-current="{{$user->present()->design('bg_image', $type)}}" title="" id="background-image-input" class="" type="file" name="image">
                                                         <input type="hidden" value="{{$user->present()->design('bg_image', $type)}}" name="val[bg_image]"/>
                                                     </a>


                                                 </span>

                                                <p class="help-block">{{trans('design.choose-image-note')}}</p>
                                    </div>
                                </div>

                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('design.background-color')}}</label>
                <div class="col-sm-7">
                    <input id="bgcolorpicker" class="width100 form-control" type="text" value="{{$user->present()->design('bg_color', $type)}}" name="val[bg_color]">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('design.background-position')}}</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control " id="bg-position-input" value="{{$user->present()->design('bg_position', $type)}}" name="val[bg_position]"/>
                    <p class="help-block">{{trans('design.background-position-note')}}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('design.background-attachment')}}</label>
                <div class="col-sm-7">
                    <select class="form-control" id="bg-attachment-input" name="val[bg_attachment]">
                        <option {{($user->present()->design('bg_attachment', $type) == 'scroll') ? 'selected' : null}}  value="scroll">{{trans('design.scroll')}}</option>
                        <option {{($user->present()->design('bg_attachment', $type) == 'fixed') ? 'selected' : null}}  value="fixed">{{trans('design.fixed')}}</option>

                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('design.background-repeat')}}</label>
                <div class="col-sm-7">
                    <select class="form-control" id="bg-repeat-input" name="val[bg_repeat]">
                        <option {{($user->present()->design('bg_repeat', $type) == 'no-repeat') ? 'selected' : null}}  value="no-repeat">{{trans('design.no-repeat')}}</option>
                        <option {{($user->present()->design('bg_repeat', $type) == 'repeat') ? 'selected' : null}}  value="repeat">{{trans('design.repeat')}}</option>
                        <option {{($user->present()->design('bg_repeat', $type) == 'repeat-x') ? 'selected' : null}}  value="repeat-x">{{trans('design.repeat-x')}}</option>
                        <option {{($user->present()->design('bg_repeat', $type) == 'repeat-y') ? 'selected' : null}}  value="repeat-y">{{trans('design.repeat-y')}}</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('design.link-color')}}</label>
                <div class="col-sm-7">
                    <input id="linkcolorpicker" class="width100 form-control" type="text" value="{{$user->present()->design('link_color', $type)}}" name="val[link_color]">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{trans('design.page-content')}}</label>
                <div class="col-sm-7">
                    <input id="pagecolorpicker" contenteditable="false" class="width100 form-control" type="text" value="{{$user->present()->design('content_bg_color', $type)}}" name="val[content_bg_color]">
                </div>
            </div>

            <div class="divider"></div>

            <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-sm btn-success">{{trans('design.save-design')}}</button>
                        </div>
            </div>
        </form>

