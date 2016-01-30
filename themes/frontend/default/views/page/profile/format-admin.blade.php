<div class="media user" id="page-admin-{{$admin->id}}">
    <div class="media-object pull-left">
        <a href="{{$admin->user->present()->fullName()}}">
            <img src="{{$admin->user->present()->getAvatar(100)}}"/>
        </a>
    </div>
    <div class="media-body">
        <a  data-ajaxify="true" href="{{$admin->user->present()->url()}}">
            <h5 class="media-heading">{{$admin->user->fullname}} {{Theme::section('user.verified', ['user' => $admin->user])}} <span></span> </h5>

        </a>
                <span id="page-admin-form-role-info-{{$admin->id}}">
                       @if($admin->type == 1)
                            {{trans('page.admin-roles')}}
                       @elseif($admin->type == 2)
                            {{trans('page.editor-roles')}}
                       @else
                            {{trans('page.moderator-roles')}}
                       @endif
                    </span>
                <form class="each-page-admin-form" data-admin-id="{{$admin->id}}" style="display: inline-block" action="" method="post">
                    <select
                        class="page-admin-role-selection"
                        data-admin = '{{trans('page.admin-roles')}}'
                        data-moderator = "{{trans('page.moderator-roles')}}"
                        data-editor = "{{trans('page.editor-roles')}}"
                        data-target ="#page-admin-form-role-info-{{$admin->id}}"
                        name="val[type]" style="border: none">
                        <option value="1" {{($admin->type == 1) ? 'selected' : null}}>{{trans('page.admin')}}</option>
                        <option value="2" {{($admin->type == 2) ? 'selected' : null}}>{{trans('page.editor')}}</option>
                        <option value="3" {{($admin->type == 3) ? 'selected' : null}}>{{trans('page.moderator')}}</option>
                    </select>
                    <span style="position: relative"><button class="btn btn-danger btn-xs">{{trans('global.save')}}</button> <a  data-admin="{{$admin->id}}" class="remove-page-admin btn btn-success btn-xs" href=""><i class="icon ion-close"></i></a></span>
                </form>
    </div>
</div>