<div class="col-md-3">
    <div class="left-menu-open"><span>Seçenekler Menüsü</span></div>
    <div class="list-group left-user-menu">
        <a class="close-window">Kapat</a>
        <a href="{{route('hesabim')}}"
           class="list-group-item list-group-item-action @if(getPage() == 'hesabim') active @endif "
           @if(getPage() == 'hesabim') aria-current="true" @endif>
            <span><i class="fas fa-key"></i></span> Hesabım
        </a>


        <a data-bs-toggle="collapse" href="#siparislerim" role="button"
           aria-expanded="@if(getPage() == 'siparislerim' or getPage() == 'siparislerim-ilan' or getPage() == 'siparislerim-game-gold') true @endif"
           aria-controls="collapseExample"
           class="list-group-item list-group-item-action more-list @if(getPage() == 'siparislerim' or getPage() == 'siparislerim-ilan' or getPage() == 'siparislerim-game-gold' or getPage() == 'siparislerim-cd-key') active @else collapsed @endif"
           @if(getPage() == 'siparislerim' or getPage() == 'siparislerim-ilan' or getPage() == 'siparislerim-game-gold') aria-current="true" @endif>
            <span><i class="fas fa-shopping-bag"></i></span> Siparişlerim <span class="right-clear"><i
                        class="fas fa-chevron-down"></i><i class="fas fa-chevron-down"></i></span>
        </a>
        <div class="collapse @if(getPage() == 'siparislerim' or getPage() == 'siparislerim-ilan' or getPage() == 'siparislerim-game-gold' or getPage() == 'siparislerim-cd-key') show @endif"
             id="siparislerim">
            <a href="{{route('siparislerim')}}"
               class="list-group-item list-group-item-action @if(getPage() == 'siparislerim') active @endif "
               @if(getPage() == 'siparislerim') aria-current="true" @endif>
                <span><i class="fas fa-chevron-right"></i></span> E-pin Siparişlerim
            </a>
            <a href="{{route('siparislerim_ilan')}}"
               class="list-group-item list-group-item-action @if(getPage() == 'siparislerim-ilan') active @endif "
               @if(getPage() == 'siparislerim-ilan') aria-current="true" @endif>
                <span><i class="fas fa-chevron-right"></i></span> İtem Siparişlerim
            </a>
            <a href="{{route('siparislerim_game_gold')}}"
               class="list-group-item list-group-item-action @if(getPage() == 'siparislerim-game-gold') active @endif "
               @if(getPage() == 'siparislerim-game-gold') aria-current="true" @endif>
                <span><i class="fas fa-chevron-right"></i></span> Oyun Parası Siparişlerim
            </a>
            <a href="{{route('siparislerim_cdkey')}}"
               class="list-group-item list-group-item-action @if(getPage() == 'siparislerim-cd-key') active @endif "
               @if(getPage() == 'siparislerim-cd-key') aria-current="true" @endif>
                <span><i class="fas fa-chevron-right"></i></span> Cd Key Siparişlerim
            </a>
        </div>

        <a href="{{route('favorilerim')}}"
           class="list-group-item list-group-item-action @if(getPage() == 'favorilerim') active @endif "
           @if(getPage() == 'favorilerim') aria-current="true" @endif>
            <span><i class="far fa-bookmark"></i></span> Favorilerim
        </a>

        <a href="{{route('bildirimlerim')}}"
           class="list-group-item list-group-item-action @if(getPage() == 'bildirimlerim') active @endif "
           @if(getPage() == 'bildirimlerim') aria-current="true" @endif>
            <span><i class="fas fa-bell"></i></span> Bildirimlerim
        </a>


        <a href="{{route('odemelerim')}}"
           class="list-group-item list-group-item-action @if(getPage() == 'odemelerim') active @endif "
           @if(getPage() == 'odemelerim') aria-current="true" @endif>
            <span><i class="fas fa-credit-card"></i></span> Ödemelerim
        </a>


        <a data-bs-toggle="collapse" href="#ayarlarim" role="button"
           aria-expanded="@if(getPage() == 'ayarlarim' or getPage() == 'hesap-onayla') true @endif"
           aria-controls="ayarlarim"
           class="list-group-item list-group-item-action more-list  @if(getPage() == 'ayarlarim' or getPage() == 'hesap-onayla') active @else collapsed @endif"
           @if(getPage() == 'ayarlarim' or getPage() == 'hesap-onayla') aria-current="true" @endif>
            <span><i class="fas fa-cog"></i></span> Hesap Ayarlarım <span class="right-clear"><i
                        class="fas fa-chevron-down"></i><i class="fas fa-chevron-down"></i></span>
        </a>
        <div class="collapse @if(getPage() == 'ayarlarim' or getPage() == 'hesap-onayla') show @endif"
             id="ayarlarim">
            <a href="{{route('ayarlarim')}}"
               class="list-group-item list-group-item-action @if(getPage() == 'ayarlarim') active @endif "
               @if(getPage() == 'ayarlarim') aria-current="true" @endif>
                <span><i class="fas fa-cog"></i></span> Hesap Ayarlarım
            </a>
            <a href="{{route('hesap_onayla')}}"
               class="list-group-item list-group-item-action @if(getPage() == 'hesap-onayla') active @endif "
               @if(getPage() == 'hesap-onayla') aria-current="true" @endif>
                <span><i class="fas fa-user-check"></i></span> Hesap Onayla
            </a>
        </div>


        <a href="{{route('fatura_adreslerim')}}"
           class="list-group-item list-group-item-action @if(getPage() == 'fatura-adreslerim') active @endif"
           @if(getPage() == 'fatura-adreslerim') aria-current="true" @endif>
            <span><i class="fas fa-file-invoice"></i></span> Fatura Bilgilerim
        </a>
        <a href="{{route('hizli_menu')}}"
           class="list-group-item list-group-item-action @if(getPage() == 'hizli-menu') active @endif "
           @if(getPage() == 'hizli-menu') aria-current="true" @endif>
            <span><i class="fas fa-bars"></i></span> Hızlı Menü
        </a>
        <a href="{{route('alici_panelim')}}"
           class="list-group-item list-group-item-action @if(getPage() == 'alici-panelim') active @endif"
           @if(getPage() == 'alici-panelim') aria-current="true" @endif>
            <span><i class="fas fa-shopping-basket"></i></span> Alıcı Panelim
        </a>
        <a href="{{route('satici_panelim')}}"
           class="list-group-item list-group-item-action @if(getPage() == 'satici-panelim') active @endif"
           @if(getPage() == 'satici-panelim') aria-current="true" @endif>
            <span><i class="fas fa-shopping-basket"></i></span> Satıcı Panelim
        </a>
        @if(DB::table('twitch_support_streamer')->where('user', Auth::user()->id)->count() > 0)
            <a data-bs-toggle="collapse" href="#yayinci_panelim" role="button"
               aria-expanded="@if(getPage() == 'twitch-support/yayinci-panelim' or getPage() == 'twitch-support/yayinci-ayarlarim' or getPage() == 'twitch-support/yayinci-bakiye-cevir' or getPage() == 'twitch-support/yayinci-kesintisiz' or getPage() == 'twitch-support/yayinci-ol') true @endif"
               aria-controls="yayinci_panelim"
               class="list-group-item list-group-item-action more-list @if(getPage() == 'twitch-support/yayinci-panelim' or getPage() == 'twitch-support/yayinci-ayarlarim' or getPage() == 'twitch-support/yayinci-bakiye-cevir' or getPage() == 'twitch-support/yayinci-kesintisiz' or getPage() == 'twitch-support/yayinci-ol')  active @else collapsed @endif"
               @if(getPage() == 'twitch-support/yayinci-panelim' or getPage() == 'twitch-support/yayinci-ayarlarim' or getPage() == 'twitch-support/yayinci-bakiye-cevir' or getPage() == 'twitch-support/yayinci-kesintisiz' or getPage() == 'twitch-support/yayinci-ol')
               aria-current="true" @endif>
                <span><i><img src="{{asset('public/front/images/streamlabs.png')}}"
                              width="22" height="22"></i></span> Yayıncı Panelim <span class="right-clear"><i
                            class="fas fa-chevron-down"></i><i class="fas fa-chevron-down"></i></span>
            </a>
            <div class="collapse @if(getPage() == 'twitch-support/yayinci-panelim' or getPage() == 'twitch-support/yayinci-ayarlarim' or getPage() == 'twitch-support/yayinci-bakiye-cevir' or getPage() == 'twitch-support/yayinci-kesintisiz' or getPage() == 'twitch-support/yayinci-ol') show @endif"
                 id="yayinci_panelim">
                <a href="{{route('twitch_support_yayinci_ol')}}"
                   class="list-group-item list-group-item-action @if(getPage() == 'twitch-support/yayinci-ol') active @endif"
                   @if(getPage() == 'twitch-support/yayinci-ol') aria-current="true" @endif>
                    <span><i class="fas fa-chevron-right"></i></span> Yayıncı Ol
                </a>
                <a href="{{route('twitch_support_yayinci_ayarlarim')}}"
                   class="list-group-item list-group-item-action @if(getPage() == 'twitch-support/yayinci-ayarlarim') active @endif"
                   @if(getPage() == 'twitch-support/yayinci-ayarlarim') aria-current="true" @endif>
                    <span><i class="fas fa-chevron-right"></i></span> Yayıncı Ayarlarım
                </a>
                <a href="{{route('twitch_support_yayinci_panelim')}}"
                   class="list-group-item list-group-item-action @if(getPage() == 'twitch-support/yayinci-panelim') active @endif"
                   @if(getPage() == 'twitch-support/yayinci-panelim') aria-current="true" @endif>
                    <span><i class="fas fa-chevron-right"></i></span> Gelen Bağışlar
                </a>
                <a href="{{route('twitch_support_yayinci_bakiye_cevir')}}"
                   class="list-group-item list-group-item-action @if(getPage() == 'twitch-support/yayinci-bakiye-cevir') active @endif"
                   @if(getPage() == 'twitch-support/yayinci-bakiye-cevir') aria-current="true" @endif>
                    <span><i class="fas fa-chevron-right"></i></span> Bakiye Çevir
                </a>
                <a href="{{route('twitch_support_yayinci_kesintisiz')}}"
                   class="list-group-item list-group-item-action @if(getPage() == 'twitch-support/yayinci-kesintisiz') active @endif"
                   @if(getPage() == 'twitch-support/yayinci-kesintisiz')) aria-current="true" @endif>
                    <span><i class="fas fa-chevron-right"></i></span> Kesintisiz Yayıncı Başvurusu
                </a>
            </div>
        @else
            <a href="{{route('twitch_support_yayinci_ol')}}"
               class="list-group-item list-group-item-action @if(getPage() == 'twitch-support/yayinci-ol') active @endif"
               @if(getPage() == 'twitch-support/yayinci-ol') aria-current="true" @endif>
                <span><i><img src="{{asset('public/front/images/streamlabs.png')}}"
                              width="22" height="22"></i></span> Yayıncı Ol
            </a>
        @endif
        <a href="{{route('yayin_bagislarim')}}"
           class="list-group-item list-group-item-action @if(getPage() == 'yayin-bagislarim') active @endif"
           @if(getPage() == 'yayin-bagislarim') aria-current="true" @endif>
            <span><i class="fab fa-twitch"></i></span> Yayıncı Bağışlarım
        </a>
        <a href="{{route('bakiye_cek')}}"
           class="list-group-item list-group-item-action @if(getPage() == 'bakiye-cek') active @endif"
           @if(getPage() == 'bakiye-cek') aria-current="true" @endif>
            <span><i class="fas fa-money-check"></i></span> Para Çek
        </a>
        <a href="{{route('bakiye_ekle')}}"
           class="list-group-item list-group-item-action @if(getPage() == 'bakiye-ekle') active @endif"
           @if(getPage() == 'bakiye-ekle') aria-current="true" @endif>
            <span><i class="fas fa-wallet"></i></span> Para Yükle
        </a>
    </div>
    
</div>
