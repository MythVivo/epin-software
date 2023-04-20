@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/select2/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link type="text/css" href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/x-editable/css/bootstrap-editable.css')}}" rel="stylesheet">

    <style>
        table.dataTable.nowrap td:nth-child(2) {
            white-space: unset !important;
        }
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
                    <i class="mdi mdi-crosshairs alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{__('admin.hata-2')}}</strong>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row mt-3">
        <div class="table-responsive">
            <table id="datatable" class="nowrap small table table-hover table-sm text-body" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Alıcı</th>
                    <th>Email</th>
                    <th>Steam ID</th>
                    <th>Muve ID</th>
                    <th>Oyun</th>
                    <th>Alış</th>
                    <th>Adet</th>
                    <th>Satış</th>
                    <th>Not</th>
                    <th>Key</th>
                    <th>Tarih</th>
                </tr>

                </thead>
                <tbody>
<?php
                $sor=DB::select("SELECT mgs.*, u.name,u.email, mg.title,mg.alis,mg.muveId,mg.steamId,mk.mkey
                            FROM `muve_games_satis` mgs
                            join users u on u.id=mgs.user
                            join muve_games mg on mg.muveId=mgs.muveId
                            left join muve_keys mk on mk.trans=mgs.transId
                            where isnull(mgs.deleted_at)
                            order by mgs.created_at desc
");
                $r=0;
?>
        @foreach($sor as $m)
            @php($r++)
                <tr>
                    <td>{{$r}}</td>
                    <td>{{$m->name}}</td>
                    <td>{{$m->email}}</td>
                    <td>{{$m->steamId}}</td>
                    <td>{{$m->muveId}}</td>
                    <td>{{$m->title}}</td>
                    <td>{{$m->alis}}</td>
                    <td>{{$m->adet}}</td>
                    <td>{{$m->price}}</td>

                    <td><? if($m->status==1){echo 'Başarılı';} if($m->status==2){echo $m->note;} ?></td>
                    <td>{{$m->mkey}}</td>
                    <td>{{$m->created_at}}</td>
                </tr>
        @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection

@section('js')
    <script>
$(document).ready(function () {

        $('#datatable thead tr').clone(true).appendTo('#datatable thead');
        $('#datatable thead tr:eq(1) th').each(function (i) {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" />');
        $('input', this).on('keyup change', function () {
        if (table.column(i).search() !== this.value) {
        table.column(i).search(this.value).draw();
        }
        });
        });
        $('#datatable input[type="text"]').css(
        {'width': '100%', 'display': 'inline-block'}
        );
        var table = $('#datatable').DataTable({
        pageLength: 50,
        orderCellsTop: true,
        fixedHeader: true,
        lengthChange: false,
        buttons: [
        {
        extend: 'excelHtml5',
        title: '{{getPageTitle(getPage(), getLang())}} - Excel',
        text: '<i class="far fa-file-excel"></i>',
        className: 'btn btn-outline-success',
        init: function (api, node, config) {
        $(node).removeClass('btn-secondary')
        },
        exportOptions: {
        columns: [1, 2, 3, 4,6,7,8,9,10,11]
        }
        },
        {
        extend: 'csvHtml5',
        title: '{{getPageTitle(getPage(), getLang())}} - Csv',
        text: '<i class="fas fa-file-csv"></i>',
        className: 'btn btn-outline-info',
        init: function (api, node, config) {
        $(node).removeClass('btn-secondary')
        },
        exportOptions: {
            columns: [1, 2, 3, 4,6,7,8,9,10,11]
        }
        },
        {
        extend: 'pdfHtml5',
        title: '{{getPageTitle(getPage(), getLang())}} - Pdf',
        className: 'btn btn-outline-danger',
        init: function (api, node, config) {
        $(node).removeClass('btn-secondary')
        },
        text: '<i class="far fa-file-pdf"></i>',
        exportOptions: {
            columns: [1, 2, 3, 4,6,7,8,9,10,11]
        }
        },

        ],
        "order": [[10, "desc"]],
        "language": {
        "decimal": "",
        "emptyTable": "{{__('admin.hic-veri-yok')}}",
        "info": "{{__('admin.adet-veri-gosterim', ['TOTAL' => '_TOTAL_', 'START' => '_START_', 'END' => '_END_'])}}",
        "infoEmpty": "{{__('admin.sifir-veri-var')}}",
        "infoFiltered": "{{__('admin.adet-veri-araniyor', ['MAX' => '_MAX_'])}}",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "{{__('admin.veri-gosteriliyor', ['MENU' => '_MENU_'])}}",
        "loadingRecords": "{{__('admin.yukleniyor')}}",
        "processing": "{{__('admin.isleniyor')}}",
        "search": "{{__('admin.ara')}}",
        "zeroRecords": "{{__('admin.eslesen-veri-bulunamadi')}}",
        "paginate": {
        "first": "{{__('admin.ilk')}}",
        "last": "{{__('admin.son')}}",
        "next": "{{__('admin.sonraki')}}",
        "previous": "{{__('admin.onceki')}}"
        },
        }
        });
        table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(1)');
        $('#datatable_filter').css(
        {'display': 'none'}
        );

});
</script>

@endsection
