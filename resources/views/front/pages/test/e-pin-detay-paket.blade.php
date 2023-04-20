<?php
use Carbon\Carbon;
?>
@extends('front.layouts.app')

<?php
    $epin = DB::table('games_titles')
    ->select('games_titles.*')
    ->join('games', 'games_titles.game', '=', 'games.id')
    ->where('games_titles.link', $epin)
    ->whereNull('games_titles.deleted_at')
    ->whereNull('games.deleted_at')
    ->first();
    if ($epin == NULL) {
        header("Location: " . URL::to(route('errors_404')), true, 302);
        exit();
    }
    $pakets = explode("-", $paket);
    $paket = DB::table('games_packages')->where('id', end($pakets))->first();
    if ($paket == NULL) {
        header("Location: " . URL::to(route('errors_404')), true, 302);
        exit();
    }

?>
@section('head')
    <meta name="description" content="{{$paket->title}}">
    <meta name="keywords" content="{{$paket->title}}">
@endsection

@section('body')
    <?php



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
        foreach ($result_package->GameDto as $u) {
            if ($u->Id == end($pakets)) {
                $paket = new stdClass();
                $paket->id = $u->Id;
                $paket->title = $u->Name;
                $paket->image = $epin->image;
                $paket->description = $u->Description;
                $paket->price = $u->Price;
                $paket->stok = $u->Stock;
            }
        }
        if(DB::table('games_packages_epin')->where('epinPaket', $u->Id)->count() > 0) {
            $uu = DB::table('games_packages_epin')->where('epinPaket', $u->Id)->first();
            $name = $uu->title;
            $image = asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES').$uu->image);
        } else {
            $name = $paket->title;
            $image = asset(env('ROOT').env('FRONT').env('GAMES_TITLES').$epin->image);
        }
    } else {*/

    $name = $paket->title;
    /*}*/
    array_pop($pakets);
    $ilanIsmi = implode("-", $pakets);
    if (!$paket or ($ilanIsmi != Str::slug($name))) {
        header("Location: " . URL::to(route('errors_404')), true, 302);
        exit();
    }
    ?>
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="card" style="width: 100%;">
                                <img src="{{asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES').$paket->image)}}"
                                     class="card-img-top" alt="{{$paket->alt}}">
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="card card-bg-border" style="width: 100%;">
                                <div class="card-body">
                                    <h1 class="card-title">{{$paket->title}}</h1>
                                    <h2 class="card-text">{!! $paket->text !!}</h2>
                                    <div class="row">
                                        <div class="colx float-left display-flex-wrap">
                                            <?php
                                            $simdiki = date('Y-m-d H:i:s');
                                            $dateTimeS = new DateTime($simdiki);
                                            $timestampS = $dateTimeS->format('U');
                                            $kaydedilen = Carbon::parse($paket->bonus_date)->format('Y-m-d H:i:s');
                                            $dateTimeK = new DateTime($kaydedilen);
                                            $timestampK = $dateTimeK->format('U');
                                            $kalanSaniyeToplam = $timestampK - $timestampS;
                                            ?>
                                            @if($paket->bonus_type != 0 and $kalanSaniyeToplam >= 0)
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
                                                    @if($paket->bonus_type != 0 and $kalanSaniyeToplam >= 0)
                                                        <div class="discount">
                                                            @if($paket->bonus_type == 1)
                                                                <p class="bonus-fiyat">
                                                                    ₺{{MF(findGamesPackagesBonus($paket->id))}}
                                                                    <span>Bonus</span></p>
                                                            @else
                                                                <p class="bonus-fiyat">
                                                                    ₺{{MF(findGamesPackagesBonus($paket->id))}}
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
                                            $kaydedilen = Carbon::parse($paket->discount_date)->format('Y-m-d H:i:s');
                                            $dateTimeK = new DateTime($kaydedilen);
                                            $timestampK = $dateTimeK->format('U');
                                            $kalanSaniyeToplam = $timestampK - $timestampS;
                                            ?>
                                            @if($paket->discount_type != 0 and $kalanSaniyeToplam >= 0)
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
                                                    @if($paket->discount_type != 0 and $kalanSaniyeToplam >= 0)
                                                        @if($paket->discount_type == 1)
                                                            <div class="discount">
                                                                <div class="old-price">
                                                                    <p class="eski-fiyat">₺{{MF($paket->price)}}</p>
                                                                    <p class="indirim-tutari">
                                                                        %{{$paket->discount_amount}}</p>
                                                                </div>

                                                                <p class="yeni-fiyat">
                                                                    <span>₺</span>{{MF(findGamesPackagesPrice($paket->id))}}
                                                                </p>
                                                            </div>
                                                        @else
                                                            <div class="discount">
                                                                <p class="eski-fiyat">₺{{MF($paket->price)}}</p>
                                                                <p class="indirim-tutari">{{$paket->discount_amount}}
                                                                    TL</p>
                                                                <p class="yeni-fiyat">
                                                                    <span>₺</span>{{MF(findGamesPackagesPrice($paket->id))}}
                                                                </p>
                                                            </div>
                                                        @endif
                                                </div>
                                            @else
                                                <p class="price">
                                                    <span>₺</span>{{MF(findGamesPackagesPrice($paket->id))}}</p>
                                            @endif
                                        </div>
                                        <?php
                                        $stok = DB::table('games_packages_codes')->where('package_id', $paket->id)->where('is_used', '0')->count();
                                        ?>
                                        <div class="colx">
                                            <div class="form-decrement">
                                                <span class="negative">-</span>
                                                <input id="adetAl" name="adet"
                                                       type="number" class="form-control style-input" placeholder="Adet"
                                                       min="1"
                                                       value="1"
                                                       max="{{$stok}}" required>
                                                <span class="positive">+</span>
                                            </div>

                                        </div>
                                        <div class="colx zero float-right">
                                            <p class="card-text">
                                                <?php
                                                if ($paket->kapat == '1') {
                                                echo '<button type="button" class="btn-inline color-red small" >Stok Yok</button>';
                                                }else
                                                {
                                                ?>
                                                <button onclick="location.href='{{route('epin_detay_paket_satin_al', [$epin->link, Str::slug($paket->title).'-'.$paket->id])}}?adet='+$('#adetAl').val()"
                                                        class="btn-inline color-darkgreen w-100">
                                                    Satın Al
                                                </button>
                                                <?php
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php /*
                        @if($epin->epin != 0)
                            <?php
                            if(DB::table('games_packages_epin')->where('epinPaket', $u->Id)->count() > 0) {
                                $uu = DB::table('games_packages_epin')->where('epinPaket', $u->Id)->first();
                                $name = $uu->title;
                                $image = asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES').$uu->image);
                            } else {
                                $name = $paket->title;
                                $image = asset(env('ROOT').env('FRONT').env('GAMES_TITLES').$epin->image);
                            }
                            ?>
                            <div class="col-md-4 col-sm-12">
                                <div class="card card-bg-border" style="width: 100%;">
                                    <img src="{{$image}}"
                                         class="card-img-top" alt="{{$name}} görsel">
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12">

                                <div class="card card-bg-border" style="width: 100%;">
                                    <div class="card-body">
                                        <h3 class="card-title">{{$name}}</h3>
                                        <p class="card-text">{!! $paket->description !!}</p>
                                        <div class="row">
                                            <div class="colx float-left display-flex-wrap">
                                                <p class="price">
                                                    <span>₺</span>{{MF($paket->price)}}</p>
                                            </div>
                                            <div class="colx">
                                                <input id="adetAl" name="adet"
                                                       type="number" class="form-control style-input" placeholder="Adet"
                                                       min="1"
                                                       max="{{$paket->stok}}" required>
                                            </div>
                                            <div class="col float-right">
                                                <p class="card-text">
                                                    <button onclick="location.href='{{route('epin_detay_paket_satin_al', [$epin->link, Str::slug($name).'-'.$paket->id])}}?adet='+$('#adetAl').val()"
                                                            class="btn-inline color-darkgreen w-100">
                                                        Satın Al
                                                    </button>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-4 col-sm-12">
                                <div class="card" style="width: 100%;">
                                    <img src="{{asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES').$paket->image)}}"
                                         class="card-img-top" alt="{{$paket->title}} görsel">
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div class="card card-bg-border" style="width: 100%;">
                                    <div class="card-body">
                                        <h3 class="card-title">{{$paket->title}}</h3>
                                        <p class="card-text">{!! $paket->text !!}</p>
                                        <div class="row">
                                            <div class="colx float-left display-flex-wrap">
                                                <?php
                                                $simdiki = date('Y-m-d H:i:s');
                                                $dateTimeS = new DateTime($simdiki);
                                                $timestampS = $dateTimeS->format('U');
                                                $kaydedilen = Carbon::parse($paket->bonus_date)->format('Y-m-d H:i:s');
                                                $dateTimeK = new DateTime($kaydedilen);
                                                $timestampK = $dateTimeK->format('U');
                                                $kalanSaniyeToplam = $timestampK - $timestampS;
                                                ?>
                                                @if($paket->bonus_type != 0 and $kalanSaniyeToplam >= 0)
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
                                                        @if($paket->bonus_type != 0 and $kalanSaniyeToplam >= 0)
                                                            <div class="discount">
                                                                @if($paket->bonus_type == 1)
                                                                    <p class="bonus-fiyat">
                                                                        ₺{{MF(findGamesPackagesBonus($paket->id))}}
                                                                        <span>Bonus</span></p>
                                                                @else
                                                                    <p class="bonus-fiyat">
                                                                        ₺{{MF(findGamesPackagesBonus($paket->id))}}
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
                                                $kaydedilen = Carbon::parse($paket->discount_date)->format('Y-m-d H:i:s');
                                                $dateTimeK = new DateTime($kaydedilen);
                                                $timestampK = $dateTimeK->format('U');
                                                $kalanSaniyeToplam = $timestampK - $timestampS;
                                                ?>
                                                @if($paket->discount_type != 0 and $kalanSaniyeToplam >= 0)
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
                                                        @if($paket->discount_type != 0 and $kalanSaniyeToplam >= 0)
                                                            @if($paket->discount_type == 1)
                                                                <div class="discount">
                                                                    <div class="old-price">
                                                                        <p class="eski-fiyat">₺{{MF($paket->price)}}</p>
                                                                        <p class="indirim-tutari">
                                                                            %{{$paket->discount_amount}}</p>
                                                                    </div>

                                                                    <p class="yeni-fiyat">
                                                                        <span>₺</span>{{MF(findGamesPackagesPrice($paket->id))}}
                                                                    </p>
                                                                </div>
                                                            @else
                                                                <div class="discount">
                                                                    <p class="eski-fiyat">₺{{MF($paket->price)}}</p>
                                                                    <p class="indirim-tutari">{{$paket->discount_amount}}
                                                                        TL</p>
                                                                    <p class="yeni-fiyat">
                                                                        <span>₺</span>{{MF(findGamesPackagesPrice($paket->id))}}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                    </div>
                                                @else
                                                    <p class="price">
                                                        <span>₺</span>{{MF(findGamesPackagesPrice($paket->id))}}</p>
                                                @endif
                                            </div>
                                            <?php
                                            $stok = DB::table('games_packages_codes')->where('package_id', $paket->id)->where('is_used', '0')->count();
                                            ?>
                                            <div class="colx">
                                                <input id="adetAl" name="adet"
                                                       type="number" class="form-control style-input" placeholder="Adet"
                                                       min="1"
                                                       max="{{$stok}}" required>
                                            </div>
                                            <div class="colx zero float-right">
                                                <p class="card-text">
                                                    <button onclick="location.href='{{route('epin_detay_paket_satin_al', [$epin->link, Str::slug($paket->title).'-'.$paket->id])}}?adet='+$('#adetAl').val()"
                                                            class="btn-inline color-darkgreen w-100">
                                                        Satın Al
                                                    </button>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif */ ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('js')
@endsection
