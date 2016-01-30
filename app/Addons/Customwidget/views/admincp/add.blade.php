<div class="box">
    <div class="box-title">Add New Widget</div>
    <div class="box-content">

        @if($message)
            <div class="alert alert-danger">{{$message}}</div>
        @endif
        <form action="" method="post" >
            <div class="form-group">
                <label>Title</label>
                <input class="form-control" name="val[title]" placeholder="Widget title"/>
            </div>

            <div class="form-group">
                <label>Display In</label>
                <select class="form-control" name="val[page]">
                    @foreach(app('App\\Addons\\Customwidget\\Classes\\CustomWidgetRepository')->pages() as $page => $title)

                        <option value="{{$page}}">{{$title}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="val[status]">
                    <option value="1">Enabled</option>
                    <option value="0">Disabled</option>
                </select>
            </div>

            <div class="form-group">
                <label>Widget content</label>
                <textarea name="val[content]" class="pane-editor"></textarea>
                <p class="help-block">Widget content can contain any content html, css e.t.c</p>
            </div>

            <div class="form-group">
                <button class="btn btn-danger">Add Widget</button>
            </div>
        </form>
    </div>
</div>