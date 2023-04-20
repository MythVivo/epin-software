<div class="left-sidenav">
    <ul class="metismenu left-sidenav-menu">
        @if(sayfaIzinKontrol('panel'))
            <li @if(getPage() == 'panel') class="mm-active" @endif>
                <a href="{{route('panel')}}"><i class="mdi mdi-desktop-mac-dashboard"></i> @lang('admin.anasayfa')</a>
            </li>
        @endif
        @if(sayfaIzinKontrol('panel/site-yonetim'))
            <li @if(getPage() == 'site-yonetim') class="mm-active" @endif>
                <a href="{{route('site_yonetim')}}"><i class="mdi mdi-desktop-mac-dashboard"></i> Site Ayarları</a>
            </li>
        @endif
            @if(sayfaIzinKontrol('panel/seo-yonetim'))
                <li @if(getPage() == 'seo-yonetim') class="mm-active" @endif>
                    <a href="{{route('seo_yonetim')}}"><i class="mdi mdi-trending-up"></i> Seo Yönetimi</a>
                </li>
            @endif

            <li @if(getPage() == 'panel/bayi') class="mm-active" @endif>
                <a href="{{route('bayi')}}"><i class="mdi mdi-trending-up"></i>Bayi İşlemleri</a>
            </li>

            <li @if(getPage() == 'panel/epintakip') class="mm-active" @endif>
                <a href="{{route('epintakip')}}"><i class="mdi mdi-trending-up"></i>Epin Takip</a>
            </li>

        @if(sayfaIzinKontrol('panel/slider') or sayfaIzinKontrol('panel/haberler') or sayfaIzinKontrol('panel/yorumlar') or sayfaIzinKontrol('panel/sayfalar') or sayfaIzinKontrol('panel/sss') or sayfaIzinKontrol('panel/header-menu') or sayfaIzinKontrol('panel/avatarlar') or sayfaIzinKontrol('panel/ikonlar'))
            <li>
                <a @if(getUrl() == route('slider')) class="link-active" @endif href="javascript:void(0);"><i
                            class="mdi mdi-cogs"></i><span>@lang('admin.siteYonetimi')</span>
                    <span class="menu-arrow">
                    <i class="mdi mdi-chevron-right"></i>
                </span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    @if(sayfaIzinKontrol('panel/slider'))
                        <li class="nav-item"><a class="nav-link" href="{{route('slider')}}"><i class="ti-control-record"></i>@lang('admin.sliderYonetimi')</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/haberler'))
                        <li class="nav-item"><a class="nav-link" href="{{route('haberler')}}"><i class="ti-control-record"></i>@lang('admin.haberYonetimi')</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/yorumlar'))
                        <li class="nav-item"><a class="nav-link" href="{{route('yorumlar')}}"><i class="ti-control-record"></i>@lang('admin.yorumYonetimi')</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/sayfalar'))
                        <li class="nav-item"><a class="nav-link" href="{{route('sayfalar')}}"><i class="ti-control-record"></i>@lang('admin.sayfaYonetimi')</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/sss'))
                        <li class="nav-item"><a class="nav-link" href="{{route('sss')}}"><i class="ti-control-record"></i>@lang('admin.sssYonetimi')</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/header-menu'))
                        <li class="nav-item"><a class="nav-link" href="{{route('header_menu')}}"><i class="ti-control-record"></i>Mega Menü</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/avatarlar'))
                        <li class="nav-item"><a class="nav-link" href="{{route('avatarlar')}}"><i class="ti-control-record"></i>Avatarlar</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/ikonlar'))
                        <li class="nav-item"><a class="nav-link" href="{{route('ikonlar')}}"><i class="ti-control-record"></i>İkonlar</a></li>
                    @endif
                </ul>
            </li>
        @endif
        @if(sayfaIzinKontrol('panel/kategoriler') or sayfaIzinKontrol('panel/oyunlar') or sayfaIzinKontrol('panel/oyun-toplu-paket') or sayfaIzinKontrol('panel/oyun-toplu-stok') or sayfaIzinKontrol('panel/yorumlar-ilan') or sayfaIzinKontrol('panel/twitch-yayincilar-yonetim') or sayfaIzinKontrol('panel/hediye-kodlari') or sayfaIzinKontrol('panel/yorumlar-satici') or sayfaIzinKontrol('panel/tedarikciler'))
            <li>
                <a @if(getUrl() == route('kategoriler')) class="link-active" @endif href="javascript:void(0);"><i
                            class="fas fa-gamepad"></i><span>@lang('admin.urunYonetimi')</span>
                    <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    @if(sayfaIzinKontrol('panel/kategoriler'))
                        <li class="nav-item"><a class="nav-link" href="{{route('kategoriler')}}"><i class="ti-control-record"></i>@lang('admin.kategoriYonetimi')</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/oyunlar'))
                        <li class="nav-item"><a class="nav-link" href="{{route('oyunlar')}}"><i class="ti-control-record"></i>@lang('admin.oyunYonetimi')</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/oyun-toplu-oyun-parasi'))
                        <li class="nav-item"><a class="nav-link" href="{{route('toplu_oyun_parasi')}}"><i class="ti-control-record"></i>Toplu GB</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/oyun-toplu-paket'))
                        <li class="nav-item"><a class="nav-link" href="{{route('toplu_paket')}}"><i class="ti-control-record"></i>Toplu Paket</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/oyun-toplu-stok'))
                        <li class="nav-item"><a class="nav-link" href="{{route('toplu_stok')}}"><i class="ti-control-record"></i>Stok Yönetimi</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('silinen_stok')}}"><i class="ti-control-record"></i>Stok Silinenler</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/yorumlar-ilan'))
                        <li class="nav-item"><a class="nav-link" href="{{route('yorumlarIlan')}}"><i class="ti-control-record"></i>İlan Yorumları</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/yorumlar-satici'))
                        <li class="nav-item"><a class="nav-link" href="{{route('yorumlar_satici')}}"><i class="ti-control-record"></i>Satıcı Yorumları</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/twitch-yayincilar-yonetim'))
                        <li class="nav-item"><a class="nav-link" href="{{route('twitch_yayincilar_yonetim')}}"><i class="ti-control-record"></i>Twitch Yayıncıları</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/hediye-kodlari'))
                        <li class="nav-item"><a class="nav-link" href="{{route('hediye_kodlari_yonetim')}}"><i class="ti-control-record"></i>Hediye Kodları</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/tedarikciler'))
                        <li class="nav-item"><a class="nav-link" href="{{route('tedarikciler')}}"><i class="ti-control-record"></i>Tedarikçiler</a></li>
                    @endif
                </ul>
            </li>
        @endif
        @if(sayfaIzinKontrol('panel/muve-oyunlar'))
            <li>
                <a @if(getUrl() == route('muve_oyunlar')) class="link-active" @endif href="javascript:void(0);"><i class="fas fa-gamepad"></i><span>Muve Oyun Satışı</span>
                    <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    @if(sayfaIzinKontrol('panel/muve-oyunlar'))
                        <li class="nav-item"><a class="nav-link" href="{{route('muve_oyunlar')}}"><i class="ti-control-record"></i>Muve Oyunları</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('muve_satislar')}}"><i class="ti-control-record"></i>Muve Satislar</a></li>
                    @endif
                </ul>
            </li>
        @endif
        @if(sayfaIzinKontrol('panel/odemeler') or sayfaIzinKontrol('panel/epin-siparis-detaylari') or sayfaIzinKontrol('panel/twitch-para-cek-yonetim') or sayfaIzinKontrol('panel/twitch-kesintisiz-yonetim') or sayfaIzinKontrol('panel/ilanlar-yonetim') or sayfaIzinKontrol('panel/game-gold-yonetim') or sayfaIzinKontrol('panel/epin-yonetim') or sayfaIzinKontrol('panel/para-cek-yonetim') or sayfaIzinKontrol('panel/twitch-donate-yonetim') or sayfaIzinKontrol('panel/ilanlar-yonetim-buy'))
            <li>
                <a @if(getUrl() == route('odemeler')) class="link-active" @endif href="javascript:void(0);"><i
                            class="fas fa-file-invoice-dollar"></i><span>Finans</span>
                    <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    @if(sayfaIzinKontrol('panel/odemeler'))
                        <li class="nav-item"><a class="nav-link" href="{{route('cari')}}"><i class="ti-control-record"></i>Cari İşlemler</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/odemeler'))
                        <li class="nav-item"><a class="nav-link" href="{{route('odemeler')}}"><i class="ti-control-record"></i>Ödemeler</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/odemeler'))
                        <li class="nav-item"><a class="nav-link" href="{{route('epinf')}}"><i class="ti-control-record"></i>E-fatura</a></li>
                    @endif
                        <li class="nav-item"><a class="nav-link" href="{{route('siparisler')}}"><i class="ti-control-record"></i>Siparişler</a></li>
                    @if(sayfaIzinKontrol('panel/ilanlar-yonetim'))
                            <li class="nav-item"><a class="nav-link" href="{{route('ilanlar_yonetim')}}"><i class="ti-control-record"></i>İlan Siparişleri</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/ilanlar-yonetim-buy'))
                        <li class="nav-item"><a class="nav-link" href="{{route('ilanlar_yonetim_buy')}}"><i class="ti-control-record"></i>Alış İlanları</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/game-gold-yonetim'))
                        <li class="nav-item"><a class="nav-link" href="{{route('game_gold_yonetim')}}"><i class="ti-control-record"></i>Oyun Parası Siparişleri</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/epin-yonetim'))
                        <li class="nav-item"><a class="nav-link" href="{{route('epin_yonetim')}}"><i class="ti-control-record"></i>E-pin Siparişleri</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/epin-siparis-detaylari'))
                        <li class="nav-item"><a class="nav-link" href="{{route('epin_siparis_detaylari')}}"><i class="ti-control-record"></i>E-pin Sipariş Detaylı</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/para-cek-yonetim'))
                        <li class="nav-item"><a class="nav-link" href="{{route('para_cek_yonetim')}}"><i class="ti-control-record"></i>Para Çekim Talepleri</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/twitch-para-cek-yonetim'))
                        <li class="nav-item"><a class="nav-link" href="{{route('twitch_para_cek_yonetim')}}"><i class="ti-control-record"></i>Twitch Para Çekim Talepleri</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/twitch-kesintisiz-yonetim'))
                        <li class="nav-item"><a class="nav-link" href="{{route('twitch_kesintisi_yonetim')}}"><i class="ti-control-record"></i>Twitch Kesintisiz Talepleri</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/twitch-donate-yonetim'))
                        <li class="nav-item"><a class="nav-link" href="{{route('twitch_donate_yonetim')}}"><i class="ti-control-record"></i>Twitch Donate Takip</a></li>
                    @endif
                </ul>
            </li>
        @endif
        @if(sayfaIzinKontrol('panel/uyeler') or sayfaIzinKontrol('panel/kullanici-gruplari') or sayfaIzinKontrol('panel/kimlik-yonetim') or sayfaIzinKontrol('panel/panel-loglar') or sayfaIzinKontrol('panel/manuel-telefon-onaylama'))
            <li>
                <a @if(getUrl() == route('uyeler') or getUrl() == route('uye_aktivite')  ) class="link-active" @endif href="javascript:void(0);"><i class="fas fa-users"></i><span>@lang('admin.uyeYonetimi')</span>
                    <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    @if(sayfaIzinKontrol('panel/uyeler'))
                        <li class="nav-item"><a class="nav-link" href="{{route('uyeler')}}"><i class="ti-control-record"></i>@lang('admin.uyeYonetimi')</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('uye_aktivite')}}"><i class="ti-control-record"></i>Üye Aktivite</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('uye_ozel_fiyat')}}"><i class="ti-control-record"></i>Üye Özel Fiyat</a></li>
                    @endif

                    @if(sayfaIzinKontrol('panel/kimlik-yonetim'))
                        <li class="nav-item"><a class="nav-link" href="{{route('kimlik_yonetim')}}"><i class="ti-control-record"></i>Kimlik Onaylar</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/kullanici-gruplari'))
                        <li class="nav-item"><a class="nav-link" href="{{route('kullanici_gruplari')}}"><i class="ti-control-record"></i>Kullanıcı Grupları</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/manuel-telefon-onaylama'))
                        <li class="nav-item"><a class="nav-link" href="{{route('telefon_yonetim')}}"><i class="ti-control-record"></i>Telefon Onayları</a></li>
                    @endif
                    @if(sayfaIzinKontrol('panel/panel-loglar'))
                        <li class="nav-item"><a class="nav-link" href="{{route('panel_loglar')}}"><i class="ti-control-record"></i>Panel Loglar</a></li>
                    @endif
                </ul>
            </li>
        @endif
        @if(sayfaIzinKontrol('panel/rapor/odeme-kanallari'))
            <li>
                <a href="javascript:void(0);"><i
                            class="fas fa-users"></i><span>Raporlar</span>
                    <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    @if(sayfaIzinKontrol('panel/rapor/odeme-kanallari'))
                        <li class="nav-item"><a class="nav-link" href="{{route('odeme_kanallari')}}"><i
                                        class="ti-control-record"></i>Ödeme Kanalı Raporu</a></li>
                    @endif
                </ul>
				<ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="#" onclick="alert('Working on it.. queue-1')"><i class="ti-control-record"></i>E-pin Raporu</a></li>
                </ul>
				<ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="#" onclick="alert('Working on it.. queue-3')"><i class="ti-control-record"></i>Oyun Parası Raporu</a></li>
                </ul>
				<ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="#" onclick="alert('Working on it.. queue-2')"><i class="ti-control-record"></i>İlan Raporu</a></li>
                </ul>
				<ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="#" onclick="alert('Working on it.. queue-4')"><i class="ti-control-record"></i>Boss Report</a></li>
                </ul>

            </li>
        @endif
    </ul>
    <ul class="metismenu left-sidenav-menu">
        <button type="button" onclick="startFCM()" class="btn btn-outline-success btn-bildirim w-100">Bildirimlere İzin
            Ver
        </button>
    </ul>
    <ul class="metismenu left-sidenav-menu">
        <button type="button" onclick="location.href='?cacheClear=1'" class="btn btn-outline-secondary w-100">Çerezleri
            Temizle
        </button>
    </ul>
    <ul>
    <p class="mt-4">Günlük Çekim limitleri<br>
        Papara max. : <?=number_format(getCacheSetings()->papara_max,2) ?> TL<br>
        Bankalar max: <?=number_format(getCacheSetings()->banka_max,2) ?> TL
    </p>
    </ul>

</div>
