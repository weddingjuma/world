<ul class="nav user-action-buttons">
    @if($page->isOwner() or $page->present()->isAdmin() or $page->present()->isEditor())
    <li>
        <a data-ajaxify="true" href="{{$page->present()->url('edit')}}">
            <i class="icon ion-compose"></i> <span>{{trans('page.edit-page')}}</span>
        </a>
    </li>
    @endif

    @if($page->present()->isAdmin())
    <li>
        <a data-ajaxify="true" href="{{$page->present()->url('roles')}}">
            <i class="icon ion-ios7-people"></i> <span>{{trans('page.page-roles')}}</span>
        </a>
    </li>
    @endif

    @if($page->isOwner() or $page->present()->isAdmin() or $page->present()->isEditor())
        @if(Config::get('page-design'))
            <li>
                <a data-ajaxify="true" href="{{$page->present()->url('design')}}">
                    <i class="icon ion-wrench"></i> <span>{{trans('page.design-page')}}</span>
                </a>
            </li>
        @endif
    @endif

    {{Theme::extend('page-side-menu-list')}}
</ul>

<div class="box">
    <div class="box-title">{{trans('global.about')}} {{$page->title}}</div>
    <div class="box-conten" style="padding: 0 10px">
        <div class="page-like">
            <i class="icon ion-thumbsup"></i> <span class="post-like-count-{{$page->id}}">{{$page->countLikes()}}</span> {{trans('like.likes')}}
        </div>
    </div>
        @if(Auth::check())
            <div class="friends-inviter">
                <div class="box-title">
                    <input data-page-id="{{$page->id}}" type="text" class="form-control page-friends-inviter-search" placeholder="{{trans('page.invite-friends-placeholder')}}"/>
                </div>
                <div class="box-content" data-offset="0"  data-page-id="{{$page->id}}" id="page-inviter-members">
                    @foreach(app('App\\Repositories\\PageRepository')->friendsToLike($page->id) as $user)
                        {{Theme::section('page.profile.display-invite-user', ['user' => $user, 'page' => $page])}}
                    @endforeach
                </div>
            </div>
        @endif
    <div class="box-content"  style="margin: 0;padding-bottom: 0">
        <table class="profile-side-detail table table-striped">

            <tbody>

            <tr>
                <td><strong>{{trans('user.date-created')}} :</strong></td>
                <td><span class="post-time" ><span title="{{$page->present()->joinedOn()}}">{{$page->created_at}}</span></span> </td>
            </tr>


            @if($page->description)
                <tr>
                    <td><strong>{{trans('global.about')}}</strong></td>
                    <td>{{$page->description}}</td>
                </tr>
            @endif

            @if($page->website)
                <tr>
                    <td><strong>{{trans('global.website')}}</strong></td>
                    <td><a href="{{$page->website}}">{{$page->website}}</a> </td>
                </tr>
            @endif

            @foreach($page->present()->fields() as $field)
                <?php $value = $page->present()->field($field->id)?>

                @if($value)
                    <tr>
                        <td><strong>{{$field->name}}</strong></td>
                        <td>{{$page->present()->field($field->id)}}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>

        </table>

    </div>
</div>