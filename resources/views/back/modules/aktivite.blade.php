<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title mt-0 mb-3">@lang('admin.aktivite')</h4>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="slimscroll crm-dash-activity ajax-area">
                                @foreach(\App\Models\Logs::where('lang', getLang())->orderBy('created_at', 'desc')->take(20)->get() as $u)
                                    <div class="activity">
                                        <div class="activity-info">
                                            <div class="icon-info-activity">
                                                <i class="{{findLogsCategory($u->category)->icon}} bg-soft-{{findLogsCategory($u->category)->type}}"></i>
                                            </div>
                                            <div class="activity-info-text">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="m-0 w-75">{{findLogsCategory($u->category)->title}} - {{findLogsUserName($u->id)}}</h6>
                                                    <span
                                                        class="text-muted d-block">{{findLogsTime($u->id)}}</span>
                                                </div>
                                                <p class="text-muted mt-3">{{$u->text}}</p>
                                            </div>
                                        </div>
                                    </div><!--end activity-->
                                @endforeach
                            </div><!--end crm-dash-activity-->

                        </div>  <!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end card-body-->
    </div><!--end card-->
</div>
