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
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/select2/select2.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link type="text/css"
          href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/x-editable/css/bootstrap-editable.css')}}"
          rel="stylesheet">

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
        <div class="col-lg-6">
            <button onclick="editorStart(0)" data-toggle="modal" data-target=".ekle" type="button"
                    class="btn btn-block btn-outline-success">
                @lang('admin.oyunEkle')
            </button>
            <div class="modal fade ekle" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.oyunEkle')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                            </button>
                        </div>
                        <form id="yeniEkle" method="post" action="{{route('oyun_add')}}"
                              enctype="multipart/form-data">
                            @csrf
                            {!! getLangInput() !!}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="1">@lang('admin.oyunBasligi')</label>
                                            <input name="title" type="text" class="form-control" id="1"
                                                   placeholder="@lang('admin.oyunBasligi')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="2">@lang('admin.oyunKategori')</label>
                                            <select name="category" class="select2 form-group">
                                                @foreach(\App\Models\Category::where('lang', getLang())->whereNull('deleted_at')->get() as $u)
                                                    <option value="{{$u->id}}">{{$u->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="sira">Oyun Sırası</label>
                                            <input name="sira" type="number" step="1" class="form-control" id="sira"
                                                   placeholder="Sıra">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="3">@lang('admin.oyunMetni')</label>
                                            <textarea class="editorText0" placeholder="@lang('admin.oyunMetni')" id="3"
                                                      name="text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="4">@lang('admin.oyunResmi')</label>
                                            <input name="image" type="file" id="4" class="dropify"
                                                   accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Oyun İkonu</label>
                                            <input name="icon" type="file" class="dropify"
                                                   accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Oyun İkonu 2</label>
                                            <input name="icon_2" type="file" class="dropify"
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
        </div>
        <div class="col-lg-6">
            <button onclick="editorStart(999999)" data-toggle="modal" data-target=".baslik" type="button"
                    class="btn btn-block btn-outline-beanred">
                @lang('admin.baslikEkle')
            </button>
            <div class="modal fade baslik" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.baslikEkle')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                            </button>
                        </div>
                        <form id="baslikEkle" method="post" action="{{route('baslik_add')}}"
                              enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            {!! getLangInput() !!}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12 col-md-4 bt">
                                        <div class="form-group">
                                            <label for="1">@lang('admin.baslikOyun')</label>
                                            <select name="game" id="1" class="select2 form-control custom-select">
                                                @foreach(\App\Models\Games::get() as $u)
                                                    <option value="{{$u->id}}">{{$u->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 bt">
                                        <div class="form-group">
                                            <label for="2">@lang('admin.baslikBasligi')</label>
                                            <input name="title" type="text" class="form-control" id="2"
                                                   placeholder="@lang('admin.baslikBasligi')">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 bt">
                                        <div class="form-group">
                                            <label for="3">@lang('admin.baslikTuru')</label>
                                            <select name="type" id="3"
                                                    class="select2 form-control custom-select baslikturu">
                                                <option value="1">@lang('admin.baslikPazarYeri')</option>
                                                <option value="2">@lang('admin.baslikPaketSatisi')</option>
                                                <option value="3">@lang('admin.baslikTrade')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- başlık özellikler -->
                                    <div class="col-sm-12 col-md-3" id="pazaryeri" style="display: none;">
                                        <div class="form-group">
                                            <label for="3">@lang('admin.baslikOzel')</label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       class="custom-control-input" name="special"
                                                       id="horizontalCheckbox" data-parsley-multiple="groups"
                                                       data-parsley-mincheck="2">
                                                <label class="custom-control-label asd"
                                                       for="horizontalCheckbox">@lang('admin.baslikResimSec')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- başlık özellikler -->
                                    <div class="col-sm-12 col-md-3" id="epin" style="display: none;">
                                        <div class="form-group">
                                            <label for="3">E-pin ID</label>
                                            <input class="form-control" name="epin"
                                                   id="3">
                                        </div>
                                    </div>
                                    <!-- son başlık özellikler -->
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="33">Etiketler</label>
                                            <input class="form-control" name="etiket"
                                                   id="33" placeholder="Etiket 1, Etiket 2, Etiket 3">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="sira">Başlık Sırası</label>
                                            <input name="sira" type="number" step="1" class="form-control" id="sira"
                                                   placeholder="Sıra">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <div class="form-group">
                                            <label for="3333">Fatura Kesilsin Mi?</label>
                                            <select name="fatura_kes" id="3333"
                                                    class="select2 form-control" style="width: 100%">
                                                <option value="1">Evet Kesilsin</option>
                                                <option value="0">Hayır Kesilmesin</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="3">@lang('admin.baslikAciklama')</label>
                                            <textarea class="editorText999999"
                                                      placeholder="@lang('admin.baslikAciklama')"
                                                      id="3"
                                                      name="text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="4">@lang('admin.baslikResmi')</label>
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
        </div>
    </div>
    <div class="row mt-3">
        <div class="table-responsive">
            {{view('back.pages.games.table')}}
        </div>
    </div>
@endsection
@section('js')
    <script>
        var baslikturu = $(".baslikturu").val();
        if (baslikturu == 1) {
            $(".bt").removeClass("col-md-4");
            $(".bt").addClass("col-md-3");
            $("#pazaryeri").css("display", "block");
            $("#epin").css("display", "none");
        } else if (baslikturu == 2) {
            $(".bt").removeClass("col-md-4");
            $(".bt").addClass("col-md-3");
            $("#epin").css("display", "block");
            $("#pazaryeri").css("display", "none");
        } else {
            $(".bt").addClass("col-md-4");
            $(".bt").removeClass("col-md-3");
            $("#epin").css("display", "none");
            $("#pazaryeri").css("display", "none");
        }
        $(".baslikturu").change(function () {
                baslikturu = $(".baslikturu").val();
                if (baslikturu == 1) {
                    $(".bt").removeClass("col-md-4");
                    $(".bt").addClass("col-md-3");
                    $("#pazaryeri").css("display", "block");
                    $("#epin").css("display", "none");
                } else if (baslikturu == 2) {
                    $(".bt").removeClass("col-md-4");
                    $(".bt").addClass("col-md-3");
                    $("#epin").css("display", "block");
                    $("#pazaryeri").css("display", "none");
                } else {
                    $(".bt").addClass("col-md-4");
                    $(".bt").removeClass("col-md-3");
                    $("#epin").css("display", "none");
                    $("#pazaryeri").css("display", "none");
                }
            }
        );
    </script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/select2/select2.min.js')}}"></script>
    <script>
        $(".select2").select2({
            width: '100%'
        });
    </script>
    <script>
        $(function () {
            $('.dropify').dropify({
                height: "300",
                allowedFileExtensions: ["png", "jpg", "jpeg", "gif", "webp"],
                messages: {
                    default: '{{__('admin.dropifyResimSec')}}',
                    replace: '{{__('admin.dropifyDegistir')}}',
                    remove: '{{__('admin.dropifySil')}}',
                    error: '{{__('admin.dropifyHata')}}',
                }
            });
        });
    </script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/tinymce/tinymce.min.js')}}"></script>

    <script type="text/javascript">
        function editorStart(gelen) {
            if (gelen == 0) {
                var modalName = ".ekle";
            } else if (gelen == 999999) {
                var modalName = ".baslik";
            } else {
                var modalName = ".duzenle" + gelen;
            }


            var selectorName = ".editorText" + gelen;
            $(modalName).on('shown.bs.modal', function () {
                tinymce.init({
                    selector: selectorName,
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
            })
        }

        $('.modal').on('hide.bs.modal', function () {
            var modalId = "#" + this.dataset.id;
            // scope the selector to the modal so you remove any editor on the page underneath.
            tinymce.remove();
            //tinymce.destroy(modalId + ' textarea');
        });


        $(document).ready(function () {

            setTimeout(function () {
                if ($("#3").length > 0) {

                }
            }, 100);


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


            /*$('#datatable').DataTable({
                columnDefs: [{orderable: false, targets: [6]}],
                pageLength: 10,
                "processing": true,
                "deferLoading": 57,
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
            });*/
        });
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
                    swal.fire(
                        '{{__("admin.basarili")}}',
                        '{{__("admin.basariliMetin")}}',
                        'success'
                    )
                    setTimeout(function () {
                        location.reload();
                    }, 2000);

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

@endsection

