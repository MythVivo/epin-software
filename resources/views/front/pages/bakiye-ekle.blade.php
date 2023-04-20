<? #--------------Ajax get req. karsilama



    if(@$_GET['razer_kod']&&$_GET['plt']){
        $p = (object) $_GET;

        DB::table('razer_kod')->insert([
                            'user' => Auth::user()->id,
                            'kod' => $p->razer_kod,
                            'btutar'=>$p->razer_tut,
                            'plt'=> $p->plt_n,
                            'durum'=>1,
                            'created_at' => date('YmdHis')
                        ]);
        die();
    }
?>

@extends('front.layouts.app')
@section('css')
    <style>
        td {
            vertical-align: middle;
        }
    </style>
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/sweet-alert2/sweetalert2.min.css') }}"
        rel="stylesheet" type="text/css">
@endsection
@section('body')

    @php
        $userData = DB::table('users')
            ->where('id', Auth::user()->id)
            ->first();

        $canOzanPay = canOzanPay();
    @endphp

    <style>
        .colflex {
            width: 16.66%;
        }

        @media only screen and (max-width: 1024px) {
            .colflex {
                width: 33%;
            }
        }
    </style>
    <section class="bg-gray pb-40">
        <div class="container">

            <div class="row">
                @include('front.modules.user-menu')
                <div class="col-md-9">
                    <div class="accordion mb-100" id="online-pay">
                        <div class="row">
                            <div class="col-12">
                                @if (session('success'))
                                    <div class="col-12">
                                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center"
                                            role="alert">
                                            <h5>
                                                {{ session('success') }}
                                            </h5>
                                        </div>
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
                                            role="alert">
                                            <i class="fas fa-exclamation-triangle me-3"></i>
                                            <h5>
                                                {{ session('error') }}
                                            </h5>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <ul class="nav nav-pills custom-nav mb-3" id="v-pills-tab" role="tablist"
                                    aria-orientation="vertical">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="kredi-karti-tab" data-bs-toggle="pill"
                                            data-bs-target="#kredi-karti" type="button" role="tab"
                                            aria-controls="kredi-karti" aria-selected="true">Kredi / Banka Kartı
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="yurtdisi-tab" data-bs-toggle="pill"
                                            data-bs-target="#yurtdisi" type="button" role="tab"
                                            aria-controls="yurtdisi" aria-selected="false">
                                            Yurtdışı Kredi / Banka Kartı
                                        </button>
                                    </li>
                                    @if ($userData->refId != 27156)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="havale-eft-tab" data-bs-toggle="pill"
                                                data-bs-target="#havale-eft" type="button" role="tab"
                                                aria-controls="havale-eft" aria-selected="false">
                                                Havale / EFT / ATM
                                            </button>
                                        </li>
                                    @endif
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="papara-tab" data-bs-toggle="pill"
                                            data-bs-target="#papara" type="button" role="tab" aria-controls="papara"
                                            aria-selected="false">
                                            Papara
                                        </button>
                                    </li>

{{--                                    <li class="nav-item" role="presentation">--}}
{{--                                        <button class="nav-link" id="razer-tab" data-bs-toggle="pill"--}}
{{--                                            data-bs-target="#razer" type="button" role="tab" aria-controls="razer"--}}
{{--                                            aria-selected="false">Razer</button>--}}
{{--                                    </li>--}}

{{--                                    <li class="nav-item" role="presentation">--}}
{{--                                        <button class="nav-link" id="kripto-tab" data-bs-toggle="pill"--}}
{{--                                            data-bs-target="#kripto" type="button" role="tab" aria-controls="kripto"--}}
{{--                                            aria-selected="false">--}}
{{--                                            Kripto Para--}}
{{--                                        </button>--}}
{{--                                    </li>--}}
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="hediye-tab" data-bs-toggle="pill"
                                            data-bs-target="#hediye" type="button" role="tab" aria-controls="hediye"
                                            aria-selected="false">
                                            Hediye Kodu
                                        </button>
                                    </li>
                                    @if (Auth::user()->id == 2)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="mobile-tab" data-bs-toggle="pill"
                                                data-bs-target="#mobile" type="button" role="tab"
                                                aria-controls="mobile" aria-selected="false">
                                                Mobil Ödeme
                                            </button>
                                        </li>
                                    @endif
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="mobile-tab" data-bs-toggle="pill"
                                            data-bs-target="#diger" type="button" role="tab" aria-controls="diger"
                                            aria-selected="false">
                                            {{ $userData->refId != 27156 ? 'Diğer Ödeme' : 'Havale / EFT' }}
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-12">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="kredi-karti" role="tabpanel"
                                        aria-labelledby="kredi-karti-tab">
                                        <div class="havale-container">
                                            <div class="custom-bank-select">

                                                <div class="bank-list">
                                                    <div class="row">
                                                        @if ($canOzanPay)
                                                            <div class="bank-card colflex">
                                                                <span>
                                                                    <img
                                                                        src="{{ asset('/public/front/images/card-logo/ozan.png') }}">
                                                                    <div class="tagline">
                                                                        <div class="tag-inline">
                                                                            <p></p>
                                                                        </div>
                                                                    </div>
                                                                </span>

                                                                <div class="bankinfo">
                                                                    <div class="bankinfo-left-col">
                                                                        <img
                                                                            src="{{ asset('/public/front/images/card-logo/ozaninnerimg.png') }}">
                                                                    </div>
                                                                    <div class="bankinfo-right-col">
                                                                        <div class="row">

                                                                            <div class="bank-info col-12">
                                                                                <span>%{{ getCacheSetings()->ozanKomisyon }}
                                                                                    komisyon ile OzanPay ile güvenli bir
                                                                                    şekilde
                                                                                    tüm banka ve kredi kartlarınızla yükleme
                                                                                    yapabilirsiniz.</span>
                                                                            </div>
                                                                            <div class="col-12 payOnlineWrapper">
                                                                                <!--<form id="payOnline" method="post" action="{{ route('odeme_yap') }}" target="_blank" autocomplete="off">-->
                                                                                @csrf
                                                                                <input type="hidden" name="tur"
                                                                                    value="1">
                                                                                <div class="payOnlinefooter row">
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Siteye Yatırmak
                                                                                                İstediğiniz
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="ozanode1"
                                                                                                class="form-control style-input tutar1 ozanode1"
                                                                                                name="tutar"
                                                                                                onkeyup="ozanhesapla()"
                                                                                                placeholder="00">
                                                                                            <i>,</i>
                                                                                            <input id="ozanode2"
                                                                                                class="form-control style-input tutar2 ozanode2"
                                                                                                name="tutar2"
                                                                                                onkeyup="ozanhesapla()"
                                                                                                placeholder="00">
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Toplam Ödenecek
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="ozanodetutar1"
                                                                                                class="form-control style-input tutar1 ozanodetutar1"
                                                                                                readonly>
                                                                                            <i class="disabled">,</i>
                                                                                            <input id="ozanodetutar2"
                                                                                                class="form-control style-input tutar2 ozanodetutar2"
                                                                                                readonly>
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2 mb-4 button-flex">
                                                                                        <a href="" target="_blank"
                                                                                            onclick="return ozansend()"
                                                                                            class="btn-inline color-darkgreen ozanbutton"
                                                                                            style="color: white">
                                                                                            Ödeme Yap
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- </form> -->
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endif





                                                        <div class="bank-card colflex" style="z-index: 2">
                                                            <span>
                                                                <img
                                                                    src="{{ asset('/public/front/images/card-logo/paytr-cover.png') }}">
                                                                <div class="tagline">
                                                                    <div class="tag-inline">
                                                                        <p style="font-size: 11px">Taksit: 2-3-6-9-12</p>
                                                                    </div>
                                                                </div>
                                                            </span>

                                                            <div class="bankinfo">
                                                                <div class="bankinfo-left-col">
                                                                    <img
                                                                        src="{{ asset('/public/front/images/card-logo/paytr.svg') }}">
                                                                </div>
                                                                <div class="bankinfo-right-col">
                                                                    <div class="row">

                                                                        <div class="bank-info col-12">
                                                                            <span>%{{ getCacheSetings()->onlineOdemeKomisyon }}
                                                                                komisyon ile PayTR ile güvenli bir şekilde
                                                                                tüm banka ve kredi kartlarınızla yükleme
                                                                                yapabilirsiniz.</span>
                                                                        </div>


                                                                        <div class="col-12 payOnlineWrapper">
                                                                            <form id="payOnline" method="post"
                                                                                action="{{ route('odeme_yap') }}"
                                                                                target="_blank" autocomplete="off">
                                                                                @csrf
                                                                                <input type="hidden" name="tur"
                                                                                    value="1">
                                                                                <div class="payOnlinefooter row">
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Siteye Yatırmak
                                                                                                İstediğiniz
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="ode111"
                                                                                                class="form-control style-input tutar1 ode111"
                                                                                                name="tutar"
                                                                                                onkeyup="hesapla()"
                                                                                                placeholder="00">
                                                                                            <i>,</i>
                                                                                            <input id="ode222"
                                                                                                class="form-control style-input tutar2 ode222"
                                                                                                name="tutar2"
                                                                                                onkeyup="hesapla()"
                                                                                                placeholder="00">
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Toplam Ödenecek
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="odeTutar1"
                                                                                                class="form-control style-input tutar1 odeTutar1"
                                                                                                readonly>
                                                                                            <i class="disabled">,</i>
                                                                                            <input id="odeTutar2"
                                                                                                class="form-control style-input tutar2 odeTutar2"
                                                                                                readonly>
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2 mb-4 button-flex">
                                                                                        <button
                                                                                            class="btn-inline color-darkgreen">
                                                                                            Ödeme Yap
                                                                                        </button>
                                                                                    </div>


                                                                                </div>

                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        @if ($canOzanPay)
                                                            <div class="bank-card colflex">
                                                                <span>
                                                                    <img
                                                                        src="{{ asset('/public/front/images/card-logo/papara-kredikarti.png') }}">
                                                                    <div class="tagline">
                                                                        <div class="tag-inline">
                                                                            <p></p>
                                                                        </div>
                                                                    </div>
                                                                </span>

                                                                <div class="bankinfo">
                                                                    <div class="bankinfo-left-col">
                                                                        <img
                                                                            src="{{ asset('/public/front/images/card-logo/paparaLogo2.png') }}">
                                                                    </div>
                                                                    <div class="bankinfo-right-col">
                                                                        <div class="row">
                                                                            <div class="bank-info col-12">
                                                                                <span>%2 komisyon ile Papara üzerinden
                                                                                    güvenli
                                                                                    bir şekilde yükleme
                                                                                    yapabilirsiniz.</span>

                                                                            </div>
                                                                            <div class="col-12 payOnlineWrapper">
                                                                                <form id="payOnline" method="post"
                                                                                    action="{{ route('odeme_yap') }}"
                                                                                    target="_blank" autocomplete="off">
                                                                                    @csrf
                                                                                    <input type="hidden" name="tur"
                                                                                        value="8">
                                                                                    <div class="payOnlinefooter">
                                                                                        <div class="col-md-5 mb-4">
                                                                                            <label><span>Siteye Yatırmak
                                                                                                    İstediğiniz
                                                                                                    Tutar:</span></label>
                                                                                            <div
                                                                                                class="input-group has-validation">
                                                                                                <input id="ode1111"
                                                                                                    class="form-control style-input tutar1 ode1111"
                                                                                                    name="tutar"
                                                                                                    onkeyup="hesaplaPapara(this)"
                                                                                                    placeholder="00">
                                                                                                <i>,</i>
                                                                                                <input id="ode2222"
                                                                                                    class="form-control style-input tutar2 ode2222"
                                                                                                    name="tutar2"
                                                                                                    onkeyup="hesaplaPapara(this)"
                                                                                                    placeholder="00">
                                                                                                <span
                                                                                                    class="input-group-text style-input"
                                                                                                    id="inputGroupPrepend"><span
                                                                                                        class="moneysymbol">₺</span></span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-5 mb-4">
                                                                                            <label><span>Toplam Ödenecek
                                                                                                    Tutar:</span></label>
                                                                                            <div
                                                                                                class="input-group has-validation">
                                                                                                <input id="odeTutar11"
                                                                                                    class="form-control style-input tutar1 odeTutar11"
                                                                                                    readonly>
                                                                                                <i class="disabled">,</i>
                                                                                                <input id="odeTutar22"
                                                                                                    class="form-control style-input tutar2 odeTutar22"
                                                                                                    readonly>
                                                                                                <span
                                                                                                    class="input-group-text style-input"
                                                                                                    id="inputGroupPrepend"><span
                                                                                                        class="moneysymbol">₺</span></span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-md-2 mb-4 button-flex">
                                                                                            <button
                                                                                                class="btn-inline color-darkgreen">
                                                                                                Ödeme Yap
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>

                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="bank-card colflex" style="z-index: 2">
                                                            <span>
                                                                <img
                                                                    src="{{ asset('/public/front/images/card-logo/gpay-cover.png') }}">
                                                                <div class="tagline">
                                                                    <div class="tag-inline">
                                                                        <p style="font-size: 11px">Taksit: 3-6-9-12</p>
                                                                    </div>
                                                                </div>
                                                            </span>

                                                            <div class="bankinfo">
                                                                <div class="bankinfo-left-col">
                                                                    <img
                                                                        src="{{ asset('/public/front/images/card-logo/gpay-logo.png') }}">
                                                                </div>
                                                                <div class="bankinfo-right-col">
                                                                    <div class="row">

                                                                        <div class="bank-info col-12">
                                                                            <span>%2.3 komisyon ile Gpay ile güvenli bir
                                                                                şekilde tüm banka ve kredi kartlarınızla
                                                                                yükleme yapabilirsiniz.</span>
                                                                        </div>


                                                                        <div class="col-12 payOnlineWrapper">
                                                                            <form id="payOnline" method="post"
                                                                                action="{{ route('odeme_yap') }}"
                                                                                target="_blank" autocomplete="off">
                                                                                @csrf
                                                                                <input type="hidden" name="tur"
                                                                                    value="9">
                                                                                <input type="hidden"
                                                                                    name="selected_payment"
                                                                                    value="krediKarti">
                                                                                <input type="hidden" name="channel"
                                                                                    value="10">
                                                                                <div class="payOnlinefooter">
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Siteye Yatırmak
                                                                                                İstediğiniz
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="ode11111"
                                                                                                class="form-control style-input tutar1 ode11111"
                                                                                                name="tutar"
                                                                                                onkeyup="hesaplaGpayYurtici()"
                                                                                                placeholder="00">
                                                                                            <i>,</i>
                                                                                            <input id="ode22222"
                                                                                                class="form-control style-input tutar2 ode22222"
                                                                                                name="tutar2"
                                                                                                onkeyup="hesaplaGpayYurtici()"
                                                                                                placeholder="00">
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Toplam Ödenecek
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="odeTutar11111"
                                                                                                class="form-control style-input tutar1 odeTutar11111"
                                                                                                readonly>
                                                                                            <i class="disabled">,</i>
                                                                                            <input id="odeTutar22222"
                                                                                                class="form-control style-input tutar2 odeTutar22222"
                                                                                                readonly>
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2 mb-4 button-flex">
                                                                                        <button
                                                                                            class="btn-inline color-darkgreen">
                                                                                            Ödeme Yap
                                                                                        </button>
                                                                                    </div>
                                                                                </div>

                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="bank-card colflex">
                                                            <span>
                                                                <img
                                                                    src="{{ asset('/public/front/images/card-logo/ininal-cover.png') }}">
                                                                <div class="tagline">
                                                                    <div class="tag-inline">
                                                                        <p></p>
                                                                    </div>
                                                                </div>
                                                            </span>

                                                            <div class="bankinfo">
                                                                <div class="bankinfo-left-col">
                                                                    <img
                                                                        src="{{ asset('/public/front/images/card-logo/ininal-logo.svg') }}">
                                                                </div>
                                                                <div class="bankinfo-right-col">
                                                                    <div class="row">

                                                                        <div class="bank-info col-12">
                                                                            <span>Gpay İninal ile güvenli bir şekilde
                                                                                yükleme yapabilirsiniz.</span>
                                                                            <span></span>
                                                                            <span></span>
                                                                        </div>


                                                                        <div class="col-12 payOnlineWrapper">
                                                                            <form id="payOnline" method="post"
                                                                                action="{{ route('odeme_yap') }}"
                                                                                target="_blank" autocomplete="off">
                                                                                @csrf
                                                                                <input type="hidden" name="tur"
                                                                                    value="9">
                                                                                <input type="hidden"
                                                                                    name="selected_payment"
                                                                                    value="ininal">
                                                                                <input type="hidden" name="channel"
                                                                                    value="11">
                                                                                <div class="payOnlinefooter">
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Siteye Yatırmak
                                                                                                İstediğiniz
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="ode1234"
                                                                                                class="form-control style-input tutar1 ode1234"
                                                                                                name="tutar"
                                                                                                onkeyup="hesaplaGpayIninal()"
                                                                                                placeholder="00">
                                                                                            <i>,</i>
                                                                                            <input id="ode22222"
                                                                                                class="form-control style-input tutar2 ode2345"
                                                                                                name="tutar2"
                                                                                                onkeyup="hesaplaGpayIninal()"
                                                                                                placeholder="00">
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Toplam Ödenecek
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="odeTutar1234"
                                                                                                class="form-control style-input tutar1 odeTutar1234"
                                                                                                readonly>
                                                                                            <i class="disabled">,</i>
                                                                                            <input id="odeTutar2345"
                                                                                                class="form-control style-input tutar2 odeTutar2345"
                                                                                                readonly>
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2 mb-4 button-flex">
                                                                                        <button
                                                                                            class="btn-inline color-darkgreen">
                                                                                            Ödeme Yap
                                                                                        </button>
                                                                                    </div>
                                                                                </div>

                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="bank-card colflex">
                                                            <span>
                                                                <img
                                                                    src="{{ asset('/public/front/images/card-logo/bkm-express-cover.png') }}">
                                                                <div class="tagline">
                                                                    <div class="tag-inline">
                                                                        <p></p>
                                                                    </div>
                                                                </div>
                                                            </span>

                                                            <div class="bankinfo">
                                                                <div class="bankinfo-left-col">
                                                                    <img
                                                                        src="{{ asset('/public/front/images/card-logo/bkm-express-logo.png') }}">
                                                                </div>
                                                                <div class="bankinfo-right-col">
                                                                    <div class="row">

                                                                        <div class="bank-info col-12">
                                                                            <span>Gpay BKM Express ile güvenli bir şekilde
                                                                                yükleme yapabilirsiniz.</span>
                                                                            <span></span>
                                                                            <span></span>
                                                                        </div>


                                                                        <div class="col-12 payOnlineWrapper">
                                                                            <form id="payOnline" method="post"
                                                                                action="{{ route('odeme_yap') }}"
                                                                                target="_blank" autocomplete="off">
                                                                                @csrf
                                                                                <input type="hidden" name="tur"
                                                                                    value="9">
                                                                                <input type="hidden"
                                                                                    name="selected_payment"
                                                                                    value="bkmexpress">
                                                                                <input type="hidden" name="channel"
                                                                                    value="12">
                                                                                <div class="payOnlinefooter">
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Siteye Yatırmak
                                                                                                İstediğiniz
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="ode12345"
                                                                                                class="form-control style-input tutar1 ode12345"
                                                                                                name="tutar"
                                                                                                onkeyup="hesaplaGpayBkm()"
                                                                                                placeholder="00">
                                                                                            <i>,</i>
                                                                                            <input id="ode23456"
                                                                                                class="form-control style-input tutar2 ode23456"
                                                                                                name="tutar2"
                                                                                                onkeyup="hesaplaGpayBkm()"
                                                                                                placeholder="00">
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Toplam Ödenecek
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="odeTutar12345"
                                                                                                class="form-control style-input tutar1 odeTutar12345"
                                                                                                readonly>
                                                                                            <i class="disabled">,</i>
                                                                                            <input id="odeTutar23456"
                                                                                                class="form-control style-input tutar2 odeTutar23456"
                                                                                                readonly>
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2 mb-4 button-flex">
                                                                                        <button
                                                                                            class="btn-inline color-darkgreen">
                                                                                            Ödeme Yap
                                                                                        </button>
                                                                                    </div>
                                                                                </div>

                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="bank-payment-form">

                                                        </div>


                                                    </div>

                                                </div>


                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="yurtdisi" role="tabpanel"
                                        aria-labelledby="yurtdisi-tab">
                                        <div class="havale-container">
                                            <div class="custom-bank-select">

                                                <div class="bank-list">
                                                    <div class="row">
                                                        <div class="bank-card col-6 col-md-3">
                                                            <span>
                                                                <img
                                                                    src="{{ asset('/public/front/images/card-logo/paytr-yd-cover.png') }}">
                                                                <div class="tagline">
                                                                    <div class="tag-inline">
                                                                        <p></p>
                                                                    </div>
                                                                </div>
                                                            </span>

                                                            <div class="bankinfo">
                                                                <div class="bankinfo-left-col">
                                                                    <img
                                                                        src="{{ asset('/public/front/images/card-logo/paytr.svg') }}">
                                                                </div>
                                                                <div class="bankinfo-right-col">
                                                                    <div class="row">

                                                                        <div class="bank-info col-12">
                                                                            <span>%2.65 komisyon ile PayTR ile güvenli bir
                                                                                şekilde yurtdışından tüm banka ve kredi
                                                                                kartlarınızla yükleme yapabilirsiniz.</span>
                                                                        </div>


                                                                        <div class="col-12 payOnlineWrapper">
                                                                            <form id="payOnline" method="post" action="{{ route('odeme_yap') }}" target="_blank" autocomplete="off">
                                                                                @csrf
                                                                                <input type="hidden" name="tur" value="1">
                                                                                <input type="hidden" name="channel" value="13">
                                                                                <div class="payOnlinefooter row">
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Siteye YatırmakİstediğinizTutar:</span></label>
                                                                                        <div class="input-group has-validation">
                                                                                            <input id="ode123" class="form-control style-input tutar1 ode123" name="tutar" onkeyup="hesaplaYurtdisiPaytr()" placeholder="00">
                                                                                            <i>,</i>
                                                                                            <input id="ode234" class="form-control style-input tutar2 ode234" name="tutar2" onkeyup="hesaplaYurtdisiPaytr()" placeholder="00">
                                                                                            <span class="input-group-text style-input" id="inputGroupPrepend"><span class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Toplam ÖdenecekTutar:</span></label>
                                                                                        <div class="input-group has-validation">
                                                                                            <input id="odeTutar123" class="form-control style-input tutar1 odeTutar123" readonly>
                                                                                            <i class="disabled">,</i>
                                                                                            <input id="odeTutar234" class="form-control style-input tutar2 odeTutar234" readonly>
                                                                                            <span class="input-group-text style-input" id="inputGroupPrepend"><span class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row m-1" >
                                                                                        <div class="col-6">
                                                                                            <select id="country" name="country" class="form-control"><option value="sec">Ülkenizi Seçin</option>
                                                                                                    <option value="AFG">Afganistan</option>
                                                                                                    <option value="ALA">Åland Adaları</option>
                                                                                                    <option value="ALB">Arnavutluk</option>
                                                                                                    <option value="DZA">Cezayir</option>
                                                                                                    <option value="ASM">Amerikan Samoası</option>
                                                                                                    <option value="AND">Andorra</option>
                                                                                                    <option value="AGO">Angola</option>
                                                                                                    <option value="AIA">Anguilla</option>
                                                                                                    <option value="ATA">Antarktika</option>
                                                                                                    <option value="ATG">Antigua ve Barbuda</option>
                                                                                                    <option value="ARG">Arjantin</option>
                                                                                                    <option value="ARM">Ermenistan</option>
                                                                                                    <option value="ABW">Aruba</option>
                                                                                                    <option value="AUS">Avustralya</option>
                                                                                                    <option value="AUT">Avusturya</option>
                                                                                                    <option value="AZE">Azerbaycan</option>
                                                                                                    <option value="BHS">Bahamalar</option>
                                                                                                    <option value="BHR">Bahreyn</option>
                                                                                                    <option value="BGD">Bangladeş</option>
                                                                                                    <option value="BRB">Barbados</option>
                                                                                                    <option value="BLR">Belarus</option>
                                                                                                    <option value="BEL">Belçika</option>
                                                                                                    <option value="BLZ">Belize</option>
                                                                                                    <option value="BEN">Benin</option>
                                                                                                    <option value="BMU">Bermuda</option>
                                                                                                    <option value="BTN">Butan</option>
                                                                                                    <option value="BOL">Bolivya</option>
                                                                                                    <option value="BES">Karayip Hollandası</option>
                                                                                                    <option value="BIH">Bosna-Hersek</option>
                                                                                                    <option value="BWA">Botsvana</option>
                                                                                                    <option value="BVT">Bouvet Adası</option>
                                                                                                    <option value="BRA">Brezilya</option>
                                                                                                    <option value="IOT">Britanya Hint Okyanusu Toprakları</option>
                                                                                                    <option value="BRN">Brunei</option>
                                                                                                    <option value="BGR">Bulgaristan</option>
                                                                                                    <option value="BFA">Burkina Faso</option>
                                                                                                    <option value="BDI">Burundi</option>
                                                                                                    <option value="KHM">Kamboçya</option>
                                                                                                    <option value="CMR">Kamerun</option>
                                                                                                    <option value="CAN">Kanada</option>
                                                                                                    <option value="CPV">Cape Verde</option>
                                                                                                    <option value="CYM">Cayman Adaları</option>
                                                                                                    <option value="CAF">Orta Afrika Cumhuriyeti</option>
                                                                                                    <option value="TCD">Çad</option>
                                                                                                    <option value="CHL">Şili</option>
                                                                                                    <option value="CHN">Çin</option>
                                                                                                    <option value="CXR">Christmas Adası</option>
                                                                                                    <option value="CCK">Cocos (Keeling) Adaları</option>
                                                                                                    <option value="COL">Kolombiya</option>
                                                                                                    <option value="COM">Komorlar</option>
                                                                                                    <option value="COG">Kongo - Brazavil</option>
                                                                                                    <option value="COD">Kongo - Kinşasa</option>
                                                                                                    <option value="COK">Cook Adaları</option>
                                                                                                    <option value="CRI">Kosta Rika</option>
                                                                                                    <option value="CIV">Côte d’Ivoire</option>
                                                                                                    <option value="HRV">Hırvatistan</option>
                                                                                                    <option value="CUB">Küba</option>
                                                                                                    <option value="CUW">Curaçao</option>
                                                                                                    <option value="CYP">Kıbrıs</option>
                                                                                                    <option value="CZE">Çekya</option>
                                                                                                    <option value="DNK">Danimarka</option>
                                                                                                    <option value="DJI">Cibuti</option>
                                                                                                    <option value="DMA">Dominika</option>
                                                                                                    <option value="DOM">Dominik Cumhuriyeti</option>
                                                                                                    <option value="ECU">Ekvador</option>
                                                                                                    <option value="EGY">Mısır</option>
                                                                                                    <option value="SLV">El Salvador</option>
                                                                                                    <option value="GNQ">Ekvator Ginesi</option>
                                                                                                    <option value="ERI">Eritre</option>
                                                                                                    <option value="EST">Estonya</option>
                                                                                                    <option value="ETH">Etiyopya</option>
                                                                                                    <option value="FLK">Falkland Adaları (Malvinas Adaları)</option>
                                                                                                    <option value="FRO">Faroe Adaları</option>
                                                                                                    <option value="FJI">Fiji</option>
                                                                                                    <option value="FIN">Finlandiya</option>
                                                                                                    <option value="FRA">Fransa</option>
                                                                                                    <option value="GUF">Fransız Guyanası</option>
                                                                                                    <option value="PYF">Fransız Polinezyası</option>
                                                                                                    <option value="ATF">Fransız Güney Toprakları</option>
                                                                                                    <option value="GAB">Gabon</option>
                                                                                                    <option value="GMB">Gambiya</option>
                                                                                                    <option value="GEO">Gürcistan</option>
                                                                                                    <option value="DEU">Almanya</option>
                                                                                                    <option value="GHA">Gana</option>
                                                                                                    <option value="GIB">Cebelitarık</option>
                                                                                                    <option value="GRC">Yunanistan</option>
                                                                                                    <option value="GRL">Grönland</option>
                                                                                                    <option value="GRD">Grenada</option>
                                                                                                    <option value="GLP">Guadeloupe</option>
                                                                                                    <option value="GUM">Guam</option>
                                                                                                    <option value="GTM">Guatemala</option>
                                                                                                    <option value="GGY">Guernsey</option>
                                                                                                    <option value="GIN">Gine</option>
                                                                                                    <option value="GNB">Gine-Bissau</option>
                                                                                                    <option value="GUY">Guyana</option>
                                                                                                    <option value="HTI">Haiti</option>
                                                                                                    <option value="HMD">Heard Adası ve McDonald Adaları</option>
                                                                                                    <option value="VAT">Vatikan</option>
                                                                                                    <option value="HND">Honduras</option>
                                                                                                    <option value="HKG">Hong Kong</option>
                                                                                                    <option value="HUN">Macaristan</option>
                                                                                                    <option value="ISL">İzlanda</option>
                                                                                                    <option value="IND">Hindistan</option>
                                                                                                    <option value="IDN">Endonezya</option>
                                                                                                    <option value="IRN">İran</option>
                                                                                                    <option value="IRQ">Irak</option>
                                                                                                    <option value="IRL">İrlanda</option>
                                                                                                    <option value="IMN">Man Adası</option>
                                                                                                    <option value="ISR">İsrail</option>
                                                                                                    <option value="ITA">İtalya</option>
                                                                                                    <option value="JAM">Jamaika</option>
                                                                                                    <option value="JPN">Japonya</option>
                                                                                                    <option value="JEY">Jersey</option>
                                                                                                    <option value="JOR">Ürdün</option>
                                                                                                    <option value="KAZ">Kazakistan</option>
                                                                                                    <option value="KEN">Kenya</option>
                                                                                                    <option value="KIR">Kiribati</option>
                                                                                                    <option value="PRK">Kuzey Kore</option>
                                                                                                    <option value="KOR">Güney Kore</option>
                                                                                                    <option value="XKX">Kosova</option>
                                                                                                    <option value="KWT">Kuveyt</option>
                                                                                                    <option value="KGZ">Kırgızistan</option>
                                                                                                    <option value="LAO">Laos</option>
                                                                                                    <option value="LVA">Letonya</option>
                                                                                                    <option value="LBN">Lübnan</option>
                                                                                                    <option value="LSO">Lesotho</option>
                                                                                                    <option value="LBR">Liberya</option>
                                                                                                    <option value="LBY">Libya</option>
                                                                                                    <option value="LIE">Liechtenstein</option>
                                                                                                    <option value="LTU">Litvanya</option>
                                                                                                    <option value="LUX">Lüksemburg</option>
                                                                                                    <option value="MAC">Makao</option>
                                                                                                    <option value="MKD">Kuzey Makedonya</option>
                                                                                                    <option value="MDG">Madagaskar</option>
                                                                                                    <option value="MWI">Malavi</option>
                                                                                                    <option value="MYS">Malezya</option>
                                                                                                    <option value="MDV">Maldivler</option>
                                                                                                    <option value="MLI">Mali</option>
                                                                                                    <option value="MLT">Malta</option>
                                                                                                    <option value="MHL">Marshall Adaları</option>
                                                                                                    <option value="MTQ">Martinik</option>
                                                                                                    <option value="MRT">Moritanya</option>
                                                                                                    <option value="MUS">Mauritius</option>
                                                                                                    <option value="MYT">Mayotte</option>
                                                                                                    <option value="MEX">Meksika</option>
                                                                                                    <option value="FSM">Mikronezya</option>
                                                                                                    <option value="MDA">Moldova</option>
                                                                                                    <option value="MCO">Monako</option>
                                                                                                    <option value="MNG">Moğolistan</option>
                                                                                                    <option value="MNE">Karadağ</option>
                                                                                                    <option value="MSR">Montserrat</option>
                                                                                                    <option value="MAR">Fas</option>
                                                                                                    <option value="MOZ">Mozambik</option>
                                                                                                    <option value="MMR">Myanmar (Burma)</option>
                                                                                                    <option value="NAM">Namibya</option>
                                                                                                    <option value="NRU">Nauru</option>
                                                                                                    <option value="NPL">Nepal</option>
                                                                                                    <option value="NLD">Hollanda</option>
                                                                                                    <option value="ANT">Curaçao</option>
                                                                                                    <option value="NCL">Yeni Kaledonya</option>
                                                                                                    <option value="NZL">Yeni Zelanda</option>
                                                                                                    <option value="NIC">Nikaragua</option>
                                                                                                    <option value="NER">Nijer</option>
                                                                                                    <option value="NGA">Nijerya</option>
                                                                                                    <option value="NIU">Niue</option>
                                                                                                    <option value="NFK">Norfolk Adası</option>
                                                                                                    <option value="MNP">Kuzey Mariana Adaları</option>
                                                                                                    <option value="NOR">Norveç</option>
                                                                                                    <option value="OMN">Umman</option>
                                                                                                    <option value="PAK">Pakistan</option>
                                                                                                    <option value="PLW">Palau</option>
                                                                                                    <option value="PSE">Filistin</option>
                                                                                                    <option value="PAN">Panama</option>
                                                                                                    <option value="PNG">Papua Yeni Gine</option>
                                                                                                    <option value="PRY">Paraguay</option>
                                                                                                    <option value="PER">Peru</option>
                                                                                                    <option value="PHL">Filipinler</option>
                                                                                                    <option value="PCN">Pitcairn Adaları</option>
                                                                                                    <option value="POL">Polonya</option>
                                                                                                    <option value="PRT">Portekiz</option>
                                                                                                    <option value="PRI">Porto Riko</option>
                                                                                                    <option value="QAT">Katar</option>
                                                                                                    <option value="REU">Reunion</option>
                                                                                                    <option value="ROM">Romanya</option>
                                                                                                    <option value="RUS">Rusya</option>
                                                                                                    <option value="RWA">Ruanda</option>
                                                                                                    <option value="BLM">Saint Barthelemy</option>
                                                                                                    <option value="SHN">Saint Helena</option>
                                                                                                    <option value="KNA">Saint Kitts ve Nevis</option>
                                                                                                    <option value="LCA">Saint Lucia</option>
                                                                                                    <option value="MAF">Saint Martin</option>
                                                                                                    <option value="SPM">Saint Pierre ve Miquelon</option>
                                                                                                    <option value="VCT">Saint Vincent ve Grenadinler</option>
                                                                                                    <option value="WSM">Samoa</option>
                                                                                                    <option value="SMR">San Marino</option>
                                                                                                    <option value="STP">Sao Tome ve Principe</option>
                                                                                                    <option value="SAU">Suudi Arabistan</option>
                                                                                                    <option value="SEN">Senegal</option>
                                                                                                    <option value="SRB">Sırbistan</option>
                                                                                                    <option value="SCG">Sırbistan</option>
                                                                                                    <option value="SYC">Seyşeller</option>
                                                                                                    <option value="SLE">Sierra Leone</option>
                                                                                                    <option value="SGP">Singapur</option>
                                                                                                    <option value="SXM">Sint Maarten</option>
                                                                                                    <option value="SVK">Slovakya</option>
                                                                                                    <option value="SVN">Slovenya</option>
                                                                                                    <option value="SLB">Solomon Adaları</option>
                                                                                                    <option value="SOM">Somali</option>
                                                                                                    <option value="ZAF">Güney Afrika</option>
                                                                                                    <option value="SGS">Güney Georgia ve Güney Sandwich Adaları</option>
                                                                                                    <option value="SSD">Güney Sudan</option>
                                                                                                    <option value="ESP">İspanya</option>
                                                                                                    <option value="LKA">Sri Lanka</option>
                                                                                                    <option value="SDN">Sudan</option>
                                                                                                    <option value="SUR">Surinam</option>
                                                                                                    <option value="SJM">Svalbard ve Jan Mayen</option>
                                                                                                    <option value="SWZ">Esvatini</option>
                                                                                                    <option value="SWE">İsveç</option>
                                                                                                    <option value="CHE">İsviçre</option>
                                                                                                    <option value="SYR">Suriye</option>
                                                                                                    <option value="TWN">Tayvan</option>
                                                                                                    <option value="TJK">Tacikistan</option>
                                                                                                    <option value="TZA">Tanzanya</option>
                                                                                                    <option value="THA">Tayland</option>
                                                                                                    <option value="TLS">Timor-Leste</option>
                                                                                                    <option value="TGO">Togo</option>
                                                                                                    <option value="TKL">Tokelau</option>
                                                                                                    <option value="TON">Tonga</option>
                                                                                                    <option value="TTO">Trinidad ve Tobago</option>
                                                                                                    <option value="TUN">Tunus</option>
                                                                                                    <option value="TUR" disabled>Türkiye</option>
                                                                                                    <option value="TKM">Türkmenistan</option>
                                                                                                    <option value="TCA">Turks ve Caicos Adaları</option>
                                                                                                    <option value="TUV">Tuvalu</option>
                                                                                                    <option value="UGA">Uganda</option>
                                                                                                    <option value="UKR">Ukrayna</option>
                                                                                                    <option value="ARE">Birleşik Arap Emirlikleri</option>
                                                                                                    <option value="GBR">Birleşik Krallık</option>
                                                                                                    <option value="USA">Amerika Birleşik Devletleri</option>
                                                                                                    <option value="UMI">ABD Küçük Harici Adaları</option>
                                                                                                    <option value="URY">Uruguay</option>
                                                                                                    <option value="UZB">Özbekistan</option>
                                                                                                    <option value="VUT">Vanuatu</option>
                                                                                                    <option value="VEN">Venezuela</option>
                                                                                                    <option value="VNM">Vietnam</option>
                                                                                                    <option value="VGB">Britanya Virjin Adaları</option>
                                                                                                    <option value="VIR">ABD Virjin Adaları</option>
                                                                                                    <option value="WLF">Wallis ve Futuna</option>
                                                                                                    <option value="ESH">Batı Sahra</option>
                                                                                                    <option value="YEM">Yemen</option>
                                                                                                    <option value="ZMB">Zambiya</option>
                                                                                                    <option value="ZWE">Zimbabve</option>
                                                                                            </select>
                                                                                        </div  >
                                                                                    </div>

                                                                                </div>
                                                                                <div class="col-md-2 m-3 button-flex">
                                                                                    <button class="btn-inline color-darkgreen">Ödeme Yap</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="bank-card col-6 col-md-3">
                                                            <span>
                                                                <img
                                                                    src="{{ asset('/public/front/images/card-logo/gpay-yd-cover.png') }}">
                                                                <div class="tagline">
                                                                    <div class="tag-inline">
                                                                        <p></p>
                                                                    </div>
                                                                </div>
                                                            </span>

                                                            <div class="bankinfo">
                                                                <div class="bankinfo-left-col">
                                                                    <img
                                                                        src="{{ asset('/public/front/images/card-logo/gpay-logo.png') }}">
                                                                </div>
                                                                <div class="bankinfo-right-col">
                                                                    <div class="row">

                                                                        <div class="bank-info col-12">
                                                                            <span>%2.7 komisyon ile Gpay ile güvenli bir
                                                                                şekilde yurtdışından tüm banka ve kredi
                                                                                kartlarınızla yükleme yapabilirsiniz.</span>
                                                                        </div>


                                                                        <div class="col-12 payOnlineWrapper">
                                                                            <form id="payOnline" method="post" action="{{ route('odeme_yap') }}" target="_blank" autocomplete="off">
                                                                                @csrf
                                                                                <input type="hidden" name="tur" value="9">
                                                                                <input type="hidden" name="selected_payment" value="krediKarti">
                                                                                <input type="hidden" name="channel" value="14">
                                                                                <div class="payOnlinefooter">
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Siteye YatırmakİstediğinizTutar:</span></label>
                                                                                        <div class="input-group has-validation">
                                                                                            <input id="ode123456" class="form-control style-input tutar1 ode123456" name="tutar" onkeyup="hesaplaGpayYurtdisi()" placeholder="00">
                                                                                            <i>,</i>
                                                                                            <input id="ode234567" class="form-control style-input tutar2 ode234567" name="tutar2" onkeyup="hesaplaGpayYurtdisi()" placeholder="00">
                                                                                            <span class="input-group-text style-input" id="inputGroupPrepend"><span class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Toplam ÖdenecekTutar:</span></label>
                                                                                        <div class="input-group has-validation">
                                                                                            <input id="odeTutar123456" class="form-control style-input tutar1 odeTutar123456" readonly>
                                                                                            <i class="disabled">,</i>
                                                                                            <input id="odeTutar234567" class="form-control style-input tutar2 odeTutar234567" readonly>
                                                                                            <span class="input-group-text style-input" id="inputGroupPrepend">
                                                                                                <span class="moneysymbol">₺</span>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                                <div class="row m-1" >
                                                                                    <div class="col-6">
                                                                                        <select id="country" name="country" required class="form-control"><option value="sec">Ülkenizi Seçin</option>
                                                                                            <option value="AFG">Afganistan</option>
                                                                                            <option value="ALA">Åland Adaları</option>
                                                                                            <option value="ALB">Arnavutluk</option>
                                                                                            <option value="DZA">Cezayir</option>
                                                                                            <option value="ASM">Amerikan Samoası</option>
                                                                                            <option value="AND">Andorra</option>
                                                                                            <option value="AGO">Angola</option>
                                                                                            <option value="AIA">Anguilla</option>
                                                                                            <option value="ATA">Antarktika</option>
                                                                                            <option value="ATG">Antigua ve Barbuda</option>
                                                                                            <option value="ARG">Arjantin</option>
                                                                                            <option value="ARM">Ermenistan</option>
                                                                                            <option value="ABW">Aruba</option>
                                                                                            <option value="AUS">Avustralya</option>
                                                                                            <option value="AUT">Avusturya</option>
                                                                                            <option value="AZE">Azerbaycan</option>
                                                                                            <option value="BHS">Bahamalar</option>
                                                                                            <option value="BHR">Bahreyn</option>
                                                                                            <option value="BGD">Bangladeş</option>
                                                                                            <option value="BRB">Barbados</option>
                                                                                            <option value="BLR">Belarus</option>
                                                                                            <option value="BEL">Belçika</option>
                                                                                            <option value="BLZ">Belize</option>
                                                                                            <option value="BEN">Benin</option>
                                                                                            <option value="BMU">Bermuda</option>
                                                                                            <option value="BTN">Butan</option>
                                                                                            <option value="BOL">Bolivya</option>
                                                                                            <option value="BES">Karayip Hollandası</option>
                                                                                            <option value="BIH">Bosna-Hersek</option>
                                                                                            <option value="BWA">Botsvana</option>
                                                                                            <option value="BVT">Bouvet Adası</option>
                                                                                            <option value="BRA">Brezilya</option>
                                                                                            <option value="IOT">Britanya Hint Okyanusu Toprakları</option>
                                                                                            <option value="BRN">Brunei</option>
                                                                                            <option value="BGR">Bulgaristan</option>
                                                                                            <option value="BFA">Burkina Faso</option>
                                                                                            <option value="BDI">Burundi</option>
                                                                                            <option value="KHM">Kamboçya</option>
                                                                                            <option value="CMR">Kamerun</option>
                                                                                            <option value="CAN">Kanada</option>
                                                                                            <option value="CPV">Cape Verde</option>
                                                                                            <option value="CYM">Cayman Adaları</option>
                                                                                            <option value="CAF">Orta Afrika Cumhuriyeti</option>
                                                                                            <option value="TCD">Çad</option>
                                                                                            <option value="CHL">Şili</option>
                                                                                            <option value="CHN">Çin</option>
                                                                                            <option value="CXR">Christmas Adası</option>
                                                                                            <option value="CCK">Cocos (Keeling) Adaları</option>
                                                                                            <option value="COL">Kolombiya</option>
                                                                                            <option value="COM">Komorlar</option>
                                                                                            <option value="COG">Kongo - Brazavil</option>
                                                                                            <option value="COD">Kongo - Kinşasa</option>
                                                                                            <option value="COK">Cook Adaları</option>
                                                                                            <option value="CRI">Kosta Rika</option>
                                                                                            <option value="CIV">Côte d’Ivoire</option>
                                                                                            <option value="HRV">Hırvatistan</option>
                                                                                            <option value="CUB">Küba</option>
                                                                                            <option value="CUW">Curaçao</option>
                                                                                            <option value="CYP">Kıbrıs</option>
                                                                                            <option value="CZE">Çekya</option>
                                                                                            <option value="DNK">Danimarka</option>
                                                                                            <option value="DJI">Cibuti</option>
                                                                                            <option value="DMA">Dominika</option>
                                                                                            <option value="DOM">Dominik Cumhuriyeti</option>
                                                                                            <option value="ECU">Ekvador</option>
                                                                                            <option value="EGY">Mısır</option>
                                                                                            <option value="SLV">El Salvador</option>
                                                                                            <option value="GNQ">Ekvator Ginesi</option>
                                                                                            <option value="ERI">Eritre</option>
                                                                                            <option value="EST">Estonya</option>
                                                                                            <option value="ETH">Etiyopya</option>
                                                                                            <option value="FLK">Falkland Adaları (Malvinas Adaları)</option>
                                                                                            <option value="FRO">Faroe Adaları</option>
                                                                                            <option value="FJI">Fiji</option>
                                                                                            <option value="FIN">Finlandiya</option>
                                                                                            <option value="FRA">Fransa</option>
                                                                                            <option value="GUF">Fransız Guyanası</option>
                                                                                            <option value="PYF">Fransız Polinezyası</option>
                                                                                            <option value="ATF">Fransız Güney Toprakları</option>
                                                                                            <option value="GAB">Gabon</option>
                                                                                            <option value="GMB">Gambiya</option>
                                                                                            <option value="GEO">Gürcistan</option>
                                                                                            <option value="DEU">Almanya</option>
                                                                                            <option value="GHA">Gana</option>
                                                                                            <option value="GIB">Cebelitarık</option>
                                                                                            <option value="GRC">Yunanistan</option>
                                                                                            <option value="GRL">Grönland</option>
                                                                                            <option value="GRD">Grenada</option>
                                                                                            <option value="GLP">Guadeloupe</option>
                                                                                            <option value="GUM">Guam</option>
                                                                                            <option value="GTM">Guatemala</option>
                                                                                            <option value="GGY">Guernsey</option>
                                                                                            <option value="GIN">Gine</option>
                                                                                            <option value="GNB">Gine-Bissau</option>
                                                                                            <option value="GUY">Guyana</option>
                                                                                            <option value="HTI">Haiti</option>
                                                                                            <option value="HMD">Heard Adası ve McDonald Adaları</option>
                                                                                            <option value="VAT">Vatikan</option>
                                                                                            <option value="HND">Honduras</option>
                                                                                            <option value="HKG">Hong Kong</option>
                                                                                            <option value="HUN">Macaristan</option>
                                                                                            <option value="ISL">İzlanda</option>
                                                                                            <option value="IND">Hindistan</option>
                                                                                            <option value="IDN">Endonezya</option>
                                                                                            <option value="IRN">İran</option>
                                                                                            <option value="IRQ">Irak</option>
                                                                                            <option value="IRL">İrlanda</option>
                                                                                            <option value="IMN">Man Adası</option>
                                                                                            <option value="ISR">İsrail</option>
                                                                                            <option value="ITA">İtalya</option>
                                                                                            <option value="JAM">Jamaika</option>
                                                                                            <option value="JPN">Japonya</option>
                                                                                            <option value="JEY">Jersey</option>
                                                                                            <option value="JOR">Ürdün</option>
                                                                                            <option value="KAZ">Kazakistan</option>
                                                                                            <option value="KEN">Kenya</option>
                                                                                            <option value="KIR">Kiribati</option>
                                                                                            <option value="PRK">Kuzey Kore</option>
                                                                                            <option value="KOR">Güney Kore</option>
                                                                                            <option value="XKX">Kosova</option>
                                                                                            <option value="KWT">Kuveyt</option>
                                                                                            <option value="KGZ">Kırgızistan</option>
                                                                                            <option value="LAO">Laos</option>
                                                                                            <option value="LVA">Letonya</option>
                                                                                            <option value="LBN">Lübnan</option>
                                                                                            <option value="LSO">Lesotho</option>
                                                                                            <option value="LBR">Liberya</option>
                                                                                            <option value="LBY">Libya</option>
                                                                                            <option value="LIE">Liechtenstein</option>
                                                                                            <option value="LTU">Litvanya</option>
                                                                                            <option value="LUX">Lüksemburg</option>
                                                                                            <option value="MAC">Makao</option>
                                                                                            <option value="MKD">Kuzey Makedonya</option>
                                                                                            <option value="MDG">Madagaskar</option>
                                                                                            <option value="MWI">Malavi</option>
                                                                                            <option value="MYS">Malezya</option>
                                                                                            <option value="MDV">Maldivler</option>
                                                                                            <option value="MLI">Mali</option>
                                                                                            <option value="MLT">Malta</option>
                                                                                            <option value="MHL">Marshall Adaları</option>
                                                                                            <option value="MTQ">Martinik</option>
                                                                                            <option value="MRT">Moritanya</option>
                                                                                            <option value="MUS">Mauritius</option>
                                                                                            <option value="MYT">Mayotte</option>
                                                                                            <option value="MEX">Meksika</option>
                                                                                            <option value="FSM">Mikronezya</option>
                                                                                            <option value="MDA">Moldova</option>
                                                                                            <option value="MCO">Monako</option>
                                                                                            <option value="MNG">Moğolistan</option>
                                                                                            <option value="MNE">Karadağ</option>
                                                                                            <option value="MSR">Montserrat</option>
                                                                                            <option value="MAR">Fas</option>
                                                                                            <option value="MOZ">Mozambik</option>
                                                                                            <option value="MMR">Myanmar (Burma)</option>
                                                                                            <option value="NAM">Namibya</option>
                                                                                            <option value="NRU">Nauru</option>
                                                                                            <option value="NPL">Nepal</option>
                                                                                            <option value="NLD">Hollanda</option>
                                                                                            <option value="ANT">Curaçao</option>
                                                                                            <option value="NCL">Yeni Kaledonya</option>
                                                                                            <option value="NZL">Yeni Zelanda</option>
                                                                                            <option value="NIC">Nikaragua</option>
                                                                                            <option value="NER">Nijer</option>
                                                                                            <option value="NGA">Nijerya</option>
                                                                                            <option value="NIU">Niue</option>
                                                                                            <option value="NFK">Norfolk Adası</option>
                                                                                            <option value="MNP">Kuzey Mariana Adaları</option>
                                                                                            <option value="NOR">Norveç</option>
                                                                                            <option value="OMN">Umman</option>
                                                                                            <option value="PAK">Pakistan</option>
                                                                                            <option value="PLW">Palau</option>
                                                                                            <option value="PSE">Filistin</option>
                                                                                            <option value="PAN">Panama</option>
                                                                                            <option value="PNG">Papua Yeni Gine</option>
                                                                                            <option value="PRY">Paraguay</option>
                                                                                            <option value="PER">Peru</option>
                                                                                            <option value="PHL">Filipinler</option>
                                                                                            <option value="PCN">Pitcairn Adaları</option>
                                                                                            <option value="POL">Polonya</option>
                                                                                            <option value="PRT">Portekiz</option>
                                                                                            <option value="PRI">Porto Riko</option>
                                                                                            <option value="QAT">Katar</option>
                                                                                            <option value="REU">Reunion</option>
                                                                                            <option value="ROM">Romanya</option>
                                                                                            <option value="RUS">Rusya</option>
                                                                                            <option value="RWA">Ruanda</option>
                                                                                            <option value="BLM">Saint Barthelemy</option>
                                                                                            <option value="SHN">Saint Helena</option>
                                                                                            <option value="KNA">Saint Kitts ve Nevis</option>
                                                                                            <option value="LCA">Saint Lucia</option>
                                                                                            <option value="MAF">Saint Martin</option>
                                                                                            <option value="SPM">Saint Pierre ve Miquelon</option>
                                                                                            <option value="VCT">Saint Vincent ve Grenadinler</option>
                                                                                            <option value="WSM">Samoa</option>
                                                                                            <option value="SMR">San Marino</option>
                                                                                            <option value="STP">Sao Tome ve Principe</option>
                                                                                            <option value="SAU">Suudi Arabistan</option>
                                                                                            <option value="SEN">Senegal</option>
                                                                                            <option value="SRB">Sırbistan</option>
                                                                                            <option value="SCG">Sırbistan</option>
                                                                                            <option value="SYC">Seyşeller</option>
                                                                                            <option value="SLE">Sierra Leone</option>
                                                                                            <option value="SGP">Singapur</option>
                                                                                            <option value="SXM">Sint Maarten</option>
                                                                                            <option value="SVK">Slovakya</option>
                                                                                            <option value="SVN">Slovenya</option>
                                                                                            <option value="SLB">Solomon Adaları</option>
                                                                                            <option value="SOM">Somali</option>
                                                                                            <option value="ZAF">Güney Afrika</option>
                                                                                            <option value="SGS">Güney Georgia ve Güney Sandwich Adaları</option>
                                                                                            <option value="SSD">Güney Sudan</option>
                                                                                            <option value="ESP">İspanya</option>
                                                                                            <option value="LKA">Sri Lanka</option>
                                                                                            <option value="SDN">Sudan</option>
                                                                                            <option value="SUR">Surinam</option>
                                                                                            <option value="SJM">Svalbard ve Jan Mayen</option>
                                                                                            <option value="SWZ">Esvatini</option>
                                                                                            <option value="SWE">İsveç</option>
                                                                                            <option value="CHE">İsviçre</option>
                                                                                            <option value="SYR">Suriye</option>
                                                                                            <option value="TWN">Tayvan</option>
                                                                                            <option value="TJK">Tacikistan</option>
                                                                                            <option value="TZA">Tanzanya</option>
                                                                                            <option value="THA">Tayland</option>
                                                                                            <option value="TLS">Timor-Leste</option>
                                                                                            <option value="TGO">Togo</option>
                                                                                            <option value="TKL">Tokelau</option>
                                                                                            <option value="TON">Tonga</option>
                                                                                            <option value="TTO">Trinidad ve Tobago</option>
                                                                                            <option value="TUN">Tunus</option>
                                                                                            <option value="TUR" disabled>Türkiye</option>
                                                                                            <option value="TKM">Türkmenistan</option>
                                                                                            <option value="TCA">Turks ve Caicos Adaları</option>
                                                                                            <option value="TUV">Tuvalu</option>
                                                                                            <option value="UGA">Uganda</option>
                                                                                            <option value="UKR">Ukrayna</option>
                                                                                            <option value="ARE">Birleşik Arap Emirlikleri</option>
                                                                                            <option value="GBR">Birleşik Krallık</option>
                                                                                            <option value="USA">Amerika Birleşik Devletleri</option>
                                                                                            <option value="UMI">ABD Küçük Harici Adaları</option>
                                                                                            <option value="URY">Uruguay</option>
                                                                                            <option value="UZB">Özbekistan</option>
                                                                                            <option value="VUT">Vanuatu</option>
                                                                                            <option value="VEN">Venezuela</option>
                                                                                            <option value="VNM">Vietnam</option>
                                                                                            <option value="VGB">Britanya Virjin Adaları</option>
                                                                                            <option value="VIR">ABD Virjin Adaları</option>
                                                                                            <option value="WLF">Wallis ve Futuna</option>
                                                                                            <option value="ESH">Batı Sahra</option>
                                                                                            <option value="YEM">Yemen</option>
                                                                                            <option value="ZMB">Zambiya</option>
                                                                                            <option value="ZWE">Zimbabve</option>
                                                                                        </select>
                                                                                    </div  >
                                                                                </div>
                                                                                <div class="col-md-2 m-3 button-flex">
                                                                                    <button class="btn-inline color-darkgreen">Ödeme Yap</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="bank-payment-form">

                                                        </div>


                                                    </div>

                                                </div>


                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="havale-eft" role="tabpanel"
                                        aria-labelledby="havale-eft-tab">
                                        <div class="havale-container">
                                            <div class="custom-bank-select">


                                                <div class="bank-list">
                                                    <div class="row">
                                                        @foreach (DB::table('payment_channels_eft')->whereNull('deleted_at')->where('status', '1')->get() as $u)
                                                            <div class="bank-card colflex">
                                                                <span>
                                                                    <img
                                                                        src="{{ asset('/public/front/bank_logo/' . $u->image) }}">
                                                                    <div class="tagline">
                                                                        <div class="tag-inline">
                                                                            <p>{{ $u->text }}</p>
                                                                        </div>
                                                                    </div>
                                                                </span>

                                                                <div class="bankinfo">
                                                                    <div class="bankinfo-left-col">
                                                                        <img src="">
                                                                    </div>
                                                                    <div class="bankinfo-right-col">
                                                                        <div class="row">

                                                                            <div class="bank-info col-12 col-md-6">
                                                                                <span>Alıcı :</span>
                                                                                <span><input type="text" readonly=""
                                                                                        value="{{ $u->alici }}"></span>
                                                                                <span>
                                                                                    <div class="clipboard"><i></i><i></i>
                                                                                    </div>
                                                                                </span>
                                                                            </div>
                                                                            <div class="bank-info col-12 col-md-6">
                                                                                <span>İban Numarası :</span>
                                                                                <span><input type="text" readonly=""
                                                                                        value="{{ $u->iban }}"></span>
                                                                                <span>
                                                                                    <div class="clipboard"><i></i><i></i>
                                                                                    </div>
                                                                                </span>
                                                                            </div>
                                                                            <div class="bank-info col-12 col-md-6">
                                                                                <span>Şube :</span>
                                                                                <span><input type="text" readonly=""
                                                                                        value="{{ $u->sube }}"></span>
                                                                                <span>
                                                                                    <div class="clipboard"><i></i><i></i>
                                                                                    </div>
                                                                                </span>
                                                                            </div>
                                                                            <div class="bank-info col-12 col-md-6">
                                                                                <span>Hesap No :</span>
                                                                                <span><input type="text" readonly=""
                                                                                        value="{{ $u->hesap }}"></span>
                                                                                <span>
                                                                                    <div class="clipboard"><i></i><i></i>
                                                                                    </div>
                                                                                </span>
                                                                            </div>


                                                                            <div class="col-12 payOnlineWrapper">
                                                                                <form id="payOnline" method="post"
                                                                                    action="{{ route('odeme_yap') }}"
                                                                                    target="_blank" autocomplete="off">
                                                                                    @csrf
                                                                                    @if ($u->channel_type == 2)
                                                                                        <input type="hidden"
                                                                                            name="tur" value="2">
                                                                                    @elseif($u->channel_type == 15)
                                                                                        <input type="hidden"
                                                                                            name="tur" value="9">
                                                                                        <input type="hidden"
                                                                                            name="channel" value="15">
                                                                                        <input type="hidden"
                                                                                            name="selected_payment"
                                                                                            value="havale">
                                                                                    @endif
                                                                                    <input type="hidden" name="banka"
                                                                                        value="{{ $u->id }}">
                                                                                    <div class="payOnlinefooter">
                                                                                        <label><span>Gönderilen
                                                                                                Tutar:</span></label>
                                                                                                <div class="pulseIt">
                                                                                                    <div
                                                                                                    class="input-group has-validation ">
                                                                                                    <input id="1"
                                                                                                        class="form-control style-input tutar1"
                                                                                                        name="tutar"
                                                                                                        placeholder="00">
                                                                                                    <i>,</i>
                                                                                                    <input id="1"
                                                                                                        class="form-control style-input tutar2"
                                                                                                        name="tutar2"
                                                                                                        placeholder="00">
                                                                                                    <span
                                                                                                        class="input-group-text style-input"
                                                                                                        id="inputGroupPrepend"><span
                                                                                                            class="moneysymbol">₺</span></span>
                                                                                                </div>
                                                                                                </div>

                                                                                        <button
                                                                                            class="btn-inline color-darkgreen"
                                                                                            style="margin-left: auto;">
                                                                                            Ödeme Bildir
                                                                                        </button>
                                                                                    </div>

                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <div class="bank-payment-form">

                                                        </div>


                                                    </div>

                                                </div>


                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="hediye" role="tabpanel"
                                        aria-labelledby="hediye-tab">
                                        <div class="card">
                                            <div class="card-body">
                                                <form id="payOnline" method="post" action="{{ route('odeme_yap') }}"
                                                    autocomplete="off">
                                                    @csrf
                                                    <input type="hidden" name="tur" value="3">
                                                    <div class="row">
                                                        <div class="col-md-8 mb-4">
                                                            <label class="form-label" for="kod">Hediye Kodu</label>
                                                            <input type="text" id="kod" name="kod"
                                                                class="form-control style-input"
                                                                placeholder="XXXX-XXXX-XXXX-XXXX">
                                                        </div>
                                                        <div class="col-md-4 btn-in-content">
                                                            <button class="btn-inline color-darkgreen">
                                                                Kodu Kullan
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="kripto" role="tabpanel"
                                        aria-labelledby="kripto-tab">
                                        <div class="havale-container">
                                            <div class="custom-bank-select">


                                                <div class="bank-list">
                                                    <div class="row">
                                                        @foreach (DB::table('payment_channels_crypto')->whereNull('deleted_at')->get() as $u)
                                                            <div class="bank-card col-6 col-md-3 col-lg-2"
                                                                style="z-index: 2">
                                                                <span>
                                                                    <img
                                                                        src="{{ asset('/public/front/crypto_logo/' . $u->image) }}">
                                                                    <div class="tagline">
                                                                        <div class="tag-inline">
                                                                            <p>Lütfen canlı destek üzerinden cüzdan talep
                                                                                ediniz</p>
                                                                        </div>
                                                                    </div>
                                                                </span>

                                                                <div class="bankinfo">
                                                                    <div class="bankinfo-left-col">
                                                                        <img src="">
                                                                    </div>
                                                                    <div class="bankinfo-right-col">
                                                                        <div class="row">

                                                                            <div class="bank-info col-12">
                                                                                <span>Cüzdan Numarası :</span>
                                                                                <span><input type="text" readonly=""
                                                                                        value="Lütfen canlı destek üzerinden cüzdan talep ediniz"></span>
                                                                                <span>
                                                                                    <div class="clipboard"><i></i><i></i>
                                                                                    </div>
                                                                                </span>
                                                                            </div>


                                                                            <div class="col-12 payOnlineWrapper">
                                                                                <form id="payOnline" method="post"
                                                                                    action="{{ route('odeme_yap') }}"
                                                                                    autocomplete="off">
                                                                                    @csrf
                                                                                    <input type="hidden" name="tur"
                                                                                        value="4">
                                                                                    <input type="hidden" name="crypto"
                                                                                        value="{{ $u->id }}">
                                                                                    <div class="payOnlinefooter">
                                                                                        <label><span>Gönderilen
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="1"
                                                                                                class="form-control style-input tutar1"
                                                                                                name="tutar"
                                                                                                placeholder="0000">
                                                                                            <i>,</i>
                                                                                            <input id="1"
                                                                                                class="form-control style-input tutar2"
                                                                                                name="tutar2"
                                                                                                placeholder="00">
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                        <button
                                                                                            class="btn-inline color-darkgreen">
                                                                                            Bildir
                                                                                        </button>
                                                                                    </div>

                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <div class="bank-payment-form">

                                                        </div>


                                                    </div>

                                                </div>


                                            </div>

                                        </div>
                                    </div>
                                    @if (Auth::user()->id == 2)
                                        <div class="tab-pane fade" id="mobile" role="tabpanel"
                                            aria-labelledby="mobile-tab">
                                            <div class="havale-container">
                                                <div class="custom-bank-select">

                                                    <div class="bank-list">
                                                        <div class="row">

                                                            <div class="bank-card col-6 col-md-3">
                                                                <span>
                                                                    <img
                                                                        src="{{ asset('/public/front/mobile_logo/paybyme_logo_transparent.png') }}">
                                                                    <div class="tagline">
                                                                        <div class="tag-inline">
                                                                            <p></p>
                                                                        </div>
                                                                    </div>
                                                                </span>

                                                                <div class="bankinfo">
                                                                    <div class="bankinfo-left-col">
                                                                        <img src="">
                                                                    </div>
                                                                    <div class="bankinfo-right-col">
                                                                        <div class="row">

                                                                            <div class="bank-info col-12">
                                                                                <span>PayByMe ile mobil ödeme
                                                                                    gerçekleştirebilirsiniz.</span>
                                                                                <span></span>
                                                                                <span></span>
                                                                            </div>


                                                                            <div class="col-12 payOnlineWrapper">
                                                                                <form id="payOnline" method="post"
                                                                                    action="{{ route('odeme_yap') }}"
                                                                                    target="_blank" autocomplete="off">
                                                                                    @csrf
                                                                                    <input type="hidden" name="tur"
                                                                                        value="5">
                                                                                    <div class="payOnlinefooter">
                                                                                        <label><span>Ödemek İstediğiniz
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="1"
                                                                                                class="form-control style-input tutar1"
                                                                                                name="tutar"
                                                                                                placeholder="0000">
                                                                                            <i>,</i>
                                                                                            <input id="1"
                                                                                                class="form-control style-input tutar2"
                                                                                                name="tutar2"
                                                                                                placeholder="00">
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                        <button
                                                                                            class="btn-inline color-darkgreen">
                                                                                            Ödeme Yap
                                                                                        </button>
                                                                                    </div>

                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="bank-payment-form">

                                                            </div>


                                                        </div>

                                                    </div>


                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                    <div class="tab-pane fade" id="diger" role="tabpanel"
                                        aria-labelledby="diger-tab">
                                        <div class="card">
                                            <div class="card-body">
                                                <form id="payOnline" method="post" action="{{ route('odeme_yap') }}"
                                                    autocomplete="off">
                                                    @csrf
                                                    <input type="hidden" name="tur" value="7">
                                                    @php
                                                        $ibans = ['TR67 0001 0017 7697 5034 2350 01', 'TR20 0011 1000 0000 0104 0346 85', 'TR31 0001 5001 5800 7313 2408 58', 'TR84 0020 5000 0980 2728 8000 01'];
                                                        $banks = ['Ziraat Bankası', 'QNB Finansbank', 'Vakıfbank', 'Kuveyt Türk'];
                                                    @endphp
                                                    @if ($userData->refId == 27156)
                                                        <div class="row mb-5"
                                                            style="padding: 12px 0px;border: 1px solid #ccc">
                                                            <div class="col-md-3">
                                                            </div>
                                                            <div class="col-md-6 mb-2" style="text-align: center">
                                                                <label>Hesap Sahibi</label>
                                                                <input class="form-control" placeholder="IBAN" readonly
                                                                    value="OYUNEKS BİLİŞİM VE OYUN HİZMETLERİ LİMİTED ŞİRKETİ">
                                                            </div>
                                                            <div class="col-md-3">
                                                            </div>
                                                            @foreach ($ibans as $k => $iban)
                                                                <div class="col-md-6">
                                                                    <label>{{ @$banks[$k] }}</label>
                                                                    <input class="form-control" placeholder="IBAN"
                                                                        readonly value="{{ $iban }}">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="tcno">T.C. No (T.C. No sahibi değilseniz 11
                                                                adet
                                                                1 koyunuz)</label>
                                                            <input type="number" class="form-control"
                                                                placeholder="T.C. No" name="tcno">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="tcno">Ödeme Tutarınız</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="tutar" placeholder="0.00">
                                                        </div>
                                                        <div class="col-md-12 mt-3">
                                                            <label for="tcno">Ödeme Kanalı</label>
                                                            <select name="odeme_kanali" class="form-control">

                                                                @foreach (DB::table('payment_channels_diger')->whereNull('deleted_at')->get() as $k => $u)
                                                                    <option value="{{ $u->title }}">
                                                                        {{ $u->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mt-3">
                                                            <label for="tcno">Ödeme Açıklaması</label>
                                                            <input type="text" class="form-control" name="aciklama"
                                                                placeholder="Ödeme açıklamanız">
                                                        </div>
                                                        <div class="col-md-4 offset-md-4 mt-3">
                                                            <button class="btn-inline w-100 color-darkgreen">
                                                                Ödemeyi Gönder
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="papara" role="tabpanel"
                                        aria-labelledby="papara-tab">
                                        <div class="havale-container">
                                            <div class="custom-bank-select">

                                                <div class="bank-list">
                                                    <div class="row">

                                                        <div class="bank-card col-6 col-md-3" style="z-index: 2">
                                                            <span>
                                                                <img
                                                                    src="{{ asset('/public/front/images/card-logo/papara-cover.png') }}">
                                                                <div class="tagline">
                                                                    <div class="tag-inline">
                                                                        <p>Sadece kendi adınıza kayıtlı Papara hesabından
                                                                            ödeme yapabilirsiniz.</p>
                                                                    </div>
                                                                </div>
                                                            </span>

                                                            <div class="bankinfo">
                                                                <div class="bankinfo-left-col">
                                                                    <img
                                                                        src="{{ asset('/public/front/images/card-logo/paparaLogo2.png') }}">
                                                                </div>
                                                                <div class="bankinfo-right-col">
                                                                    <div class="row">

                                                                        <div class="bank-info col-12">
                                                                            <span>%2 komisyon ile Papara üzerinden güvenli
                                                                                bir şekilde yükleme yapabilirsiniz.</span>

                                                                        </div>


                                                                        <div class="col-12 payOnlineWrapper">
                                                                            <form id="payOnline" method="post"
                                                                                action="{{ route('odeme_yap') }}"
                                                                                target="_blank" autocomplete="off">
                                                                                @csrf
                                                                                <input type="hidden" name="tur"
                                                                                    value="8">
                                                                                <div class="payOnlinefooter">
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Siteye Yatırmak
                                                                                                İstediğiniz
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="ode1111"
                                                                                                class="form-control style-input tutar1 ode1111"
                                                                                                name="tutar"
                                                                                                onkeyup="hesaplaPapara(this)"
                                                                                                placeholder="00">
                                                                                            <i>,</i>
                                                                                            <input id="ode2222"
                                                                                                class="form-control style-input tutar2 ode2222"
                                                                                                name="tutar2"
                                                                                                onkeyup="hesaplaPapara(this)"
                                                                                                placeholder="00">
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-5 mb-4">
                                                                                        <label><span>Toplam Ödenecek
                                                                                                Tutar:</span></label>
                                                                                        <div
                                                                                            class="input-group has-validation">
                                                                                            <input id="odeTutar11"
                                                                                                class="form-control style-input tutar1 odeTutar11"
                                                                                                readonly>
                                                                                            <i class="disabled">,</i>
                                                                                            <input id="odeTutar22"
                                                                                                class="form-control style-input tutar2 odeTutar22"
                                                                                                readonly>
                                                                                            <span
                                                                                                class="input-group-text style-input"
                                                                                                id="inputGroupPrepend"><span
                                                                                                    class="moneysymbol">₺</span></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2 mb-4 button-flex">
                                                                                        <button
                                                                                            class="btn-inline color-darkgreen">
                                                                                            Ödeme Yap
                                                                                        </button>
                                                                                    </div>
                                                                                </div>

                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="bank-payment-form">

                                                        </div>


                                                    </div>

                                                </div>


                                            </div>

                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="razer" role="tabpanel"
                                        aria-labelledby="razer-tab">
                                        <div class="havale-container">
                                            <div class="custom-bank-select">
                                                <div class="bank-list">
                                                    <div class="row">

                                                        <div class="bank-card col-6 col-md-3" style="z-index: 2">
                                                            <span class="raz_ac"><img
                                                                    src="{{ asset('/public/front/images/card-logo/razer.png') }}"></span>
                                                        </div>

                                                        <div class="row "
                                                            @if (@$_GET['test']) @else style="display: none" @endif>
                                                            <div class="col-lg-4">
                                                                <div class="col-12">
                                                                    <label class="mt-3">Razer Platform</label>
                                                                    <select id="plt" class="form-select">
                                                                        <option oran=" " value="sec">Platformu
                                                                            Seçin</option>
                                                                        <option oran="1"
                                                                            value="{{ getCacheSetings()->razertl }}">Razer
                                                                            TL</option>
                                                                        <option oran="{{ getCacheSetings()->razerusdk }}"
                                                                            value="{{ getCacheSetings()->razerusd }}">
                                                                            Razer USD</option>
                                                                        <option oran="{{ getCacheSetings()->razercstk }}"
                                                                            value="{{ getCacheSetings()->razercsto }}">
                                                                            {{ getCacheSetings()->razercstn }}</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="mt-3">Razer Kod</label>
                                                                    <input type="text" name="kod" id="rkod"
                                                                        readonly class="form-control"
                                                                        placeholder="Razer Kod">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="col-12">
                                                                    <label class="mt-3">Razer Kod Tutarı (<span
                                                                            class="text-white kur"
                                                                            style="font-weight: bold"></span>)</label>
                                                                    <input type="number" name="kod_tutar" id="kod_tutar"
                                                                        readonly class="form-control"
                                                                        placeholder="Kod Tutarı">
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="mt-3">Bakiyenize Eklenecek
                                                                        Tutar</label>
                                                                    <input type="text" id="son_tutar"
                                                                        class="form-control" readonly
                                                                        placeholder="Bakiyenize Eklenecek Tutar">

                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col"
                                                                    style="display: flex; justify-content: flex-end">
                                                                    <button
                                                                        class="btn btn-success col-auto mt-3 rzrs">Gönder</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/imask/3.4.0/imask.min.js"></script>
    {{-- <script src="{{asset(env('ROOT').env('FRONT').env('JS').'paycard.js')}}"></script> --}}
    <script src="{{ asset(env('ROOT') . env('FRONT') . env('JS') . 'custom-select.js') }}"></script>
    <script src="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
    <script>
        var kur;
        $('#rkod').click(function() {
            if ($('#rkod').prop('readonly')) {
                swal.fire({
                    icon: 'error',
                    html: 'Öncelikle Platformu Seçin',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
        $('#kod_tutar').click(function() {
            if ($('#kod_tutar').prop('readonly')) {
                swal.fire({
                    icon: 'error',
                    html: 'Öncelikle Platformu Seçin',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
        $('#plt').change(function() {
            kur = $('#plt :selected').attr('oran');
            if ($('#plt').val() == 'sec') {
                $('#rkod, #kod_tutar').attr('readonly', 'readonly');
                $('.kur').text('')
            } else {
                $('#rkod, #kod_tutar').removeAttr('readonly');
                $('.kur').text($('#plt :selected').text() + ' Kuru : ' + kur)
            }
            if (kur > 1) {
                $('#kod_tutar').attr('placeholder', 'Kodunuzun USD değerini girin')
            } else {
                $('#kod_tutar').attr('placeholder', 'Kodunuzun TL değerini girin')
            }

        });
        $('#kod_tutar').keyup(function() {
            $('#son_tutar').val(($('#kod_tutar').val() - ($('#plt').val() * $('#kod_tutar').val() / 100)).toFixed(2)
                .split('.'));
            if (kur != '1') {
                $('#son_tutar').val($('#kod_tutar').val() * kur);
            }
        });
        $('.rzrs').click(function() {
            if ($('#rkod').val() == '') {
                swal.fire({
                    icon: 'error',
                    html: 'Girilen kod hatalı',
                    showConfirmButton: false,
                    timer: 1500
                });
                return
            }
            if ($('#kod_tutar').val() == '') {
                swal.fire({
                    icon: 'error',
                    html: 'Girilen tutar hatalı',
                    showConfirmButton: false,
                    timer: 1500
                });
                return
            }
            $.get('?', {
                razer_kod: $('#rkod').val(),
                razer_tut: $('#kod_tutar').val(),
                plt: $('#plt').val(),
                plt_n: $('#plt :selected').text()
            }, function(x) {
                swal.fire({
                    icon: 'success',
                    html: 'Gönderdiğiniz bilgiler kontrol edildikten sonra tutar bakiyenize eklenecektir.',
                    showConfirmButton: true
                });
            });

        });
        $('.raz_ac').click(function() {
            $('.razp').toggle();
        });


        function ozanhesapla() {
            tutar1 = $(".ozanode1").eq(1).val();
            tutar2 = $(".ozanode2").eq(1).val();
            deger = parseFloat(tutar1 + "." + tutar2);
            komisyon = {{ getCacheSetings()->ozanKomisyon }};
            yeniTutar = (deger / (1 - (komisyon / 100))).toFixed(2).split('.');
            $(".ozanodetutar1").eq(1).val(yeniTutar[0]);
            if (yeniTutar[1] == undefined) {
                $(".ozanodetutar2").eq(1).val("00");
            } else {
                $(".ozanodetutar2").eq(1).val(yeniTutar[1]);
            }
            var amount = String($(".ozanodetutar1").eq(1).val()) + String($(".ozanodetutar2").eq(1).val());
            $(".ozanbutton").attr("href", "{{ route('ozan_send', ['']) }}/" + amount);
        }

        function ozansend() {
            var amount = String($(".ozanodetutar1").eq(1).val()) + '.' + String($(".ozanodetutar2").eq(1).val());
            if (isNaN(parseFloat(amount)) || parseFloat(amount) < 0.01)
                return false;
        }

        function hesapla() {
            tutar1 = $(".ode111").eq(1).val();
            tutar2 = $(".ode222").eq(1).val();
            deger = parseFloat(tutar1 + "." + tutar2);
            komisyon = {{ getCacheSetings()->onlineOdemeKomisyon }};
            yeniTutar = (deger / (1 - (komisyon / 100))).toFixed(2).split('.');
            $(".odeTutar1").eq(1).val(yeniTutar[0]);
            if (yeniTutar[1] == undefined) {
                $(".odeTutar2").eq(1).val("00");
            } else {
                $(".odeTutar2").eq(1).val(yeniTutar[1]);
            }
        }

        function hesaplaYurtdisiPaytr() {
            tutar1 = $(".ode123").eq(1).val();
            tutar2 = $(".ode234").eq(1).val();
            deger = parseFloat(tutar1 + "." + tutar2);
            komisyon = {{ getCacheSetings()->onlineOdemeYurtdisiKomisyon }};
            yeniTutar = (deger / (1 - (komisyon / 100))).toFixed(2).split('.');
            $(".odeTutar123").eq(1).val(yeniTutar[0]);
            if (yeniTutar[1] == undefined) {
                $(".odeTutar234").eq(1).val("00");
            } else {
                $(".odeTutar234").eq(1).val(yeniTutar[1]);
            }
        }
    </script>
    <script>
        function hesaplaPapara(that) {
            var form = $(that).closest('form');
            tutar1 = form.find('.ode1111').val();
            tutar2 = form.find('.ode2222').val();
            deger = parseFloat(tutar1 + "." + tutar2);
            komisyon = {{ getCacheSetings()->paparaKomisyon }};
            yeniTutar = (deger / (1 - (komisyon / 100))).toFixed(2).split('.');

            form.find(".odeTutar11").val(yeniTutar[0]);
            console.log(yeniTutar);
            if (yeniTutar[1] == undefined) {
                form.find(".odeTutar22").val("00");
            } else {
                form.find(".odeTutar22").val(yeniTutar[1]);
            }
        }
    </script>

    <script>
        function hesaplaGpayYurtici() {
            tutar1 = $(".ode11111").eq(1).val();
            tutar2 = $(".ode22222").eq(1).val();
            deger = parseFloat(tutar1 + "." + tutar2);
            komisyon = {{ getCacheSetings()->gpayKomisyon }};
            yeniTutar = (deger / (1 - (komisyon / 100))).toFixed(2).split('.');
            $(".odeTutar11111").eq(1).val(yeniTutar[0]);
            if (yeniTutar[1] == undefined) {
                $(".odeTutar22222").eq(1).val("00");
            } else {
                $(".odeTutar22222").eq(1).val(yeniTutar[1]);
            }
        }

        function hesaplaGpayYurtdisi() {
            tutar1 = $(".ode123456").eq(1).val();
            tutar2 = $(".ode234567").eq(1).val();
            deger = parseFloat(tutar1 + "." + tutar2);
            komisyon = {{ getCacheSetings()->gpayYurtdisiKomisyon }};
            yeniTutar = (deger / (1 - (komisyon / 100))).toFixed(2).split('.');
            $(".odeTutar123456").eq(1).val(yeniTutar[0]);
            if (yeniTutar[1] == undefined) {
                $(".odeTutar234567").eq(1).val("00");
            } else {
                $(".odeTutar234567").eq(1).val(yeniTutar[1]);
            }
        }

        function hesaplaGpayIninal() {
            tutar1 = $(".ode1234").eq(1).val();
            tutar2 = $(".ode2345").eq(1).val();
            deger = parseFloat(tutar1 + "." + tutar2);
            komisyon = {{ getCacheSetings()->ininalKomisyon }};
            yeniTutar = (deger / (1 - (komisyon / 100))).toFixed(2).split('.');
            $(".odeTutar1234").eq(1).val(yeniTutar[0]);
            if (yeniTutar[1] == undefined) {
                $(".odeTutar2345").eq(1).val("00");
            } else {
                $(".odeTutar2345").eq(1).val(yeniTutar[1]);
            }
        }

        function hesaplaGpayBkm() {
            tutar1 = $(".ode12345").eq(1).val();
            tutar2 = $(".ode23456").eq(1).val();
            deger = parseFloat(tutar1 + "." + tutar2);
            komisyon = {{ getCacheSetings()->bkmKomisyon }};
            yeniTutar = (deger / (1 - (komisyon / 100))).toFixed(2).split('.');
            $(".odeTutar12345").eq(1).val(yeniTutar[0]);
            if (yeniTutar[1] == undefined) {
                $(".odeTutar23456").eq(1).val("00");
            } else {
                $(".odeTutar23456").eq(1).val(yeniTutar[1]);
            }
        }
    </script>

    <style>
        .modalWrap {
            position: absolute;
            left: 20%;
            top: 20%;
            background-color: #559B77;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            row-gap: 8px;
            font-size: 16px;
            max-width: 60%;
            color: #FCF7F1;
            font-weight: 500;
            z-index: 11111111;
            text-align: center;
            transform: scale(0);
            transition: 1s;
        }

        .animateModal {
            transform: scale(1);
        }

        .modalButton {
            border: none;
            border-radius: 6px;
            padding: 6px;
            background-color: #27272D;
            color: #D9D9D9;
            font-weight: 500;
        }

        .modalButton:hover {
            background-color: #24265B;
        }

        .bank-payment-form {
            position: relative;
        }

        .heartPulse {
            animation: pulse-heart 2s ease-in-out;
        }

        @keyframes pulse-heart {
            0% {
                transform: scale(1);
            }

            20% {
                transform: scale(1.3);
            }

            40% {
                transform: scale(1.0);
            }

            60% {
                transform: scale(1.3);
            }

            75% {
                transform: scale(1.0);
            }

            90% {
                transform: scale(1.3);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
    <script>
        //bakiye ekle popup //havale-eft section
        function clickBank() {
            console.log("xxx", event.target.closest('.bank-card'), event.target.closest('#havale-eft'))
            if (event.target.closest('.bank-card') && (event.target.closest('#havale-eft') || document.querySelector(
                        '#havale-eft-tab')
                    .classList.contains('active'))) {
                console.log("içinde")
                const modalWrap = document.createElement('div')
                const modalContent = document.createElement('div')
                const modalButton = document.createElement('button')
                modalWrap.className = 'modalWrap'
                modalButton.className = 'modalButton'
                modalContent.innerHTML =
                    'Seçili ibana gönderim yaptıktan sonra, sağ alt kısımda bulunan "Ödeme Bildir" butonu ile bildirim yapınız. Bildirim yapmamanız durumunda hesabınıza bakiye eklenmeyecektir.'
                modalButton.innerHTML = 'Anladım :)'
                modalWrap.appendChild(modalContent);
                modalWrap.appendChild(modalButton);
                event.target.closest('.bank-list').querySelector('.bank-payment-form').appendChild(modalWrap);
                modalButton.addEventListener('click', () => {
                    document.querySelector('.modalWrap').style.display = 'none'
                    setTimeout(() => {
                        modalButton.closest('.bank-payment-form').querySelector('button').classList.add(
                            'heartPulse')
                    }, 2000);

                    modalButton.closest('.bank-payment-form').querySelectorAll('.pulseIt').forEach(elem => {
                        elem.classList.add(('heartPulse'))
                    })
                    setTimeout(() => {
                        modalButton.closest('.bank-payment-form').querySelector('button').classList.remove(
                            'heartPulse')
                    }, 4000);
                    setTimeout(() => {
                        modalButton.closest('.bank-payment-form').querySelectorAll('.pulseIt').forEach(elem => {
                        elem.classList.remove(('heartPulse'))
                    })
                    }, 2000);
                })
                setTimeout(() => modalWrap.classList.add("animateModal"), 200);
                document.removeEventListener('click', clickBank)
            }
        }
        document.addEventListener('click', clickBank)
    </script>
@endsection
