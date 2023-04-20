<!doctype html>
<html lang="tr">

<head>
    {{ getStatistic() }}
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{ getPageTitle(getPage(), getLang()) }} | {{ getSiteName() }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="@if (\App\Models\Settings::first()->robots == 1) index, follow @else noindex, nofollow @endif">
    <meta name="description" content="Oyuneks Şifremi Unuttum">
    <meta name="keywords" content="Oyuneks Şifremi Unuttum">
    <link href="{{ asset(env('ROOT') . env('FRONT') . env('CSS') . 'bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset(env('ROOT') . env('FRONT') . env('CSS') . 'style.css') }}" rel="stylesheet">
    <!-- font awesome -->
    <link rel="stylesheet" href="{{ asset(env('ROOT') . env('FRONT') . env('VENDORS') . 'fontawesome/css/all.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
@if (isset($_COOKIE['theme']) and $_COOKIE['theme'] == 'dark')

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
                            <img src="{{ asset(env('ROOT') . env('BRAND') . 'oyuneks-form-logo.svg') }}">
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
                            <h5>@lang('general.hata-2')</h5>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="post">
                        <div class="row">
                            @csrf
                            <div class="col-12">
                                <label><input class="form-control style-input" type="text" name="email"
                                        placeholder="Email" required></label>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit">Şifremi Yenile</button>
                            </div>
                        </div>
                    </form>


                    <div class="login-register-buttons mt-3">
                        <div class="col-md-6 d-flex justify-content-start align-items-center">
                            <div class="button-register">
                                <a href="{{ route('kayit') }}">Kayıt Ol</a>
                                <span>- veya -</span>
                                <a href="{{ route('giris') }}">Oturum Aç</a>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>


    </div>


</section>

@include('front.layouts.structures.footer')
