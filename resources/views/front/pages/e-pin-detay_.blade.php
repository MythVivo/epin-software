<?php
use Carbon\Carbon;
    $epin = getCacheEpinDetay($epin);
    if ($epin == NULL) {
        header("Location: " . URL::to(route('errors_404')), true, 302);
        exit();
    }
    
    //dd($epin);
?>
@extends('front.layouts.app')
@section('body')



    <section class="bg-gray pb-40">
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
                                                <a href="#">
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
                                                <? // Yakup stok yok butonları
                                                       if($u->id==17 || $u->id==18 || $u->id==23 || $u->id==20 || $u->id==19 || $u->id==34 || $u->id==21 || $u->id==22|| $u->id==35|| $u->id==36){ ?> >Stok Yok <? } else {      ?>
                                                onclick="location.href='{{route('epin_detay_paket_satin_al', [$epin->link, Str::slug($u->title).'-'.$u->id])}}?adet=1'"> Satın Al
                                                    <? }?>
                                                </button>
                                                <!-- Modal -->
                                            </div>
                                            <!-- Modal END -->
                                        </div>

                                    </article>                                    
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

            <div class="row">
                <div class=" col-sm-12">           
            <a name="yorums" id="yorums"></a>
            {{view('front.pages.yorumlar',["epin"=>$epin,"table"=>"epin"])}} 
            
            
                </div>
            </div>

        </div>
    </section>

@endsection
