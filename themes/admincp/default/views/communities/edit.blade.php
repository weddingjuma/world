<div class="box">
    <div class="box-title">Edit Community ({{$community->title}})</div>
    <div class="box-content">
        @if($message)
            <div class="alert alert-success">{{$message}}</div>
        @endif
        <form class="form-horizontal" action="" method="post">
            <div class="form-group">
                <label class="col-sm-3 control-label">Title</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" value="{{$community->title}}" name="val[title]"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Description</label>
                <div class="col-sm-7">
                    <textarea class="form-control" name="val[description]">{{$community->description}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Type</label>
                <div class="col-sm-7">
                    <select name="val[type]">
                        <option {{($community->privacy == 1) ? 'selected' : ''}} value="1">Public</option>
                        <option  {{($community->privacy == 0) ? 'selected' : ''}} value="0">Private</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Searchable</label>
                <div class="col-sm-7">
                    <select name="val[searchable]">
                        <option {{($community->searchable == 1) ? 'selected' : ''}} value="1">Yes</option>
                        <option  {{($community->searchable == 0) ? 'selected' : ''}} value="0">No</option>
                    </select>
                </div>
            </div>

            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Save Community"/>
            </div>
        </form>
    </div>
</div>