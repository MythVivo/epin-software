<?php
use Carbon\Carbon;
?>
@extends('front.layouts.app')
@section('body')
    <?php
    $epin = getCacheEpinDetay($epin);
    if ($epin == NULL) {
        header("Location: " . URL::to(route('errors_404')), true, 302);
        exit();
    }

    /*if ($epin->epin != 0) {
        $ch = curl_init();
        $headers = array(
            'Authorization: ' . getAuthName(),
            'ApiName: ' . getApiName(),
            'ApiKey: ' . getApiKey(),
            'Content-Type: application/x-www-form-urlencoded',
        );
        curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE').'/GameItemListById');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'id=' . $epin->epin);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $result_package = json_decode($response);
        curl_close($ch);
    }*/
    ?>


    <section class="bg-gray pb-40">
        <div class="container">

            <div class="row">

                <div class="col-md-4 col-sm-12">
                    <div class="game-info-wrapper">
                        <div class="game-info-col">
                            <figure>
                                <img src="{{asset(env('ROOT').env('FRONT').env('GAMES_TITLES').$epin->image)}}" alt="{{$epin->alt}}">
                            </figure>
                            <h5 class="heading-secondary-title">{{$epin->title}}</h5>
                            <div class="game-info-text-col-mask">
                                <div class="game-info-text-col">
                                    <p>{!! $epin->text !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-sm-12">
                    <div class="items-frame-buttons">
                        <ul class="nav nav-pills custom-nav" id="epinTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="urunler-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#urunler" type="button" role="tab" aria-controls="urunler"
                                        aria-selected="true">
                                    <i class="fas fa-sitemap"></i>
                                    Ürünler
                                </button>
                            </li>
                            @if(DB::table('epin_nasil_yuklenir')->where('epin', $epin->id)->count() > 0)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="nasil-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#nasil" type="button" role="tab" aria-controls="nasil"
                                            aria-selected="false">
                                        <i class="fas fa-info"></i>
                                        Nasıl Yüklenir?
                                    </button>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="tab-content" id="epinTabContent">
                        <div class="tab-pane fade show active no-p-b" id="urunler" role="tabpanel"
                             aria-labelledby="urunler-tab">
                            <div class="items-collection-wrapper">
                                <?php /*
                                @if($epin->epin != 0)
                                    @foreach($result_package->GameDto as $u)
                                        <?php
                                        if(DB::table('games_packages_epin')->where('epinPaket', $u->Id)->count() > 0) {
                                            $uu = DB::table('games_packages_epin')->where('epinPaket', $u->Id)->first();
                                            $name = $uu->title;
                                            $image = asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES').$uu->image);
                                        } else {
                                            $name = $u->Name;
                                            $image = asset(env('ROOT').env('FRONT').env('GAMES_TITLES').$epin->image);
                                        }
                                        ?>
                                        <article class="item-col-wrapper">
                                            <figure>
                                                <img src="{{$image}}">
                                            </figure>
                                            <div class="item-col-center">
                                                <h5 class="heading-secondary">
                                                    <a href="{{route('epin_detay_paket', [$epin->link, Str::slug($name)."-".$u->Id])}}">
                                                        {{$name}}
                                                    </a>
                                                </h5>
                                            </div>
                                            <div class="item-col-buy">
                                                <div class="price-tck">
                                                    <p class="price"><span>₺</span>{{MF($u->Price)}}</p>
                                                    <button type="button" class="btn-inline color-yellow small"
                                                            data-bs-toggle="modal"
                                                            data-bs-target=".satinAl{{$u->Id}}">@lang('general.satin-al')</button>
                                                    <!-- Modal -->
                                                </div>
                                            </div>
                                        </article>
                                        <div class="modal fade satinAl{{$u->Id}}" tabindex="-1" role="dialog"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered style-modal" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="exampleModalLabel">@lang('general.satin-al')</h5>
                                                        <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"
                                                                aria-label="Close"><i class="far fa-times"></i></button>
                                                    </div>
                                                    <?php
                                                    $maxStok = $u->Stock;
                                                    ?>
                                                    <div class="modal-body">
                                                        @csrf
                                                        <label>@lang('general.adet')</label>
                                                        <input id="adetAl{{$u->Id}}" name="adet"
                                                               type="number" class="form-control style-input"
                                                               placeholder="Adet"
                                                               min="1"
                                                               max="{{$maxStok}}" required>

                                                    </div>
                                                    <div class="modal-footer">

                                                        <button onclick="location.href='{{route('epin_detay_paket_satin_al', [$epin->link, Str::slug($name).'-'.$u->Id])}}?adet='+$('#adetAl{{$u->Id}}').val()"
                                                                class="btn-inline color-blue">@lang('admin.kaydet')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    @foreach(DB::table('games_packages')->where('games_titles', $epin->id)->whereNull('deleted_at')->get() as $u)
                                        <article class="item-col-wrapper">
                                            <figure>
                                                <img src="{{asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES').$u->image)}}">
                                            </figure>
                                            <div class="item-col-center">
                                                <h5 class="heading-secondary">
                                                    <a href="{{route('epin_detay_paket', [$epin->link, Str::slug($u->title)."-".$u->id])}}">
                                                        {{$u->title}}
                                                    </a>
                                                </h5>
                                            </div>
                                            <div class="item-col-buy">
                                                <div class="price-tck">
                                                    <?php
                                                    $simdiki = date('Y-m-d H:i:s');
                                                    $dateTimeS = new DateTime($simdiki);
                                                    $timestampS = $dateTimeS->format('U');
                                                    $kaydedilen = Carbon::parse($u->bonus_date)->format('Y-m-d H:i:s');
                                                    $dateTimeK = new DateTime($kaydedilen);
                                                    $timestampK = $dateTimeK->format('U');
                                                    $kalanSaniyeToplam = $timestampK - $timestampS;
                                                    ?>
                                                    @if($u->bonus_type != 0 and $kalanSaniyeToplam >= 0)
                                                        <div class="bonus_indirim">
                                                            <div class="timer" data-time="{{$kalanSaniyeToplam}}">
                                                                <div class="gun"><span></span>
                                                                    <p>Gün</p></div>
                                                                <div class="saat"><span></span>
                                                                    <p>Saat</p></div>
                                                                <div class="dk"><span></span>
                                                                    <p>dk</p></div>
                                                                <div class="saniye"><span></span>
                                                                    <p>sn</p></div>
                                                            </div>
                                                            @endif
                                                            @if($u->bonus_type != 0 and $kalanSaniyeToplam >= 0)
                                                                <div class="discount">
                                                                    @if($u->bonus_type == 1)
                                                                        <p class="bonus-fiyat">
                                                                            ₺{{MF(findGamesPackagesBonus($u->id))}}
                                                                            <span>Bonus</span></p>
                                                                    @else
                                                                        <p class="bonus-fiyat">
                                                                            ₺{{MF(findGamesPackagesBonus($u->id))}}
                                                                            <span>Bonus</span>
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                        </div>
                                                    @endif


                                                    <?php
                                                    $simdiki = date('Y-m-d H:i:s');
                                                    $dateTimeS = new DateTime($simdiki);
                                                    $timestampS = $dateTimeS->format('U');
                                                    $kaydedilen = Carbon::parse($u->discount_date)->format('Y-m-d H:i:s');
                                                    $dateTimeK = new DateTime($kaydedilen);
                                                    $timestampK = $dateTimeK->format('U');
                                                    $kalanSaniyeToplam = $timestampK - $timestampS;
                                                    ?>
                                                    @if($u->discount_type != 0 and $kalanSaniyeToplam >= 0)
                                                        <div class="indirim">
                                                            <div class="timer" data-time="{{$kalanSaniyeToplam}}">
                                                                <div class="gun"><span></span>
                                                                    <p>Gün</p></div>
                                                                <div class="saat"><span></span>
                                                                    <p>Saat</p></div>
                                                                <div class="dk"><span></span>
                                                                    <p>dk</p></div>
                                                                <div class="saniye"><span></span>
                                                                    <p>sn</p></div>
                                                            </div>
                                                            @endif
                                                            @if($u->discount_type != 0 and $kalanSaniyeToplam >= 0)
                                                                @if($u->discount_type == 1)
                                                                    <div class="discount">

                                                                        <p class="indirim-tutari">
                                                                            %{{$u->discount_amount}}</p>
                                                                        <p class="eski-fiyat">₺{{MF($u->price)}}</p>
                                                                        <p class="yeni-fiyat">
                                                                            <span>₺</span>{{MF(findGamesPackagesPrice($u->id))}}
                                                                        </p>
                                                                    </div>
                                                                @else
                                                                    <div class="discount">

                                                                        <p class="indirim-tutari">{{$u->discount_amount}}
                                                                            TL</p>
                                                                        <p class="eski-fiyat">₺{{MF($u->price)}}</p>
                                                                        <p class="yeni-fiyat">
                                                                            <span>₺</span>{{MF(findGamesPackagesPrice($u->id))}}
                                                                        </p>
                                                                    </div>

                                                                @endif
                                                        </div>
                                                    @else
                                                        <p class="price">
                                                            <span>₺</span>{{MF(findGamesPackagesPrice($u->id))}}</p>

                                                    @endif

                                                    <button type="button" class="btn-inline color-blue small"
                                                            data-bs-toggle="modal"
                                                            data-bs-target=".satinAl{{$u->id}}">@lang('general.satin-al')</button>
                                                    <!-- Modal -->
                                                </div>
                                                <!-- Modal END -->
                                            </div>

                                        </article>
                                        <div class="modal fade satinAl{{$u->id}}" tabindex="-1" role="dialog"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered style-modal" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="exampleModalLabel">@lang('general.satin-al')</h5>
                                                        <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"
                                                                aria-label="Close"><i class="far fa-times"></i></button>
                                                    </div>
                                                    <?php
                                                    $maxStok = DB::table('games_packages_codes')->where('package_id', $u->id)->where('is_used', '0')->count();
                                                    ?>
                                                    <div class="modal-body">
                                                        @csrf
                                                        <label>@lang('general.adet')</label>
                                                        <input id="adetAl{{$u->id}}" name="adet"
                                                               type="number" class="form-control style-input"
                                                               placeholder="Adet"
                                                               min="1"
                                                               max="{{$maxStok}}" required>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button onclick="location.href='{{route('epin_detay_paket_satin_al', [$epin->link, Str::slug($u->title).'-'.$u->id])}}?adet='+$('#adetAl{{$u->id}}').val()"
                                                                class="btn-inline color-blue">@lang('admin.kaydet')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                @endif */ ?>
                                <?php
                                $sorgu = getCacheEpinDetayPackages($epin->id);
                                ?>
                                @foreach($sorgu as $u)
                                    <article class="item-col-wrapper">
                                        <figure>
                                            <img src="{{asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES').$u->image)}}" alt="{{$u->alt}}">
                                        </figure>
                                        <div class="item-col-center">
                                            <h5 class="heading-secondary">
                                                <a href="{{route('epin_detay_paket_satin_al', [$epin->link, Str::slug($u->title).'-'.$u->id])}}?adet=1">
                                                    {{$u->title}}
                                                </a>
                                            </h5>
                                            <h6>{!! $u->text !!}</h6>
                                        </div>
                                        <div class="item-col-buy">
                                            <div class="price-tck">
                                                <?php
                                                $simdiki = date('Y-m-d H:i:s');
                                                $dateTimeS = new DateTime($simdiki);
                                                $timestampS = $dateTimeS->format('U');
                                                $kaydedilen = Carbon::parse($u->bonus_date)->format('Y-m-d H:i:s');
                                                $dateTimeK = new DateTime($kaydedilen);
                                                $timestampK = $dateTimeK->format('U');
                                                $kalanSaniyeToplam = $timestampK - $timestampS;
                                                ?>
                                                @if($u->bonus_type != 0 and $kalanSaniyeToplam >= 0)
                                                    <div class="bonus_indirim">
                                                        <div class="timer" data-time="{{$kalanSaniyeToplam}}">
                                                            <div class="gun"><span>00</span>
                                                                <p>Gün</p></div>
                                                            <div class="saat"><span>00</span>
                                                                <p>Saat</p></div>
                                                            <div class="dk"><span>00</span>
                                                                <p>dk</p></div>
                                                            <div class="saniye"><span>00</span>
                                                                <p>sn</p></div>
                                                        </div>
                                                        @endif
                                                        @if($u->bonus_type != 0 and $kalanSaniyeToplam >= 0)
                                                            <div class="discount">
                                                                @if($u->bonus_type == 1)
                                                                    <p class="bonus-fiyat">
                                                                        ₺{{MF(findGamesPackagesBonus($u->id))}}
                                                                        <span>Bonus</span></p>
                                                                @else
                                                                    <p class="bonus-fiyat">
                                                                        ₺{{MF(findGamesPackagesBonus($u->id))}}
                                                                        <span>Bonus</span>
                                                                    </p>
                                                                @endif
                                                            </div>
                                                    </div>
                                                @endif


                                                <?php
                                                $simdiki = date('Y-m-d H:i:s');
                                                $dateTimeS = new DateTime($simdiki);
                                                $timestampS = $dateTimeS->format('U');
                                                $kaydedilen = Carbon::parse($u->discount_date)->format('Y-m-d H:i:s');
                                                $dateTimeK = new DateTime($kaydedilen);
                                                $timestampK = $dateTimeK->format('U');
                                                $kalanSaniyeToplam = $timestampK - $timestampS;
                                                ?>
                                                @if($u->discount_type != 0 and $kalanSaniyeToplam >= 0)
                                                    <div class="indirim">
                                                        <div class="timer" data-time="{{$kalanSaniyeToplam}}">
                                                            <div class="gun"><span>00</span>
                                                                <p>Gün</p></div>
                                                            <div class="saat"><span>00</span>
                                                                <p>Saat</p></div>
                                                            <div class="dk"><span>00</span>
                                                                <p>dk</p></div>
                                                            <div class="saniye"><span>00</span>
                                                                <p>sn</p></div>
                                                        </div>
                                                        @endif
                                                        @if($u->discount_type != 0 and $kalanSaniyeToplam >= 0)
                                                            @if($u->discount_type == 1)
                                                                <div class="discount">

                                                                    <p class="indirim-tutari">
                                                                        %{{$u->discount_amount}}</p>
                                                                    <p class="eski-fiyat">
                                                                        <span>₺</span>{{MF($u->price)}}</p>
                                                                    <p class="yeni-fiyat">
                                                                        <span>₺</span>{{MF(findGamesPackagesPrice($u->id))}}
                                                                    </p>
                                                                </div>
                                                            @else
                                                                <div class="discount">

                                                                    <p class="indirim-tutari">{{$u->discount_amount}}
                                                                        TL</p>
                                                                    <p class="eski-fiyat">
                                                                        <span>₺</span>{{MF($u->price)}}</p>
                                                                    <p class="yeni-fiyat">
                                                                        <span>₺</span>{{MF(findGamesPackagesPrice($u->id))}}
                                                                    </p>
                                                                </div>

                                                            @endif
                                                    </div>
                                                @else
                                                    <p class="price">
                                                        <span>₺</span>{{MF(findGamesPackagesPrice($u->id))}}</p>

                                                @endif

                                                <button type="button" class="btn-inline color-blue small"
                                                        onclick="location.href='{{route('epin_detay_paket_satin_al', [$epin->link, Str::slug($u->title).'-'.$u->id])}}?adet=1'">@lang('general.satin-al')</button>
                                                <!-- Modal -->
                                            </div>
                                            <!-- Modal END -->
                                        </div>

                                    </article>
                                    <?php /*
                                    <div class="modal fade satinAl{{$u->id}}" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered style-modal" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="exampleModalLabel">@lang('general.satin-al')</h5>
                                                    <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"
                                                            aria-label="Close"><i class="far fa-times"></i></button>
                                                </div>
                                                <?php
                                                $maxStok = DB::table('games_packages_codes')->where('package_id', $u->id)->where('is_used', '0')->count();
                                                ?>
                                                <div class="modal-body">
                                                    @csrf
                                                    <label>@lang('general.adet')</label>
                                                    <input id="adetAl{{$u->id}}" name="adet"
                                                           type="number" class="form-control style-input"
                                                           placeholder="Adet"
                                                           min="1"
                                                           max="{{$maxStok}}" required>

                                                </div>
                                                <div class="modal-footer">
                                                    <button onclick="location.href='{{route('epin_detay_paket_satin_al', [$epin->link, Str::slug($u->title).'-'.$u->id])}}?adet='+$('#adetAl{{$u->id}}').val()"
                                                            class="btn-inline color-blue">@lang('admin.kaydet')</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    */ ?>
                                @endforeach
                            </div>
                        </div>
                        @if(DB::table('epin_nasil_yuklenir')->where('epin', $epin->id)->count() > 0)
                            <div class="tab-pane fade" id="nasil" role="tabpanel" aria-labelledby="nasil-tab">
                                {!! DB::table('epin_nasil_yuklenir')->where('epin', $epin->id)->first()->text !!}
                            </div>
                        @endif
                    </div>


                </div>

            </div>

        </div>
    </section>

@endsection
