<div class="box">
    <div class="box-title">{{trans('admincp.add-language')}}</div>

    <div class="box-content">


        <form class="form-horizontal" method="post">
            <div class="form-group">
                <label class=" col-sm-3">{{trans('admincp.language-var')}}</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" placeholder="{{trans('global.var')}}" name="val[var]"/>
                    <span class="help-block">For example: en</span>
                </div>
            </div>

            <div class="form-group">
                <label class=" col-sm-3">{{trans('admincp.language-name')}}</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" placeholder="{{trans('global.name')}}" name="val[name]"/>
                    <span class="help-block">For example: English Language</span>
                </div>
            </div>

            <input type="submit" class="btn btn-danger no-radius" value="{{trans('admincp.add-language')}}"/>
        </form>
    </div>
</div>