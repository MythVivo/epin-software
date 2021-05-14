@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('root').env('back').env('assets').'plugins/datatables/dataTables.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('root').env('back').env('assets').'plugins/datatables/buttons.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('root').env('back').env('assets').'plugins/dropify/css/dropify.min.css')}}"
          rel="stylesheet">
    <link href="{{asset(env('root').env('back').env('assets').'plugins/sweet-alert2/sweetalert2.min.css')}}"
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
                                @lang('admin.yorumYonetimi')
                            </div>
                        </div>
                    </h4>
                    <div class="table-responsive">
                        {{view('back.pages.comments.table')}}
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('js')
    <script
        src="{{asset(env('root').env('back').env('assets').'plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script
        src="{{asset(env('root').env('back').env('assets').'plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset(env('root').env('back').env('assets').'plugins/dropify/js/dropify.min.js')}}"></script>
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
    <script src="{{asset(env('root').env('back').env('assets').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
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
            if (event.originalTarget.children[0] == undefined) { //ikonu verir
                icon = event.originalTarget;
                buton = event.originalTarget.offsetParent;
            } else { //butonu verir
                icon = event.originalTarget.children[0];
                buton = event.originalTarget;
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
