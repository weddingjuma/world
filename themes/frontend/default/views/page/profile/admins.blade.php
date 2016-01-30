<div class="box">
    <div class="box-title">{{trans('page.page-roles')}}</div>
    <div class="box-content">
        <div class="media user">
            <div class="media-object pull-left">
                <a href="{{$page->user->present()->fullName()}}">
                    <img src="{{$page->user->present()->getAvatar(100)}}"/>
                </a>
            </div>
            <div class="media-body">
                <a  data-ajaxify="true" href="{{$page->user->present()->url()}}">
                    <h5 class="media-heading">{{$page->user->fullname}} {{Theme::section('user.verified', ['user' => $page->user])}} <span></span> </h5>

                </a>
                <span>
                       {{trans('page.admin-roles')}}
                    </span>
            </div>
        </div>

        <div class="custom-admin-list">
            @foreach($admins as $admin)
                {{Theme::section('page.profile.format-admin', ['admin' => $admin])}}
            @endforeach
        </div>


        <div class="box-title">{{trans('page.add-new-admin')}}</div>
        <form class="add-admin-form" data-page-id="{{$page->id}}" action="" method="post">
            <div class="input-container">
                <input autocomplete="off" data-page-id="{{$page->id}}" placeholder="{{trans('page.add-admin-input')}}" type="text" name="val[name]" class="form-control"/>
                <input class="the-selected-user" type="hidden" name="val[userid]"/>
                <input type="hidden" name="val[pageid]" value="{{$page->id}}"/>
                <div class="selected-user">

                </div>

                <div class="suggestion-container">
                    <img class="indicator" src="{{Theme::asset()->img('theme/images/loading.gif')}}"/>
                    <div class="listing"></div>
                </div>
            </div>

            <p class="help-block" id="page-admin-form-role-info">{{trans('page.admin-roles')}}</p>
            {{trans('page.role')}} :
            <select
                class="page-admin-role-selection"
                data-admin = '{{trans('page.admin-roles')}}'
            data-moderator = "{{trans('page.moderator-roles')}}"
            data-editor = "{{trans('page.editor-roles')}}"
            data-target ="#page-admin-form-role-info"
            name="val[type]">
            <option value="1">{{trans('page.admin')}}</option>
            <option value="2">{{trans('page.editor')}}</option>
            <option value="3">{{trans('page.moderator')}}</option>
            </select>
            <button class="btn btn-danger btn-sm">{{trans('page.save-admin')}}</button>

        </form>
    </div>
</div>