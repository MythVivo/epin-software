@extends('back.layouts.app')
@section('css')
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

    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 text-left">
                                E-pin Yönetimi
                            </div>
                        </div>
                    </h4>
                    <div class="table-responsive">
                        {{view('back.pages.epin.table-detay')}}
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
                    var j = 3;
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
                    while (j < 4) {
                        total = api.column(j).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                        pageTotal = api.column( j, { page: 'current'} ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                        sumCol4Filtered = display.map(el => data[el][j]).reduce((a, b) => intVal(a) + intVal(b), 0 );
                        $( api.column(j).footer() ).html(sumCol4Filtered + " Adet");
                        j++;
                    }
                    while (e < 4) {
                        total = api.column(e).data().reduce( function (a, b) {return intVal2(a) + intVal2(b);}, 0 );
                        pageTotal = api.column( e, { page: 'current'} ).data().reduce( function (a, b) {return intVal2(a) + intVal2(b);}, 0 );
                        sumCol4Filtered = display.map(el => data[el][e]).reduce((a, b) => intVal2(a) + intVal2(b), 0 );
                        $( api.column(e).footer() ).html(sumCol4Filtered.toLocaleString());
                        e++;
                    }
                },
                
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
                    swal.fire(
                        '{{__("admin.iptal-edildi")}}',
                        '',
                        'error'
                    )
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
