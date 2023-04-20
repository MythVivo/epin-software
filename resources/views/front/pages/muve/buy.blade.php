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
   /* if (Auth::user()->izin != 0) {
        header("Location: " . URL::to(route('errors_403')), true, 302);
        exit();
    }*/
    $cdkey = DB::table('muve_games')->where('link', $cdkey)->whereNull('deleted_at')->first();
    if (!$cdkey) {
        header("Location: " . URL::to(route('errors_404')), true, 302);
        exit();
    }
    if ($cdkey->winSup != 1) {
        $cdkey->winGer = false;
    }
    if ($cdkey->macSup != 1) {
        $cdkey->macGer = false;
    }
    if ($cdkey->linuxSup != 1) {
        $cdkey->linuxGer = false;
    }
    $cdkey->categories = explode("\n", $cdkey->categories);
    $cdkey->images = explode("\n", $cdkey->images);
    $cdkey->videos = explode("\n", $cdkey->videos);
    if ($cdkey->steamId == 0) {
        $cdkey->image = asset('public/front/games/' . $cdkey->image);
        $cdkey->background = asset('public/front/games/' . $cdkey->background);
    }
    if (isset($_GET['adet']) and is_numeric($_GET['adet'])) {
        $adet = $_GET['adet'];
    } else {
        $adet = 1;
    }
    $stok = 10;

    $adet=1;

    $tekilFiyat = getMuveGamesPrice($cdkey->id);
    $fiyat = $adet * $tekilFiyat;
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


                        <form method="post" class="epin-form" action="{{route('cd_key_detay_satin_al_post')}}" autocomplete="off">
                            @csrf
                            <div class="row">
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
                                        <td class="w-25">
                                            <img src="{{$cdkey->image}}"
                                                 class="card-img-top" alt="{{$cdkey->alt}}">
                                        </td>
                                        <td>{{$cdkey->title}}</td>
                                        <td>
                                            <div class="decrement">1
{{--                                                <span class="input-number-decrement">–</span>--}}
{{--                                                <input name="adet" class="input-number" type="number" value="{{$adet}}" min="1" max="{{$stok}}">--}}
{{--                                                <span class="input-number-increment">+</span>--}}
                                            </div>
                                            <input name="adet" type="hidden" value="1">
                                        </td>
                                        <? if(Auth::user()->id=='2497') {$tekilFiyat=$cdkey->alis; $fiyat=$cdkey->alis;} # bizim ofis hesabına alış fiyatından werelim ;)?>
                                        <td><i>₺</i>{{MF($tekilFiyat)}}</td>
                                        <td><i>₺</i><span id="totalPrice">{{MF($fiyat)}}</span></td>
                                        <td>
                                            @if(isset(Auth::user()->id))
                                                <i>₺</i>{{MF(Auth::user()->bakiye+Auth::user()->bakiye_cekilebilir)}}
                                            @else
                                                Bakiyenizi Görmek İçin Giriş Yapın
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset(Auth::user()->id))
                                                @if(Auth::user()->bakiye+Auth::user()->bakiye_cekilebilir - $fiyat < 0)
                                                    İşlem için yeterli bakiyeniz yok
                                                @else
                                                    <i>₺</i><span
                                                            id="userWallet">{{MF(Auth::user()->bakiye+Auth::user()->bakiye_cekilebilir - $fiyat)}}</span>
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
                                                @if(Auth::user()->bakiye+Auth::user()->bakiye_cekilebilir >= $fiyat)
                                                    @if($adet > $stok)
                                                        <div class="alert alert-danger fade show d-flex align-items-center"
                                                             role="alert">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h5>
                                                                        Satın Alma işlemi için yeterli stok yok, lütfen
                                                                        miktarı düşürün veya stokların güncellenmesi
                                                                        için bizimle iletişime geçin.
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <input type="hidden" name="gameId" value="{{$cdkey->id}}">
                                                        <input type="hidden" name="muveId" value="{{$cdkey->muveId}}">
                                                        <button type="submit" class="btn btn-primary mt-3">@lang('general.onayla')</button>
                                                        @include('front.plugins.siparisiniz-isleniyor')

                                                    @endif
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
                        let bakiye = parseFloat({{Auth::user()->bakiye+Auth::user()->bakiye_cekilebilir}});
                        @else
                        let bakiye = 0;
                        @endif
                        let urunFiyati = parseFloat({{$tekilFiyat}});
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
                bakiye = {{Auth::user()->bakiye+Auth::user()->bakiye_cekilebilir}};
            @else
                bakiye = -1;
            @endif
                urunFiyati = {{$tekilFiyat}}
                yeniFiyat = urunFiyati * 1; //adet;
            kullaniciBakiyesi = bakiye - yeniFiyat;
            $("#totalPrice").html(yeniFiyat.toFixed(2));
            $("#userWallet").html(kullaniciBakiyesi.toFixed(2));
        }
    </script>
@endsection
