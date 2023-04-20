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
@endsection
@section('body')

    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 text-left">
                                @lang('admin.kategoriYonetimi')
                            </div>
                            <div class="col-md-6 col-sm-6 text-right">
                                <button type="button" class="btn btn-outline-success waves-effect waves-light"
                                        data-toggle="modal" data-target=".ekle">@lang('admin.kategoriEkle')</button>
                            </div>
                        </div>
                    </h4>
                    <div class="modal fade ekle" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.kategoriEkle')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="yeniEkle" method="post" action="{{route('kategori_add')}}"
                                      onsubmit="return false" enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="1">@lang('admin.kategoriBasligi')</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="@lang('admin.kategoriBasligi')">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="4">@lang('admin.kategoriResmi')</label>
                                                    <input name="image" type="file" id="4" class="dropify"
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
                    <div class="modal fade duzenle" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.kategoriDuzenle')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form autocomplete="off" id="duzenle" method="post" action="{{route('kategori_edit')}}"
                                      onsubmit="return false" enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="1">@lang('admin.kategoriBasligi')</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="@lang('admin.kategoriBasligi')">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="4">@lang('admin.kategoriResmi')</label>
                                                    <input name="image" type="file" id="4" class="dropify-edit"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary waves-effect"
                                                data-dismiss="modal">@lang('admin.kapat')</button>
                                        <input id="5" type="hidden" name="id" value="0">
                                        <button type="submit"
                                                class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                    </div>
                                </form>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class="table-responsive">
                        {{view('back.pages.category.table')}}
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
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/js/dropify.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').DataTable({
                columnDefs: [{orderable: false, targets: [4]}],
                pageLength: 10,
                "order": [[2, "desc"]],
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
        $("#yeniEkle").submit(function () {
            $.post({
                type: "POST",
                url: $("#yeniEkle")[0].action,
                data: new FormData(this),
                contentType: false,
                processData: false,
            }).done(function (data) {
                var data = JSON.parse(data);
                if (data.sonuc != 0) {
                    $(".close").click();
                    Swal.fire(
                        '{{__("admin.basarili")}}',
                        '{{__("admin.basariliMetin")}}',
                        'success'
                    );
                    var t = $('#datatable').DataTable();
                    var table = "'categories'";
                    var row = t.row.add([
                        data.title,
                        '<img style="max-width: 50%;" src="{{asset(env('ROOT').env('FRONT').env('CATEGORIES'))}}/' + data.image + '">',
                        data.created_at,
                        "<?=getDataStatus(1)?>",
                        '<button id="status" onclick="status(' + data.id + ', ' + table + ', event)" type="button" class="btn btn-lg btn-outline-success waves-effect waves-light">'
                        + '<i id="status-icon" class="mdi mdi-eye"></i>'
                        + '</button>'
                        + '<button data-toggle="modal" data-target=".duzenle" onclick="edit(' + data.id + ', ' + table + ', event)" type="button"'
                        + 'class="btn btn-lg btn-outline-primary waves-effect waves-light">'
                        + '<i class="far fa-edit"></i>'
                        + '</button>'
                        + '<button onclick="deleteContent(' + table + ', ' + data.id + ')" type="button"'
                        + 'class="btn btn-lg btn-outline-danger waves-effect waves-light">'
                        + '<i class="far fa-trash-alt"></i>'
                        + '</button>'
                    ]).node().id = 'row-' + data.id;
                    t.row(row).column(1).nodes().to$().addClass('text-center');
                    t.row(row).column(3).nodes().to$().attr('id', 'statusText');
                    t.row(row).draw(false);
                } else {
                    Swal.fire(
                        '{{__("admin.basarisiz")}}',
                        '{{__("admin.basarisizMetin")}}',
                        'error'
                    )
                }
            });
        });

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
    <script>

        function callback(a) {
            $(".duzenle").find('input#1').val(a.title);
            var edit = $('.dropify-edit').dropify({
                height: "300",
                defaultFile: "{{asset(env('ROOT').env('FRONT').env('CATEGORIES'))}}/" + a.image,
                allowedFileExtensions: ["png", "jpg", "jpeg", "gif"],
                messages: {
                    default: '{{__('admin.dropifyResimSec')}}',
                    replace: '{{__('admin.dropifyDegistir')}}',
                    remove: '{{__('admin.dropifySil')}}',
                    error: '{{__('admin.dropifyHata')}}',
                }
            });
            edit = edit.data('dropify');
            edit.resetPreview();
            edit.clearElement();
            edit.settings.defaultFile = "{{asset(env('ROOT').env('FRONT').env('CATEGORIES'))}}/" + a.image;
            edit.destroy();
            edit.init();
            $(".duzenle").find('input#5').val(a.id)

            $("#duzenle").submit(function () {
                $.post({
                    type: "POST",
                    url: $("#duzenle")[0].action,
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                }).done(function (data) {
                    var data = JSON.parse(data);
                    if (data.sonuc != 0) {
                        $(".close").click();
                        Swal.fire(
                            '{{__("admin.basarili")}}',
                            '{{__("admin.basariliMetin")}}',
                            'success'
                        );
                        var t = $('#datatable').DataTable();
                        t.row($("#row-" + data.id)).remove().draw();
                        t = $('#datatable').DataTable();
                        var table = "'categories'";
                        var row = t.row.add([
                            data.title,
                            '<img style="max-width: 50%;" src="{{asset(env('ROOT').env('FRONT').env('CATEGORIES'))}}/' + data.image + '">',
                            data.created_at,
                            "<?=getDataStatus(1)?>",
                            '<button id="status" onclick="status(' + data.id + ', ' + table + ', event)" type="button" class="btn btn-lg btn-outline-success waves-effect waves-light">'
                            + '<i id="status-icon" class="mdi mdi-eye"></i>'
                            + '</button>'
                            + '<button data-toggle="modal" data-target=".duzenle" onclick="edit(' + data.id + ', ' + table + ', event)" type="button"'
                            + 'class="btn btn-lg btn-outline-primary waves-effect waves-light">'
                            + '<i class="far fa-edit"></i>'
                            + '</button>'
                            + '<button onclick="deleteContent(' + table + ', ' + data.id + ')" type="button"'
                            + 'class="btn btn-lg btn-outline-danger waves-effect waves-light">'
                            + '<i class="far fa-trash-alt"></i>'
                            + '</button>'
                        ]).node().id = 'row-' + data.id;
                        t.row(row).column(1).nodes().to$().addClass('text-center');
                        t.row(row).column(3).nodes().to$().attr('id', 'statusText');
                        t.row(row).draw(false);

                    } else {
                        Swal.fire(
                            '{{__("admin.basarisiz")}}',
                            '{{__("admin.basarisizMetin")}}',
                            'error'
                        )
                    }
                });
            });
        }

        function edit(id, table, event) {
            let gelen;
            $.get("{{route('getData')}}", {table: table, id: id})
                .done(function (data, status) {
                    gelen = JSON.parse(data);
                    callback(gelen);
                });
        }
    </script>
@endsection
