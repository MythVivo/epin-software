<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="tr">

<head>
    {{ getStatistic() }}
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Oyuneks AkınSoft Kafe Kayıt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name=”robots” content="@if (\App\Models\Settings::first()->robots == 1) index, follow @else noindex, nofollow @endif">
    <meta name="description" content="{{ \App\Models\Settings::first()->description }}">
    <link href="{{ asset(env('ROOT') . env('FRONT') . env('CSS') . 'bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset(env('ROOT') . env('FRONT') . env('CSS') . 'style.css') }}?5" rel="stylesheet">
    <!-- font awesome -->
    <link rel="stylesheet" href="{{ asset(env('ROOT') . env('FRONT') . env('VENDORS') . 'fontawesome/css/all.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

</head>

@if (!isset($_COOKIE['theme']) or isset($_COOKIE['theme']) and $_COOKIE['theme'] == 'dark')

    <body class="dark">
        <!-- dark theme -->
    @else

        <body>
@endif
<style>
    section.login-page .form-wrapper .form-container {
        max-width: 500px;
    }

    section.login-page .form-wrapper .form-container label {
        display: block;
        margin-bottom: 20px;
    }

    section.login-page .form-wrapper {
        max-width: 700px;
        min-height: 810px;
        margin: auto;
        /* height: 100vh; */
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding-bottom: unset;
        align-items: center;
    }

    .form-info {
        color: #000;
        font-weight: bold;
        background-color: #fff;
        border: 1px solid black;
        text-decoration: auto;
        text-align: justify;
        padding: 5px 20px;
        font-size: 16px;
        font-family: Arial, Helvetica, sans-serif
    }

    .panelContainer {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;

    }

    .leftPanel {
        padding: 0px 20px;
    }

    .rightPanel {
        width: 500px;
        background: url(https://oyuneks.com/bayi/assets/img/bg.png);
        align-self: stretch;
    }

    .form-control {
        color: #000 !important;
        background-color: #fff !important;
        border: 1px solid #000 !important;
        border-radius: unset;

    }

    body.dark .form-control:focus {
        box-shadow: unset !important;
    }
</style>
<section class="login-page">
    <div class="container panelContainer">
        <div class="leftPanel">
            <div class="form-wrapper">
                <div class="form-container">
                    <nav class="navbar" style="margin-bottom:50px">
                        <div class="container">
                            <div class="d-flex d-xl-flex flex-row align-items-center"
                                style="width: 270px;flex-grow:1;justify-content:space-between">
                                <img src="/bayi/assets/img/oyuneks-logo.png">
                                <img src="/bayi/assets/img/akinsoft-logo.png" width="100px">
                            </div>
                        </div>
                    </nav>
                    <div class="form-info">
                        Oyuneks.com üzerinden bayi üyeliği alan siz değerli AKINSOFT Kafe kullanıcılarına tüm ürünlerde
                        özel
                        fiyatlar ve daha fazlası!</div>
                    @if (session('success'))
                        <div class="alert alert-success fade show d-flex align-items-center" role="alert">
                            <h5>{{ session('success') }}</h5>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger fade show d-flex align-items-center" role="alert">
                            <h5>{{ session('error') }}</h5>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger fade show d-flex align-items-center" role="alert">
                            <h5>@lang('general.hata-2')</h5>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="" autocomplete="off" class="border"
                        style="margin-top: 10px; padding: 50px;background-color:#fff;border: 1px solid black!important;">
                        <div class="row">
                            @csrf
                            <div class="col-6">
                                <label><input type="text" class="form-control style-input" name="name_1"
                                        placeholder="Ad" required></label>
                            </div>
                            <div class="col-6">
                                <label><input type="text" class="form-control style-input" name="name_2"
                                        placeholder="Soyad" required></label>
                            </div>
                            <div class="col-12">
                                <label><input type="text" class="form-control style-input" name="firma_ad"
                                        placeholder="Firma Adı" required></label>
                            </div>
                            <div class="col-6">
                                <label><select name="il" id="il" class="form-control" required>
                                        <option value="0">İl</option>
                                        @foreach (DB::select('select * from iller') as $p)
                                            <option value="{{ $p->id }}">{{ $p->il_adi }}</option>
                                        @endforeach
                                    </select></label>
                            </div>
                            <div class="col-6">
                                <label><select class="form-control" name="ilce" id="ilce" required>
                                        <option value="0">İlçe</option>
                                    </select></label>
                            </div>
                            <div class="col-12">
                                <label><input type="email" class="form-control style-input" name="email"
                                        placeholder="E-Posta" required></label>
                            </div>
                            <div class="col-12">
                                <label><input type="password" class="form-control style-input" name="password"
                                        placeholder="Şifre" required></label>
                            </div>

                            <div class="col-12">
                                <label>
                                    <input type="text" class="form-control style-input" name="ref"
                                        value="AKINSOFT referansı ile kayıt oluyorsunuz" readonly></label>
                            </div>
                            <input type="hidden" name="yayinciRef" value="27156">
                        </div>
                        <div class="login-register-buttons">
                            <div class="col-6 d-flex justify-content-start align-items-center">
                                <div class="button-register">
                                    <span>Hesabınız var mı?</span>
                                    <a href="{{ route('giris') }}">Oturum Aç</a>
                                </div>
                            </div>
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <button type="submit">Kayıt ol</button>
                            </div>
                        </div>
                    </form>

                    <div class="col-12 kvkk-text text-center">
                        <p>Kişisel verileriniz, <span>Aydınlatma Metni</span> kapsamında işlenmektedir. “Üye ol”
                            butonuna basarak <span>Gizlilik Politikası </span> ile
                            <span>Üyelik ve Hizmet Alım Sözleşmesi</span>’ni
                            okuduğunuzu ve kabul ettiğinizi onaylıyorsunuz.
                        </p>
                    </div>
                </div>

            </div>

        </div>
        <div class="rightPanel">
            asdfsadfsdfsdf
        </div>
    </div>
</section>


<script src="/bayi/assets/js/jquery.min.js"></script>
<script type="text/javascript">
    $('#il').change(function() {
        $.post('/ykp.php', {
            ilceler: $(this).val()
        }, function(x) {
            $('#ilce').empty().append(x);
        });
    })
</script>



{{-- @include('front.layouts.structures.footer') --}}
