<div style="padding-bottom: 10px" class="box">
    <div class="box-title">{{$profileUser->present()->fullName()}}
    {{trans('global.information')}}
    @if($profileUser->isOwner())
        <a href="{{URL::route('edit-profile')}}" data-ajaxify="true"><i class="icon ion-edit"></i> Edit Profile</a>
    @endif
     </div>


        <table class="profile-side-detail table table-striped">

            <thead>
                <th style="width: 30%"></th>
                <th style="width: 70%"></th>
            </thead>
            <tbody>

                <tr>
                    <td><strong>{{trans('user.date-joined')}} </strong></td>
                    <td><span class="post-time" ><span title="{{$profileUser->present()->joinedOn()}}">{{$profileUser->created_at}}</span></span> </td>
                </tr>

                <tr>
                    <td><strong>{{trans('user.last-login')}} :</strong></td>
                    <td><span class="post-time" ><span title="{{$profileUser->present()->lastLoginOn()}}">{{$profileUser->updated_at}}</span></span> </td>
                </tr>

                @if(Config::get('user-enable-birth-date', 1))
                    @if($profileUser->birth_day)
                        <tr>
                            <td><strong>{{trans('month.born-on')}}: </strong></td>
                            <td>
                                {{getMonthName($profileUser->birth_month)}}, {{$profileUser->birth_day}}

                                @if($profileUser->present()->canSeeBirth())
                                    ,{{$profileUser->birth_year}} ({{getProperAge($profileUser->birth_year, $profileUser->birth_day, $profileUser->birth_month)}} {{trans('month.years-old')}})
                                @endif
                            </td>
                        </tr>
                    @endif
                @endif
               @if($profileUser->country)
                    <tr>
                        <td><strong>{{trans('global.country')}}</strong></td>
                        <td>{{ucwords($profileUser->country)}}</td>
                    </tr>
               @endif

               @if($profileUser->city)
                <tr>
                    <td><strong>{{trans('global.city')}}</strong></td>
                    <td>{{ucwords($profileUser->city)}}</td>
                </tr>
               @endif

                @if($profileUser->genre)
                <tr>
                    <td><strong>{{trans('global.genre')}}</strong></td>
                    <td>{{trans('global.'.$profileUser->genre)}}</td>
                </tr>
                @endif
                @if($profileUser->bio)
                    <tr>
                        <td><strong>{{trans('user.bio')}}</strong></td>
                        <td>{{$profileUser->bio}}</td>
                    </tr>
                @endif

                @foreach($profileUser->present()->fields() as $field)
                    <?php $value = $profileUser->present()->profile($field->id)?>
                    @if(!empty($value))
                        <tr>
                            <td><strong>{{$field->name}}</strong></td>
                            <td>{{$profileUser->present()->profile($field->id)}}</td>
                        </tr>
                    @endif
                @endforeach

            </tbody>

        </table>



</div>