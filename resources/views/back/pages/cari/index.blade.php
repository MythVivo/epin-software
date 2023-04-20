<? error_reporting(E_PARSE); ?>
@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
    <style>
        table.dataTable.nowrap td:nth-child(2) {white-space: unset !important;}
    </style>
@endsection
@section('body')

    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                   @include('back.pages.cari.menu')
                    <div class="table-responsive" style="height: 500px; vertical-align: center">
                        <br><br><br><br><br><br><br><br><br>
                        <p style="text-align: center; vertical-align: center">Cari Durum Özet rapor vs..</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?

echo json_decode(DB::table('integrations_finance_bulut')->where('id',24794)->first()->response)->Response->Status;






    ?>

@endsection

@section('js')
    <script src="/back/assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
@endsection
