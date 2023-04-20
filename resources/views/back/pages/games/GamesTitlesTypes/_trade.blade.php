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
    <div class="row mt-3">
        <div class="col-lg-12 mb-4">
            <div class="card card-border client-card">
                <div class="card-body text-center">
                    <h2 class="client-name">
                        <span>{{$u->title}}</span></h2>
                    <div class="row">
                        @foreach(\App\Models\GamesPackagesTrade::where('games_titles', $u->id)->get() as $uu)
                            <div class="col-12 mb-4">
                                <div class="altBaslik float-left">
                                    <a href="javascript:void(0)" data-toggle="modal"
                                       data-target=".tradeDuzenle{{$uu->id}}">
                                        {{$uu->title}} - Alış : ₺{{findGamesPackagesTradeMusteridenAlPrice($uu->id)}} -
                                        Satış :
                                        ₺{{findGamesPackagesTradeMusteriyeSatPrice($uu->id)}}
                                        <?php
                                        $toplamSatislar = DB::table('game_gold_satis')->where('paket', $uu->id)->where('tur', 'bizden-al')->where('status', '1');
                                        $toplamSatisSayi = $toplamSatislar->count();
                                        $toplamSatisAdet = $toplamSatislar->sum('adet');
                                        $toplamSatisTutar = $toplamSatislar->sum('price');
                                        ?>
                                        | <span class="text-success">Ortalama Müşteriye Satış Fiyatı
                                        : @if($toplamSatisSayi > 0) {{substr($toplamSatisTutar / $toplamSatisAdet, 0, 6)}} @else
                                                {{findGamesPackagesTradeMusteriyeSatPrice($uu->id)}} @endif </span>

                                        <?php
                                        $toplamAlislar = DB::table('game_gold_satis')->where('paket', $uu->id)->where('tur', 'bize-sat')->where('status', '1');
                                        $toplamAlisSayi = $toplamAlislar->count();
                                        $toplamAlisAdet = $toplamAlislar->sum('adet');
                                        $toplamAlisTutar = $toplamAlislar->sum('price');
                                        ?>
                                        | <span class="text-danger">Ortalama Müşteriden Alış Fiyatı
                                        : @if($toplamAlisSayi > 0) {{substr($toplamAlisTutar / $toplamAlisAdet, 0, 6)}} @else
                                                {{findGamesPackagesTradeMusteridenAlPrice($uu->id)}} @endif </span>
                                    </a>
                                </div>
                            </div>
                            <div class="modal fade tradeDuzenle{{$uu->id}}" tabindex="-1" role="dialog"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title mt-0"
                                                id="myModalLabel">@lang('admin.paketTradeDuzenle')</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                X
                                            </button>
                                        </div>
                                        <form id="duzenle" method="post" action="{{route('oyun_paket_trade_edit')}}"
                                              enctype="multipart/form-data">
                                            @csrf
                                            {!! getLangInput() !!}
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label for="1">@lang('admin.paketBasligi')</label>
                                                            <input name="title" type="text" class="form-control" id="1"
                                                                   placeholder="@lang('admin.paketBasligi')"
                                                                   value="{{$uu->title}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label for="2">@lang('admin.paketAlisFiyati')</label>
                                                            <input name="alis" type="number" step="0.01"
                                                                   class="form-control" id="2"
                                                                   placeholder="@lang('admin.paketAlisFiyati')"
                                                                   value="{{$uu->alis_fiyat}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label for="3">@lang('admin.paketSatisFiyati')</label>
                                                            <input name="satis" type="number" step="0.01"
                                                                   class="form-control"
                                                                   id="3"
                                                                   placeholder="@lang('admin.paketSatisFiyati')"
                                                                   value="{{$uu->satis_fiyat}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label for="9">Max Alış Stoğu</label>
                                                            <input name="alis_stok" type="number" step="0.01"
                                                                   class="form-control"
                                                                   id="9"
                                                                   placeholder="Max Alış Stoğu"
                                                                   value="{{$uu->alis_stok}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label for="10">Max Satış Sınır</label>
                                                            <input name="satis_stok" type="number" step="0.01"
                                                                   class="form-control"
                                                                   id="10"
                                                                   placeholder="Max Satış Sınır"
                                                                   value="{{$uu->satis_stok}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="form-group">
                                                            <label for="100">Genel Stok</label>
                                                            <input name="stok" type="number"
                                                                   class="form-control"
                                                                   id="100"
                                                                   placeholder="Genel Stok"
                                                                   readonly
                                                                   value="{{$uu->stok}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="form-group">
                                                            <label>Bonus Tipi</label>
                                                            <select name="bonus_type" class="select2 form-group">
                                                                <option value="0"
                                                                        @if($uu->bonus_type == 0) selected @endif>Bonus
                                                                    Yok
                                                                </option>
                                                                <option value="1"
                                                                        @if($uu->bonus_type == 1) selected @endif>Yüzde
                                                                    Bonus
                                                                </option>
                                                                <option value="2"
                                                                        @if($uu->bonus_type == 2) selected @endif>Tutar
                                                                    Bonus
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="form-group">
                                                            <label>Bonus Tutarı</label>
                                                            <input name="bonus_amount" type="number" step="0.01"
                                                                   class="form-control"
                                                                   placeholder="Bonus Tutarı"
                                                                   value="{{$uu->bonus_amount}}">
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if ($uu->bonus_date != NULL) {
                                                        $dateb = \Carbon\Carbon::parse($uu->bonus_date);
                                                        $date1b = $dateb->format('Y-m-d');
                                                        $date2b = $dateb->format('H:i');
                                                    } else {
                                                        $date1b = "";
                                                        $date2b = "";
                                                    }
                                                    ?>
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="form-group">
                                                            <label>Bonus Son Tarihi</label>
                                                            <input name="bonus_date" type="date" class="form-control"
                                                                   placeholder="Bonus Son Tarihi" value="{{$date1b}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="form-group">
                                                            <label>Bonus Son Saati</label>
                                                            <input name="bonus_date_time" type="time"
                                                                   class="form-control"
                                                                   placeholder="Bonus Son Saati" value="{{$date2b}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <label for="333">Etiket</label>
                                                            <input name="etiket" type="text"
                                                                   class="form-control" id="333"
                                                                   placeholder="Etiket 1, Etiket 2, Etiket 3"
                                                                   value="{{$uu->etiket}}"
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="333">Sıra</label>
                                                            <input name="sira" type="number" step="1"
                                                                   class="form-control" id="444"
                                                                   value="{{$uu->sira}}"
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="5">@lang('admin.paketMetni')</label>
                                                            <textarea class="editorText"
                                                                      placeholder="@lang('admin.paketMetni')"
                                                                      id="5"
                                                                      name="text">{!! $uu->description !!}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="6">@lang('admin.paketResmi')</label>
                                                            <input name="image"
                                                                   data-default-file="{{asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES_TRADE').$uu->image)}}"
                                                                   type="file" id="6" class="dropify"
                                                                   accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-danger waves-effect"
                                                        onclick="deleteContent('games_packages_trade', {{$uu->id}})">@lang('admin.paketSil')</button>
                                                <button type="button" class="btn btn-outline-secondary waves-effect"
                                                        data-dismiss="modal">@lang('admin.kapat')</button>
                                                <input type="hidden" name="id" value="{{$uu->id}}">
                                                <button type="submit"
                                                        class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                            </div>
                                        </form>


                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        @endforeach
                        <div class="col-12">
                            <button type="button" data-toggle="modal" data-target=".paketEkle{{$u->id}}"
                                    class="btn btn-block btn-outline-secondary">@lang('admin.paketEkle')</button>
                            <button type="button" data-toggle="modal" data-target=".topluGB"
                                    class="btn btn-block btn-outline-success">Toplu GB Güncelleme
                            </button>
                            <div class="row mt-3 mb-3">
                                <div class="col-6">
                                    <button type="button" data-toggle="modal" data-target=".stokEkle"
                                            class="btn btn-block btn-outline-warning">Stok Ekle
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" data-toggle="modal" data-target=".stokCikar"
                                            class="btn btn-block btn-outline-warning">Stok Çıkar
                                    </button>
                                </div>
                            </div>

                            <button onclick="deleteContent('games_titles', {{$u->id}})" type="button"
                                    class="btn btn-block btn-outline-danger">@lang('admin.baslikSil')
                            </button>
                        </div>
                    </div>
                    <div class="modal fade paketEkle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.paketEkle')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="yeniEkle" method="post" action="{{route('oyun_paket_trade_add')}}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="1">@lang('admin.paketBasligi')</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="@lang('admin.paketBasligi')">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="2">@lang('admin.paketAlisFiyati')</label>
                                                    <input name="alis" type="number" step="0.01"
                                                           class="form-control" id="2"
                                                           placeholder="@lang('admin.paketAlisFiyati')">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="3">@lang('admin.paketSatisFiyati')</label>
                                                    <input name="satis" type="number" step="0.01"
                                                           class="form-control" id="3"
                                                           placeholder="@lang('admin.paketSatisFiyati')">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="33">Genel Stok</label>
                                                    <input name="stok" type="number"
                                                           class="form-control" id="33"
                                                           placeholder="Genel Stok">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label>Bonus Tipi</label>
                                                    <select name="bonus_type" class="select2 form-group">
                                                        <option value="0">Bonus Yok</option>
                                                        <option value="1">Yüzde Bonus</option>
                                                        <option value="2">Tutar Bonus</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label>Bonus Tutarı</label>
                                                    <input name="bonus_amount" type="number" step="0.01"
                                                           class="form-control"
                                                           placeholder="Bonus Tutarı">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label>Bonus Son Tarihi</label>
                                                    <input name="bonus_date" type="date" class="form-control"
                                                           placeholder="Bonus Son Tarihi">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label>Bonus Son Saati</label>
                                                    <input name="bonus_date_time" type="time" class="form-control"
                                                           placeholder="Bonus Son Saati">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="333">Etiket</label>
                                                    <input name="etiket" type="text"
                                                           class="form-control" id="333"
                                                           placeholder="Etiket 1, Etiket 2, Etiket 3">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="5">@lang('admin.paketMetni')</label>
                                                    <textarea class="editorText"
                                                              placeholder="@lang('admin.paketMetni')" id="5"
                                                              name="text"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="6">@lang('admin.paketResmi')</label>
                                                    <input name="image" type="file" id="6" class="dropify"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary waves-effect"
                                                data-dismiss="modal">@lang('admin.kapat')</button>
                                        <input type="hidden" name="games_titles" value="{{$u->id}}">
                                        <button type="submit"
                                                class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                    </div>
                                </form>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class="modal fade topluGB" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Toplu GB Güncelleme</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="yeniEkle" method="post" action="{{route('oyun_paket_trade_toplu_edit')}}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            @foreach(\App\Models\GamesPackagesTrade::where('games_titles', $u->id)->get() as $uu)
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="form-label">Paket</label>
                                                        <input class="form-control" type="text" value="{{$uu->title}}"
                                                               readonly>
                                                    </div>
                                                </div>
                                                <?php
                                                $toplamSatislar = DB::table('game_gold_satis')->where('paket', $uu->id)->where('tur', 'bizden-al')->where('status', '1');
                                                $toplamSatisSayi = $toplamSatislar->count();
                                                $toplamSatisAdet = $toplamSatislar->sum('adet');
                                                $toplamSatisTutar = $toplamSatislar->sum('price');
                                                if ($toplamSatisSayi > 0) {
                                                    $toplamSatis = substr($toplamSatisTutar / $toplamSatisAdet, 0, 6);
                                                } else {
                                                    $toplamSatis = findGamesPackagesTradeMusteriyeSatPrice($uu->id);
                                                }
                                                ?>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="form-label">Genel Stok</label>
                                                        <input class="form-control" type="text"
                                                               value="{{$uu->stok}}"
                                                               readonly>
                                                    </div>
                                                </div>

                                                <?php
                                                $toplamAlislar = DB::table('game_gold_satis')->where('paket', $uu->id)->where('tur', 'bize-sat')->where('status', '1')->whereNull('deleted_at');
                                                $toplamAlisSayi = $toplamAlislar->count();
                                                $toplamAlisAdet = $toplamAlislar->sum('adet');
                                                $toplamAlisTutar = $toplamAlislar->sum('price');
                                                if ($toplamAlisSayi > 0) {
                                                    $toplamAlis = substr($toplamAlisTutar / $toplamAlisAdet, 0, 6);
                                                } else {
                                                    $toplamAlis = findGamesPackagesTradeMusteridenAlPrice($uu->id);
                                                }
                                                ?>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="form-label">Müşteriden Alış Ortalama</label>
                                                        <input class="form-control" type="text"
                                                               value="{{$toplamAlis}}"
                                                               readonly>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('admin.paketAlisFiyati')</label>
                                                        <input name="alisT_{{$uu->id}}" type="number" step="0.01"
                                                               class="form-control" value="{{$uu->alis_fiyat}}">
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>@lang('admin.paketSatisFiyati')</label>
                                                        <input name="satisT_{{$uu->id}}" type="number" step="0.01"
                                                               class="form-control" value="{{$uu->satis_fiyat}}">
                                                    </div>
                                                </div>





                                                <div class="col-12">
                                                    <hr>
                                                </div>
                                            @endforeach

                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary waves-effect"
                                                data-dismiss="modal">@lang('admin.kapat')</button>
                                        <input type="hidden" name="games_titles" value="{{$u->id}}">
                                        <button type="submit"
                                                class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                    </div>
                                </form>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class="modal fade stokEkle" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Toplu Stok Ekle</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="yeniEkle" method="post" action="{{route('oyun_paket_trade_toplu_stok_ekle')}}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            @foreach(\App\Models\GamesPackagesTrade::where('games_titles', $u->id)->get() as $uu)
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="form-label">Paket</label>
                                                        <input class="form-control" type="text" value="{{$uu->title}}"
                                                               readonly>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="form-label">Genel Stok</label>
                                                        <input class="form-control" type="text"
                                                               value="{{$uu->stok}}"
                                                               readonly>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="form-label">Eklenecek Adet</label>
                                                        <input name="eklenecek_{{$uu->id}}" type="number" step="1"
                                                               class="form-control" value="0">
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>Ekleme Nedeni</label>
                                                        <input name="eklemeNedeni_{{$uu->id}}" type="text"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <hr>
                                                </div>
                                            @endforeach

                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary waves-effect"
                                                data-dismiss="modal">@lang('admin.kapat')</button>
                                        <input type="hidden" name="games_titles" value="{{$u->id}}">
                                        <button type="submit"
                                                class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                    </div>
                                </form>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class="modal fade stokCikar" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Toplu Stok Çıkar</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="yeniEkle" method="post" action="{{route('oyun_paket_trade_toplu_stok_cikar')}}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            @foreach(\App\Models\GamesPackagesTrade::where('games_titles', $u->id)->get() as $uu)
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="form-label">Paket</label>
                                                        <input class="form-control" type="text" value="{{$uu->title}}"
                                                               readonly>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="form-label">Genel Stok</label>
                                                        <input class="form-control" type="text"
                                                               value="{{$uu->stok}}"
                                                               readonly>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="form-label">Çıkartılacak Adet</label>
                                                        <input name="cikartilacak_{{$uu->id}}" type="number" step="1"
                                                               class="form-control" value="0">
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>Çıkartma Nedeni</label>
                                                        <input name="cikartmaNedeni_{{$uu->id}}" type="text"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <hr>
                                                </div>
                                            @endforeach

                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary waves-effect"
                                                data-dismiss="modal">@lang('admin.kapat')</button>
                                        <input type="hidden" name="games_titles" value="{{$u->id}}">
                                        <button type="submit"
                                                class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                    </div>
                                </form>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->

                </div>
            </div>
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
            @if(isset($_GET['show']))
            $(".topluGB").modal("show");
            @endif
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
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Hepsi"]],
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

