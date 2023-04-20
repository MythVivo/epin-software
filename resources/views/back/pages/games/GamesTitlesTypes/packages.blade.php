@if($u->epin != 0)
    <h5 class="card-title">Bu bir e-pin oyunudur</h5>
    <?php
    $paketler = findApiGamePackage($u->id);
    ?>
    @if($paketler != "Bulunamadı")
        @foreach($paketler as $paket)
            <?php
            $uu = DB::table('games_packages')->where('stockCode', $paket->StockCode);
            if ($uu->count() > 0) {
                $title = $uu->first()->title;
                $etiket = $uu->first()->etiket;
                $image = $uu->first()->image;
                $id = $uu->first()->id;
                $price = findGamesPackagesPrice($uu->first()->id);
                $discount_type = $uu->first()->discount_type;
                $discount_amount = $uu->first()->discount_amount;
                $discount_date = $uu->first()->discount_date;
                $bonus_type = $uu->first()->bonus_type;
                $bonus_amount = $uu->first()->bonus_amount;
                $bonus_date = $uu->first()->bonus_date;
                $etiket = $uu->first()->etiket;
                $text = $uu->first()->text;
            } else {
                $title = $paket->Name;
                $etiket = "";
                $image = "";
                $id = $paket->Id;
                $price = $paket->Price;
                $discount_type = 0;
                $discount_amount = 0;
                $discount_date = NULL;
                $bonus_type = 0;
                $bonus_amount = 0;
                $bonus_date = 0;
                $etiket = "";
                $text = "";
            }
            ?>
            <a href="javascript:void(0)" data-toggle="modal" data-target=".paketDuzenleEpin{{$id}}">
                {{$title}} - ₺{{$price}}
            </a><br>

            <div class="modal fade paketDuzenleEpin{{$id}}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.paketPaketDuzenle')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                            </button>
                        </div>
                        <form id="duzenle" method="post" action="{{route('oyun_paket_epin_edit')}}"
                              enctype="multipart/form-data">
                            @csrf
                            {!! getLangInput() !!}
                            <div class="modal-body">
                                <div class="row">

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label for="1">@lang('admin.paketBasligi')</label>
                                            <input name="title" type="text" class="form-control" id="1"
                                                   placeholder="@lang('admin.paketBasligi')" value="{{$title}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label for="2">@lang('admin.paketFiyati')</label>
                                            <input name="price" type="number" step="0.01" class="form-control" id="2"
                                                   placeholder="@lang('admin.paketFiyati')" value="{{$price}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label for="3">@lang('admin.paketIndirimTipi')</label>
                                            <select id="3" name="discount_type" class="select2 form-group">
                                                <option value="0"
                                                        @if($discount_type == 0) selected @endif>@lang('admin.paketIndirimYok')</option>
                                                <option value="1"
                                                        @if($discount_type == 1) selected @endif>@lang('admin.paketIndirimYuzde')</option>
                                                <option value="2"
                                                        @if($discount_type == 2) selected @endif>@lang('admin.paketIndirimTutar')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label for="4">@lang('admin.paketIndirimTutari')</label>
                                            <input name="discount_amount" type="number" step="0.01" class="form-control"
                                                   id="4"
                                                   placeholder="@lang('admin.paketIndirimTutari')"
                                                   value="{{$discount_amount}}">
                                        </div>
                                    </div>
                                    <?php
                                    if ($discount_date != NULL) {
                                        $date = \Carbon\Carbon::parse($discount_date);
                                        $date1 = $date->format('Y-m-d');
                                        $date2 = $date->format('H:i');
                                    } else {
                                        $date1 = "";
                                        $date2 = "";
                                    }
                                    ?>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label for="44">İndirim Son Tarihi</label>
                                            <input name="discount_date" type="date" class="form-control" id="44"
                                                   placeholder="İndirim Son Tarihi"
                                                   value="{{$date1}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label for="55">İndirim Son Saati</label>
                                            <input name="discount_date_time" type="time" class="form-control" id="55"
                                                   placeholder="İndirim Son Saati"
                                                   value="{{$date2}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>Bonus Tipi</label>
                                            <select name="bonus_type" class="select2 form-group">
                                                <option value="0" @if($bonus_type == 0) selected @endif>Bonus Yok
                                                </option>
                                                <option value="1" @if($bonus_type == 1) selected @endif>Yüzde Bonus
                                                </option>
                                                <option value="2" @if($bonus_type == 2) selected @endif>Tutar Bonus
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>Bonus Tutarı</label>
                                            <input name="bonus_amount" type="number" step="0.01" class="form-control"
                                                   placeholder="Bonus Tutarı" value="{{$bonus_amount}}">
                                        </div>
                                    </div>
                                    <?php
                                    if ($bonus_date != NULL) {
                                        $dateb = \Carbon\Carbon::parse($bonus_date);
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
                                            <input name="bonus_date_time" type="time" class="form-control"
                                                   placeholder="Bonus Son Saati" value="{{$date2b}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="33">Etiketler</label>
                                            <input class="form-control" name="etiket"
                                                   id="33" placeholder="Etiket 1, Etiket 2, Etiket 3"
                                                   value="{{$etiket}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Sıra</label>
                                            <input class="form-control" type="number" step="1" name="sira"
                                                   id="666" value="{{$paket->sira}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Stok Kodu (Epinden Gelir)</label>
                                            <input class="form-control" name="stockCode"
                                                   id="33" readonly
                                                   value="{{$paket->StockCode}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="5">@lang('admin.paketMetni')</label>
                                            <textarea class="editorText" placeholder="@lang('admin.paketMetni')" id="5"
                                                      name="text">{!! $text !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="6">@lang('admin.paketResmi')</label>
                                            <input name="image"
                                                   data-default-file="{{asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES').$image)}}"
                                                   type="file" id="6" class="dropify"
                                                   accept="image/*">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-danger waves-effect"
                                        onclick="deleteContent('games_packages', {{$id}})">@lang('admin.paketSil')</button>
                                <button type="button" class="btn btn-outline-secondary waves-effect"
                                        data-dismiss="modal">@lang('admin.kapat')</button>
                                <input type="hidden" name="id" value="{{$id}}">
                                <input type="hidden" name="games_titles" value="{{$u->id}}">
                                <button type="submit"
                                        class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        E-pin sistemlerine ulaşılamıyor!
    @endif
@else
    @foreach(\App\Models\GamesPackages::where('games_titles', $u->id)->get() as $uu)
        <div class="row">
            <div class="col-9 altBaslik float-left">
                <a href="javascript:void(0)" data-toggle="modal" data-target=".paketDuzenle{{$uu->id}}">{{$uu->title}} -
                    ₺{{findGamesPackagesPrice($uu->id)}}</a>
            </div>
            <div class="col-3 btn-group" role="group">
                @if(sayfaIzinKontrol(0))
                    <?php /*
                    <button type="button" data-toggle="modal" data-target=".kodGoruntule{{$uu->id}}"
                            class="btn btn-outline-orange btn-sm float-right"><i class="fa fa-eye"></i></button>*/ ?>

                    <button type="button" onclick="window.open('{{route('oyun_paket_kod_view', $uu->id)}}')" class="btn btn-outline-orange btn-sm float-right"><i class="fa fa-eye"></i></button>

                    <div class="modal fade kodGoruntule{{$uu->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.kodGoruntule')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="1">{{$uu->title}} -
                                                    ₺{{findGamesPackagesPrice($uu->id)}}</label>
                                                <hr>
{{--                                                @foreach(DB::table('games_packages_codes')->where('package_id', $uu->id)->get() as $uuu)--}}
{{--                                                    <p>--}}
{{--                                                        {{\epin::DEC($uuu->code)}}--}}
{{--                                                        - @if($uuu->is_used == 1) @lang('admin.kullanildi') @else @lang('admin.kullanilmadi') @endif--}}
{{--                                                        <button class="btn btn-outline-danger btn-sm"--}}
{{--                                                                onclick="location.href='?silKod={{$uuu->id}}'">Kodu Sil--}}
{{--                                                        </button>--}}
{{--                                                    </p>--}}
{{--                                                @endforeach--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary waves-effect"
                                            data-dismiss="modal">@lang('admin.kapat')</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                @endif
                <button type="button" data-toggle="modal" data-target=".kodEkle{{$uu->id}}"
                        class="btn btn-outline-beanred btn-sm float-right"><i class="fa fa-plus"></i></button>
                <div class="modal fade kodEkle{{$uu->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.kodEkle')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                </button>
                            </div>
                            <form method="post" action="{{route('oyun_paket_kod_add')}}"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="1">{{$uu->title}} -
                                                    ₺{{findGamesPackagesPrice($uu->id)}}</label>
                                                <textarea rows="7" name="code" type="text" class="form-control"
                                                          id="1"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="2">Alış Fiyatı</label>
                                                <input name="alis_fiyati" type="number" step="0.01" class="form-control" id="2">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="3">Kdv Tutarı</label>
                                                <input name="kdv" type="number" step="0.01" class="form-control" id="3">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="3">Tedarikçi</label>
                                                <select name="tedarikci" class="form-control">
                                                    <option value="0">Belirsiz</option>
                                                    @foreach(DB::table('games_packages_codes_suppliers')->whereNull('deleted_at')->get() as $uuu)
                                                        <option value="{{$uuu->id}}">{{$uuu->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary waves-effect"
                                            data-dismiss="modal">@lang('admin.kapat')</button>
                                    <input type="hidden" name="games_titles_package" value="{{$uu->id}}">
                                    <button type="submit"
                                            class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                </div>
                            </form>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div>
            <div class="col-12">
                <hr>
            </div>
        </div>
        <div class="modal fade paketDuzenle{{$uu->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.paketPaketDuzenle')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                        </button>
                    </div>
                    <form id="duzenle" method="post" action="{{route('oyun_paket_edit')}}"
                          enctype="multipart/form-data">
                        @csrf
                        {!! getLangInput() !!}
                        <div class="modal-body">
                            <div class="row">

                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label for="1">@lang('admin.paketBasligi')</label>
                                        <input name="title" type="text" class="form-control" id="1"
                                               placeholder="@lang('admin.paketBasligi')" value="{{$uu->title}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label for="2">@lang('admin.paketFiyati')</label>
                                        <input name="price" type="number" step="0.01" class="form-control" id="2"
                                               placeholder="@lang('admin.paketFiyati')" value="{{$uu->price}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label for="3">@lang('admin.paketIndirimTipi')</label>
                                        <select id="3" name="discount_type" class="select2 form-group">
                                            <option value="0"
                                                    @if($uu->discount_type == 0) selected @endif>@lang('admin.paketIndirimYok')</option>
                                            <option value="1"
                                                    @if($uu->discount_type == 1) selected @endif>@lang('admin.paketIndirimYuzde')</option>
                                            <option value="2"
                                                    @if($uu->discount_type == 2) selected @endif>@lang('admin.paketIndirimTutar')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label for="4">@lang('admin.paketIndirimTutari')</label>
                                        <input name="discount_amount" type="number" step="0.01" class="form-control"
                                               id="4"
                                               placeholder="@lang('admin.paketIndirimTutari')"
                                               value="{{$uu->discount_amount}}">
                                    </div>
                                </div>
                                <?php
                                if ($uu->discount_date != NULL) {
                                    $date = \Carbon\Carbon::parse($uu->discount_date);
                                    $date1 = $date->format('Y-m-d');
                                    $date2 = $date->format('H:i');
                                } else {
                                    $date1 = "";
                                    $date2 = "";
                                }
                                ?>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="44">İndirim Son Tarihi</label>
                                        <input name="discount_date" type="date" class="form-control" id="44"
                                               placeholder="İndirim Son Tarihi"
                                               value="{{$date1}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="55">İndirim Son Saati</label>
                                        <input name="discount_date_time" type="time" class="form-control" id="55"
                                               placeholder="İndirim Son Saati"
                                               value="{{$date2}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Bonus Tipi</label>
                                        <select name="bonus_type" class="select2 form-group">
                                            <option value="0" @if($uu->bonus_type == 0) selected @endif>Bonus Yok
                                            </option>
                                            <option value="1" @if($uu->bonus_type == 1) selected @endif>Yüzde Bonus
                                            </option>
                                            <option value="2" @if($uu->bonus_type == 2) selected @endif>Tutar Bonus
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Bonus Tutarı</label>
                                        <input name="bonus_amount" type="number" step="0.01" class="form-control"
                                               placeholder="Bonus Tutarı" value="{{$uu->bonus_amount}}">
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
                                        <input name="bonus_date_time" type="time" class="form-control"
                                               placeholder="Bonus Son Saati" value="{{$date2b}}">
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="33">Etiketler</label>
                                        <input class="form-control" name="etiket"
                                               id="33" placeholder="Etiket 1, Etiket 2, Etiket 3"
                                               value="{{$uu->etiket}}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="1234">Sıra</label>
                                        <input class="form-control" name="sira" type="number" step="1"
                                               id="1234" value="{{$uu->sira}}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="5">@lang('admin.paketMetni')</label>
                                        <textarea class="editorText" placeholder="@lang('admin.paketMetni')" id="5"
                                                  name="text">{!! $uu->text !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="6">@lang('admin.paketResmi')</label>
                                        <input name="image"
                                               data-default-file="{{asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES').$uu->image)}}"
                                               type="file" id="6" class="dropify"
                                               accept="image/*">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger waves-effect"
                                    onclick="deleteContent('games_packages', {{$uu->id}})">@lang('admin.paketSil')</button>
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
@endif
<div class="card-bottom mt-5">
    @if($u->epin == 0)
        <button type="button" data-toggle="modal" data-target=".paketEkle{{$u->id}}"
                class="btn btn-block btn-outline-secondary">@lang('admin.paketEkle')</button>
    @endif
    <button type="button" data-toggle="modal" data-target=".nasilYuklenir{{$u->id}}"
            class="btn btn-block w-100 btn-outline-success">Nasıl Yüklenir
    </button>
    <button onclick="deleteContent('games_titles', {{$u->id}})" type="button"
            class="btn btn-block btn-outline-danger">@lang('admin.baslikSil')
    </button>
</div>
<div class="modal fade paketEkle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.paketEkle')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                </button>
            </div>
            <form id="yeniEkle" method="post" action="{{route('oyun_paket_add')}}"
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
                                <label for="2">@lang('admin.paketFiyati')</label>
                                <input name="price" type="number" step="0.01" class="form-control" id="2"
                                       placeholder="@lang('admin.paketFiyati')">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="3">@lang('admin.paketIndirimTipi')</label>
                                <select id="3" name="discount_type" class="select2 form-group">
                                    <option value="0">@lang('admin.paketIndirimYok')</option>
                                    <option value="1">@lang('admin.paketIndirimYuzde')</option>
                                    <option value="2">@lang('admin.paketIndirimTutar')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="4">@lang('admin.paketIndirimTutari')</label>
                                <input name="discount_amount" type="number" step="0.01" class="form-control" id="4"
                                       placeholder="@lang('admin.paketIndirimTutari')">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="44">İndirim Son Tarihi</label>
                                <input name="discount_date" type="date" class="form-control" id="44"
                                       placeholder="İndirim Son Tarihi">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="55">İndirim Son Saati</label>
                                <input name="discount_date_time" type="time" class="form-control" id="55"
                                       placeholder="İndirim Son Saati">
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
                                <input name="bonus_amount" type="number" step="0.01" class="form-control"
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
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="33">Etiketler</label>
                                <input class="form-control" name="etiket"
                                       id="33" placeholder="Etiket 1, Etiket 2, Etiket 3">
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="555">Sıra</label>
                                <input type="number" step="1" class="form-control" name="sira"
                                       id="555">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="5">@lang('admin.paketMetni')</label>
                                <textarea class="editorText" placeholder="@lang('admin.paketMetni')" id="5"
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
<div class="modal fade nasilYuklenir{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myModalLabel">Nasıl Yüklenir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                </button>
            </div>
            <form method="post" action="{{route('oyun_paket_nasil_yuklenir')}}"
                  enctype="multipart/form-data">
                @csrf
                {!! getLangInput() !!}
                <div class="modal-body">
                    <div class="row">
                        <?php
                        $nasil_yuklenir = DB::table('epin_nasil_yuklenir')->where('epin', $u->id);
                        if ($nasil_yuklenir->count() > 0) {
                            $text = $nasil_yuklenir->first()->text;
                        } else {
                            $text = "";
                        }
                        ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="5">Nasıl Yüklenir Metni</label>
                                <textarea class="editorText" id="5"
                                          name="text_nasil_yuklenir">{!! $text !!}</textarea>
                            </div>
                        </div>
                        @if($nasil_yuklenir->count() > 0)
                            <input type="hidden" name="nasil_yuklenir" value="{{$nasil_yuklenir->first()->id}}">
                        @endif
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
