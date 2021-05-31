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

                <div class="form-container">
                    <div class="form-brand-logo">
                        <div class="center-logo">
                            <img src="{{asset(env('root').env('brand').'oyuneks-form-logo.svg')}}">
                        </div>
                    </div>
                @if(session('success'))
                    <!--Mesaj bildirimi--->
                        <div class="form-info">
                            <figure>
                                <i class="fas fa-exclamation"></i>
                                <p>{{session('success')}}</p>
                            </figure>
                        </div>
                        <!--Mesaj bildirim END--->
                @endif
                @if(session('error'))
                    <!--Mesaj bildirimi--->
                        <div class="form-info">
                            <figure>
                                <i class="fas fa-exclamation"></i>
                                <p>{{session('error')}}</p>
                            </figure>
                        </div>
                        <!--Mesaj bildirim END--->
                @endif
                @if ($errors->any())
                    <!--Hata bildirimi--->
                        <div class="form-info">
                            <figure>
                                <i class="fas fa-exclamation"></i>
                                <p>@lang('general.hata-2')</p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </figure>
                        </div>
                        <!--Hata bildirim END--->
                    @endif
                    <div class="login-register-buttons">
                        <a class="thisPage" href="{{route('giris')}}">Oturum Aç</a>
                        <a href="{{route('kayit')}}">Kayıt Ol</a>
                    </div>
                    @if(Cookie::get('redirect') !== null)
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">@lang('general.odemeyeDevamEt')</h4>
                            <p class="mb-0">@lang('general.odemeyeDevamEtAciklama')</p>
                        </div>
                    @endif
                    <form method="post">
                        <div class="row">
                            @csrf
                            @if(Cookie::get('redirect') !== null)
                                <input type="hidden" name="adet" value="{{Cookie::get('adet')}}">
                                <input type="hidden" name="package" value="{{Cookie::get('package')}}">
                                <input type="hidden" name="redirect" value="{{Cookie::get('redirect')}}">
                            @endif
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

                            <div class="col-12">
                                <button type="submit">Oturum Aç</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>


    </div>


</section>

@include('front.layouts.structures.footer')


