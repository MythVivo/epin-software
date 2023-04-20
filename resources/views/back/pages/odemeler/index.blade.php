<?php
    if(isset($_GET['banka'])) {
        if(isset($_GET['bankaId'])) {
            $bankaid = $_GET['bankaId'];
            $banka = DB::table('payment_channels_eft')->where('id', $bankaid)->first();
            if($banka->status == 1) {
                $status = 0;
            } else {
                $status = 1;
            }
            DB::table('payment_channels_eft')->where('id', $bankaid)->update([
                'status' => $status,
            ]);
            Header("Location: ?ok=Okey");
            exit();
            die();
        }
    }
    ?>
@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}"
          rel="stylesheet">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}"
          rel="stylesheet" type="text/css">
    <style>
        table.dataTable.nowrap td {
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

    @if(isset($_GET['ok']))
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

    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2 col-sm-6 text-left">
                                Ödeme Yönetimi
                            </div>
                            <div class="col-md-10 col-sm-6 text-right">
                                <button type="button" class="btn btn-outline-success waves-effect waves-light"
                                        data-toggle="modal" data-target=".ekleDiger">Diğer Ödeme Yöntemi Ekle</button>
                                <button type="button" class="btn btn-outline-info waves-effect waves-light"
                                        data-toggle="modal" data-target=".odemeYontemleriDiger">Diğer Ödeme Yöntemleri</button>

                                <button type="button" class="btn btn-outline-success waves-effect waves-light"
                                        data-toggle="modal" data-target=".ekleCrypto">Kripto Ödeme Yöntemi Ekle</button>
                                <button type="button" class="btn btn-outline-info waves-effect waves-light"
                                        data-toggle="modal" data-target=".odemeYontemleriCrypto">Kripto Ödeme Yöntemleri</button>

                                <button type="button" class="btn btn-outline-success waves-effect waves-light"
                                        data-toggle="modal" data-target=".ekle">Havale / Eft Ödeme Yöntemi Ekle</button>
                                <button type="button" class="btn btn-outline-info waves-effect waves-light"
                                        data-toggle="modal" data-target=".odemeYontemleri">Havale / Eft Ödeme Yöntemleri</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade ekle" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Havale / Eft Ödeme Yöntemi Ekle</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="yeniEkle" method="post" action="{{route('odemeler_add')}}" enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="1">Banka Adı</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="Banka Adı">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="2">Alıcı</label>
                                                    <input name="alici" type="text" class="form-control" id="2"
                                                           placeholder="Alıcı">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="3">Iban</label>
                                                    <input name="iban" type="text" class="form-control" id="3"
                                                           placeholder="Başında TR ile yazınız">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="4">Şube</label>
                                                    <input name="sube" type="text" class="form-control" id="4"
                                                           placeholder="Şube">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="5">Hesap No</label>
                                                    <input name="hesap" type="text" class="form-control" id="5"
                                                           placeholder="Hesap No">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="6">Havale Kesinti</label>
                                                    <input name="havale_kesinti" type="text" class="form-control" id="6"
                                                           placeholder="Havale Kesinti">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="7">Atm Kesinti</label>
                                                    <input name="atm_kesinti" type="text" class="form-control" id="7"
                                                           placeholder="Atm Kesinti">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="8">Açıklama</label>
                                                    <input name="text" type="text" class="form-control" id="8"
                                                           placeholder="Açıklama">
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label>Banka Slug Değeri</label>
                                                    <input name="bankSlug" type="text" class="form-control"
                                                           placeholder="Banka Slug">
                                                    <small>
                                                        [Paytr İçin : isbank, akbank, denizbank, finansbank,
                                                        halkbank, ptt, teb, vakifbank, yapikredi,
                                                        ziraat | Gpay için api sayfasından bank id'sini alınız!]
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Havale Aracısı</label>
                                                    <select name="channel_type" class="form-control">
                                                        <option value="2">PayTR</option>
                                                        <option value="15">Gpay</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="9">Banka Resmi Açık Tema</label>
                                                    <input name="image" type="file" id="9" class="dropify"
                                                           accept="image/*">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="123">Banka Resmi Koyu Tema</label>
                                                    <input name="image_dark" type="file" id="123" class="dropify"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary waves-effect"
                                                data-dismiss="modal">@lang('admin.kapat')</button>
                                        <button type="submit"
                                                class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                    </div>
                                </form>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class="modal fade odemeYontemleri" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Havale / Eft Ödeme Yöntemleri</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>

                                    <div class="modal-body">

                                        <div class="table-responsive">
                                            {{view('back.pages.odemeler.tableOdemeYontemleri')}}
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary waves-effect"
                                                data-dismiss="modal">@lang('admin.kapat')</button>
                                    </div>

                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>

                    <div class="modal fade ekleCrypto" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Kripto Para Ödeme Yöntemi Ekle</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="yeniEkle" method="post" action="{{route('crypto_add')}}" enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="1">Kripto Para Adı</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="Kripto Para Adı">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="1">Kripto Para Açıklaaması</label>
                                                    <input name="text" type="text" class="form-control" id="1"
                                                           placeholder="Kripto Para Açıklaması">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="9">Kripto Para Resmi</label>
                                                    <input name="image" type="file" id="9" class="dropify"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary waves-effect"
                                                data-dismiss="modal">@lang('admin.kapat')</button>
                                        <button type="submit"
                                                class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                    </div>
                                </form>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class="modal fade odemeYontemleriCrypto" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Kripto Para Ödeme Yöntemleri</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>

                                <div class="modal-body">

                                    <div class="table-responsive">
                                        {{view('back.pages.odemeler.tableOdemeYontemleriCrypto')}}
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary waves-effect"
                                            data-dismiss="modal">@lang('admin.kapat')</button>
                                </div>

                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>

                    <div class="modal fade ekleDiger" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Diğer Ödeme Yöntemi Ekle</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="yeniEkle" method="post" action="{{route('digerOdeme_add')}}" enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="1">Diğer Ödeme Yöntemi Adı</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="Diğer Ödeme Yöntemi Adı">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary waves-effect"
                                                data-dismiss="modal">@lang('admin.kapat')</button>
                                        <button type="submit"
                                                class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                    </div>
                                </form>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class="modal fade odemeYontemleriDiger" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Diğer Ödeme Yöntemleri</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>

                                <div class="modal-body">

                                    <div class="table-responsive">
                                        {{view('back.pages.odemeler.tableOdemeYontemleriDiger')}}
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary waves-effect"
                                            data-dismiss="modal">@lang('admin.kapat')</button>
                                </div>

                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>

                    <div class="table-responsive">

                        {{view('back.pages.odemeler.table')}}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/js/dropify.min.js')}}"></script>
    <script>
        $(function () {
            $('.dropify').dropify({
                height: "300",
                allowedFileExtensions: ["png", "jpg", "jpeg", "gif"],
                messages: {
                    default: '{{__('admin.dropifyResimSec')}}',
                    replace: '{{__('admin.dropifyDegistir')}}',
                    remove: '{{__('admin.dropifySil')}}',
                    error: '{{__('admin.dropifyHata')}}',
                }
            });
        });
    </script>
    <script type="text/javascript">
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
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();
                    nb_cols = api.columns().nodes().length;
                    var j = 4;
                    var e = 2;
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\Adet,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                    var intVal2 = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\TL,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                    while (j < 5) {
                        total = api.column(j).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                        pageTotal = api.column( j, { page: 'current'} ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                        sumCol4Filtered = display.map(el => data[el][j]).reduce((a, b) => intVal(a) + intVal(b), 0 );
                        $( api.column(j).footer() ).html(sumCol4Filtered + " Adet");
                        j++;
                    }
                    while (e < 3) {
                        total = api.column(e).data().reduce( function (a, b) {return intVal2(a) + intVal2(b);}, 0 );
                        pageTotal = api.column( e, { page: 'current'} ).data().reduce( function (a, b) {return intVal2(a) + intVal2(b);}, 0 );
                        sumCol4Filtered = display.map(el => data[el][e]).reduce((a, b) => intVal2(a) + intVal2(b), 0 );
                        $( api.column(e).footer() ).html(sumCol4Filtered.toLocaleString());
                        e++;
                    }
                },
                columnDefs: [{orderable: false, targets: [7]}],
                pageLength: 100,
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
                            columns: [0, 1, 2, 3, 4, 5, 6]
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
                            columns: [0, 1, 2, 3, 4, 5, 6]
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
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },

                ],
                "order": [[6, "desc"]],
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
            $('#table-filter').on('change', function () {
                table.search(this.value, true, false).draw();
                //table.search(this.value).draw();
            });
            table.search($("#table-filter").val(), true, false).draw();


            table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(1)');
            $('#datatable_filter').css(
                {'display': 'none'}
            );
        });


    </script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
    <script>
        $('.onay').click(function (){
            var id=$(this).attr('id');
            var ttr=$(this).attr('ttr');

            swal.fire({
                title: ttr+ " TL",
                html: "Ödeme onaylanıp kullanıcı bakiyesine yukarıdaki tutar eklenecek emin misiniz ?",
                //icon: "info",
                showCancelButton: true,
                confirmButtonText: 'Devam Et',
                cancelButtonText: 'İPTAL',
                reverseButtons: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {

                    location.href='https://oyuneks.com/panel/odemeler-onayla/1/'+id

                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire({icon:'error',title:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});
                }
            });
        });


        $('.red').click(function (){
            var id=$(this).attr('id');

            swal.fire({
                title: "UYARI",
                html: "Ödeme talebi reddedilecek. Emin misiniz ?",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: 'EVET',
                cancelButtonText: 'Vazgeç',
                reverseButtons: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    location.href='https://oyuneks.com/panel/odemeler-onayla/2/'+id
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire({icon:'error',title:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});
                }
            });

        })

        function deleteContent(table, id) {
            swal.fire({
                title: "{{__("admin.silme")}}",
                text: "{{__("admin.silmeText")}}",
                icon: "info",
                type: 'error',
                showCancelButton: true,
                confirmButtonText: '{{__("admin.onayliyorum")}}',
                cancelButtonText: '{{__("admin.vazgec")}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get("{{route('deleteContent')}}", {table: table, id: id});
                    swal.fire(
                        '{{__("admin.basarili")}}',
                        '{{__("admin.basariliMetin")}}',
                        'success'
                    )
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                   swal.fire({icon:'error',title:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});
                }
            })
        }

    </script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/tinymce/tinymce.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            if ($("#3").length > 0) {
                tinymce.init({
                    selector: ".editorText",
                    theme: "modern",
                    language: '{{getLang()}}',
                    height: 300,
                    plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "save table contextmenu directionality emoticons template paste textcolor"
                    ],
                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
                    style_formats: [
                        {title: 'Bold text', inline: 'b'},
                        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                        {title: 'Example 1', inline: 'span', classes: 'example1'},
                        {title: 'Example 2', inline: 'span', classes: 'example2'},
                        {title: 'Table styles'},
                        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                    ]
                });
            }
        });
    </script>
@endsection
