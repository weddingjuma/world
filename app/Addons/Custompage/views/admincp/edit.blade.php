<div class="box">
    <div class="box-title">Edit Custom Page</div>
    <div class="box-content">
        @if (!empty($message))
        <div class="alert alert-danger">{{$message}}</div>
        @endif
        <form class="form-horizontal" action="" method="post">


            <div class="form-group">
                <label class="col-sm-4">Title</label>
                <div class="col-sm-7">
                    <input type="text" value="{{$page->title}}" class="form-control" placeholder="Page title" name="val[title]"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Meta Keywords</label>
                <div class="col-sm-7">
                    <input type="text" value="{{$page->keywords}}" class="form-control" name="val[keywords]"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Meta Description</label>
                <div class="col-sm-7">
                    <textarea name="val[description]" class="form-control" placeholder="Meta description">{{$page->description}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Tags</label>
                <div class="col-sm-7">
                    <input value="{{$page->tags}}" type="text" class="form-control" name="val[tags]"/>
                    <p class="helper-block">Seperate tags with comma</p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Page Access</label>
                <div class="col-sm-7">
                    <select id="custom-field-type" class="form-control" name="val[privacy]">
                        <option {{($page->privacy == 0) ? 'selected' : null}} value="0">Public</option>
                        <option {{($page->privacy == 1) ? 'selected' : null}} value="1">Only Members</option>
                        <option {{($page->privacy == 2) ? 'selected' : null}} value="2">Admins</option>
                    </select>


                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Add Comments</label>
                <div class="col-sm-7">
                    <select id="custom-field-type" class="form-control" name="val[comments]">
                        <option {{($page->show_comments == 1) ? 'selected' : null}} value="1">Yes</option>
                        <option {{($page->show_comments == 0) ? 'selected' : null}} value="0">No</option>

                    </select>


                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-4">Add Likes</label>
                <div class="col-sm-7">
                    <select id="custom-field-type" class="form-control" name="val[likes]">
                        <option {{($page->show_likes == 1) ? 'selected' : null}} value="1">Yes</option>
                        <option {{($page->show_likes == 0) ? 'selected' : null}} value="0">No</option>

                    </select>


                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Active</label>
                <div class="col-sm-7">
                    <select id="custom-field-type" class="form-control" name="val[active]">
                        <option {{($page->active == 1) ? 'selected' : null}} value="1">Yes</option>
                        <option {{($page->active == 0) ? 'selected' : null}} value="0">No</option>
                    </select>


                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Show In Menu</label>
                <div class="col-sm-7">
                    <select id="custom-field-type" class="form-control" name="val[show_menu]">
                        <option {{($page->show_menu == 1) ? 'selected' : null}} value="1">Yes</option>
                        <option {{($page->show_menu == 0) ? 'selected' : null}} value="0">No</option>

                    </select>


                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Content</label>
                <div class="col-sm-7">
                    <textarea name="val[content]" class="form-control editor" placeholder="Page content">{{$page->content}}</textarea>

                </div>
            </div>



            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Submit"/>
            </div>

        </form>
    </div>
</div>