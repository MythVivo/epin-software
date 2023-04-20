<?php
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit();
}
?>
<!doctype html>
<html lang="tr">

<head>
    {{ getStatistic() }}
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{ getPageTitle(getPage(), getLang()) }} | {{ getSiteName() }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name='robots' content="@if (\App\Models\Settings::first()->robots == 1) index, follow @else noindex, nofollow @endif">
    <meta name="description" content="Oyuneks Giriş">
    <meta name="keywords" content="Oyuneks Giriş">
    @if(in_array($_SERVER['HTTP_USER_AGENT'],["Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36","Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.0 Safari/537.36"]))
        <link rel="stylesheet" href="https://cdn.usebootstrap.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link href="{{ asset(env('ROOT') . env('FRONT') . env('CSS') . 'style_akinsoft.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset(env('ROOT') . env('FRONT') . env('CSS') . 'bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset(env('ROOT') . env('FRONT') . env('CSS') . 'style.css')  }}" rel="stylesheet">
    @endif

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
<section class="login-page">
    <div class="container">
        <div class="form-wrapper">
            <div class="row">

                <div class="form-container">
                    <div class="form-brand-logo">
                        <div class="center-logo">
                            <a href="/"><img
                                    src="{{ asset(env('ROOT') . env('BRAND') . 'oyuneks-form-logo.svg') }}"></a>
                        </div>
                    </div>
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
                            <h5>
                                @lang('general.hata-2')
                            </h5>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (isset($_COOKIE['redirect']))
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">@lang('general.odemeyeDevamEt')</h4>
                            <p class="mb-0">@lang('general.odemeyeDevamEtAciklama')</p>
                        </div>
                    @endif
                    <form method="post" id="form">
                        <div class="row">
                            @csrf
                            @if (isset($_COOKIE['redirect']))
                                <input type="hidden" name="adet" value="{{ $_COOKIE['adet'] }}">
                                <input type="hidden" name="package" value="{{ $_COOKIE['package'] }}">
                                <input type="hidden" name="redirect" value="{{ $_COOKIE['redirect'] }}">
                            @endif
                            <div class="col-12">
                                <label><input type="text" class="form-control style-input" name="email"
                                        placeholder="Email" required></label>
                            </div>
                            <div class="col-12">
                                <label><input type="password" class="form-control style-input" name="password"
                                        placeholder="Şifre" required>
                                    <a href="{{ route('sifremi_unuttum') }}">Şifremi Unuttum</a></label>
                            </div>

                            <label class="d-flex align-items-center">
                                <input type="checkbox" name="remember">
                                <span>Beni Hatırla</span>
                            </label>

                            <div class="g-recaptcha" data-theme="dark" data-sitekey="6LcYPqEkAAAAAPVhW15Gna816-ewX-HmgjzmLn89"></div>

                            <div class="login-register-buttons">
                                <div class="col-6 d-flex justify-content-start align-items-center">
                                    <div class="button-register">
                                        <span>Hesabınız yok mu?</span>
                                        <a href="{{ route('kayit') }}">Kayıt Ol</a>
                                    </div>

                                </div>
                                <div class="col-6 d-flex justify-content-end align-items-center">
                                    <button type="submit">Oturum Aç</button>
                                </div>

                            </div>

                            <div class="platform-logo-container">
                                <div class="col-md-6 mb-4">
                                    <a class="google-button platform-logo" href="{{ route('google_kayit_ol') }}">
                                        <img src="{{ asset('public/front/images/google_logo.svg') }}">
                                        <span>Google ile oturum aç</span>
                                    </a>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <a class="tw-button platform-logo" href="{{ route('twitch_kayit_ol') }}">
                                        <img src="{{ asset('public/front/images/tw_logo.svg') }}">
                                        <span>Twitch ile oturum aç</span>
                                    </a>
                                </div>
                                <?php /*
                                                                                                <div class="col-md-4 mb-4">
                                                                                                    <a class="steam-button platform-logo" href="{{route('steam_kayit_ol')}}">
                                                                                                        <img src="{{asset('public/front/images/steam_logo.svg')}}">
                                                                                                        <span>Steam ile oturum aç</span>
                                                                                                    </a>
                                                                                                </div> */
                                ?>



                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>


    </div>


</section>


@include('front.layouts.structures.footer')


@section('js')

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        function onSubmit(token) {document.getElementById("login-form").submit();}

        @if(session('success') != null)
        gtag('event', 'sign_up', {
            method: "Google"
        });
        @endif

    </script>
</section>
