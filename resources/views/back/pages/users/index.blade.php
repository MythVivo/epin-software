<? if(@$_GET['t'] && @$_GET['m']){ sendSms($_GET['t'],urldecode(base64_decode($_GET['m'])));} ?>
@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
    <style>
        table.dataTable.nowrap td:nth-child(2) {
            white-space: unset !important;
        }

        .swal2-popup {
            font-size: 16px !important;

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

    @if ($errors->any())
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-danger alert-danger-shadow" role="alert">
                    <i class="mdi mdi-alert alert-icon"></i>
                    <div class="alert-text">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
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
                                @lang('admin.uyeYonetimi')
                            </div>
                            <div class="col-md-6 col-sm-6 text-right">
                                <button type="button" class="btn btn-outline-success waves-effect waves-light" data-toggle="modal" data-target=".ekle">@lang('admin.uyeEkle')</button>
                                <a href="?blk=316"  class="btn btn-danger" >Blokeler</a>
                            </div>
                        </div>
                    </h4>
                    <div class="modal fade ekle" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.uyeEkle')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="yeniEkle" method="post" action="{{route('uye_add')}}"
                                      enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label for="1">@lang('admin.uyeIsmi')</label>
                                                    <input name="name" type="text" class="form-control" id="1"
                                                           placeholder="@lang('admin.uyeIsmi')">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label for="2">@lang('admin.uyeEposta')</label>
                                                    <input name="email" type="text" class="form-control" id="2"
                                                           placeholder="@lang('admin.uyeEposta')">
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label for="3">@lang('admin.uyeSifre')</label>
                                                    <input name="password" type="password" class="form-control" id="3"
                                                           placeholder="@lang('admin.uyeSifre')">
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
                        {{view('back.pages.users.table')}}
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->



    <div class="modal modal-xl-centered fade" id="SMS_S" tabindex="-1"  aria-hidden="true" >
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Kullanıcıya Erişim Öncesi Bilgilendirme Mesajı</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                <div class="modal-body">
                    <div class="row">
                        Telefon no : <input type="text" class="form-control" id="telno" >
                        <br>
                        <label>Gönderilecek Mesaj: <i id="kac"></i></label>
<textarea class="form-control" id="mess" style="height: 130px">
Merhabalar, 20dk. içerisinde OYUNEKS Destek Ekibi tarafından size aşağıdaki numara üzerinden ulaşılacaktır.
(850) 308 0007
Vaktinizi alacağımız için özür dileriz.
</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="zend" class="btn btn-sm btn-success">Gönder</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
    <script type="text/javascript">
        var tel,uid,ak;
// -----------------------SMS Göderim-------------------------------
        $('.sms').click(function (){tel= $(this).attr('uid'); if(tel.length<10) {swal.fire({icon:'error',html:'Telefon Numarası Kayıtlı değil yada hatalı',showConfirmButton: false,timer:2500});} else {$('#telno').val(tel);$('#SMS_S').modal('show');}});
        $('#mess').keyup(function (){$('#kac').empty().text($('#mess').val().length);})
        $('#kac').empty().text($('#mess').val().length);
        $('#zend').click(function (){location.href='?t='+tel+'&m='+btoa(encodeURIComponent($('#mess').val()));})
        @if(@$_GET['t'] && @$_GET['m'])
        swal.fire({icon:'success',html:'SMS Gönderildi',showConfirmButton: false,timer:1500});
        @endif
// -----------------------SMS Göderim-------------------------------


        $('.bloke').click(function (){
            if($(this).attr('dr')==1) {ak=2; var dr='btn-danger';  var dr1='Blokeyi Kaldır'; var ph='disabled';}
            if($(this).attr('dr')==2) {ak=1; var dr='btn-success'; var dr1='Bakiyeyi Bloke Et';var ph='';}

            uid= $(this).attr('uid');

            swal.fire({
                showConfirmButton: false,
                html:"<p class='btn btn-soft-success sgir'><i class='btn "+dr+" fa-power-off fas font-22'></i> "+dr1+" </p>" +
                    "<input type='text' id='sebep' style='width: 100%' "+ph+" placeholder='Bloke sebebini girin (kullanıcı bunu göremez)'><br><br>" +
                    "Kullanıcı Bakiye Bloke Geçmişi" +
                    "<div style='height:300px; overflow: auto'><table id='bloke_table' class='table font-12 table-bordered small table-sm'><thead><tr><th>No</th><th>Admin</th><th>Sebep</th><th>Bloke Tarih</th></tr></thead><tbody>" +
                    "</tbody></table></div>"

            });

            $.post('/ykp.php', {bloke_list:316, uid: uid, _token: $('meta[name="csrf-token"]').attr('content')}, function (x) {
                $('#bloke_table tbody').html(x);
            });

        });



        $(document).ready(function () {

            $(document).on('click','.sgir',function () {
                $.post('/ykp.php', {bloke_gir:316,uid:uid,admin:{{Auth::user()->id}},sebep:$('#sebep').val(),aktif:ak, _token: $('meta[name="csrf-token"]').attr('content')}, function (x) {
                    location.reload();
                });
            });


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
                columnDefs: [{orderable: false, targets: [9]}],
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
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
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
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
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
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },

                ],
                "order": [[5, "desc"]],
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
                buton = event.target;
            } else { //butonu verir
                icon = event.target.children[0];
                buton = event.target;
            }
            var statusText = buton.parentElement.parentElement;
            statusText = $(statusText).find('td#statusText');
            $.get("{{route('setStatus')}}", {table: table, id: id});
            $(buton).prop('disabled', true);
            $(buton).css('cursor', 'not-allowed');
            $(icon).removeClass("fa-eye");
            $(icon).addClass("mdi mdi-spin mdi-loading");
            if ($(buton).hasClass("btn-outline-warning")) {
               $(buton).removeClass("btn-outline-warning");               $(buton).addClass("btn-outline-primary");
                var cevir = "success";
            } else {
                $(buton).removeClass("btn-outline-success");
                $(buton).addClass("btn-outline-primary");
                var cevir = "warning";
            }
            setTimeout(function () {
                $(buton).css('cursor', 'pointer');
                $(buton).prop('disabled', false);
                $(icon).removeClass("mdi mdi-spin mdi-loading");
                $(icon).addClass("fa-eye");
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
