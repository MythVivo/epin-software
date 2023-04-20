<?php

use Carbon\Carbon;


//dd($epin);
?>
@extends('front.layouts.app')
@section('body')


<style>
    @media only screen and (max-width: 600px) {

        .game-info-text-col-mask,
        .heading-secondary-title {
            display: none;
        }
    }
</style>


<section class="bg-gray pb-40">
    @if($epin->id == 13 || $epin->id == 201)
    <style>
        @media only screen and (max-width: 1600px) {
            .gbbar {
                display: none !important;
            }
        }
    </style>
    <div class="gbbar container mb-2" style="text-align: center;">
        <a href="{{ route('item_detay', ['item-satis']) }}">
            <img style="border-radius:10px" src="https://oyuneks.com/public/front/images/topbar/gbbar.gif" alt="">
        </a>
    </div>
    @endif
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="fas fa-check me-2"></i>
            <h5>{{session('success')}}</h5>
        </div>
        @endif

        <div class="row">

            <div class="col-md-4 col-sm-12">
                <div class="game-info-wrapper">
                    <div class="game-info-col">
                        <figure>
                            <img src="{{cdn(env('ROOT').env('FRONT').env('GAMES_TITLES').$epin->image,493,493)}}" alt="{{$epin->alt}}" width="493" height="493">
                        </figure>
                        <h1 class="heading-secondary-title">{{$epin->title}}</h1>
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
                            <button class="nav-link active" id="urunler-tab" data-bs-toggle="tab" data-bs-target="#urunler" type="button" role="tab" aria-controls="urunler" aria-selected="true">
                                <i class="fas fa-sitemap"></i>
                                Ürünler
                            </button>
                        </li>
                        @if(DB::table('epin_nasil_yuklenir')->where('epin', $epin->id)->count() > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="nasil-tab" data-bs-toggle="tab" data-bs-target="#nasil" type="button" role="tab" aria-controls="nasil" aria-selected="false">
                                <i class="fas fa-info"></i>
                                Nasıl Yüklenir?
                            </button>
                        </li>
                        @endif
                        <li class="nav-item" role="presentation">
                            <a href="#yorums" class="bg-gray border-0 btn form-control nav-item" type="button" role="tab" aria-selected="false"><span class="fa-pen-alt fas"> </span> Yorumlar</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="epinTabContent">
                    <div class="tab-pane fade show active no-p-b" id="urunler" role="tabpanel" aria-labelledby="urunler-tab">
                        <div class="items-collection-wrapper">

                            @foreach($packages as $u)
                            <article class="item-col-wrapper">
                                <figure>
                                    <img src="{{cdn(env('ROOT').env('FRONT').env('GAMES_PACKAGES').$u->image,126,126)}}" alt="{{$u->alt}}" width="126" height="126">
                                </figure>
                                <div class="item-col-center">
                                    <a href="{{$epin->link}}/{{Str::slug($u->title)}}-{{$u->id}}">
                                        <h2 class="heading-secondary">
                                            {{$u->title}}
                                        </h2>
                                    </a>
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
                                                    <p>Gün</p>
                                                </div>
                                                <div class="saat"><span>00</span>
                                                    <p>Saat</p>
                                                </div>
                                                <div class="dk"><span>00</span>
                                                    <p>dk</p>
                                                </div>
                                                <div class="saniye"><span>00</span>
                                                    <p>sn</p>
                                                </div>
                                            </div>
                                            @endif
                                            @if($u->bonus_type != 0 and $kalanSaniyeToplam >= 0)
                                            <div class="discount">
                                                @if($u->bonus_type == 1)
                                                <p class="bonus-fiyat">₺{{MF(findGamesPackagesBonus($u->id))}}<span>Bonus</span></p>
                                                @else
                                                <p class="bonus-fiyat">₺{{MF(findGamesPackagesBonus($u->id))}}<span>Bonus</span></p>
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
                                                    <p>Gün</p>
                                                </div>
                                                <div class="saat"><span>00</span>
                                                    <p>Saat</p>
                                                </div>
                                                <div class="dk"><span>00</span>
                                                    <p>dk</p>
                                                </div>
                                                <div class="saniye"><span>00</span>
                                                    <p>sn</p>
                                                </div>
                                            </div>
                                            @endif

                                            <?
                                            #---------------------------------------------------------------------------------------------------------------------------------
                                            /* if (Auth::check() && Auth::user()->refId > 1 && Auth::user()->onay == 1) {  // Akınsoft yada refrans indirim tanımı var mı
                                                $refid = Auth::user()->refId;
                                                $al = DB::select("select epin from bayi where uid='$refid'")[0];  // epin indirim oranı alalım yoxa hata verir
                                                $indirimli = $u->price - ($al->epin * $u->price / 100); // indirimli rakam
                                                $kam_fiy = findGamesPackagesPrice($u->id); // kampanyalı rakam ne ?
                                                if ($kam_fiy > $indirimli) {
                                                    $satis_fiyati = $indirimli;
                                                    $oran = $al->epin;
                                                } else {
                                                    $satis_fiyati = $kam_fiy;
                                                    $oran = $u->discount_amount;
                                                } // hangisi uygunsa onu yazıyoruz
                                            } else {
                                                $satis_fiyati = findGamesPackagesPrice($u->id);
                                                $oran = $u->discount_amount;
                                            } // refid yoxa dewam */
                                            #---------------------------------------------------------------------------------------------------------------------------------


                                            $satis_fiyati = findGamesPackagesPrice($u->id);
                                            $oran = round((($u->price - $satis_fiyati) / $u->price) * 100);
                                            ?>


                                            @if($u->discount_type != 0 and $kalanSaniyeToplam >= 0)
                                            @if($u->discount_type == 1)
                                            <div class="discount">
                                                <p class="indirim-tutari">%{{$oran}} </p>
                                                <p class="eski-fiyat"><span>₺</span>{{MF($u->price)}}</p>
                                                <p class="yeni-fiyat"><span>₺</span>{{MF($satis_fiyati)}}</p>
                                            </div>
                                            @else
                                            <div class="discount">
                                                <p class="indirim-tutari">{{$u->discount_amount}}TL</p>
                                                <p class="eski-fiyat"><span>₺</span>{{MF($u->price)}}</p>
                                                <p class="yeni-fiyat"><span>₺</span>{{MF(findGamesPackagesPrice($u->id))}}</p>
                                            </div>
                                            @endif
                                        </div>
                                        @else
                                        @if($oran > 0)
                                        <div class="discount">
                                            <p class="indirim-tutari">%{{$oran}} </p>
                                            <p class="eski-fiyat"><span>₺</span>{{MF($u->price)}}</p>
                                            <p class="yeni-fiyat"><span>₺</span>{{MF($satis_fiyati)}}</p>
                                        </div>
                                        @else
                                        <p class="price"><span>₺</span>{{MF($satis_fiyati)}}</p>
                                        @endif
                                        @endif


                                        <?php  // Bu oyun panelden satışa kapalıysa buton pasif

                                        $oyun = DB::select("select kapat from oyun_siparis where oyun='$epin->game'")[0]->kapat;
                                        if ($oyun == 1) {
                                            echo '<button type="button" class="btn-inline color-red small" >Stok Yok</button>';
                                        }   // oyun genel olarak satışa kapalıysa
                                        else {
                                            if ($u->kapat == '1') {
                                                echo '<button type="button" class="btn-inline color-red small" >Stok Yok</button>';
                                            } // oyun paketi satışa kapalıysa
                                            else {
                                        ?>
                                                <button type="button" class="btn-inline color-blue small" onclick="location.href='{{route('epin_detay_paket_satin_al', [$epin->link, Str::slug($u->title).'-'.$u->id])}}?adet=1'"> Satın Al</button>

                                        <?
                                            }
                                        } ?>
                                        <!-- Modal -->
                                    </div>
                                    <!-- Modal END -->
                                </div>

                            </article>
                            @endforeach
                        </div>
                    </div>
                    <?php

                    $req = DB::table('epin_nasil_yuklenir')->where('epin', $epin->id);
                    $epin_nasil_yuklenir = $req->count() > 0 ? $req->first()->text : '';
                    $epin_nasil_yuklenir  = preg_replace_callback('/<img.*(src="([^"]*)")/', function ($match) {
                        $replace = 'data-' . $match[1] . ' class="lazyload"';
                        $output = str_replace($match[1], $replace, $match[0]);
                        return $output;
                    }, $epin_nasil_yuklenir);
                    ?>
                    <div class="tab-pane fade" id="nasil" role="tabpanel" aria-labelledby="nasil-tab">
                        {!! $epin_nasil_yuklenir !!}
                    </div>

                </div>

            </div>

        </div>

        <div class="row">
            <div class=" col-sm-12">
                <a name="yorums" id="yorums"></a>
                {{view('front.pages.yorumlar',["epin"=>$epin,"table"=>"epin"])}}
            </div>
        </div>

    </div>
</section>

@endsection