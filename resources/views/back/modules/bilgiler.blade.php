<div class="row justify-content-center">
    <div class="col-md-6 col-lg-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-dark font-weight-semibold font-14">@lang('admin.kullanici')</p>
                        <h3 class="my-3">{{\App\Models\User::count()}}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="dripicons-user-group report-main-icon bg-soft-purple text-purple"></i>
                    </div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
    <div class="col-md-6 col-lg-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-dark font-weight-semibold font-14">@lang('admin.ziyaretci')</p>
                        <h3 class="my-3">{{\App\Models\Statistic::count()}}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="dripicons-user report-main-icon bg-soft-success text-danger"></i>
                    </div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
    <div class="col-md-6 col-lg-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-dark font-weight-semibold font-14">@lang('admin.odeme')</p>
                        <h3 class="my-3">2400</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="dripicons-card report-main-icon bg-soft-secondary text-secondary"></i>
                    </div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
    <div class="col-md-6 col-lg-3">
        <div class="card report-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-dark font-weight-semibold font-14">@lang('admin.urun')</p>
                        <h3 class="my-3">85000</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="dripicons-cart report-main-icon bg-soft-warning text-warning"></i>
                    </div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
</div>
