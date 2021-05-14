<div class="col-lg-6">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title mt-0 mb-3">@lang('admin.aktivite')</h4>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="slimscroll crm-dash-activity">
                                @foreach(\App\Models\Logs::where('lang', getLang())->orderBy('created_at', 'desc')->take(5)->get() as $u)
                                    <div class="activity">
                                        <div class="activity-info">
                                            <div class="icon-info-activity">
                                                <i class="{{findLogsCategory($u->id)->icon}} bg-soft-{{findLogsCategory($u->id)->type}}"></i>
                                            </div>
                                            <div class="activity-info-text">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="m-0 w-75">{{findLogsCategory($u->id)->title}}</h6>
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
