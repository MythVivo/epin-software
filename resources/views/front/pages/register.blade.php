<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="tr">
<head>
    {{getStatistic()}}
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{getPageTitle(getPage(), getLang())}} | {{getSiteName()}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{asset(env('ROOT').env('FRONT').env('CSS').'bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset(env('ROOT').env('FRONT').env('CSS').'style.css')}}" rel="stylesheet">
    <!-- font awesome -->
    <link rel="stylesheet" href="{{asset(env('ROOT').env('FRONT').env('VENDORS').'fontawesome/css/all.css')}}">

</head>
<body>
<section class="login-page">
    <div class="container">
        <div class="form-wrapper">
            <div class="row">
                <div class="form-container">
                    <div class="form-brand-logo">
                        <div class="center-logo">
                            <img src="{{asset(env('ROOT').env('BRAND').'oyuneks-form-logo.svg')}}">
                        </div>
                    </div>

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


                    <div class="login-register-buttons mt-5">
                        <a href="{{route('giris')}}">Oturum Aç</a>
                        <a class="thisPage" href="{{route('kayit')}}">Kayıt Ol</a>
                    </div>
                    <form method="post" action="" autocomplete="off">
                        <div class="row">
                            @csrf
                            <div class="col-6">
                                <label><input type="text" name="name_1" placeholder="Ad" required></label>
                            </div>
                            <div class="col-6">
                                <label><input type="text" name="name_2" placeholder="Soyad" required></label>
                            </div>
                            <div class="col-12">
                                <label><input type="text" name="email" placeholder="E-Posta"></label>
                            </div>
                            <div class="col-12">
                                <label><input type="password" name="password" placeholder="Şifre"></label>
                            </div>
                            <div class="col-12">
                                <button type="submit">Kayıt ol</button>
                            </div>
                        </div>
                    </form>

                    <div class="col-12 kvkk-text text-center">
                        <p>Kişisel verileriniz, <span>Aydınlatma Metni</span> kapsamında işlenmektedir. “Üye ol” veya
                            “Sosyal Hesap” butonlarından birine basarak <span>Gizlilik Politikası </span> ile <span>Üyelik ve Hizmet Alım Sözleşmesi</span>’ni
                            okuduğunuzu ve kabul ettiğinizi onaylıyorsunuz.</p>
                    </div>
                </div>
            </div>

        </div>


    </div>


</section>

@include('front.layouts.structures.footer')
