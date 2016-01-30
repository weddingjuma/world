<div class="box">
    <div class="box-title">Create Game Category</div>
    <div class="box-content">
        @if($message)
        <div class="alert alert-danger">{{$message}}</div>
        @endif
        <form class="form-horizontal" method="post" action="">
            <div class="alert alert-info">Add Your game categories with there description</div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Title</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" placeholder="Category title" name="val[title]"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Description</label>
                <div class="col-sm-7">
                    <textarea class="form-control" name="val[description]"></textarea>
                </div>
            </div>

            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Add Category"/>
            </div>
        </form>
    </div>
</div>