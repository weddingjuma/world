<div class="container page-content">
    <div class="box" style="margin-top: 30px">
        <div class="box-title">{{trans('report.report-content')}}</div>
        <div class="box-content">

        @if (!empty($message))
                    <div class="alert alert-danger">{{$message}}</div>
        @endif
        <form class="form-horizontal" role="form" action="" method="post">

                <div class="form-group">
                   <label class="col-sm-4 control-label">{{trans('report.type')}}</label>
                    <div class="col-sm-6">
                        <input class="form-control"  type="text" value="{{$type}}" name="val[type]"/>
                    </div>
                </div>

                <div class="form-group">
                   <label class="col-sm-4 control-label">{{trans('report.url')}}</label>
                    <div class="col-sm-6">
                        <input class="form-control" contenteditable="false" type="text" value="{{$url}}" name="val[url]"/>
                    </div>
                </div>

                <div class="form-group">
                   <label class="col-sm-4 control-label">{{trans('report.reason')}}</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="val[reason]"></textarea>
                        <p class="help-block">{{trans('report.reason-note')}}</p>
                    </div>
                </div>

                      <div class="divider"></div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-sm btn-danger">{{trans('report.nows')}}</button>

                        </div>
                      </div>

        </form>

        </div>
    </div>
</div>