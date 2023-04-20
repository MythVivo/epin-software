<?php
    if(isset($_GET['sil']) and $_GET['id']) {
        if($_GET['sil'] == 1) {
            $id = $_GET['id'];
            $megaMenu = DB::table('header_menu')->where('id', $id)->first();
            if(file_exists('public/front/mega_menu/'.$megaMenu->image)) {
                unlink('public/front/mega_menu/'.$megaMenu->image);
                DB::table('header_menu')->where('id', $id)->update([
                    'image' => NULL,
                ]);
                header('Location: ?okey');
                die();
            }
        }
    }
    ?>
@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}"
          rel="stylesheet">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}"
          rel="stylesheet" type="text/css">
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
    @if(isset($_GET['okey']))
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

    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 text-left">
                                Mega Menü Yönetimi
                            </div>
                            <div class="col-md-6 col-sm-6 text-right">
                                <button type="button" class="btn btn-outline-success waves-effect waves-light"
                                        data-toggle="modal" data-target=".ekle">Mega Menü Ekle
                                </button>
                            </div>
                        </div>
                    </h4>
                    <div class="modal fade ekle" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Mega Menü Ekle</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="yeniEkle" method="post" action="{{route('header_menu_add')}}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="1">Mega Menü Başlığı</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="Mega Menü Başlığı">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="2">Alt Başlık</label>
                                                    <select id="2" class="form-control" name="sub_menu">
                                                        <option value="0">Genel Menü</option>
                                                        @foreach(DB::table('header_menu')->orderBy('sub_menu', 'asc')->whereNull('deleted_at')->get() as $u)
                                                            @if($u->sub_menu == 0)
                                                                <option value="{{$u->id}}">{{$u->title}}</option>
                                                            @else

                                                                @if(DB::table('header_menu')->where('id', $u->sub_menu)->first()->sub_menu != 0)
                                                                    <option value="{{$u->id}}">
                                                                        >> {{$u->title}}</option>
                                                                @else
                                                                    <option value="{{$u->id}}"> > {{$u->title}}</option>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="4">Mega Menü Linki</label>
                                                    <input name="link" type="text" class="form-control" id="4"
                                                           placeholder="Mega Menü Linki">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="3">Mega Menü Resmi</label>
                                                    <input name="image" type="file" id="3" class="dropify"
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

                    <div class="table-responsive">
                        {{view('back.pages.header_menu.table')}}
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection
@section('js')
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
                columnDefs: [{orderable: false, targets: [6]}],
                pageLength: 10,
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
                            columns: [0, 1, 2, 3, 4, 5]
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
                            columns: [0, 1, 2, 3, 4, 5]
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
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },

                ],
                "order": [[4, "desc"]],
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
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
    <script>
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
                    var t = $('#datatable').DataTable();
                    t.row($("#row-" + id)).remove().draw();
                    swal.fire(
                        '{{__("admin.basarili")}}',
                        '{{__("admin.basariliMetin")}}',
                        'success'
                    )
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire(
                        '{{__("admin.iptal-edildi")}}',
                        '',
                        'error'
                    )
                }
            })
        }

    </script>
    <script>
        function status(id, table, event) {
            var icon;
            var buton;
            if (event.target.children[0] == undefined) { //ikonu verir
                icon = event.target;
                buton = event.target.offsetParent;
            } else { //butonu verir
                icon = event.target.children[0];
                buton = event.target;
            }
            var statusText = buton.parentElement.parentElement;
            statusText = $(statusText).find('td#statusText');
            $.get("{{route('setStatus')}}", {table: table, id: id});
            $(buton).prop('disabled', true);
            $(buton).css('cursor', 'not-allowed');
            $(icon).removeClass("mdi-eye");
            $(icon).addClass("mdi-spin mdi-loading");
            if ($(buton).hasClass("btn-outline-warning")) {
                $(buton).removeClass("btn-outline-warning");
                $(buton).addClass("btn-outline-primary");
                var cevir = "success";
            } else {
                $(buton).removeClass("btn-outline-success");
                $(buton).addClass("btn-outline-primary");
                var cevir = "warning";
            }
            setTimeout(function () {
                $(buton).css('cursor', 'pointer');
                $(buton).prop('disabled', false);
                $(icon).removeClass("mdi-spin mdi-loading");
                $(icon).addClass("mdi-eye");
                $(buton).removeClass("btn-outline-primary");
                if (cevir == "success") {
                    $(buton).addClass("btn-outline-success");
                } else {
                    $(buton).addClass("btn-outline-warning");
                }

                if ($(statusText)[0].innerText == "{{__('admin.aktif')}}") {
                    $(statusText)[0].innerHTML = "{!! getDataStatus(0) !!}";
                } else {
                    $(statusText)[0].innerHTML = "{!! getDataStatus(1) !!}";
                }
            }, 2000);
        }
    </script>
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
@endsection
