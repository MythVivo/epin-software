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
    $u = DB::table('pazar_yeri_ilanlar')->where('id', end($ilan))->first();
    array_pop($ilan);
    $ilanIsmi = implode("-", $ilan);
    if (!$u or $ilanIsmi != Str::slug($u->title)) {
        header("Location: " . URL::to(route('errors_404')), true, 302);
        exit();
    }
    ?>
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">

                <section class="game">
                    <div class="container">


                        @if(session('error'))
                            <div class="row">
                                <div class="alert alert-danger" role="alert">
                                    <h4 class="alert-heading">{{session('error')}}</h4>
                                </div>
                            </div>
                        @endif


                        <div class="row overflow-table">
                            <table class="table table-hover table-bordered text-center item-checkout-table">
                                <thead>
                                <tr class="table-secondary">
                                    <th>@lang('general.resim')</th>
                                    <th>@lang('general.adi')</th>
                                    <th>@lang('general.fiyat')</th>
                                    <th>Bakiyeniz</th>
                                    <th>Satın Alım Sonrası Bakiyeniz</th>
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
                                                                        <?php
                                                                        $ilanIcerik = DB::table('pazar_yeri_ilan_icerik')->where('ilan', $t->ilan)->first();
                                                                        ?>
                                                                        @if($ilanIcerik)
                                                                            <?php
                                                                            $photo = DB::table('games_titles_items_photos')->where('item', $ilanIcerik->item)->first();
                                                                            ?>
                                                                            @if($photo)
                                                                                <img src="{{asset('public/front/games_items/'.$photo->image)}}"
                                                                                     class="card-img-top">
                                                                            @endif
                                                                        @endif
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
                                                @if(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->count() > 1)
                                                    <div id="carouselExampleControls" class="carousel slide"
                                                         data-bs-ride="carousel">
                                                        <div class="carousel-inner">
                                                            @foreach(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->get() as $uu)
                                                                <div class="carousel-item @if($loop->first) active @endif">
                                                                    <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', $uu->item)->first()->image)}}"
                                                                         class="d-block w-100"
                                                                         alt="{{DB::table('games_titles_items_info')->where('id', $uu->item)->first()->title}} görseli">
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
                                                @else
                                                    <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->first()->item)->first()->image)}}"
                                                         class="card-img-top" alt="...">
                                                @endif
                                            @else
                                                <img src="{{asset('public/front/ilanlar/'.$u->image)}}"
                                                     class="card-img-top" alt="{{$u->title}} Görseli">
                                            @endif
                                        @endif

                                    </td>
                                    <td>{{$u->title}}</td>
                                    <td>{{MF($u->price)}} TL</td>
                                    <td>
                                        @if(isset(Auth::user()->id))
                                            {{MF(Auth::user()->bakiye)}} TL
                                        @else
                                            Bakiyenizi Görmek İçin Giriş Yapın
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset(Auth::user()->id))
                                            @if(Auth::user()->bakiye - $u->price < 0)
                                                İşlem için yeterli bakiyeniz yok
                                            @else
                                                {{Auth::user()->bakiye - $u->price}} TL
                                            @endif
                                        @else
                                            Bakiyenizi Görmek İçin Giriş Yapın
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-12 p-0 d-flex justify-content-center align-self-center">
                                <div class="confirmation card text-center mt-100 mb-100">
                                    <div class="card-body">
                                        @if($u->status == 1)
                                            <h5 class="card-title">@lang('general.siparisOnay')</h5>
                                            @if(isset(Auth::user()->id))
                                                @if(Auth::user()->bakiye >= $u->price)
                                                    <form method="post" action="{{route('ilan_satin_al')}}">
                                                        @csrf
                                                        <input type="hidden" name="ilan" value="{{$u->id}}">
                                                        <label for="note" class="form-label">Teslim Edilecek Kullanıcı
                                                            Adı</label><br>
                                                        <input id="note" type="text" class="form-control style-input"
                                                               name="note"
                                                               placeholder="Teslim Edilecek Kullanıcı Adı" required>
                                                        <button type="submit"
                                                                class="btn-inline color-blue mt-3"
                                                                onclick="gonderiliyor()">@lang('general.onayla')</button>
                                                        @include('front.plugins.siparisiniz-isleniyor')
                                                    </form>
                                                @else
                                                    <div class="alert alert-danger fade show d-flex align-items-center"
                                                         role="alert">

                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h5>
                                                                    Satın Alma işlemi için yeterli bakiyeniz yok, lütfen
                                                                    bakiye ekleyin.
                                                                </h5>
                                                            </div>
                                                            <div class="col-12 mt-4">
                                                                <button type="button"
                                                                        class="btn-inline color-red"
                                                                        onclick="location.href='{{route('bakiye_ekle')}}'">
                                                                    Bakiye Ekle
                                                                </button>
                                                            </div>
                                                        </div>


                                                    </div>
                                                @endif
                                            @else
                                                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
                                                     role="alert">
                                                    <i class="fas fa-exclamation-triangle me-3"></i>
                                                    <h5>
                                                        Bu ilanı satın alabilmek için lütfen giriş yapın.
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

            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        function gonderiliyor() {

            $("body").append($(".process-screen").addClass("open"))

            //bunu form submit fonksiyonuna bağlayabilir misin abi tasarımı bitirdikten sonra

        }
    </script>
@endsection
