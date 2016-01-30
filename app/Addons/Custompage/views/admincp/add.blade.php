<div class="box">
    <div class="box-title">Add Custom Page</div>
    <div class="box-content">
        @if (!empty($message))
        <div class="alert alert-danger">{{$message}}</div>
        @endif
        <form class="form-horizontal" action="" method="post">


            <div class="form-group">
                <label class="col-sm-4">Title</label>
                <div class="col-sm-7">
                    <input type="text" value="{{Input::get('val.title')}}" class="form-control" placeholder="Page title" name="val[title]"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Meta Keywords</label>
                <div class="col-sm-7">
                    <input type="text" value="{{Input::get('val.keywords')}}" class="form-control" name="val[keywords]"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Meta Description</label>
                <div class="col-sm-7">
                    <textarea name="val[description]" class="form-control" placeholder="Meta description">{{Input::get('val.description')}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Tags</label>
                <div class="col-sm-7">
                    <input value="{{Input::get('val.tags')}}" type="text" class="form-control" name="val[tags]"/>
                    <p class="helper-block">Seperate tags with comma</p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Page Access</label>
                <div class="col-sm-7">
                    <select id="custom-field-type" class="form-control" name="val[privacy]">
                        <option value="0">Public</option>
                        <option value="1">Only Members</option>
                        <option value="2">Admins</option>
                    </select>


                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Add Comments</label>
                <div class="col-sm-7">
                    <select id="custom-field-type" class="form-control" name="val[comments]">
                        <option value="1">Yes</option>
                        <option value="0">No</option>

                    </select>


                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-4">Add Likes</label>
                <div class="col-sm-7">
                    <select id="custom-field-type" class="form-control" name="val[likes]">
                        <option value="1">Yes</option>
                        <option value="0">No</option>

                    </select>


                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Active</label>
                <div class="col-sm-7">
                    <select id="custom-field-type" class="form-control" name="val[active]">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>


                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Show In Menu</label>
                <div class="col-sm-7">
                    <select id="custom-field-type" class="form-control" name="val[show_menu]">
                        <option value="1">Yes</option>
                        <option value="0">No</option>

                    </select>


                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Content</label>
                <div class="col-sm-7">
                    <textarea name="val[content]" class="form-control editor" placeholder="Page content">{{Input::get('val.content')}}</textarea>

                </div>
            </div>



            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Submit"/>
            </div>

        </form>
    </div>
</div>