@if(isset($_GET['silKod']))
    <?php  #---------------------------------------Epin silme olayından önce silinecek kodu ve sebebini öğrenip logluyoruz
    $p = (object) $_GET;
    DB::select("insert into games_packages_codes_deleted (kod,paket,tedarikci,alis,kdv,eklenme) select code,package_id,tedarikci,alis_fiyati,kdv,created_at from games_packages_codes where id='$p->silKod'");
    $last_id = DB::getPdo()->lastInsertId();$user=Auth::user()->id;$sbp=base64_decode($p->s);$tar=date('YmdHis');
    DB::select("update games_packages_codes_deleted set user='$user', sebep='$sbp', deleted_at='$tar' where id='$last_id'");
    DB::table('games_packages_codes')->where('id', $p->silKod)->delete();
    header("Location: ?okey");
    die();
    ?>
@endif
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
    <?php
    $paket = \App\Models\GamesPackages::where('id', $paket)->first();
    ?>

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

    <div class="row" data-lang="{{getLang()}}">
        <div class="col-lg-12">
            <button onclick="location.href='{{route('oyun_paket_kod_edit', $paket->id)}}'" type="button"
                    class="btn btn-block btn-outline-success">
                {{$paket->title}} kodlarını düzenle
            </button>
        </div>
        <div class="col-lg-12 mt-3 mb-3">
            <form method="post" enctype="multipart/form-data" action="{{route('oyun_paket_kod_edit_excel_post')}}" autocomplete="off">
                @csrf
                <input type="hidden" name="paketId" value="{{$paket->id}}">

                <div class="row">
                    <div class="col-lg-6">
                        <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="form-control" name="excelDosyasi">
                    </div>
                    <div class="col-lg-6">
                        <button type="submit"
                                class="btn btn-block btn-outline-success">
                            Exceli İçeri Aktar
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <div class="row mt-3">
        <div class="table-responsive">
            {{view('back.pages.games.views.kod-table')->with('paket', $paket)}}
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
                columnDefs: [{orderable: false, targets: [8]}],
                pageLength: 1000,
                orderCellsTop: true,
                fixedHeader: true,
                lengthChange: false,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: '{{$paket->title}} Kodları - Excel',
                        text: '<i class="far fa-file-excel"></i>',
                        className: 'btn btn-outline-success',
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        title: '{{$paket->title}} Kodları - Csv',
                        text: '<i class="fas fa-file-csv"></i>',
                        className: 'btn btn-outline-info',
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: '{{$paket->title}} Kodları - Pdf',
                        className: 'btn btn-outline-danger',
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        text: '<i class="far fa-file-pdf"></i>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },

                ],
                "order": [[7, "desc"]],
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
                html: "<p>Bu içeriği silmenizi gerektiren sebebi aşağıya belirtebilirsiniz. Kullanıcı adınız ve işlem saatiyle birlikte silinen kod kayıt altına alınacaktır. </p> <input type='text' maxlength='250' id='sebep'>",
                icon: "question",
                type: 'error',
                showCancelButton: true,
                confirmButtonText: '{{__("admin.onayliyorum")}}',
                cancelButtonText: '{{__("admin.vazgec")}}',
                reverseButtons: true ,

            }).then((result) => {
                if (result.value) {
                    var sb=Swal.getPopup().querySelector('#sebep').value;
                    swal.fire(
                        '{{__("admin.basarili")}}',
                        '{{__("admin.basariliMetin")}}',
                        'success'
                    );
                    setTimeout(function () {
                        location.href = '?silKod=' + id+'&s='+btoa(unescape(encodeURIComponent(sb)));
                    }, 100);

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

