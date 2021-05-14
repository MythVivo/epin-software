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
    <link href="{{asset(env('root').env('back').env('assets').'plugins/select2/select2.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link type="text/css"
          href="{{asset(env('root').env('back').env('assets').'plugins/x-editable/css/bootstrap-editable.css')}}"
          rel="stylesheet">
    <style>
        table.dataTable.nowrap td:nth-child(2) {
            white-space: unset !important;
        }
    </style>
@endsection
@section('body')

    <?php
    $oyun = \App\Models\Games::where('link', $link)->first();
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

    <div class="row" data-lang="{{getLang()}}">
        <div class="col-lg-6">
            <button data-toggle="modal" data-target=".ekle" type="button" class="btn btn-block btn-outline-success">
                @lang('admin.ozellikEkle')
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
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label for="1">@lang('admin.oyunBasligi')</label>
                                            <input name="title" type="text" class="form-control" id="1"
                                                   placeholder="@lang('admin.oyunBasligi')">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="2">@lang('admin.oyunKategori')</label>
                                            <select name="category" class="select2 form-group">
                                                @foreach(\App\Models\Category::where('lang', getLang())->whereNull('deleted_at')->get() as $u)
                                                    <option value="{{$u->id}}">{{$u->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="3">@lang('admin.oyunMetni')</label>
                                            <textarea class="editorText" placeholder="@lang('admin.oyunMetni')" id="3"
                                                      name="text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="4">@lang('admin.oyunResmi')</label>
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
        <div class="col-lg-6">
            <button data-toggle="modal" data-target=".baslik" type="button" class="btn btn-block btn-outline-beanred">
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
                                    <!-- son başlık özellikler -->
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="3">@lang('admin.baslikAciklama')</label>
                                            <textarea class="editorText" placeholder="@lang('admin.baslikAciklama')"
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

        @foreach(\App\Models\GamesTitles::where('lang', getLang())->where('game', $oyun->id)->get() as $u)
            <div class="col-lg-3 mb-4">
                <div class="card card-border client-card game">
                    <span data-target=".duzenle{{$u->id}}" data-toggle="modal" class="game-edit"><i
                            class="fa fa-edit text-white fa-2x"></i></span>
                    <img class="card-img-top img-fluid"
                         src="{{asset(env('root').env('front').env('games_titles').$u->image)}}"
                         alt="Card image cap">
                    <div class="card-body text-center">
                        <h2 class="client-name">
                            <span>{{$u->title}}</span></h2>
                        <br>
                        <h4>{{findGamesTitleType($u->type)}}</h4>
                        @if($u->type == 2)
                        @include('back.pages.games.GamesTitlesTypes.packages')
                        @endif

                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->

            <div class="modal fade duzenle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.oyunEkle')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                            </button>
                        </div>
                        <form id="duzenle{{$u->id}}" method="post" action="{{route('oyun_edit')}}"
                              enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            {!! getLangInput() !!}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label for="1">@lang('admin.oyunBasligi')</label>
                                            <input name="title" type="text" class="form-control" id="1"
                                                   value="{{$u->title}}"
                                                   placeholder="@lang('admin.oyunBasligi')">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="2">@lang('admin.oyunKategori')</label>
                                            <select name="category" class="select2 form-group">
                                                @foreach(\App\Models\Category::whereNull('deleted_at')->get() as $uu)
                                                    <option value="{{$uu->id}}"
                                                            @if($u->category == $uu->id) selected @endif>{{$uu->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="3">@lang('admin.oyunMetni')</label>
                                            <textarea class="editorText" placeholder="@lang('admin.oyunMetni')" id="3"
                                                      name="text">{!! $u->text !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="4">@lang('admin.oyunResmi')</label>
                                            <input
                                                data-default-file="{{asset(env('root').env('front').env('games').'/'.$u->image)}}"
                                                name="image" type="file" id="4" class="dropify"
                                                accept="image/*">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary waves-effect"
                                        data-dismiss="modal">@lang('admin.kapat')</button>
                                <input type="hidden" name="id" value="{{$u->id}}">
                                <button type="submit"
                                        class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        @endforeach

    </div>

@endsection
@section('js')
    <script>
        var baslikturu = $(".baslikturu").val();
        if (baslikturu == 1) {
            $(".bt").removeClass("col-md-4");
            $(".bt").addClass("col-md-3");
            $("#pazaryeri").css("display", "block");
        } else {
            $(".bt").addClass("col-md-4");
            $(".bt").removeClass("col-md-3");
            $("#pazaryeri").css("display", "none");
        }
        $(".baslikturu").change(function () {
            baslikturu = $(".baslikturu").val();
            if (baslikturu == 1) {
                $(".bt").removeClass("col-md-4");
                $(".bt").addClass("col-md-3");
                $("#pazaryeri").css("display", "block");
            } else {
                $(".bt").addClass("col-md-4");
                $(".bt").removeClass("col-md-3");
                $("#pazaryeri").css("display", "none");
            }
        });
    </script>
    <script
        src="{{asset(env('root').env('back').env('assets').'plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script
        src="{{asset(env('root').env('back').env('assets').'plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset(env('root').env('back').env('assets').'plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset(env('root').env('back').env('assets').'plugins/select2/select2.min.js')}}"></script>
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
    <script src="{{asset(env('root').env('back').env('assets').'plugins/tinymce/tinymce.min.js')}}"></script>
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

