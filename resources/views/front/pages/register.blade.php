<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="tr">

<head>
    {{ getStatistic() }}
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{ getPageTitle(getPage(), getLang()) }} | {{ getSiteName() }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="@if (\App\Models\Settings::first()->robots == 1) index, follow @else noindex, nofollow @endif">
    <meta name="description" content="Oyuneks Kayıt">
    <meta name="keywords" content="Oyuneks Kayıt">
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
<section class="login-page">
    <div class="container">

{{----------------------------------KVKK-------------------------------------------------------------------}}

        <div class="modal fade" id="detay" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                        <div class="col-12 mb-1 text-white">

                                     <h6 style="display: flex; justify-content: center">OYUNEKS BİLİŞİM VE OYUN HİZ. LTD. ŞTİ.</h6>
                                          <h6 style="display: flex; justify-content: center" >ÜYELİK FORMU AYDINLATMA METNİ</h6>

İşbu aydınlatma metni, veri sorumlusu sıfatıyla hareket eden Oyuneks Bilişim ve Oyun Hizmetleri Ltd. Şti
tarafından, 6698 sayılı Kişisel Verilerin Korunması Kanununun (KVKK) 10. maddesi ile Aydınlatma Yükümlülüğünün
Yerine Getirilmesinde Uyulacak Usul ve Esaslar Hakkında Tebliğ çerçevesinde, kişisel verilerinizin işlenme amaçları,
hukuki nedenleri, toplanma yöntemlerini, kimlere aktarılabileceğini ve KVKK kapsamındaki haklarınıza ilişkin olarak
üye adaylarını bilgilendirmek amacıyla hazırlanmıştır. İşbu Üyelik Formu nezdinde; kimlik verisine (ad, soyad)
iletişim verisine (e posta) ilişkin bilgiler işlenmektedir. Söz konusu kişisel verileriniz, Kanun’un 5. Maddesinde
yer alan “ilgili kişinin temel hak ve özgürlüklerine zarar vermemek kaydıyla, veri sorumlusunun meşru menfaatleri
için veri işlenmesinin zorunlu olması” ve “bir sözleşmenin kurulması veya ifasıyla doğrudan doğruya ilgili olması
kaydıyla, sözleşmenin taraflarına ait kişisel verilerin işlenmesinin gerekli olması” hukuki sebeplerine dayanarak
otomatik yollarla işlenmektedir. Söz konusu verileriniz; firmamız online üyelik başvurunuzun değerlendirilebilmesi,
üye kaydınızın gerçekleştirilebilmesi, tarafınızla kurulacak olan iletişim faaliyetinin yürütülebilmesi, gerekli
durumlarda bilgilerinizin teyit edilebilmesi ve olumsuz risklerin önlenmesi, ilgili mevzuat gereği saklanması
gereken bilgilerinizin muhafaza edilebilmesi, bilgilerinizin güvenliği için gerekli teknik ve idari tedbirlerin
alınabilmesi, düzenleyici ve denetleyici kurumlara karşı yasal düzenlemelerin gerektirdiği veya zorunlu kıldığı
hukuki yükümlülüklerimizin yerine getirilebilmesi, hukuki süreçlerinin yürütülebilmesi amaçları ile işlenecek,
kaydedilecek, depolanacak, muhafaza edilecek ve sınıflandırılacaktır. İşbu Üye Kayıt Formu ile paylaşmış olduğunuz
kişisel verileriniz; tüzel kişilik şirketimizin tabi olduğu yasal mevzuat gereği kanuni istisna halleri, online
gerçekleştirilen ticari faaliyetlere ilişkin ilgili iş birimlerimiz tarafından gerekli çalışmaların tertibi, iş ve
pazarlama stratejilerimizin planlaması nedenleriyle; yetkili kamu kurum ve kuruluşları, iş ortağı-iştirak ve
tedarikçilerimizle, 6698 sayılı KVKK md.8 uyarınca kamu güvenliğine ilişkin hususlarda ve hukuki uyuşmazlıkların
halinde adli makamlar veya ilgili kolluk kuvvetleriyle paylaşılabilecektir. Kanunun ilgili kişinin haklarını
düzenleyen 11. Maddesi kapsamındaki taleplerinizi, “Veri Sorumlusuna Başvuru Usul ve Esasları Hakkında Tebliğe”
göre, Varlık Mh. 179 Sk. No:12/4 Muratpaşa/Antalya adresine yazılı olarak gönderebilir veya info@oyuneks.com
e-posta adresine iletebilirsiniz.
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal"> Okudum </button>
                    </div>
                    <br><br><br>
                </div>
            </div>
        </div>
{{------------------------------------------------------------------------------------------}}

        <div class="form-wrapper">
            <div class="row">
                <div class="form-container">
                    <div class="form-brand-logo">
                        <div class="center-logo">
                            <a href="/">
                                <img src="{{ asset(env('ROOT') . env('BRAND') . 'oyuneks-form-logo.svg') }}">
                            </a>
                        </div>
                    </div>

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

                    <form method="post" action="" autocomplete="off" id="reg-form">
                        <div class="row">
                            @csrf
                            <div class="col-6">
                                <label><input type="text" class="form-control style-input" name="name_1" placeholder="Ad" required></label>
                            </div>
                            <div class="col-6">
                                <label><input type="text" class="form-control style-input" name="name_2" placeholder="Soyad" required></label>
                            </div>
                            <div class="col-12">
                                <label><input type="email" class="form-control style-input" name="email" placeholder="E-Posta" required></label>
                            </div>
                            <div class="col-12">
                                <label><input type="password" class="form-control style-input" name="password" placeholder="Şifre" required></label>
                            </div>
                            <div class="col-12" style="display: flex; align-items: center">
                                <input style="height: 15px; width: 15px" type="checkbox" id="kvkk" required> <a class="m-3" data-bs-toggle="modal" data-bs-target="#detay" href="#"> Kvkk Aydınlatma Metni</a>
                            </div>

                            @if (isset($_GET['ref']))
                                <?php
                                $yayinci = DB::table('twitch_support_streamer')->where('yayin_link', $_GET['ref']);
                                ?>
                                @if ($yayinci->count() > 0)
                                    <div class="col-12">
                                        <label>
                                            <input type="text" class="form-control style-input" name="ref" value="{{ $yayinci->first()->title }} referansı ile kayıt oluyorsunuz" readonly></label>
                                    </div>
                                    <input type="hidden" name="yayinciRef" value="{{ $yayinci->first()->user }}">
                                @else
                                @php
                                LogCall('0', '1', "Kullanıcı geçersiz bir referans linki ile kaydolmaya çalıştı.");
                                @endphp
                                    <div class="col-12">
                                        <label class="text-danger">
                                            Bir referans linki verilmiş fakat bu link geçersiz. Referansın başarıyla
                                            kaydedilmesi için lütfen yayıncıdan yeni link alınız.
                                        </label>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="login-register-buttons">
                            <div class="col-6 d-flex justify-content-start align-items-center">
                                <div class="button-register">
                                    <span>Hesabınız var mı? </span> <a href="{{ route('giris') }}"> Oturum Aç</a>

                                </div>
                            </div>
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <button type="submit">Kayıt ol</button>
                            </div>
                            <div class="g-recaptcha mt-2" data-theme="dark"  data-sitekey="6LcYPqEkAAAAAPVhW15Gna816-ewX-HmgjzmLn89"></div>
                        </div>
                        <div class="platform-logo-container">
                            <div class="col-md-6 mb-4">
                                <a class="google-button platform-logo" href="{{ route('google_kayit_ol') }}"><img src="{{ asset('public/front/images/google_logo.svg') }}"><span>Google ileoturum aç</span></a>
                            </div>
                            <div class="col-md-6 mb-4">
                                <a class="tw-button platform-logo" href="{{ route('twitch_kayit_ol') }}"><img src="{{ asset('public/front/images/tw_logo.svg') }}"><span>Twitch ile oturumaç</span></a>
                            </div>
                            <?php /*
                                                                                    <div class="col-md-4 mb-4"><a class="steam-button platform-logo"
                                                                                                             href="{{route('steam_kayit_ol')}}"><img
                                                                                                    src="{{asset('public/front/images/steam_logo.svg')}}"><span>Steam ile oturum aç</span></a>
                                                                                    </div> */
                            ?>
                        </div>

                    </form>

                    <div class="col-12 kvkk-text text-center">
                        <p>Kişisel verileriniz, <span>Aydınlatma Metni</span> kapsamında işlenmektedir. “Üye ol”
                            veya
                            “Sosyal Hesap” butonlarından birine basarak <span>Gizlilik Politikası </span> ile
                            <span id="dr">Üyelik ve Hizmet Alım Sözleşmesi</span>’ni
                            okuduğunuzu ve kabul ettiğinizi onaylıyorsunuz.
                        </p>
                    </div>
                </div>
            </div>

        </div>



    </div>
</section>

@section('js')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function onSubmit(token) {
            document.getElementById("reg-form").submit();
        }
    </script>
@endsection

@include('front.layouts.structures.footer')
