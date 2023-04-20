<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title mt-0">@lang('admin.cihazlar')</h4>
            <div id="ana_device" class="apex-charts"></div>
            <div class="table-responsive mt-4">
                <table class="table mb-0">
                    <thead class="thead-light">
                    <tr>
                        <th>@lang('admin.cihaz')</th>
                        <th>@lang('admin.oturum')</th>
                        <th>@lang('admin.gunluk')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(getDeviceUnique() as $u)
                        <tr>
                            <th scope="row">{{$u}}</th>
                            <th scope="row">{{findSessionByDevice($u)}}</th>
                            <th scope="row">{{findSessionByDeviceDaily($u)}}</th>
                        </tr>
                    @endforeach
                    </tbody>
                </table><!--end /table-->
            </div>
        </div><!--end card-body-->
    </div><!--end card-->
</div><!--end col-->
