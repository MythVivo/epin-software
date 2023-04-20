@extends('back.layouts.app')

@section('css')

    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/animate/animate.css')}}" rel="stylesheet"

          type="text/css">

@endsection

@section('body')

    <div id="bilgiler">

    @include('back.modules.bilgiler')

    </div>
    <div class="row" lang="{{getLang()}}">
        <div class="col-lg-6">
            <div id="browser">
                <div>
                    Pazar yeri özeti :
                    <br><br>
                    <table class="small table-bordered table-hover table-sm table-striped" style="text-align: center">
                        <tr><th>PAZAR</th><th>TOPLAM İLAN</th><th>SİLİNEN</th><th>RED</th><th>AKTİF</th><th>SÜRESİ DOLAN</th><th title="Kullanıcı Tarafından Pasif">K.T.P.</th><th>TAMAMLANAN</th></tr>
                        <?
                        $sor=(object)DB::select("select gt.title, count(pyi.id) TOPLAM,
                            (select count(py.id) from pazar_yeri_ilanlar py  where isnull(py.deleted_at) and pazar=pyi.pazar and userStatus=1 and status=1) AKTIF,
                            (select count(py.id) from pazar_yeri_ilanlar py  where isnull(py.deleted_at) and pazar=pyi.pazar and userStatus=0) KTP,
                            (select count(py.id) from pazar_yeri_ilanlar py  where isnull(py.deleted_at) and pazar=pyi.pazar and red_neden=7) ZA,
                            (select count(py.id) from pazar_yeri_ilanlar py  where isnull(py.deleted_at) and pazar=pyi.pazar and status=3) RED,
                            (select count(py.id) from pazar_yeri_ilanlar py  where isnull(py.deleted_at) and pazar=pyi.pazar and status=6) TAMAM,
                            (select count(py.id) from pazar_yeri_ilanlar py  where py.deleted_at is not null and pazar=pyi.pazar) DELETED
                            from pazar_yeri_ilanlar pyi
                            join games_titles gt on gt.id=pyi.pazar
                            GROUP by pyi.pazar order by TOPLAM desc");
                        foreach($sor as $s){echo "<tr><td>$s->title</td><td>$s->TOPLAM</td><td>$s->DELETED</td><td>$s->RED</td><td>$s->AKTIF</td><td>$s->ZA</td><td>$s->KTP</td><td>$s->TAMAM</td></tr>";}
                        ?>
                    </table>
                    

                </div>
            </div>
        </div>

        <div class="col-lg-6">

            <div id="aktivite">

             <?php /* @include('back.modules.aktivite') */ ?>

            </div>

        </div>

    </div><!--end row-->



    <div class="row">

        <div class="col-lg-8">

            <div id="ziyaretci">

                <?php /*  @include('back.modules.ziyaretci') */ ?>



            </div>

        </div>

        <div class="col-lg-4">

            <div id="cihaz">

                <?php /*  @include('back.modules.cihaz') */ ?>

            </div>

        </div>



    </div><!--end row-->

@endsection

@section('js')

    {{--<script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/apexcharts/apexcharts.min.js')}}"></script>--}}

    {{--<script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/moment/moment.js')}}"></script>

    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'pages/jquery.analytics_dashboard.init.js')}}"></script>

    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'pages/jquery.animate.init.js')}}"></script>--}}

    <script>

        $(document).ready(function () {

        window.setInterval(function () {

            $.get('{{route('getNewLogs')}}', function (data) {

                if (data != 1) {

                    $(".ajax-area").prepend(data);

                    if ($(".ajax-area")[0].children.length > 19) {

                        $($(".ajax-area")[0]).each(function () {

                            for (i = this.children.length; i > 20; i--) {

                                this.children[i - 1].remove();

                            }

                        });

                    }



                }

            });

        }, 10000);

        });

    </script>

    <script>

        $(document).ready(function () {


        $.ajax({

            type: 'GET', url: "{{route('panelAktivite')}}",

            success: function (data) {

                $("#aktivite").html(data);

            }

        });

        /*

         * Bilgiler Ajax



        $.ajax({

            type: 'GET', url: "{{route('panelBilgiler')}}",

            success: function (data) {

                $("#bilgiler").html(data);

            }

        });

        */

        <?php

        /*

         * Ziyaretçi Ajax



        $.ajax({

            type: 'GET', url: "{{route('panelZiyaretci')}}",

            success: function (data) {

                $("#ziyaretci").html(data);

                @include('back.modules.ziyaretci-js')

            }

        });

        /*

         * Cihaz Ajax



        $.ajax({

            type: 'GET', url: "{{route('panelCihaz')}}",

            success: function (data) {

                $("#cihaz").html(data);

                @include('back.modules.cihaz-js')

            }

        });

        */ ?>

        });

    </script>

@endsection

