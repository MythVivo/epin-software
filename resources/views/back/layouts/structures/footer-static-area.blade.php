<div class="footer-info-area bg-gradient">
    <div class="row">
        <div class="btn-group w-100 footer-alt-btn" role="group" aria-label="Basic outlined example">
            @if(sayfaIzinKontrol('panel/ilanlar-yonetim'))
                <button onclick="location.href='{{route('ilanlar_yonetim')}}'" id="ilan-siparisleri" type="button"
                        class="btn btn-success shadow-none">İlan Sip.: 0
                </button>
            @endif
            @if(sayfaIzinKontrol('panel/yorumlar-ilan'))
                <button onclick="location.href='{{route('yorumlarIlan')}}'" id="ilan-yorumlari" type="button"
                        class="btn btn-success shadow-none">İlan Yorum : 0
                </button>
            @endif
            @if(sayfaIzinKontrol('panel/ilanlar-yonetim-buy'))
                <button onclick="location.href='{{route('ilanlar_yonetim_buy')}}'" id="alis-ilanlari" type="button"
                        class="btn btn-success shadow-none">Alış İlan : 0
                </button>
            @endif
            @if(sayfaIzinKontrol('panel/game-gold-yonetim'))
                <button onclick="location.href='{{route('game_gold_yonetim')}}'" id="oyun-parasi-siparisleri"
                        type="button"
                        class="btn btn-success shadow-none">Oyun Parası : 0
                </button>
            @endif
            @if(sayfaIzinKontrol('panel/para-cek-yonetim'))
                <button onclick="location.href='{{route('para_cek_yonetim')}}'" id="para-cekim-talepleri" type="button"
                        class="btn btn-success shadow-none">Para Çekim : 0
                </button>
            @endif
            @if(sayfaIzinKontrol('panel/twitch-para-cek-yonetim'))
                <button onclick="location.href='{{route('twitch_para_cek_yonetim')}}'" id="twitch-para-cekim"
                        type="button"
                        class="btn btn-success shadow-none">Tw Çekim : 0
                </button>
            @endif
            @if(sayfaIzinKontrol('panel/twitch-kesintisiz-yonetim'))
                <button onclick="location.href='{{route('twitch_kesintisi_yonetim')}}'" id="twitch-kesintisiz"
                        type="button"
                        class="btn btn-success shadow-none">Tw Kes. : 0
                </button>
            @endif
            @if(sayfaIzinKontrol('panel/kimlik-yonetim'))
                <button onclick="location.href='{{route('kimlik_yonetim')}}'" id="kimlik-onaylari" type="button"
                        class="btn btn-success shadow-none">Kimlik Onay : 0
                </button>
            @endif
            @if(sayfaIzinKontrol('panel/yorumlar'))
                <button onclick="location.href='{{route('yorumlar')}}'" id="yorumlar" type="button" class="btn btn-success shadow-none">Ürün Yorum : 0</button>
            @endif
                    @if(sayfaIzinKontrol('panel/siparisler'))
                            <button onclick="location.href='{{route('siparisler')}}'" id="siparisler" type="button" class="btn btn-success shadow-none">Epin Sip : 0</button>
                    @endif
                <button onclick="location.href='/panel/siparisler?st=316'" id="razer" type="button" class="btn btn-success shadow-none">Steam Sip: 0</button>
                <button onclick="location.href='{{route('odeme_onay')}}'" id="odeme_onay" type="button" class="btn btn-success shadow-none">Ödeme Onay : 0</button>
        </div>
    </div>
</div>
