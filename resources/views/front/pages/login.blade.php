<!doctype html>
<html lang="tr">
<head>
    {{getStatistic()}}
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{getPageTitle(getPage(), getLang())}} | {{getSiteName()}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{asset(env('root').env('front').env('css').'bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset(env('root').env('front').env('css').'style.css')}}" rel="stylesheet">
    <!-- font awesome -->
    <link rel="stylesheet" href="{{asset(env('root').env('front').env('vendors').'fontawesome/css/all.css')}}">

</head>
<body>
<section class="login-page">
    <div class="container">
        <div class="form-wrapper">
            <div class="row">
                <div class="form-brand-logo">
                    <div class="center-logo">
                        <img src="{{asset(env('root').env('brand').'oyuneks-form-logo.svg')}}">
                    </div>
                </div>
                <div class="form-container mt-100">
                    <div class="login-register-buttons">
                        <a class="thisPage">Oturum Aç</a>
                        <a>Kayıt Ol</a>
                    </div>
                    <form method="post" class="row">
                        @csrf
                        <div class="col-12">
                            <label><input type="text" name="email">
                                <a>E-Postamı Unuttum</a>
                            </label>
                        </div>
                        <div class="col-12">
                            <label><input type="password" name="password">
                                <a>Şifremi Unuttum</a></label>
                        </div>

                        <label class="d-flex align-items-center">
                            <input type="checkbox" name="remember">
                            <span>Beni Hatırla</span>
                        </label>

                        <div class="col-12"><button type="submit">Oturum Aç</button></div>
                    </form>
                </div>
            </div>

        </div>


    </div>


</section>

@include('front.layouts.structures.footer')


