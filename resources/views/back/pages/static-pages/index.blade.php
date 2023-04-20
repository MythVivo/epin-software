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
    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 text-left">
                                @lang('admin.sayfaYonetimi')
                            </div>
                            <div class="col-md-6 col-sm-6 text-right">
                                <button type="button" class="btn btn-outline-success waves-effect waves-light"
                                        data-toggle="modal" data-target=".ekle">@lang('admin.sayfaEkle')</button>
                            </div>
                        </div>
                    </h4>
                    <div class="modal fade ekle" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.sayfaEkle')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="yeniEkle" method="post" action="{{route('sayfa_add')}}"
                                      onsubmit="return false" enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="1">@lang('admin.sayfaBasligi')</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="@lang('admin.sayfaBasligi')">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="3">@lang('admin.sayfaMetni')</label>
                                                    <textarea class="editorText" placeholder="@lang('admin.sayfaMetni')" id="3" name="text"></textarea>
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
                                    <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.sayfaDuzenle')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form autocomplete="off" id="duzenle" method="post" action="{{route('sayfa_edit')}}"
                                      onsubmit="return false" enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="1">@lang('admin.sayfaBasligi')</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="@lang('admin.sayfaBasligi')">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="3">@lang('admin.sayfaMetni')</label>
                                                    <textarea class="editorText" placeholder="@lang('admin.sayfaMetni')" id="3" name="text"></textarea>
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
                        {{view('back.pages.static-pages.table')}}
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
            tinymce.activeEditor.save();
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
                    var link = "<a href='"+data.link+"' target='_blank'>{{__('admin.goruntulemek-icin-tiklayin')}}</a>";
                    var t = $('#datatable').DataTable();
                    var table = "'static_pages'";
                    var row = t.row.add([
                        data.title,
                        link,
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
            $(".duzenle").find('input#3').val(a.text);
            $(".duzenle").find('input#5').val(a.id)
            tinymce.activeEditor.setContent(a.text);
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
                        var link = "<a href='"+data.link+"' target='_blank'>{{__('admin.goruntulemek-icin-tiklayin')}}</a>";
                        t = $('#datatable').DataTable();
                        var table = "'static_pages'";
                        var row = t.row.add([
                            data.title,
                            link,
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
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/tinymce/tinymce.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            if($("#3").length > 0){
                tinymce.init({
                    selector: ".editorText",
                    theme: "modern",
                    language: '{{getLang()}}',
                    height:300,
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
