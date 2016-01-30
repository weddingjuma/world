<div class="box">
    <div class="box-title">Add Newsletter</div>
    <div class="box-content">
        @if($message)
        <div class="alert alert-danger">{{$message}}</div>
        @endif

        <form class="form-horizontal" method="post" action="">
            <div class="alert alert-info">You can send newsletter to all your members or only selected members by comma seperated there IDs</div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Subject</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" placeholder="Newsletter subject" name="val[subject]"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Newsletter Content</label>
                <div class="col-sm-7">
                    <textarea style="height: 150px" class="form-control" name="val[content]"></textarea>
                </div>
            </div>



            <div class="form-group">
                <label class="col-sm-3 control-label">To</label>
                <div class="col-sm-7">
                    <select class="newsletter-to" name="val[to]">
                        <option value="all">All Members</option>
                        <option value="selected">Selected Members</option>
                    </select>

                    <div class="selected-members" style="margin-top: 20px;display: none">
                        <textarea class="form-control" name="val[selected]"></textarea>
                        <p class="helper-text">Enter the selected members IDs seperated by comma(,)</p>
                    </div>
                </div>
            </div>




            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Send Newsletter"/>
            </div>
        </form>
    </div>
</div>