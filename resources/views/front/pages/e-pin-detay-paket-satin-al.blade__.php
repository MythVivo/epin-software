@extends('front.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        td {
            vertical-align: middle;
        }

        .bakiye_uyarisi {
            position: absolute;
            background: #fff;
            color: red;
            box-shadow: 0 0 10px 0 #00000052;
            border-radius: 10px;
            border: 2px solid red;
            padding: 10px;
            will-change: contents;
            animation-name: show;
            animation-duration: .3s;
            animation-fill-mode: forwards;
            transform: translateY(-120%);
        }

        @keyframes show {
            from {
                opacity: 0;
                transform: translateY(-120%);
            }
            to {
                opacity: 1;
                transform: translateY(-110%);
            }
        }

        .decrement {
            position: relative;
            display: flex;
            justify-content: center;

        }

        .input-number {
            max-width: 80px;
            padding: 0 12px;
            vertical-align: top;
            text-align: center;
            outline: none;
            flex: 0 1 auto;
            width: 100%;
            min-width: 40px;
        }

        .input-number,
        .input-number-decrement,
        .input-number-increment {
            border: 1px solid #ccc;
            height: 40px;
            user-select: none;
        }

        .input-number-decrement, .input-number-increment {
            display: inline-block;
            width: 30px;
            line-height: 38px;
            background: #f1f1f1;
            color: #444;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
            flex: 0 0 auto;
        }

        .input-number-decrement:active,
        .input-number-increment:active {
            background: #ddd;
        }

        .input-number-decrement {
            border-right: none;
            border-radius: 4px 0 0 4px;
        }

        .input-number-increment {
            border-left: none;
            border-radius: 0 4px 4px 0;
        }
    </style>
@endsection
@section('body')
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
    if (isset($_GET['adet']) and is_numeric($_GET['adet'])) {
        $adet = $_GET['adet'];
    } else {
        $adet = 1;
    }
    /*
    if ($epin->epin != 0) {
        $ch = curl_init();
        $headers = array(
            'Authorization: ' . getAuthName(),
            'ApiName: ' . getApiName(),
            'ApiKey: ' . getApiKey(),
            'Content-Type: application/x-www-form-urlencoded',
        );
        curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/GameItemListById');
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
                $paket->stokKodu = $u->StockCode;
            }
        }
        $fiyat = $paket->price * $adet;
        $stok = $paket->stok;
        $stokJson = json_encode(array('StockCode' => $paket->stokKodu));
        $ch = curl_init();
        $headers = array(
            'Authorization: ' . getAuthName(),
            'ApiName: ' . getApiName(),
            'ApiKey: ' . getApiKey(),
            'Content-Type: application/json',
        );
        curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/CheckOrderProduct');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $stokJson);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $stokSorgu = json_decode($response);
        curl_close($ch);
        if (DB::table('games_packages_epin')->where('epinPaket', $u->Id)->count() > 0) {
            $uu = DB::table('games_packages_epin')->where('epinPaket', $u->Id)->first();
            $name = $uu->title;
            $image = asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $uu->image);
        } else {
            $name = $paket->title;
            $image = asset(env('ROOT') . env('FRONT') . env('GAMES_TITLES') . $epin->image);
        }
    } else {*/
    $paket = DB::table('games_packages')->where('id', end($pakets))->first();
    if ($paket == NULL) {
        header("Location: " . URL::to(route('errors_404')), true, 302);
        exit();
    }
    $fiyat = findGamesPackagesPrice($paket->id) * $adet;
    if ($paket->stockCode != NULL) { // eğer epin oyunu ise
        $ch = curl_init();
        $headers = array(
            'Authorization: ' . getAuthName(),
            'ApiName: ' . getApiName(),
            'ApiKey: ' . getApiKey(),
            'Content-Type: application/x-www-form-urlencoded',
        );
        curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/GameItemListById');
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
            if ($u->StockCode == $paket->stockCode) {
                $stok = $u->Stock;
            }
        }
        $stokJson = json_encode(array('StockCode' => $paket->stockCode));
        $ch = curl_init();
        $headers = array(
            'Authorization: ' . getAuthName(),
            'ApiName: ' . getApiName(),
            'ApiKey: ' . getApiKey(),
            'Content-Type: application/json',
        );
        curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/CheckOrderProduct');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $stokJson);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $stokSorgu = json_decode($response);
        curl_close($ch);
        $stok = $stokSorgu->MaxQtyPerOrder;
    } else {
        $stok = DB::table('games_packages_codes')->where('package_id', $paket->id)->where('is_used', '0')->count();
    }
    $name = $paket->title;
    /*}*/

    array_pop($pakets);
    $ilanIsmi = implode("-", $pakets);
    if (!$paket or ($ilanIsmi != Str::slug($name))) {
        header("Location: " . URL::to(route('errors_404')), true, 302);
        exit();
    }

    $oyun=DB::select("select kapat from oyun_siparis where oyun='$epin->game'")[0]->kapat;
    if($oyun==1) {
        header("Location: https://oyuneks.com");
        exit();

    }   // oyun genel olarak satışa kapalıysa



    /*$sayim = DB::table('epin_satis')->where('user', Auth::user()->id)->where('game_title', '13')->whereDate('created_at', date('Y-m-d'))->sum('adet');
    if ($epin->id == 13) {
        if($stok > 3) {
            $stok == 3;
        } else {
            $stok = $stok;
        }
    }
    $stok = $stok - $sayim;
    if($paket->id == 23 or $paket->id == 22 or $paket->id == 20 or $paket->id == 19 or $paket->id == 21) {
        $stok = 0;
    }*/
    ?>
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">

                <section class="game">
                    <div class="container">


                        @if(session('error'))
                            <div class="row">
                                <div class="alert alert-danger" role="alert">
                                    <h4 class="alert-heading">{{session('error')}}</h4>
                                </div>
                            </div>
                        @endif


                        <form method="post" class="epin-form" action="{{route('epin_satin_al')}}" autocomplete="off">
                            @csrf
                            <div class="row overflow-table">
                                <table class="table table-hover table-bordered text-center item-checkout-table">
                                    <thead>
                                    <tr class="table-secondary">
                                        <th>@lang('general.resim')</th>
                                        <th>@lang('general.adi')</th>
                                        <th>Adet</th>
                                        <th>@lang('general.fiyat')</th>
                                        <th>Toplam Tutar</th>
                                        <th>Bakiyeniz</th>
                                        <th>Satın Alım Sonrası Bakiyeniz</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <?php /*
                                    @if($epin->epin != 0)
                                        <?php
                                        if (DB::table('games_packages_epin')->where('epinPaket', $u->Id)->count() > 0) {
                                            $uu = DB::table('games_packages_epin')->where('epinPaket', $u->Id)->first();
                                            $name = $uu->title;
                                            $image = asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $uu->image);
                                        } else {
                                            $name = $paket->title;
                                            $image = asset(env('ROOT') . env('FRONT') . env('GAMES_TITLES') . $epin->image);
                                        }
                                        ?>
                                        <td class="w-25">
                                            <img src="{{$image}}"
                                                 class="card-img-top" alt="{{$name}} görsel">
                                        </td>
                                        <td>{{$name}}</td>
                                        <td>{{$adet}}</td>
                                        <td>{{MF($paket->price)}} TL</td>
                                        <td>{{MF($fiyat)}} TL</td>
                                        <td>
                                            @if(isset(Auth::user()->id))
                                                {{MF(Auth::user()->bakiye)}} TL
                                            @else
                                                Bakiyenizi Görmek İçin Giriş Yapın
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset(Auth::user()->id))
                                                @if(Auth::user()->bakiye - $fiyat < 0)
                                                    İşlem için yeterli bakiyeniz yok
                                                @else
                                                    {{MF(Auth::user()->bakiye - $fiyat)}} TL
                                                @endif
                                            @else
                                                Bakiyenizi Görmek İçin Giriş Yapın
                                            @endif
                                        </td>
                                    @else
                                        <td class="w-25">
                                            <img src="{{asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES').$paket->image)}}"
                                                 class="card-img-top" alt="{{$paket->title}}">
                                        </td>
                                        <td>{{$paket->title}}</td>
                                        <td>{{$adet}}</td>
                                        <td>{{MF(findGamesPackagesPrice($paket->id))}} TL</td>
                                        <td>{{MF($fiyat)}} TL</td>
                                        <td>
                                            @if(isset(Auth::user()->id))
                                                {{MF(Auth::user()->bakiye)}} TL
                                            @else
                                                Bakiyenizi Görmek İçin Giriş Yapın
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset(Auth::user()->id))
                                                @if(Auth::user()->bakiye - $fiyat < 0)
                                                    İşlem için yeterli bakiyeniz yok
                                                @else
                                                    {{MF(Auth::user()->bakiye - $fiyat)}} TL
                                                @endif
                                            @else
                                                Bakiyenizi Görmek İçin Giriş Yapın
                                            @endif
                                        </td>
                                    @endif
                                    */ ?>
                                        <td class="w-25">
                                            <img src="{{asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES').$paket->image)}}"
                                                 class="card-img-top" alt="{{$paket->alt}}">
                                        </td>
                                        <td>{{$paket->title}}</td>
                                        <td>
                                            <div class="decrement">
                                                <span class="input-number-decrement">–</span>
                                                <input name="adet" maxlength="5" class="input-number" type="number" id="adet" value="{{$adet}}" min="1" >
                                                <span class="input-number-increment">+</span>
                                            </div>

                                        </td>
                                        <td><i>₺</i>{{MF(findGamesPackagesPrice($paket->id))}}</td>
                                        <td><i>₺</i><span id="totalPrice">{{MF($fiyat)}}</span></td>
                                        <td>
                                            @if(isset(Auth::user()->id))
                                                <i>₺</i>{{MF(Auth::user()->bakiye)}}
                                            @else
                                                Bakiyenizi Görmek İçin Giriş Yapın
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset(Auth::user()->id))
                                                @if(Auth::user()->bakiye - $fiyat < 0)
                                                    İşlem için yeterli bakiyeniz yok
                                                @else
                                                    <i>₺</i><span
                                                            id="userWallet">{{MF(Auth::user()->bakiye - $fiyat)}}</span>
                                                @endif
                                            @else
                                                Bakiyenizi Görmek İçin Giriş Yapın
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-12 p-0 d-flex justify-content-center align-self-center">
                                    <div class="confirmation card text-center mt-100 mb-100">
                                        <div class="card-body">
                                            <h5 class="card-title">@lang('general.siparisOnay')</h5>
                                            @if(isset(Auth::user()->id))
                                                @if(Auth::user()->bakiye >= $fiyat)
                                                    @if($adet > $stok || DB::select("select durum from oyun_siparis where oyun='$epin->game'")[0]->durum==1)
                                                        <input type="hidden" name="siparis" value="316">
                                                        <? #  ------------------------------------------------------------------------- STOK yok ise sipariş üzerinden gidecez..?>
                                                    @endif

                                                    <input type="hidden" name="baslik" value="{{$epin->id}}">
                                                    <input type="hidden" name="paket" value="{{$paket->id}}">
                                                    <?php /*@if($epin->epin != 0)
                                                            <input type="hidden" name="stokKodu"
                                                                   value="{{$paket->stokKodu}}">
                                                        @else
                                                            <input type="hidden" name="stokKodu"
                                                                   value="0">
                                                        @endif */ ?>
                                                    @if($paket->stockCode != NULL)
                                                        <input type="hidden" name="stokKodu" value="{{$paket->stockCode}}">
                                                    @else
                                                        <input type="hidden" name="stokKodu" value="0">
                                                    @endif
                                                    <input type="hidden" name="price" value="{{$fiyat}}">
                                                    <button type="submit"
                                                            class="btn btn-primary mt-3">@lang('general.onayla')</button>
                                                    @include('front.plugins.siparisiniz-isleniyor')


                                                @else
                                                    <div class="alert alert-danger fade show d-flex align-items-center"
                                                         role="alert">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h5>
                                                                    Satın Alma işlemi için yeterli bakiyeniz yok, lütfen
                                                                    bakiye ekleyin.
                                                                </h5>
                                                            </div>
                                                            <div class="col-12 mt-4">
                                                                <button type="button" class="btn-inline color-red"
                                                                        onclick="location.href='{{route('bakiye_ekle')}}'">
                                                                    Bakiye Ekle
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
                                                     role="alert">
                                                    <i class="fas fa-exclamation-triangle me-3"></i>
                                                    <h5>
                                                        Bu paketi satın alabilmek için lütfen giriş yapın.
                                                    </h5>

                                                    <button type="button" class="btn btn-outline-success btn-sm"
                                                            onclick="location.href='{{route('giris')}}'">
                                                        Giriş Yap
                                                    </button>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

            </div>
        </div>
    </section>

@endsection
@section('js')
    <script>

        $('#adet').keyup(function(x){ fiyatHesapla($(this).val());});

        (function () {


            $( ".epin-form" ).submit(function( event ) {
                $("body").append($(".process-screen").addClass("open"))
            });


            window.inputNumber = function (el) {
                let min = el.attr('min') || false;
                let max = el.attr('max') || false;
                let els = {};
                els.dec = el.prev();
                els.inc = el.next();
                el.each(function () {
                    init($(this));
                });

                function init(el) {
                    els.dec.on('click', decrement);
                    els.inc.on('click', increment);

                    function decrement() {
                        let value = el[0].value;
                        value--;
                        if (!min || value >= min) {
                            el[0].value = value;
                            fiyatHesapla(value);
                        }
                    }

                    function increment() {

                        let value = el[0].value;
                        value++;
                        @if(isset(Auth::user()->id))
                        let bakiye = parseFloat({{Auth::user()->bakiye}});
                        @else
                        let bakiye = 0;
                        @endif
                        let urunFiyati = parseFloat({{findGamesPackagesPrice($paket->id)}});
                        if (!max || value <= max) {
                            if (bakiye - urunFiyati * value > 0) {
                                fiyatHesapla(value);
                                el[0].value = value++;
                            } else {
                                if ($(".bakiye_uyarisi").length == 0) {
                                    let infobakiye = document.createElement("SPAN")
                                    infobakiye.innerText = "İşlem için yeterli bakiyeniz yok"
                                    infobakiye.classList = "bakiye_uyarisi"
                                    el[0].parentElement.insertBefore(infobakiye, el[0])
                                    setTimeout(function () {
                                        infobakiye.remove()
                                    }, 5000);
                                }
                            }
                        }
                    }
                }
            }
        })();
        inputNumber($('.input-number'));

        function fiyatHesapla(adet) {
            @if(isset(Auth::user()->id))
                bakiye = {{Auth::user()->bakiye}};
            @else
                bakiye = -1;
            @endif
                urunFiyati = {{findGamesPackagesPrice($paket->id)}}
                yeniFiyat = urunFiyati * adet;
            kullaniciBakiyesi = bakiye - yeniFiyat;
            $("#totalPrice").html(yeniFiyat.toFixed(2));
            $("#userWallet").html(kullaniciBakiyesi.toFixed(2));
        }
    </script>
@endsection
