<div class="box">
    <div class="box-title">
        {{trans('search.result-members')}}
    </div>


    <div class="box-content">
        <form action="" method="get">
            <label>{{trans('global.filter-by')}} :</label>
            <input type="hidden" name="term" value="{{$searchRepository->term}}"/>
            <select name="gender">
                <option {{(Input::get('gender') == 'both') ? 'selected' : null}} value="both">{{trans('global.both')}}</option>
                <option {{(Input::get('gender') == 'male') ? 'selected' : null}} value="male">{{trans('global.male')}}</option>
                <option {{(Input::get('gender') == 'female') ? 'selected' : null}} value="female">{{trans('global.female')}}</option>
            </select>

            <select style="margin: 5px 0"  name="country">
                <option value="all">{{trans('global.all-country')}}</option>
                @foreach(Config::get('system.countries') as $country => $name)
                <option {{(Input::get('country') == $country) ? 'selected' : null}} value="{{$country}}">{{$name}}</option>
                @endforeach
            </select>

            <input value="{{sanitizeText(Input::get('city'))}}" style="height: 28px;margin: 5px 0" type="text" name="city"  placeholder="{{trans('global.city')}}"/>
            <button style="height: 28px;margin: 5px 0"  class="btn btn-danger btn-sm"><i class="icon ion-search"></i></button>
        </form>

    </div>
    <div class="divider"></div>
    <div class="box-content">
        @if (!count($users))
            <div class="alert alert-danger">{{trans('search.no-member')}}</div>
        @endif
        @foreach($users as $user)
            {{Theme::section('user.display', ['user' => $user])}}
        @endforeach

        <?php
            $appends = ['term' => Input::get('term')];
            if (Input::get('gender')) {
                $appends['gender'] = Input::get('gender');
            }

            if (Input::get('country')) {
                $appends['country'] = Input::get('country');
            }

            if (Input::get('city')) {
                $appends['city'] = Input::get('city');
            }
        ?>

        {{$users->appends($appends)->links()}}
    </div>
</div>