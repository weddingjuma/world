<div class="box">
    <div class="box-title">Edit Widget</div>
    <div class="box-content">
        @if($message)
        <div class="alert alert-danger">{{$message}}</div>
        @endif
        <form action="" method="post" >
            <div class="form-group">
                <label>Title</label>
                <input value="{{$widget->title}}" class="form-control" name="val[title]" placeholder="Widget title"/>
            </div>

            <div class="form-group">
                <label>Display In</label>
                <select class="form-control" name="val[page]">
                    @foreach(app('App\\Addons\\Customwidget\\Classes\\CustomWidgetRepository')->pages() as $page => $title)

                    <option {{($page == $widget->page) ? 'selected' : null}} value="{{$page}}">{{$title}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="val[status]">
                    <option {{($widget->status == 1) ? 'selected' : null}} value="1">Enabled</option>
                    <option {{($widget->status == 0) ? 'selected' : null}} value="0">Disabled</option>
                </select>
            </div>

            <div class="form-group">
                <label>Widget content</label>
                <textarea name="val[content]" class="pane-editor">{{$widget->content}}</textarea>
                <p class="help-block">Widget content can contain any content html, css e.t.c</p>
            </div>

            <div class="form-group">
                <button class="btn btn-danger">Save Widget</button>
            </div>
        </form>
    </div>
</div>