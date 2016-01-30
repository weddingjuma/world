<div class="box">
    <div class="box-title">Ads Management</div>
    <div class="box-content">
        <div class="alert alert-info">For now only two ads position is available, the header and the sidebar ads.
            <strong>So below you can place your codes for each of them</strong>
        </div>

        @if($message)
        <div class="alert alert-success">{{$message}}</div>
        @endif

        <form class="form-horizontal" method="post" action="">

            <div class="form-group">
                <label class="col-sm-3 control-label">Header Ads</label>
                <div class="col-sm-7">
                    <textarea style="height: 150px" class="form-control" name="val[header]">{{$header}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Sidebar Ads</label>
                <div class="col-sm-7">
                    <textarea style="height: 150px" class="form-control" name="val[side]">{{$side}}</textarea>
                </div>
            </div>






            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Save Ads"/>
            </div>
        </form>
    </div>
</div>