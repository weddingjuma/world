<div class="container clearfix">
    <div class="getstarted-left">

        <div class="content">
            <h3>{{trans('user.welcome')}}, {{$loggedInUser->username}}.</h3>
            <p>
                {{trans('user.getstarted-welcome-note')}}
            </p>
        </div>
        <div class="arrow-down"></div>

        <div class="box">
            <form enctype="multipart/form-data" id="getstarted-form" action="" method="post">
                <div class="box-content">


                    <div class="media">
                        <div class="media-object pull-left">
                            <img src="{{$loggedInUser->present()->getAvatar(100)}}"/>
                        </div>
                        <div class="media-body">
                                    <span style=""  class=" fileupload fileupload-exists" data-provides="fileupload">

                                         <a    class=" btn btn-danger btn-file">
                                             <span class="fileupload-new">{{trans('user.change-photo')}}</span>
                                             <span class="fileupload-exists">{{trans('user.change-photo')}}</span>
                                             <input title="" id="getstarted-image-input" class="" type="file" name="image">
                                         </a>


                                     </span>

                                    <p class="help-block">{{trans('user.change-photo-info')}}</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputFile">{{trans('user.bio')}}</label>
                        <textarea name="bio" class="form-control">{{$loggedInUser->bio}}</textarea>
                        <p class="help-block">{{trans('user.bio-info')}}</p>
                    </div>

                    <div class="form-group">
                        <label>{{trans('global.city')}}</label>
                        <input class="form-control" type="text" name="city" placeholder="{{trans('global.your-city')}}"/>
                    </div>

                    {{Theme::extend("getstarted-left-extend")}}
                </div>

                <div class="getstarted-footer">
                    <input type="submit" class="btn-success btn-sm btn" value="{{trans('global.save')}}">

                    <button class="btn btn-danger btn-sm pull-right">{{trans('global.skip')}}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="getstarted-right">
        <h2 class="title">{{trans('user.see-who-here')}}
            <span>{{trans('user.see-who-here-info')}}</span>
        </h2>
        <div class="member-container">

        </div>

        <div class="getstarted-footer">
            <a disabled="disabled" id="gestarted-continue-button" href="{{URL::route('getstarted.finish')}}" class="btn btn-default">{{trans('global.continue')}}</a>
        </div>
    </div>
</div>