<div class="box">
    <div class="box-title">Additional Pages</div>
    <div class="box-content">
        <form class="form-horizontal" action="" method="post">
                            <div class="form-group">
                                <label class="col-sm-4">Page Title</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" value="{{$page->title}}" name="val[title]"/>
                                    <p class="help-block">Note : This input uses translation so don't change it</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">Page Content</label>
                                <div class="col-sm-7">
                                    <textarea style="height: 400px" class="form-control" name="val[content]">{{$page->content}}</textarea>
                                </div>
                            </div>

            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Save"/>
            </div>

        </form>
    </div>
</div>