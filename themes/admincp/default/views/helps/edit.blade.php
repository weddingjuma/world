<div class="box">
    <div class="box-title">Edit Help</div>
    <div class="box-content">
        <form class="form-horizontal" action="" method="post">

                            <div class="form-group">
                                <label class="col-sm-4">Title</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" value="{{$help->title}}" name="val[title]"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">Content</label>
                                <div class="col-sm-7">
                                    <textarea style="height: 300px" class="form-control"  name="val[content]">{{$help->content}}</textarea>
                                </div>
                            </div>

            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Save"/>
            </div>


        </form>
    </div>
</div>