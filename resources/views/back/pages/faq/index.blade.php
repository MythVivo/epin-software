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
                                @lang('admin.sssYonetimi')
                            </div>
                            <div class="col-md-6 col-sm-6 text-right">
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light" onclick="location.href='{{route('sss_kategori')}}'">@lang('admin.sssKategoriYonetimi')</button>
                                <button type="button" class="btn btn-outline-success waves-effect waves-light"
                                        data-toggle="modal" data-target=".ekle">@lang('admin.sssEkle')</button>
                            </div>
                        </div>
                    </h4>
                    <div class="modal fade ekle" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.sssEkle')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="yeniEkle" method="post" action="{{route('sss_add')}}" enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="1">@lang('admin.sssBasligi')</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="@lang('admin.sssBasligi')">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="2">@lang('admin.sssKategorisi')</label>
                                                    <select id="2" class="form-control" name="category">
                                                        @foreach(DB::table('faq_categories')->whereNull('deleted_at')->where('lang', getLang())->get() as $u)
                                                            <option value="{{$u->id}}">{{$u->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="4">Öne Çıkan</label>
                                                    <select id="4" class="form-control" name="one_cikan">
                                                        <option value="0">Hayır</option>
                                                        <option value="1">Evet</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="3">@lang('admin.sssMetni')</label>
                                                    <textarea class="form-control" placeholder="@lang('admin.sssMetni')" id="3" name="text"></textarea>
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
                        {{view('back.pages.faq.table')}}
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection
@section('js')
    <script
        src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script
        src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').DataTable({
                columnDefs: [{orderable: false, targets: [6]}],
                pageLength: 10,
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
                    "zeroRecords": "{{__('admin.eselsen-veri-bulunamadi')}}",
                    "paginate": {
                        "first": "{{__('admin.ilk')}}",
                        "last": "{{__('admin.son')}}",
                        "next": "{{__('admin.sonraki')}}",
                        "previous": "{{__('admin.onceki')}}"
                    },
                }
            });
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
@endsection
