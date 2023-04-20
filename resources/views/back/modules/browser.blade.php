<div class="col-lg-12">
    <div class="card">
        <div class="card-body" style="height: 542px; overflow: auto;">
            <h4 class="header-title mt-0 mb-3">Sistem Düzenlemeleri</h4>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="slimscroll crm-dash-activity">
                                @foreach(DB::table('panel_duzenlemeler')->orderBy('created_at', 'desc')->get() as $u)
                                    <div class="activity">
                                        <div class="activity-info">
                                            <div class="icon-info-activity">
                                                <i class="{{$u->icon}} bg-soft-{{$u->type}}"></i>
                                            </div>
                                            <div class="activity-info-text">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="m-0 w-75">{{$u->title}}</h6>
                                                    <span
                                                            class="text-muted d-block">{{findDuzenlemeTime($u->id)}}</span>
                                                </div>
                                                <p class="text-muted mt-3">{{$u->text}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
