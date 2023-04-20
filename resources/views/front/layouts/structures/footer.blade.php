<section class="trust pb-40">
    <div class="container">
        @include('front.modules.trust')
    </div>

</section>
<footer class="footer">
    <div class="container">

        <div class="row">
            <div class="payment-method">
                <span>Ödeme Yöntemleri:</span>
                <div class="bank-logo">
                    <span><img src="{{ asset('/public/front/images/card-logo/mastercard-logo.svg') }}"
                            alt="Mastercard"></span>
                    <span><img src="{{ asset('/public/front/images/card-logo/visa-logo.svg') }}" alt="Visa"></span>
                    <span><img src="{{ asset('/public/front/images/card-logo/paytr.svg') }}" alt="Paytr"></span>

                </div>
            </div>
        </div>
        <div class="line"></div>
        <div class="row">
            <div class="col-md-2 mb-4">
                <div class="footer-card">
                    <h6>Kurumsal</h6>
                    <ul>
                        @foreach (\App\Models\Pages::where('lang', getLang())->whereNull('deleted_at')->where('status', '1')->get() as $u)
                            <li><a href="{{ route('sayfa', $u->link) }}">{{ $u->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-2 mb-4">
                <div class="footer-card">
                    <h6>Hesap</h6>
                    <ul>
                        @if (isset(Auth::user()->id))
                            <li><a href="{{ route('hesabim') }}">Hesabım</a></li>
                            <li><a href="{{ route('ayarlarim') }}">Ayarlarım</a></li>
                            <li><a href="{{ route('odemelerim') }}">Ödemelerim</a></li>
                            <li><a href="{{ route('bakiye_ekle') }}">Bakiye Yükle</a></li>
                        @else
                            <li><a href="{{ route('giris') }}">Giriş Yap</a></li>
                            <li><a href="{{ route('kayit') }}">Kayıt Ol</a></li>
                            <li><a href="{{ route('sifremi_unuttum') }}">Şifremi Unuttum</a></li>
                            <li><a href="{{ route('giris') }}">Sosyal Medya Hesapları İle Oturum Aç</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-md-2 mb-4">
                <div class="footer-card">
                    <h6>Çok Satanlar</h6>
                    <ul>
                        <li><a href="https://oyuneks.com/e-pin-detay/valorant-point-satin-al-valorant-vp-fiyatlari">Valorant VP</a></li>
                        <li><a href="https://oyuneks.com/game-gold/rise-online-gold">Rise Online GB</a></li>
                        <li><a href="https://oyuneks.com/oyun/pubg-mobile-uc-uc-satin-al">PUBG Mobile UC</a></li>
                        <li><a href="https://oyuneks.com/oyun/pubg-mobile-uc-uc-satin-al">PUBG UC</a></li>
                        <li><a href="https://oyuneks.com/e-pin-detay/league-of-legends-tr">LoL RP</a></li>
                        <li><a href="https://oyuneks.com/e-pin-detay/free-fire-elmas">Free Fire Elmas</a></li>
                        <li><a href="https://oyuneks.com/game-gold/knight-online-goldbar-alim-satim">KO GB</a></li>
                        <li><a href="https://oyuneks.com/game-gold/wow-gold">WoW Gold</a></li>
                        <li><a href="https://oyuneks.com/e-pin-detay/rise-online-cash-satin-al">Rise Online Cash</a></li>
                        <li><a href="https://oyuneks.com/e-pin-detay/metin2-ejder-parasi">Metin 2 EP</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-2 mb-4">
                <div class="footer-card">
                    <h6>Yardım ve Destek</h6>
                    <ul>
                        <li><a href="{{ route('sssSayfasi') }}">Sıkça Sorulan Sorular</a></li>
                        <li><a href="javascript:void(0)" onclick="LiveChatWidget.call('maximize')">Canlı Destek Al</a></li>
                        <li><a href="mailto:{{ getCacheSetings()->email_1 }}">E-posta İle Destek Al</a></li>
                        @if (getCacheSetings()->tel_1 != '')
                            <li><a href="tel:{{ getCacheSetings()->tel_1 }}">Telefon İle Destek Al</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <!-- TrustBox widget - Micro Review Count -->

            <!-- End TrustBox widget -->


            <div class="col-md-2 ml-auto">
                @include('front.layouts.structures.qr')
                <div style="width: 200px;margin-top:40px">
                    <style>
                        body:not(.dark) .tp_dark {
                            display: none;
                        }

                        body.dark .tp_light {
                            display: none;
                        }
                    </style>
                    <a href="https://www.trustpilot.com/review/oyuneks.com?utm_medium=trustbox&utm_source=MicroReviewCount"
                        target="_blank" rel="noopener">
                        <img class="tp_dark" src="https://cdn.trustpilot.net/brand-assets/1.1.0/logo-white.svg"
                            alt="" width="100%">
                        <img class="tp_light" src="https://cdn.trustpilot.net/brand-assets/1.6.0/logo-black.svg"
                            alt="" width="100%">
                    </a>
                </div>
            </div>

        </div>
        <div class="line"></div>
        <div class="row">

            <div class="col-md-6">
                <div class="contact">
                    <span>Destek ve İletişim</span>
                    @if (getCacheSetings()->tel_1 != '')
                        <a href="tel:{{ getCacheSetings()->tel_1 }}"><i class="fal fa-phone-alt"></i>
                            {{ getCacheSetings()->tel_1 }}</a>
                    @endif
                    <a href="mailto:{{ getCacheSetings()->email_1 }}"><i class="fal fa-envelope"></i>
                        {{ getCacheSetings()->email_1 }}</a>
                    <span style="font-weight: bold">Oyuneks OÜ </span>
                    <span>Veskiposti 2-1002, 10138, Tallinn, Estonia</span>
                </div>
            </div>


            <div class="col-md-6">
                <div class="social-contact">
                    <ul>
                        <?php $settings = getCacheSetings(); ?>
                        @if ($settings->facebook != '')
                            <li><a href="{{ $settings->facebook }}" target="_blank"><i class="fab fa-facebook"></i></a>
                            </li>
                        @endif
                        @if ($settings->twitter != '')
                            <li><a href="{{ $settings->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                            </li>
                        @endif
                        @if ($settings->youtube != '')
                            <li><a href="{{ $settings->youtube }}" target="_blank"><i class="fab fa-youtube"></i></a>
                            </li>
                        @endif
                        @if ($settings->linkedin != '')
                            <li><a href="{{ $settings->linkedin }}" target="_blank"><i class="fab fa-linkedin"></i></a>
                            </li>
                        @endif
                        @if ($settings->instagram != '')
                            <li><a href="{{ $settings->instagram }}" target="_blank"><i
                                        class="fab fa-instagram"></i></a></li>
                        @endif
                        <li><a href="https://wa.me/908503080007" target='blank'><i class="fa-whatsapp fab"></i> WhatsApp
                                Destek</a></li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="line"></div>
        <div class="row">
            <div class="brand-container">
                <div class="brand-logo">
                    <p><img class="light" src="{{ asset(env('ROOT') . env('BRAND') . 'brandlogo_white.png') }}"
                            alt="Logo"><span>{{ getCacheSetings()->footer_text }}</span>
                    </p>
                </div>

            </div>

        </div>

    </div>
</footer>
@if (isset(Auth::user()->id))
    @if (DB::table('hizli_menu')->where('user', Auth::user()->id)->count() > 0)

        <div class="hizli-menuler">
            <a id="hizli-menu"><i class="fas fa-bars"></i> </a>
            <ul>
                @foreach (DB::table('hizli_menu')->where('user', Auth::user()->id)->get() as $menu)
                    <li><a href="{{ env('APP_URL') . $menu->link }}"
                            c-title="{{ $menu->title }}">{!! $menu->icon !!} </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="hizli-menuler">
            <a id="hizli-menu"><i class="fas fa-bars"></i> </a>
            <ul>
                <li><a href="{{ route('hizli_menu') }}" c-title="Hızlı Menü Oluştur"><i class="fas fa-plus"></i>
                    </a></li>
            </ul>
        </div>
    @endif
@endif
<a id="scroll-top"><i class="fas fa-chevron-up"></i></a>
<div id="rightmenu">
    <span>1</span>
    <span>1</span>
    <span>1</span>
    <span>1</span>
</div>


</div>

</body>
<script src="{{ asset(env('ROOT') . env('FRONT') . env('JS') . 'jquery-3.6.0.min.js') }}"></script>
@if (in_array(@$_SERVER['HTTP_USER_AGENT'], [
    'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.0 Safari/537.36',
]))
    <!-- Popper JS -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> --}}

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.usebootstrap.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <?php $fileTime = filemtime(env('ROOT') . env('FRONT') . env('JS') . 'asoft/asoft.js'); ?>
    <script src="{{ asset(env('ROOT') . env('FRONT') . env('JS') . 'asoft/asoft.js' . '?' . $fileTime) }}"></script>
@else
    <script src="{{ asset(env('ROOT') . env('FRONT') . env('JS') . 'bootstrap.bundle.min.js') }}"></script>
@endif
<!-- owlcarousel -->

<script src="{{ asset(env('ROOT') . env('FRONT') . env('JS') . 'owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset(env('ROOT') . env('FRONT') . env('JS') . 'toastr.min.js') }}" async></script>
<script src="{{ asset(env('ROOT') . env('FRONT') . env('JS') . 'lazyload.min.js') }}"></script>
<script src="{{ asset(env('ROOT') . env('FRONT') . env('JS') . 'jquery.overlayScrollbars.min.js') }}"></script>
<!-- <script src="{{ asset(env('ROOT') . env('FRONT') . env('JS') . 'popper.js') }}" async></script> -->
<?php $fileTime = filemtime(env('ROOT') . env('FRONT') . env('JS') . 'custom.js'); ?>
<script src="{{ asset(env('ROOT') . env('FRONT') . env('JS') . 'custom.js') . '?v=' . $fileTime }}"
    type="text/javascript" async></script>

@if (isset(Auth::user()->id))
    <?php
    $ilanKo = DB::table('pazar_yeri_ilanlar')
        ->where('user', Auth::user()->id)
        ->whereNull('deleted_at')
        ->where('status', '4');
    $ilanKo2 = DB::table('pazar_yeri_ilanlar')
        ->where('user', Auth::user()->id)
        ->whereNull('deleted_at')
        ->where('status', '5');
    ?>
    <script type="text/javascript">
        document.addEventListener("tidioChat-ready", () => {
            tidioChatApi.setVisitorData({
                user_id: "{{ Auth::user()->id }}"
            });
        });
        document.tidioIdentify = {
            distinct_id: "{{ Auth::user()->id }}", // Unique visitor ID in your system
            email: "{{ Auth::user()->email }}", // visitor email
            name: "{{ Auth::user()->name }}", // Visitor name
            phone: "+90 {{ Auth::user()->telefon }}" //Visitor phone
        };
        @if (session('panel') and session('panel') == 3)
        )
        const myTimeout = setTimeout(canliDestek, 5000);

        function canliDestek() {
            alert("asdfsdfsd");

            function onTidioChatApiReady() {
                tidioChatApi.open();
                tidioChatApi.display(true);
                tidioChatApi.setColorPalette("#343891");
                tidioChatApi.messageFromOperator(
                    "Sayın {{ Auth::user()->name }}, itemi siteye teslim etmeniz bekleniyor.");
                document.tidioChatLang = "tr";
                tidioChatApi.addVisitorTags(["Alış İlanı İtemi Siteye Teslimi İçin Bekleniyor"]);
            }

            if (window.tidioChatApi) {
                window.tidioChatApi.on("ready", onTidioChatApiReady);
            } else {
                document.addEventListener("tidioChat-ready", onTidioChatApiReady);
            }
        }
        @endif
        @if ($ilanKo->count() > 0 and $ilanKo->first()->toplu != 1)
            (function() {
                function onTidioChatApiReady() {
                    tidioChatApi.open();
                    tidioChatApi.display(true);
                    tidioChatApi.setColorPalette("#343891");
                    tidioChatApi.messageFromOperator(
                        "Sayın {{ Auth::user()->name }}, {{ $ilanKo->first()->title }} isimli ilan için itemi siteye teslim etmeniz bekleniyor."
                    );
                    document.tidioChatLang = "tr";
                    tidioChatApi.addVisitorTags(["İtemi Siteye Teslimi İçin Bekleniyor"]);
                }

                if (window.tidioChatApi) {
                    window.tidioChatApi.on("ready", onTidioChatApiReady);
                } else {
                    document.addEventListener("tidioChat-ready", onTidioChatApiReady);
                }
            })();
        @endif
        <?php
        $control = DB::table('pazar_yeri_ilan_satis')
            ->select('pazar_yeri_ilanlar.*')
            ->join('pazar_yeri_ilanlar', 'pazar_yeri_ilan_satis.ilan', '=', 'pazar_yeri_ilanlar.id')
            ->orderBy('pazar_yeri_ilan_satis.created_at', 'desc')
            ->where('pazar_yeri_ilan_satis.satin_alan', Auth::user()->id)
            ->where('pazar_yeri_ilanlar.status', '5')
            ->whereNull('pazar_yeri_ilan_satis.deleted_at')
            ->whereNull('pazar_yeri_ilanlar.deleted_at');
        ?>
        @if ($control->count() > 0)
            (function() {
                function onTidioChatApiReady() {
                    tidioChatApi.open();
                    tidioChatApi.display(true);
                    tidioChatApi.setColorPalette("#343891");
                    tidioChatApi.messageFromOperator(
                        "Sayın {{ Auth::user()->name }}, {{ $control->first()->title }} isimli ilan için site teslimata hazırdır."
                    );
                    document.tidioChatLang = "tr";
                    tidioChatApi.addVisitorTags(["İtemi Müşteriye Teslimi İçin Bekleniyor"]);
                }

                if (window.tidioChatApi) {
                    window.tidioChatApi.on("ready", onTidioChatApiReady);
                } else {
                    document.addEventListener("tidioChat-ready", onTidioChatApiReady);
                }
            })();
        @endif




        $("body").delegate(".read-nt", "click", function() {

            var id = this.dataset.bildirim_id
            var elem = this

            $.ajax({
                url: "{{ route('bildirim_oku') }}?id=" + id,
                success: function(result) {
                    if (result) { // eğer başarıyla okunduysa

                        $(elem).html('<i class="far fa-check-double"></i>')
                        var change_itm = elem.parentElement.parentElement.parentElement.parentElement
                        $(change_itm).removeClass("new-nt").addClass("old-nt")
                        nt_new_calc()

                    }
                }
            });
            return false

        });

        $(".clear-notifications").on("click", function(event) {
            const id = this.dataset.bildirim_id
            $.ajax({
                url: "{{ route('bildirim_oku') }}?id=" + id,
                success: function(result) {
                    if (result) { // eğer başarıyla okunduysa

                        var items = $(".new-nt")
                        if (items.length > 0) {
                            $.each(items, function(key, value) {
                                $(value).removeClass("new-nt").addClass("old-nt")
                                $(value).find(".nt-cont-right").html(
                                    '<i class="far fa-check-double"></i>')
                            });
                            nt_new_calc()
                        }
                    }
                }
            });
            return false
        });
    </script>
@endif

<script>
    $(window).on('load', function() {
        $('.lazyload').on('load', function() {
            $(this).addClass("loaded")
        });
        var images = document.querySelectorAll(".lazyload");
        new LazyLoad(images, {
            root: null,
            rootMargin: "0px",
            threshold: 0
        });
    });
    /*
    setTimeout(function () {
        $("#loader").remove()
    }, 10);
    $(window).on('load', function () {
        $("#loader").addClass("loaded")
    });*/
</script>
@if (@$_GET['lc'] == 'lc' || true)
    <script>
        window.__lc = window.__lc || {};
        window.__lc.license = 14714685;;
        (function(n, t, c) {
            function i(n) {
                return e._h ? e._h.apply(null, n) : e._q.push(n)
            }
            var e = {
                _q: [],
                _h: null,
                _v: "2.0",
                on: function() {
                    i(["on", c.call(arguments)])
                },
                once: function() {
                    i(["once", c.call(arguments)])
                },
                off: function() {
                    i(["off", c.call(arguments)])
                },
                get: function() {
                    if (!e._h) throw new Error("[LiveChatWidget] You can't use getters before load.");
                    return i(["get", c.call(arguments)])
                },
                call: function() {
                    i(["call", c.call(arguments)])
                },
                init: function() {
                    var n = t.createElement("script");
                    n.async = !0, n.type = "text/javascript", n.src = "https://cdn.livechatinc.com/tracking.js",
                        t.head.appendChild(n)
                }
            };
            !n.__lc.asyncInit && e.init(), n.LiveChatWidget = n.LiveChatWidget || e
        }(window, document, [].slice));
        @if (isset(Auth::user()->id) && Auth::user()->id)
            LiveChatWidget.call("set_customer_name", "{{ Auth::user()->name }}");
            LiveChatWidget.call("set_customer_email", "{{ Auth::user()->email }}");
            LiveChatWidget.call("set_session_variables", {
                email: "{{ Auth::user()->email }}",
                telefon: "{{ Auth::user()->telefon }}",
                uid: "{{ Auth::user()->id }}",
                Tip: "{{ Auth::user()->groupId == 2 ? 'VIP' : 'Standart' }}"
            });
        @endif
    </script>
{{--    <noscript><a href="https://www.livechat.com/chat-with/14714685/" rel="nofollow">Chat with us</a>, powered by <a--}}
{{--            href="https://www.livechat.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript>--}}
@else
    <script>
        $(function() {
            setTimeout(function() {
                const s = 'https://code.tidio.co/d6m2kjbmhtoxzpgvokvobd8dyihz9ue5.js';
                var d = window.document,
                    b = d.body,
                    e = d.createElement("script");

                e.async = true;
                e.src = s;
                b.appendChild(e);
            }, 3000);
        });
    </script>
@endif
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-RZ0E3YX8R2"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-RZ0E3YX8R2');
</script>
<!-- Yandex.Metrika counter -->
 <script type="text/javascript">
    (function(m, e, t, r, i, k, a) {
        m[i] = m[i] || function() {
            (m[i].a = m[i].a || []).push(arguments)
        };
        m[i].l = 1 * new Date();
        k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(
            k, a)
    })
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(88080039, "init", {
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true,
        webvisor: true,
        ecommerce: "dataLayer"
    });
</script>
<!-- /Yandex.Metrika counter -->
@yield('js')

<script async src="{{ asset(env('ROOT') . env('FRONT') . env('JS') . 'select2.min.js') }}"></script>

</html>
