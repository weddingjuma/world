<div class="form-group">
    <label for="exampleInputPassword1">{{trans('month.birthdate')}}</label><br/>
    <select style="width: 30%" name="val[birth_day]">
        <option value="">{{trans('month.day')}}</option>
        @for($i = 1;$i <= 31;$i++)
            <option {{(isset($day) and $day == $i) ? 'selected' :  null}} value="{{$i}}">{{$i}}</option>
        @endfor
    </select>

    <select style="width: 38%" name="val[birth_month]">
        <option value="">{{trans('month.month')}}</option>
        @foreach(Config::get('months.list') as $monthId => $name)
            <option {{(isset($month) and $month == $monthId) ? 'selected' :  null}} value="{{$monthId}}">{{trans($name)}}</option>
        @endforeach
    </select>

    <select  style="width: 30%" name="val[birth_year]">
        <option value="">{{trans('month.year')}}</option>
        <?php

        $start = Config::get('birth-year-max', '');
        $start = (empty($start)) ? date('Y') : $start;
        $end = Config::get('birth-year-max', '');
        $end = (empty($end)) ? 1940 : $end;

        ?>
        @for($i = $start; $i>=1940;$i--)


            <option {{(isset($year) and $year == $i) ? 'selected' :  null}} value="{{$i}}">{{$i}}</option>

        @endfor
    </select>

</div>