<div class="col-lg-8">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title mt-0">@lang('admin.ziyaret-istatistik')</h4>
            <div class="">
                <div id="liveVisits" class="apex-charts"></div>
            </div>
        </div><!--end card-body-->
        <div class="card-body bg-light chart-report-card ">
            <div class="row d-flex justify-content-between">
                <div class="col-lg-6">
                    <div class="media">
                        <i class="dripicons-user-group report-main-icon bg-card text-dark mr-2"></i>
                        <div class="media-body align-self-center text-truncate">
                            <h4 class="mt-0 mb-0 font-weight-semibold text-dark font-24">{{getPluralVisitor()}}
                            </h4>
                            <p class="text-dark font-weight-semibold mb-0 font-14">@lang('admin.cogul-ziyaretci')</p>
                        </div><!--end media-body-->
                    </div><!--end media-->
                </div><!--end col-->
                <div class="col-lg-6">
                    <div class="media">
                        <i class="dripicons-preview report-main-icon bg-card text-dark mr-2"></i>
                        <div class="media-body align-self-center text-truncate">
                            <h4 class="mt-0 mb-0 font-weight-semibold text-dark font-24">{{getUniqueVisitor()}}
                            </h4>
                            <p class="text-dark font-weight-semibold mb-0 font-14">@lang('admin.tekil-ziyaretci')</p>
                        </div><!--end media-body-->
                    </div><!--end media-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end card-body-->
    </div><!--end card-->
</div><!--end col-->
