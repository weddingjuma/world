<div class="box">
    <div class="box-title">{{trans('page.create-page')}}</div>
    <div class="box-content">
        @if($message)
            <div class="alert alert-danger">{{$message}}</div>
        @endif
        <form class="form-horizontal" method="post" action="">
            <div class="form-group">
                <label class="control-label col-sm-3">{{trans('page.name')}}:</label>
                <div class="col-sm-6">
                    <input type="text" value="{{Input::get('val.title')}}" class="form-control" name="val[title]" />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">{{trans('page.url')}}:</label>
                <div class="col-sm-6">
                    <input type="text" value="{{Input::get('val.url')}}" autocomplete="off" data-target="#page-slug"  class="form-control slug-input" name="val[url]" />
                    <p class="help-block">{{URL::to('/')}}/page/<strong><span id="page-slug"></span></strong></p>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">{{trans('page.website')}}:</label>
                <div class="col-sm-6">
                    <input type="text" value="{{Input::get('val.website')}}" class="form-control" name="val[website]" />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">{{trans('page.short-description')}}:</label>
                <div class="col-sm-6">
                    <input type="text" value="{{Input::get('val.description')}}" class="form-control" name="val[description]" />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">{{trans('page.type-of-page')}}:</label>
                <div class="col-sm-6">
                    <select class="form-control" name="val[category]">
                        @foreach($categories as $category)
                            <option {{(Input::get('val.category') == $category->id) ? 'selected' : null}} value="{{$category->id}}">{{$category->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="divider"></div>

            <button class="btn btn-success btn-sm">{{trans('page.create-page')}}</button>
        </form>
    </div>
</div>