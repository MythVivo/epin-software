@if(isset($_GET['silKod']))
<?php
DB::table('games_packages_codes')->where('id', $_GET['silKod'])->delete();
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
        <?php /*
        @if($oyun->type == 1)
        <div class="col-lg">
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
        @endif
        */ ?>
        <div class="col-lg">
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
                                                    <option value="{{$u->id}}" @if($u->id == $oyun->id) selected @endif>{{$u->title}}</option>
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
                                    <div class="col-sm-6 col-md-3" id="epin" style="display: none;">
                                        <div class="form-group">
                                            <label for="3">E-pin ID</label>
                                            <input class="form-control" name="epin" id="3">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3" id="siparis" style="display: none;">
                                        <div class="form-group">
                                            <label for="3">Siparis</label>
                                            <input class="form-control" name="siparis" id="3">
                                        </div>
                                    </div>
                                    <!-- son başlık özellikler -->
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="33">Etiketler</label>
                                            <input class="form-control" name="etiket"
                                                   id="33" placeholder="Etiket 1, Etiket 2, Etiket 3">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="sira">Başlık Sırası</label>
                                            <input name="sira" type="number" step="1" class="form-control" id="sira"
                                                   placeholder="Sıra">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="kdv">Başlık Kdv</label>
                                            <input name="kdv" type="number" step="0.01" class="form-control" id="kdv"
                                                   placeholder="Kdv">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2">
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
        <div class="col-12 text-center pb-5">
            <form method="get">
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <input placeholder="Başlıklar İçinde Ara" class="form-control style-input" name="q"
                                   @if(isset($_GET['q'])) value="{{$_GET['q']}} @endif">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <button class="btn btn-outline-success w-100">Ara</button>
                        </div>
                    </div>
                </div>


            </form>
        </div>
        @if(isset($_GET['q']))
            @php $title = $_GET['q']; @endphp
        @else
            @php $title = ""; @endphp
        @endif

        <?php
        $sorgu = \App\Models\GamesTitles::where('lang', getLang())->where('title', 'like', '%'.$title.'%')->where('game', $oyun->id)->paginate(6);


        ?>

        @foreach($sorgu as $u)
            <div class="col-lg-4 mb-4">
                <div class="card card-border client-card game">
                    <span data-target=".duzenle{{$u->id}}" data-toggle="modal" class="game-edit"><i
                                class="fa fa-edit text-white fa-2x"></i></span>
                    <img class="card-img-top img-fluid" src="{{asset(env('ROOT').env('FRONT').env('GAMES_TITLES').$u->image)}}" alt="Card image cap">
                    <div class="card-body text-center">
                        <h2 class="client-name">
                            <span>{{$u->title}}</span></h2>
                        <br>
                        <h4>{{findGamesTitleType($u->type)}}</h4>
                        @if($u->type == 2)
                            @include('back.pages.games.GamesTitlesTypes.packages')
                        @elseif($u->type == 3)
                            <a href="{{route('oyun_detay_trade', [$oyun->link, $u->link])}}">Trade Detaylar İçin Tıklayın</a>
                        @elseif($u->type == 1)
                            <a href="{{route('oyun_detay_market', [$oyun->link, $u->link])}}">Market Detaylar İçin Tıklayın</a>
                        @endif

                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->

            <div class="modal fade duzenle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0" id="myModalLabel">Başlık Düzenle</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                            </button>
                        </div>
                        <form id="duzenle{{$u->id}}" method="post" action="{{route('baslik_edit')}}"
                              enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            {!! getLangInput() !!}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label for="1">@lang('admin.baslikBasligi')</label>
                                            <input name="title" type="text" class="form-control" id="1"
                                                   value="{{$u->title}}"
                                                   placeholder="@lang('admin.baslikBasligi')">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="2">@lang('admin.durum')</label>
                                            <select name="status" class="select2 form-group">
                                                <option value="0" @if($u->status == 0) selected @endif>Pasif</option>
                                                <option value="1" @if($u->status == 1) selected @endif>Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    @if($u->type == 2)
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="5">E-pin ID</label>
                                                <input name="epin" type="text" class="form-control" id="5" value="{{$u->epin}}" placeholder="E-pin ID">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="5">Siparis ile Satış</label>
                                                <select name="siparis"  class="select2 form-control" style="width: 100%">
                                                    <option value="1" @if($u->siparis == 1) selected @endif>Evet</option>
                                                    <option value="0" @if($u->siparis != 1) selected @endif>Hayır</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <? $soru=DB::table('epin_soru')->where('game_id',$oyun->id)->first() ;
                                               // dd($soru);
                                                    ?>
                                                <label for="5">Teslimat bilgileri için soru-1</label>
                                                <input type="text" name="soru1" class="form-control" placeholder="Kullanıcıdan istenecek bilgi (User No)" value="<? if(@$soru) {echo $soru->soru1; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="5">Teslimat bilgileri için soru-2</label>
                                                <input type="text" name="soru2" class="form-control" placeholder="Kullanıcıdan istenecek bilgi (Server No)" value="<? if(@$soru) {echo $soru->soru2; } ?>">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="33">Etiketler</label>
                                            <input class="form-control" name="etiket"
                                                   id="33" placeholder="Etiket 1, Etiket 2, Etiket 3" value="{{$u->etiket}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="sira">Başlık Sırası</label>
                                            <input name="sira" type="number" step="1" class="form-control" id="sira"
                                                   placeholder="Sıra" value="{{$u->sira}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="kdv">Başlık Kdv</label>
                                            <input name="kdv" type="number" step="0.01" class="form-control" id="kdv"
                                                   placeholder="Kdv" value="{{$u->kdv}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2">
                                        <div class="form-group">
                                            <label for="3333">Fatura Kesilsin Mi?</label>
                                            <select name="fatura_kes" id="3333" class="select2 form-control" style="width: 100%">
                                                <option value="1" @if($u->fatura_kes == 1) selected @endif>Evet Kesilsin</option>
                                                <option value="0" @if($u->fatura_kes == 0) selected @endif>Hayır Kesilmesin</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="3">@lang('admin.baslikAciklama')</label>
                                            <textarea class="editorText" placeholder="@lang('admin.baslikAciklama')"
                                                      id="3"
                                                      name="text">{!! $u->text !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="4">@lang('admin.baslikResmi')</label>
                                            <input
                                                    data-default-file="{{asset(env('ROOT').env('FRONT').env('GAMES_TITLES').'/'.$u->image)}}"
                                                    name="image" type="file" id="4" class="dropify"
                                                    accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="4">@lang('admin.baslikResmi') (Alış İçin)</label>
                                            <input
                                                    data-default-file="{{asset(env('ROOT').env('FRONT').env('GAMES_TITLES').'/'.$u->image_alis)}}"
                                                    name="image_alis" type="file" id="4" class="dropify"
                                                    accept="image/*">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary waves-effect"
                                        data-dismiss="modal">@lang('admin.kapat')</button>
                                <input type="hidden" name="id" value="{{$u->id}}">
                                <input type="hidden" name="game_id" value="{{$oyun->id}}">
                                <button type="submit"
                                        class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        @endforeach






            <div class="col-md-12 text-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href=?page=1">{{"|<<"}}</a>
                        </li>
                        <?php
                        $prevPage = $sorgu->currentPage() - 1;
                        if ($prevPage < '1') {
                            $prevPage = '1';
                        }
                        ?>
                        <li class="page-item">
                            <a class="page-link"
                               href="?page={{$prevPage}}">{{"<"}}</a>
                        </li>
                        @for($i = 1; $i < $sorgu->lastPage()+1; $i++)
                            <li class="page-item @if($sorgu->currentPage() == $i) active @endif">
                                <a class="page-link" href="?page={{$i}}">{{$i}}</a></li>
                        @endfor
                        <?php
                        $nextPage = $sorgu->currentPage() + 1;
                        if ($nextPage > $sorgu->lastPage()) {
                            $nextPage = $sorgu->lastPage();
                        }
                        ?>
                        <li class="page-item">
                            <a class="page-link"
                               href="?page={{$nextPage}}">{{">"}}</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link"
                               href="?page={{$sorgu->lastPage()}}">{{">>|"}}</a>
                        </li>
                    </ul>
                </nav>
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
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
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
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').DataTable({
                columnDefs: [{orderable: false, targets: [9]}],
                pageLength: 10,
                "order": [[8, "desc"]],
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

            $('.datatable2').DataTable({
                columnDefs: [{orderable: false, targets: [8]}],
                pageLength: 10,
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
    <script>
        function deleteItem(id) {
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
                    $.get("{{route('deleteItem')}}", {id: id});
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
                    images_upload_url: '{{route('resimYukle2')}}',
                    automatic_uploads : false,
                    images_upload_handler : function(blobInfo, success, failure) {
                        var xhr, formData;
                        xhr = new XMLHttpRequest();
                        xhr.withCredentials = false;
                        xhr.open('POST', '/panel/resimYukle2');
                        xhr.onload = function() {
                            var json;
                            if (xhr.status != 200) {
                                failure('HTTP Error: ' + xhr.status);
                                return;
                            }
                            json = JSON.parse(xhr.responseText);
                            if (!json || typeof json.location != 'string') {
                                failure('Invalid JSON: ' + xhr.responseText);
                                return;
                            }
                            success(json.location);
                        };
                        formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());

                        xhr.send(formData);
                    },
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

