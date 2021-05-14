@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('root').env('back').env('assets').'plugins/animate/animate.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('body')
    <div class="row" lang="{{getLang()}}">
        @include('back.modules.browser')<!--end col-->
        @include('back.modules.aktivite')<!--end col-->
    </div><!--end row-->
    @include('back.modules.bilgiler')<!--end row-->
    <div class="row">
        @include('back.modules.ziyaretci')
        @include('back.modules.cihaz')
    </div><!--end row-->
@endsection
@section('js')
    <script src="{{asset(env('root').env('back').env('assets').'plugins/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{asset(env('root').env('back').env('assets').'plugins/moment/moment.js')}}"></script>
    @include('back.modules.tarayici')
    @include('back.modules.ziyaretci-js')
    @include('back.modules.cihaz-js')
    <script src="{{asset(env('root').env('back').env('assets').'pages/jquery.analytics_dashboard.init.js')}}"></script>
    <script src="{{asset(env('root').env('back').env('assets').'pages/jquery.animate.init.js')}}"></script>
    <script>
        window.setInterval(function () {
            $.get('{{route('getNewLogs')}}', function (data) {
                if (data != 1) {
                    $(".crm-dash-activity").prepend(data);
                    if($(".crm-dash-activity")[0].children.length > 4) {
                        $($(".crm-dash-activity")[0]).each(function() {
                            for (i=this.children.length; i > 5; i--) {
                                this.children[i-1].remove();
                            }
                        });
                    }

                }
            });
        }, 10000);

    </script>
@endsection
