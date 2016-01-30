<div class="box">
    <div class="box-title">Edit Page</div>
    <div class="box-content">
        @if($message)
        <div class="alert alert-danger">{{$message}}</div>
        @endif
        <form class="form-horizontal" method="post" action="">
            <div class="alert alert-info">Savely edit this page</div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Title</label>
                <div class="col-sm-7">
                    <input disabled="disabled" type="text" class="form-control" value="{{$page->title}}" placeholder="Category title" name="val[title]"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Description</label>
                <div class="col-sm-7">
                    <textarea class="form-control" name="val[description]">{{$page->description}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Verified</label>
                <div class="col-sm-7">
                    <select name="val[verified]">
                        <option {{($page->verified == 0)?'selected' : null}} value="0">No</option>
                        <option {{($page->verified == 1)?'selected' : null}}  value="1">Yes</option>
                    </select>
                </div>
            </div>

            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Save Page"/>
            </div>
        </form>
    </div>
</div>