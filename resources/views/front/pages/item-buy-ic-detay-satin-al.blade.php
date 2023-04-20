@extends('front.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        td {
            vertical-align: middle;
        }
    </style>
@endsection
@section('body')
    <?php
    $item = DB::table('games_titles')->where('link', $item)->first();
    $ilan = explode("-", $ilan);
    $u = DB::table('pazar_yeri_ilanlar_buy')->where('id', end($ilan))->first();
    array_pop($ilan);
    $ilanIsmi = implode("-", $ilan);
    if (!$u or $ilanIsmi != Str::slug($u->title)) {
        header("Location: " . URL::to(route('errors_404')), true, 302);
        exit();
    }
    ?>
    <div class="modal fade" id="sozlesme" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="exampleModalFullscreenLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title h4 text-white" id="exampleModalFullscreenLabel">Alış İlanı Sözleşmesi</h5>
                </div>
                <div class="modal-body bg-dark text-left">
                    <h5 style="color: var(--main-dark-link-color)!important; font-weight: 400;">
                        İlan oluşturabilmeniz için aşağıdaki sözleşmeyi okuyup onaylamanız gerekmektedir.<br><br>
                        ALICI: İlanı oluşturan kişidir. <br>
                        SATICI: İlanı uygun görmesi durumunda işlemi başlatan kişidir. <br><br>
                        1. Alış ilanı süresiz olarak oluşturulmaktadır. ALICI, SATICI işlem başlatmadığı sürece istediği
                        zaman ilanı kaldırma hakkına sahiptir. İlan oluşturulması için herhangi bir alt limit
                        bulunmamaktadır. <br>
                        2. Alış ilanlarında komisyon tutarı SATICI ‘dan alınır. ALICI ‘dan yazdığı tutar haricinde
                        herhangi bir ücret alınmaz.<br>
                        3. ALICI ilan oluşturulabilmesi için almak istenilen ilan tutarının %3 ‘ü kadar hesabında bakiye
                        bulundurması gerekir. İlan oluşturulduğunda bu tutara bloke koyulur.<br>
                        4. ALICI oluşturmuş olduğu ilan için işlem başlatılmadığı sürece değiştirme ve silme hakkına
                        sahiptir.<br>
                        5. SATICI tarafından herhangi bir işlem başlatılmadan ilanın iptal edilmesi durumunda blokeli
                        tutar ALICI ‘nın hesabına aktarılır.<br>
                        6. SATICI işlem başlatması durumunda ilan içeriği tarafımızca teslim alınır. Arama ve SMS
                        yoluyla ALICI ‘ya bilgilendirme sağlanır. Blokeli tutar harici kalan tutarın yüklenmesi için 60
                        dakika süre verilir.<br>
                        7. Tutarın yüklenmesi durumunda; ALICI ‘nın hesabından blokeli tutar harici tutar çekilir ve
                        tarafına ilan içeriği teslim edilir. İşlem tarafımızca başarılı olarak onaylanır ve SATICI ‘ya
                        hak edişi aktarılır.<br>
                        8. Tutarın yüklenmemesi durumunda; ALICI ‘nın ilanı oluştururken hesabına koyulan bloke tutarı
                        yanar ve tarafına iadesi sağlanmaz. İşlem başarısız sayılır ve ilan içeriği SATICI ‘ya teslim
                        edilir.<br>
                        9. Başarısız işlemin 3 kere tekrarlanması durumunda ALICI kişinin hesabına Alış İlanı Ekleme
                        özelliği bloke edilir. Tekrar ilan açması yasaklanır.<br>
                        10. İşlem başlatıldığından işlem sonlanmasına kadar bütün sorumluluk OYUNEKS BİLİŞİM ‘e aittir.
                        İşlemin başarılı olarak tamamlanmasının ardından OYUNEKS BİLİŞİM hiçbir sorumluluk ve zan
                        altında bırakılmayacaktır.<br><br>
                        <span class="text-danger"><b>
                                Bütün işlem süreci sadece CANLI DESTEK üzerinden yürütülecektir. CANLI DESTEK haricinde OYUNEKS BİLİŞİM sizinle başka hiçbir yerden görüşme sağlamaz. Oyun içerisinde PM atmaz. CANLI DESTEK harici yapılan bütün işlemler kişilerin sorumluluğundadır.
                                </b></span>
                    </h5>

                </div>
                <div class="modal-footer bg-dark">
                    <button type="button" onclick="history.back();" class="btn btn-danger">İptal Et Ve Çık</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-success">Okudum, Anladım, Onaylıyorum
                    </button>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-gray pb-100">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-4">
                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">{{session('error')}}</h4>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <table class="table table-hover table-bordered text-center item-checkout-table">
                        <thead>
                        <tr class="table-secondary">
                            <th>@lang('general.resim')</th>
                            <th>@lang('general.adi')</th>
                            <th>@lang('general.fiyat')</th>
                            <th>Kazancınız</th>
                            <th>Bakiyeniz</th>
                            <th>Satış Sonrası Bakiyeniz</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="w-25">
                                @if($u->toplu == 1)
                                    @if($u->type == 0)
                                        <div class="item-image">
                                            <figure>
                                                <div id="carouselExampleIndicators" class="carousel slide"
                                                     data-bs-ride="carousel">
                                                    <div class="carousel-inner">
                                                        @foreach(DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u->id)->get() as $t)
                                                            <div class="carousel-item @if($loop->iteration == 1) active @endif">
                                                                <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', DB::table('pazar_yeri_ilan_icerik')->where('ilan', $t->ilan)->first()->item)->first()->image)}}"
                                                                     class="card-img-top">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button class="carousel-control-prev" type="button"
                                                            data-bs-target="#carouselExampleIndicators"
                                                            data-bs-slide="prev">
                                                                    <span class="carousel-control-prev-icon"
                                                                          aria-hidden="true"></span>
                                                        <span class="visually-hidden">Önceki</span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button"
                                                            data-bs-target="#carouselExampleIndicators"
                                                            data-bs-slide="next">
                                                                    <span class="carousel-control-next-icon"
                                                                          aria-hidden="true"></span>
                                                        <span class="visually-hidden">Sonraki</span>
                                                    </button>
                                                </div>
                                            </figure>
                                        </div>
                                    @else
                                        <div id="carouselExampleControls" class="carousel slide"
                                             data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                @foreach(DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u->id)->get() as $t)
                                                    <div class="carousel-item @if($loop->first) active @endif">
                                                        <img src="{{asset('public/front/ilanlar/'.DB::table('pazar_yeri_ilanlar')->where('id', $t->ilan)->first()->image)}}"
                                                             class="d-block w-100"
                                                             alt="{{$u->title}} görseli">
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button class="carousel-control-prev" type="button"
                                                    data-bs-target="#carouselExampleControls"
                                                    data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon"
                                                              aria-hidden="true"></span>
                                                <span class="visually-hidden">Önceki</span>
                                            </button>
                                            <button class="carousel-control-next" type="button"
                                                    data-bs-target="#carouselExampleControls"
                                                    data-bs-slide="next">
                                                        <span class="carousel-control-next-icon"
                                                              aria-hidden="true"></span>
                                                <span class="visually-hidden">Sonraki</span>
                                            </button>
                                        </div>
                                    @endif
                                @else
                                    @if($u->type == 0)
                                        <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u->id)->first()->item)->first()->image)}}"
                                             class="card-img-top" alt="...">
                                    @else
                                        <img src="{{asset('public/front/ilanlar/'.$u->image)}}"
                                             class="card-img-top" alt="{{$u->title}} Görseli">
                                    @endif
                                @endif

                            </td>
                            <td>{{$u->title}}</td>
                            <td>₺{{MF($u->price)}}</td>
                            <td>₺{{MF($u->price - $u->moment_komisyon)}}</td>
                            <td>
                                @if(isset(Auth::user()->id))
                                    ₺{{MF(Auth::user()->bakiye+Auth::user()->bakiye_cekilebilir)}}
                                @else
                                    Bakiyenizi Görmek İçin Giriş Yapın
                                @endif
                            </td>
                            <td>
                                @if(isset(Auth::user()->id))
                                    ₺{{Auth::user()->bakiye + Auth::user()->bakiye_cekilebilir+ ($u->price - $u->moment_komisyon)}}
                                @else
                                    Bakiyenizi Görmek İçin Giriş Yapın
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>

            <div class="row">
                <div class="col-6 offset-md-3 align-self-center">
                    <div class="card text-center mt-100 mb-100">
                        <div class="card-body">
                            @if($u->status == 1)
                                <h5 class="card-title">@lang('general.siparisOnay')</h5>
                                @if(isset(Auth::user()->id))
                                    <form method="post" action="{{route('ilan_buy_satin_al')}}">
                                        @csrf
                                        <input type="hidden" name="ilan" value="{{$u->id}}">
                                        <label for="note" class="form-label">Teslim Edecek Kullanıcı
                                            Adı</label><br>
                                        <input id="note" type="text" class="form-control" name="note"
                                               placeholder="Teslim Edecek Kullanıcı Adı" required>
                                        <button type="submit"
                                                class="btn btn-primary mt-3"
                                                onclick="gonderiliyor()">@lang('general.onayla')</button>
                                        @include('front.plugins.siparisiniz-isleniyor')
                                    </form>
                                @else
                                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
                                         role="alert">
                                        <i class="fas fa-exclamation-triangle me-3"></i>
                                        <h5>
                                            Bu itemi satabilmek için lütfen giriş yapın.
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        <button type="button" class="btn btn-outline-success btn-sm"
                                                onclick="location.href='{{route('giris')}}'">
                                            Giriş Yap
                                        </button>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-danger fade show d-flex align-items-center"
                                     role="alert">
                                    <h5>Bu ilan zaten satılmış!</h5>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
@endsection
@section('js')
    <script>
        $(window).on('load', function () {
            $('#sozlesme').modal('show');
        });
    </script>
    <script>
        function gonderiliyor() {

            $("body").append($(".process-screen").addClass("open"))

            //bunu form submit fonksiyonuna bağlayabilir misin abi tasarımı bitirdikten sonra

        }
    </script>
@endsection
