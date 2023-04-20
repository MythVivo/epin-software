<? $tumu=@$_GET['son']==2?1:0; ?>
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
                                @lang('admin.sliderYonetimi')
                            </div>
                            <div class="col-md-6 col-sm-6 text-right">
                                Pasifleri Gizle <input id="son" type="checkbox" name="son" style="width: 20px; height: 20px"  @if($tumu==0) checked @endif>
                                <button type="button" class="btn btn-outline-info waves-effect waves-light"
                                        data-toggle="modal" data-target=".miniSlider">Mini Sliderlar
                                </button>
                                <button type="button" class="btn btn-outline-success waves-effect waves-light"
                                        data-toggle="modal" data-target=".ekle">@lang('admin.sliderEkle')</button>
                            </div>
                        </div>
                    </h4>
                    <div class="modal fade ekle" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.sliderEkle')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="yeniEkle" method="post" action="{{route('slider_add')}}"
                                      onsubmit="return false" enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label for="1">@lang('admin.sliderBasligi')</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="@lang('admin.sliderBasligi')">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label for="2">@lang('admin.sliderAciklama')</label>
                                                    <input name="text" type="text" class="form-control" id="2"
                                                           placeholder="@lang('admin.sliderAciklama')">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label for="3">@lang('admin.sliderButonLink')</label>
                                                    <input name="link" type="text" class="form-control" id="3"
                                                           placeholder="@lang('admin.sliderButonLink')">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="4">@lang('admin.sliderResmi')</label>
                                                    <input name="image" type="file" id="4" class="dropify"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="5">Slider Mobil Görsel</label>
                                                    <input name="image_mobile" type="file" id="4" class="dropify"
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
                                    <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.sliderDuzenle')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="duzenle" method="post" action="{{route('slider_edit')}}"
                                      onsubmit="return false" enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label for="1">@lang('admin.sliderBasligi')</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="@lang('admin.sliderBasligi')">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label for="2">@lang('admin.sliderAciklama')</label>
                                                    <input name="text" type="text" class="form-control" id="2"
                                                           placeholder="@lang('admin.sliderAciklama')">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label for="3">@lang('admin.sliderButonLink')</label>
                                                    <input name="link" type="text" class="form-control" id="3"
                                                           placeholder="@lang('admin.sliderButonLink')">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="4">@lang('admin.sliderResmi')</label>
                                                    <input name="image" type="file" id="4" class="dropify-edit"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="44">Slider Mobil Görsel</label>
                                                    <input name="image_mobile" type="file" id="44" class="dropify-edit2"
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
                    <div class="modal fade miniSlider" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Mini Sliderlar</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <?php
                                $mini1 = DB::table('slider_mini')->where('id', '1')->first();
                                $mini2 = DB::table('slider_mini')->where('id', '2')->first();
                                ?>
                                <form method="post" action="{{route('slider_mini_add')}}" enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('admin.sliderBasligi') 1</label>
                                                    <input name="title_mini_1" type="text" class="form-control"
                                                           placeholder="@lang('admin.sliderBasligi')" value="{{$mini1->title}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('admin.sliderAciklama') 1</label>
                                                    <input name="text_mini_1" type="text" class="form-control"
                                                           placeholder="@lang('admin.sliderAciklama')" value="{{$mini1->text}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('admin.sliderButonLink') 1</label>
                                                    <input name="link_mini_1" type="text" class="form-control"
                                                           placeholder="@lang('admin.sliderButonLink')" value="{{$mini1->link}}">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>@lang('admin.sliderResmi') 1</label>
                                                    <input data-default-file="{{asset(env('ROOT').env('FRONT').env('SLIDER').'/'.$mini1->image)}}" name="image_mini_1" type="file" class="dropify"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Slider Mobil Görsel 1</label>
                                                    <input data-default-file="{{asset(env('ROOT').env('FRONT').env('SLIDER').'/'.$mini1->image_mobile)}}" name="image_mobile_mini_1" type="file" class="dropify"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-12 mt-3 mb-3"><hr></div>
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('admin.sliderBasligi') 2</label>
                                                    <input name="title_mini_2" type="text" class="form-control"
                                                           placeholder="@lang('admin.sliderBasligi')" value="{{$mini2->title}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('admin.sliderAciklama') 2</label>
                                                    <input name="text_mini_2" type="text" class="form-control"
                                                           placeholder="@lang('admin.sliderAciklama')" value="{{$mini2->text}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('admin.sliderButonLink') 2</label>
                                                    <input name="link_mini_2" type="text" class="form-control"
                                                           placeholder="@lang('admin.sliderButonLink')" value="{{$mini2->link}}">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>@lang('admin.sliderResmi') 2</label>
                                                    <input data-default-file="{{asset(env('ROOT').env('FRONT').env('SLIDER').'/'.$mini2->image)}}" name="image_mini_2" type="file" class="dropify"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Slider Mobil Görsel 2</label>
                                                    <input data-default-file="{{asset(env('ROOT').env('FRONT').env('SLIDER').'/'.$mini2->image_mobile)}}" name="image_mobile_mini_2" type="file" class="dropify"
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
                        {{view('back.pages.slider.table',['tumu' => $tumu])}}
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
    <script type="text/javascript">
        $(document).ready(function () {

            $('#son').click(function (){
                if($(this).prop('checked')==false) {
                    location.href='?son=2';
                } else {location.href='?son=1';}
            })


            $('#datatable').DataTable({
                columnDefs: [{orderable: false, targets: [7]}],
                pageLength: 10,
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
                    if (data.link != null) {
                        var link = '<a href="' + data.link + '" target="_blank">{{__("admin.goruntulemek-icin-tiklayin")}}</a>';
                    } else {
                        var link = '{{__("admin.link-yok")}}';
                    }
                    var table = "'slider'";
                    var row = t.row.add([
                        data.title,
                        data.text,
                        '<img style="max-width: 50%;" src="{{asset(env('ROOT').env('FRONT').env('SLIDER'))}}/' + data.image + '">',
                        '<img style="max-width: 50%;" src="{{asset(env('ROOT').env('FRONT').env('SLIDER'))}}/' + data.image_mobile + '">',
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
                    t.row(row).column(2).nodes().to$().addClass('text-center');
                    t.row(row).column(3).nodes().to$().addClass('text-center');
                    t.row(row).column(5).nodes().to$().attr('id', 'statusText');
                    t.row(row).draw(false);

                    //$('#datatable').load("{{route('slider_table')}}");
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
            $(".duzenle").find('input#2').val(a.text);
            $(".duzenle").find('input#3').val(a.link);
            var edit = $('.dropify-edit').dropify({
                height: "300",
                defaultFile: "{{asset(env('ROOT').env('FRONT').env('SLIDER'))}}/" + a.image,
                allowedFileExtensions: ["png", "jpg", "jpeg", "gif", "webp"],
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
            edit.settings.defaultFile = "{{asset(env('ROOT').env('FRONT').env('SLIDER'))}}/" + a.image;
            edit.destroy();
            edit.init();

            var edit2 = $('.dropify-edit2').dropify({
                height: "300",
                defaultFile: "{{asset(env('ROOT').env('FRONT').env('SLIDER'))}}/" + a.image_mobile,
                allowedFileExtensions: ["png", "jpg", "jpeg", "gif", "webp"],
                messages: {
                    default: '{{__('admin.dropifyResimSec')}}',
                    replace: '{{__('admin.dropifyDegistir')}}',
                    remove: '{{__('admin.dropifySil')}}',
                    error: '{{__('admin.dropifyHata')}}',
                }
            });
            edit2 = edit2.data('dropify');
            edit2.resetPreview();
            edit2.clearElement();
            edit2.settings.defaultFile = "{{asset(env('ROOT').env('FRONT').env('SLIDER'))}}/" + a.image_mobile;
            edit2.destroy();
            edit2.init();


            $(".duzenle").find('input#5').val(a.id);
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
                        if (data.link != null) {
                            var link = '<a href="' + data.link + '" target="_blank">{{__("admin.goruntulemek-icin-tiklayin")}}</a>';
                        } else {
                            var link = '{{__("admin.link-yok")}}';
                        }
                        var table = "'slider'";
                        var row = t.row.add([
                            data.title,
                            data.text,
                            '<img style="max-width: 50%;" src="{{asset(env('ROOT').env('FRONT').env('SLIDER'))}}/' + data.image + '">',
                            '<img style="max-width: 50%;" src="{{asset(env('ROOT').env('FRONT').env('SLIDER'))}}/' + data.image_mobile + '">',
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
                        t.row(row).column(2).nodes().to$().addClass('text-center');
                        t.row(row).column(3).nodes().to$().addClass('text-center');
                        t.row(row).column(5).nodes().to$().attr('id', 'statusText');
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
