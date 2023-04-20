<?
//$kod=DB::select("select kod,id from epin_siparisler");
//foreach ($kod as $k){
//        $w=\epin::ENC($k->kod);
//        DB::select("update epin_siparisler set kod='$w' where id='$k->id'");
//    }




// $kod=DB::select("select code,id from games_packages_codes");
//foreach ($kod as $k){
//        $w=\epin::ENC($k->code);
//        DB::select("update games_packages_codes set code='$w' where id='$k->id'");
//    }


//$kod=DB::select("select kod,id from hediye_kodlari_kodlar");
//foreach ($kod as $k){
//        $w=\epin::ENC($k->kod);
//        DB::select("update hediye_kodlari_kodlar set kod='$w' where id='$k->id'");
//    }

//
//$kod=DB::select("select code,id from epin_satis_kodlar  order by id limit 50000,100000");
//foreach ($kod as $k){
//        $w=\epin::ENC($k->code);
//        DB::select("update epin_satis_kodlar set code='$w' where id='$k->id'");
//    }


?>


@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
    <style>
        table.dataTable.nowrap td:nth-child(2) {white-space: unset !important;}
    </style>
@endsection
@section('body')

    @if(session('success'))
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-success alert-success-shadow" role="alert">
                    <i class="mdi mdi-check-all alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{__('admin.basarili')}}</strong> {{__('admin.basariliMetin')}}
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-danger alert-danger-shadow" role="alert">
                    <i class="mdi mdi-bell alert-icon"></i>
                    <div class="alert-text">
                        <strong>Hata</strong> {{session('error')}}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 " >
                                EPİN / TAKİP
                            </div>
                        </div>
                    </h4>
                    <div class="table-responsive">


                        <? #---------------------------------------------------------------------------------------Tablo datatable----------------------------------------------------------------------------------?>

                        <div class="col-md-12 mb-4 mt-4">
                            <form class="row" method="get">
                                <div class="col-sm-12 col-md-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="userinput1">Epinler</label>
                                        <textarea id="epins" class="form-control style-input" name="epins" placeholder="Her satırda 1 EPIN olacak şekilde giriniz"  required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-1 d-flex justify-content-sm-start  align-items-center">
                                    <a href="#" class="btn btn-gradient-secondary eps">Sorgula </a>
                                </div>
                            </form>

                        </div>


                        <table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-bordered table-hover nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr style="text-align: center"><th>Kod</th><th>Pid</th><th>Paket</th><th>Tedarikçi</th><th>Alış</th><th>Satış</th><th>Kdv</th><th>Satın Alan</th><th>Uid</th><th>Email</th><th>Bakiye</th><th>Ç.Bak</th><th>Used</th><th>Al Tar</th><th>Sat Tar</th></tr></thead>
                            <tbody id="tablo"></tbody>
                        </table>

                        <? #-------------------------------------------------------------------------------------------------------------------------------------------------------------------------?>

                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
@section('js')
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
    <script src="//cdn.datatables.net/plug-ins/1.11.3/api/sum().js"></script>
    <script type="text/javascript">
        let sayi=0,say=0,g=0,id=[],ok=0,red=0,gon,j=0,resp=0;


        $(document).ready(function () {

            $('.eps').click(function(x){
                $.post('/ykp.php', {epins:$('#epins').val() }, function (x) {
                    $('#tablo').html(x);
                });
            })




       });
    </script>
@endsection
