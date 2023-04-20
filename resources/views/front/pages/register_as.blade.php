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


<body>
    <style>
        body {
            background: url(https://oyuneks.com/bayi/assets/img/bgnew.png);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        section {
            /* background: url(https://oyuneks.com/bayi/assets/img/asoft-bg.jpg);
            background-size: cover;
            border-radius: 30px;
            border: 1px solid black; */
            flex-shrink: 1;
        }

        section.login-page .form-wrapper {
            width: 500px;
            border-radius: 20px;
        }

        section.login-page .form-wrapper .form-container label {
            display: block;
            margin-bottom: 20px;
        }

        section.login-page .form-wrapper {
            width: 49%;
            min-height: 810px;
            /* margin: auto; */
            /* height: 100vh; */
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-bottom: unset;
            align-items: center;
            background: rgb(108, 109, 111);
            background: linear-gradient(135deg, rgb(255 255 255 / 40%) 0%, rgb(54 67 77 / 90%) 100%);
            border: 2px solid #cecece;
            padding: 0px 40px;

        }

        .form-info {
            color: #333;
            font-weight: bold;
            background: linear-gradient(178deg, rgb(255 255 255) 0%, rgb(154 155 157) 100%);
            border: 1px solid #666;
            text-decoration: auto;
            text-align: center;
            padding: 5px 20px;
            font-size: 16px;
        }

        ::placeholder {
            /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: #333 !important;
            opacity: 1;
            font-weight: bold !important
                /* Firefox */
        }

        .style-input,
        select {
            color: #333 !important;
            font-weight: bold !important;
            background: linear-gradient(178deg, rgb(255 255 255) 0%, rgb(154 155 157) 100%) !important;

        }

        .mainContainer {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: stretch;
            margin: 15px 0px;
        }

        .rightContainer {
            width: 49%;
            /* background: url(https://oyuneks.com/bayi/assets/img/bg.png); */
            padding-left: 50px;
        }

        .info-text {
            color: #fff;
            font-weight: bold;
            background: rgb(108, 109, 111);
            background: linear-gradient(141deg, rgb(131 131 131 / 70%) 0%, rgb(32 32 32 / 70%) 100%);
            padding: 20px;
            margin: auto;
            border: 2px solid #cecece;
            border-radius: 20px
        }

        .form-control {
            border-radius: unset !important;
        }
    </style>
    <section class="login-page" style="height: 100%">
        <div class="container mainContainer">
            <div class="form-wrapper">
                <div class="form-container">
                    <nav class="navbar" style="margin-bottom:20px">
                        <div class="d-flex d-xl-flex flex-row align-items-center"
                            style="flex-grow:1;justify-content:center">
                            <img src="/bayi/assets/img/oyuneks-logo-light.png" height="50px">
                            <img src="/bayi/assets/img/asoftlogo.png" height="50px"
                                style="margin-left: 20px;margin-top:10px">
                        </div>
                    </nav>
                    <div class="form-info"> Oyuneks.com üzerinden bayi üyeliği alan siz değerli AKINSOFT Kafe
                        kullanıcılarına tüm ürünlerde özel fiyatlar ve daha fazlası!</div>
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

                    <form method="post" action="" autocomplete="off"
                        style="margin-top: 10px; /* padding: 30px;background-color:#fff;border: 2px solid #666!important; */">
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
                                <label>
                                    <select name="il" id="il" class="form-control" required>
                                        <option value="0">İl</option>
                                        @foreach (DB::select('select * from iller') as $p)
                                            <option value="{{ $p->id }}">{{ $p->il_adi }}</option>
                                        @endforeach
                                    </select>
                                </label>
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
                        <div class="col-12 d-flex justify-content-center align-items-center">
                            <button type="submit"
                                style="border-radius:0px!important;background-color:#464646cf!important">
                                Kayıt ol
                            </button>
                        </div>
                        <div class="col-12 d-flex justify-content-center align-items-center mt-3">
                            <div class="button-register" style="font-size: 18px">
                                <span style="font-size: 18px;color:#fff"> Hesabınız var mı?</span>
                                <a style="font-size: 18px;color:#80eeb1!important;" href="{{ route('giris') }}">Oturum
                                    Aç</a>
                            </div>
                        </div>

                    </form>

                    <div class="col-12 kvkk-text text-center">
                        <p style="color: #fff!important;margin-top:20px">
                            Kişisel verileriniz, <span>Aydınlatma Metni</span> kapsamında
                            işlenmektedir. “Üye ol”
                            butonuna basarak <span>Gizlilik Politikası </span> ile
                            <span>Üyelik ve Hizmet Alım Sözleşmesi</span>’ni
                            okuduğunuzu ve kabul ettiğinizi onaylıyorsunuz.
                        </p>
                    </div>
                </div>

            </div>
            <div class="rightContainer">
                <div class="info-text">
                    <p>Oyuneks - Akınsoft İş birliği ile birlikte tüm Akınsoft Kafe kullanıcıları, sistem üzerinden
                        yüzlerce oyuna ait E-pin, çeşitli oyun ve alışveriş platformlarına ait Hediye Kartları, yüzlerce
                        CD-KEY ve daha fazlasını özel fiyatlarla satın alabilmektedir. Bu iş birliğinin bir parçası olan
                        kafelere teşekkür ederiz.</p>

                    <p>Oyuneks.com Steam, Riot Games , Tamgame , TravianGames , CiGames , Big FAT Simulations , Bedtime
                        , Alawar Entertainment , 1CCopmany , SAKARI 2 , Pixeljam2 gibi oyun firmalarının Türkiye'deki
                        yetkili satıcısıdır.</p>

                    <p>Oyuneks.com Bayilik ile sağlanacak yararlar</p>

                    <ul>
                        <li>Faturalı Dijital CD Keyler</li>
                        <li>Güvenilir ve Yetkili Satıcısı olduğumuz ürünler</li>
                        <li>Hızlı ve anlık teslimat</li>
                        <li>İndirimli Dijital Ürünler</li>
                        <li>Geniş Ürün Portföyü</li>
                    </ul>

                </div>
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
