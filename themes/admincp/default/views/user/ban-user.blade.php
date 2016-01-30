<div class="box">
    <div  class="box-title">Are you sure you want to perform this action?</div>
    <div class="box-content">
        <form class="form-horizontal" action="" method="post">
            <div class="form-group">
                <label class="col-sm-4">Action</label>
                <div class="col-sm-7">
                    <select class="form-control" name="val[action]">
                        <option {{($action == 'ban') ? 'selected' : null}} value="ban">Ban</option>
                        <option {{($action == 'unban') ? 'selected' : null}} value="unban">Unban</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Additional Message</label>
                <div class="col-sm-7">
                   <textarea class="form-control" name="val[message]" style="height: 300px"></textarea>
                </div>
            </div>

            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Perfom Action"/>
            </div>
        </form>
    </div>
</div>