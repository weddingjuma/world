<div class="box">
    <div class="box-title">{{trans('community.create-a-community')}}</div>
    <div class="box-content">
        @if(!empty($message))
            <div class="alert alert-danger">{{$message}}</div>
        @endif
        <form  action="" method="post" class="community-create-form">
            <input type="hidden" name="val[privacy]" value="1" class="privacy"/>
            <div class="clearfix">
                    <a data-privacy="1" class="toggle-create" href="javascript:void(0)">
                        <div  data-class="left" class="left ">
                            <h3 class="title">{{trans('community.public')}}
                                <span>{{trans('community.public-info')}}</span>
                            </h3>

                            <div class="form-container">
                                <label class="control-label">{{trans('community.call-it')}}</label>
                                <input value="{{Input::get('val.public_name')}}" class="form-control" name="val[public_name]" type="text"/>

                                <br/>
                                <label class="control-label">{{trans('community.url')}}</label>
                                <input type="text" value="{{Input::get('val.public_url')}}" autocomplete="off" data-target="#public-slug"  class="form-control slug-input" name="val[public_url]" />
                                <p class="help-block">{{URL::to('/')}}/community/<strong><span id="public-slug"></span></strong></p>
                                <br/>
                                <label class="control-label">{{trans('community.who-can-post')}}</label>
                                <select class="form-control" name="val[can_post]">
                                    <option value="1">{{trans('community.all-members')}}</option>
                                    <option value="0">{{trans('community.only-you')}}</option>
                                </select>
                            </div>
                        </div>
                    </a>

                <a data-privacy="0" class="toggle-create" href="javascript:void(0)">
                    <div  class="right " data-class="right">
                        <h3 class="title">
                            {{trans('community.private')}}
                            <span>{{trans('community.private-info')}}</span>
                        </h3>

                        <div class="form-container">
                            <label class="control-label">{{trans('community.call-it')}}</label>
                            <input value="{{Input::get('val.private_name')}}" class="form-control" name="val[private_name]" type="text"/>

                            <br/>
                            <label class="control-label">{{trans('community.url')}}</label>
                            <input type="text" value="{{Input::get('val.private_url')}}" autocomplete="off" data-target="#private-slug"  class="form-control slug-input" name="val[private_url]" />
                            <p class="help-block">{{URL::to('/')}}/community/<strong><span id="private-slug"></span></strong></p>
                            <br/>
                            <label class="control-label">{{trans('community.can-people-search')}}</label>
                            <select class="form-control" name="val[searchable]">
                                <option value="1">{{trans('global.yes')}}</option>
                                <option value="0">{{trans('global.no')}}</option>
                            </select>
                        </div>

                    </div>

                </a>




            </div>
            <div class="divider"></div>

            <button class="btn btn-success btn-sm">{{trans('community.create')}}</button>
        </form>
    </div>
</div>