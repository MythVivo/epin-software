<div class="col-lg-6">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8">
                    <h4 class="header-title mt-0 mb-3">@lang('admin.tarayici')</h4>
                    <div id="barchart" class="apex-charts"></div>
                </div><!--end col-->
                <div class="col-lg-4">
                    <h4 class="header-title mt-0 mb-3">@lang('admin.tarayici-kaynak')</h4>
                    <div class="traffic-card">
                        <h3 class="text-dark font-weight-semibold">{{getLiveVisitor()}}</h3>
                        <h5>@lang('admin.anlik-kullanici')</h5>
                    </div>

                    <ul class="list-unstyled url-list mb-0">
                        <hr>
                        @foreach (json_decode(getBrowserStatistic()) as $name )
                            <li>
                                <i class="mdi mdi-{{Str::slug($name)}} text-success"></i>
                                <span>{{$name}}</span>
                            </li>
                        @endforeach
                    </ul>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end card-body-->
    </div><!--end card-->
</div>
