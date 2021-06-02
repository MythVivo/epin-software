@extends('front.layouts.app')
@section('body')
<section class="game header-margin pt-100 pb-100">
    <div class="container">

        <div class="row">

            <div class="col-md-3">
                <nav class="d-flex align-items-start nav-aside">

                    <aside class="nav flex-column nav-pills me-3" id="myTab" role="tablist">

                        <button class="nav-link active" id="hesabim-tab" data-bs-toggle="tab" data-bs-target="#hesabim"
                                type="button" role="tab" aria-controls="hesabim" aria-selected="true"><i
                                    class="fas fa-key"></i> Hesabım
                        </button>


                        <button class="nav-link" id="siparis-tab" data-bs-toggle="tab" data-bs-target="#siparis"
                                type="button" role="tab" aria-controls="siparis" aria-selected="false"><i
                                    class="fas fa-shopping-bag"></i> Siparişlerim
                        </button>


                        <button class="nav-link" id="odeme-tab" data-bs-toggle="tab" data-bs-target="#odeme"
                                type="button" role="tab" aria-controls="odeme" aria-selected="false"><i
                                    class="fas fa-credit-card"></i> Ödemelerim
                        </button>

                        <button class="nav-link" id="hesap-ayarlar-tab" data-bs-toggle="tab"
                                data-bs-target="#hesap-ayarlar"
                                type="button" role="tab" aria-controls="hesap-ayarlar" aria-selected="false"><i
                                    class="fas fa-cog"></i> Hesap Ayarlarım
                        </button>

                        <button class="nav-link" id="fatura-tab" data-bs-toggle="tab" data-bs-target="#fatura"
                                type="button" role="tab" aria-controls="fatura" aria-selected="false"><i
                                    class="fas fa-file-invoice"></i> Fatura adresleri
                        </button>

                        <button class="nav-link" id="para-yukleme-tab" data-bs-toggle="tab"
                                data-bs-target="#para-yukleme"
                                type="button" role="tab" aria-controls="para-yukleme" aria-selected="false"><i
                                    class="fas fa-wallet"></i> Para Yükle
                        </button>

                    </aside>

                </nav>

            </div>
            <div class="col-md-9">
<div class="row">

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="hesabim" role="tabpanel" aria-labelledby="hesabim-tab">
                        Hesabım
                    </div>
                    <div class="tab-pane fade" id="siparis" role="tabpanel" aria-labelledby="siparis-tab">

                            <div class="siparis-table">
                                <div class="table-title">
                                    <span>Ürün Görseli</span>
                                    <span>Ürün Adı</span>
                                    <span>ürün Fiyatı</span>
                                    <span>Adet</span>
                                    <span>Toplam Tutar</span>
                                    <span>İşlem tarihi</span>
                                    <span>İşlemler</span>
                                </div>
                                <div class="table-val">
                                    <span><img src="{{asset('/public/front/games_titles/gladiatus-yakut-1619102917.jpg')}}"></span>
                                    <span>Ürün Adı</span>
                                    <span>ürün Fiyatı</span>
                                    <span>Adet</span>
                                    <span>Toplam Tutar</span>
                                    <span>İşlem tarihi</span>
                                    <span>İşlemler</span>
                                </div>
                                <div class="table-val">
                                    <span><img src="{{asset('/public/front/games_titles/gladiatus-yakut-1619102917.jpg')}}"></span>
                                    <span>Ürün Adı</span>
                                    <span>ürün Fiyatı</span>
                                    <span>Adet</span>
                                    <span>Toplam Tutar</span>
                                    <span>İşlem tarihi</span>
                                    <span>İşlemler</span>
                                </div>


                            </div>





                    </div>
                    <div class="tab-pane fade" id="odeme" role="tabpanel" aria-labelledby="odeme-tab">Ödeme</div>
                    <div class="tab-pane fade" id="hesap-ayarlar" role="tabpanel" aria-labelledby="hesap-ayarlar-tab">
                        Hesap Ayarları
                    </div>
                    <div class="tab-pane fade" id="fatura" role="tabpanel" aria-labelledby="fatura-tab">ffatura</div>
                    <div class="tab-pane fade" id="para-yukleme" role="tabpanel" aria-labelledby="para-yukleme-tab">Para
                        yükleme
                    </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</section>
@endsection








