@extends('front.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'css/icons.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
<?php
$item = DB::table('games_titles')
    ->where('link', $item)
    ->first();
$ilan = explode('-', $ilan);
$u = DB::table('pazar_yeri_ilanlar_buy')
    ->where('id', end($ilan))
    ->first();
array_pop($ilan);
$ilanIsmi = implode('-', $ilan);
if (!$u or $ilanIsmi != Str::slug($u->title)) {
    header('Location: ' . URL::to(route('errors_404')), true, 302);
    exit();
}
?>
@section('head')
    <meta name="description" content="{{ $u->title }}">
    <meta name="keywords" content="{{ $u->title }}">
@endsection
@section('body')
<style>
    @media only screen and (min-width: 1400px) {
        .titleContainer {
            display: flex;
            flex-direction: row;
        }

        .lSeen {
            min-width: 220px !important;
            padding-left: 10px !important;
            margin-left: auto !important;
        }
    }
</style>
    <section class="pb-100 bg-gray ">
        <div class="container">
            <div class="row">

                <div class="col-12 mt-4">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">{{ session('success') }}</h4>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">{{ session('error') }}</h4>
                        </div>
                    @endif
                    <div class="row">
                        <div class="item-detay">
                            <div class="row c-margin">
                                <div class="col-md-12 col-sm-12">
                                    <div class="card-column flex-b">
                                        <div class="card-column-container flex-b">
                                            <h3 class="card-title">Alıcıya Dair Bilgiler</h3>
                                            <p class="card-text">Başarılı Alış :
                                                <strong>{{ DB::table('pazar_yeri_ilanlar_buy')->where('user', $u->user)->whereNull('deleted_at')->where('userStatus', '1')->where('status', '6')->count() }}</strong>
                                            </p>
                                            <p class="card-text">Başarısız Alış :
                                                <strong>{{ DB::table('pazar_yeri_ilanlar_buy')->where('user', $u->user)->whereNull('deleted_at')->where('userStatus', '1')->where('status', '9')->count() }}</strong>
                                            </p>
                                            <p class="card-text">Devam Eden Alış :
                                                <strong>{{ DB::table('pazar_yeri_ilanlar_buy')->where('user', $u->user)->whereNull('deleted_at')->where('userStatus', '1')->where('status', '1')->count() }}</strong>
                                            </p>
                                        </div>
                                        <div class="card-footer flex-e">
                                            <button class="btn-inline color-blue small"
                                                onclick="location.href='{{ route('satici', [$u->user]) }}'">Alıcıyı
                                                Gör
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item-detay-page">
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">

                                        <div class="card border-radius-20 item-detay-sol" style="width: 100%;">
                                            @if ($u->toplu == 1)
                                                @if ($u->type == 0)
                                                    <div class="item-image">
                                                        <figure>
                                                            <div id="carouselExampleIndicators" class="carousel slide"
                                                                data-bs-ride="carousel">
                                                                <div class="carousel-inner">
                                                                    @foreach (DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u->id)->get() as $t)
                                                                        <div
                                                                            class="carousel-item @if ($loop->iteration == 1) active @endif">
                                                                            <img src="{{ asset('public/front/games_items/' .DB::table('games_titles_items_photos')->where('item',DB::table('pazar_yeri_ilan_icerik')->where('ilan', $t->ilan)->first()->item)->first()->image) }}"
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
                                                            @foreach (DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u->id)->get() as $t)
                                                                <div
                                                                    class="carousel-item @if ($loop->first) active @endif">
                                                                    <img src="{{ asset('public/front/ilanlar/' .DB::table('pazar_yeri_ilanlar')->where('id', $t->ilan)->first()->image) }}"
                                                                        class="d-block w-100"
                                                                        alt="{{ $u->title }} görseli">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <button class="carousel-control-prev" type="button"
                                                            data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                                            <span class="carousel-control-prev-icon"
                                                                aria-hidden="true"></span>
                                                            <span class="visually-hidden">Önceki</span>
                                                        </button>
                                                        <button class="carousel-control-next" type="button"
                                                            data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                                            <span class="carousel-control-next-icon"
                                                                aria-hidden="true"></span>
                                                            <span class="visually-hidden">Sonraki</span>
                                                        </button>
                                                    </div>
                                                @endif
                                            @else
                                                @if ($u->type == 0)
                                                    @if (DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->count() > 1)
                                                        <div id="carouselExampleControls" class="carousel slide"
                                                            data-bs-ride="carousel">
                                                            <div class="carousel-inner">
                                                                @foreach (DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->get() as $uu)
                                                                    <div
                                                                        class="carousel-item @if ($loop->first) active @endif">
                                                                        <img src="{{ asset('public/front/games_items/' .DB::table('games_titles_items_photos')->where('item', $uu->item)->first()->image) }}"
                                                                            class="d-block w-100"
                                                                            alt="{{ DB::table('games_titles_items_info')->where('id', $uu->item)->first()->title }} görseli">
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
                                                        <img src="{{ asset('public/front/games_items/' .DB::table('games_titles_items_photos')->where('item',DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u->id)->first()->item)->first()->image) }}"
                                                            class="card-img-top border-radius-20" alt="...">
                                                    @endif
                                                @else
                                                    <img src="{{ asset('public/front/ilanlar/' . $u->image) }}"
                                                        class="card-img-top" alt="{{ $u->title }} Görseli">
                                                @endif
                                            @endif

                                        </div>
                                        <div class="card-column flex-b mobile-frame">
                                            <div class="card-footer flex-e">
                                                <button class="btn-inline color-blue small"
                                                    onclick="location.href='{{ route('satici', [$u->user]) }}'">Alıcıyı
                                                    Gör
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">

                                        <div class="card border-radius-20 full-h borderoff" style="width: 100%;">
                                            <div class="card-body card-left card-flex-body">
                                                <div class="card-column">
                                                    <h3 class="card-title titleContainer">{{ $u->title }}
                                                        @if (isset(Auth::user()->id))
                                                            @if (DB::table('favoriler')->where('user', Auth::user()->id)->where('favoriId', $u->id)->where('type', '1')->whereNull('deleted_at')->count() > 0)
                                                                <a href="{{ route('favori_kaldir', ['1', $u->id]) }}"><i
                                                                        class="fas fa-star text-warning"></i></a>
                                                            @else
                                                                <a href="{{ route('favori_ekle', ['1', $u->id]) }}"><i
                                                                        class="far fa-star text-warning"></i></a>
                                                            @endif
                                                        @else
                                                            <a href="{{ route('favori_ekle', ['1', $u->id]) }}"><i
                                                                    class="far fa-star text-warning"></i></a>
                                                        @endif
                                                        <p class="card-text lSeen">Son Görülme : {!! userLastSeen($u->user) !!}</p>
                                                    </h3>
                                                    <p class="card-text">
                                                        <span style="font-weight:bold">İlan no</span> : {{ $u->id }}
                                                    </p>
                                                    <p class="card-text">
                                                        <span style="font-weight:bold">İlan Yayın Tarihi</span> : {{ date('d-m-Y', strtotime($u->created_at)) }}
                                                    </p>
                                                    <p class="card-text">
                                                        <span style="font-weight:bold">İlan Son Güncellenme</span> :
                                                        {{ date('d-m-Y', strtotime($u->updated_at)) }}
                                                    </p>

                                                    <p class="card-text">
                                                        <span style="font-weight:bold">Sunucu</span> : {{ ucfirst($u->sunucu) }}
                                                    </p>
                                                    @php
                                                        $i_icerik = DB::table('pazar_yeri_ilan_icerik_buy')
                                                            ->where('ilan', $u->id)
                                                            ->get();
                                                            if (sizeof($i_icerik) > 0) {
                                                                $itemId = $i_icerik[0]->item;
                                                                $features = (array) DB::select("SELECT feature,value FROM `games_titles_items` WHERE `item` =  $itemId");
                                                                $in = [];
                                                                foreach ($features as $feature) {
                                                                    $in[] = $feature->feature;
                                                                }
                                                        
                                                                $in = implode(',', $in);
                                                                $featNames = DB::select("select * from games_titles_features where id in ($in)");
                                                                $featNames = json_decode(json_encode($featNames), true);
                                                                $featNamesOrdered = [];
                                                                foreach ($featNames as $featName) {
                                                                    $featNamesOrdered[$featName['id']] = $featName['title'];
                                                                }
                                                                    $featuresMerged = [];
                                                                     foreach ($features as $feature) {
                                                                        $prevFeats = @$featuresMerged[$feature->feature] ? $featuresMerged[$feature->feature] : '';
                                                                        $prevFeats .= ucfirst(str_replace("hayir","hayır",$feature->value)).',';
                                                                        $featuresMerged[$feature->feature] = $prevFeats;
                                                                    } 
                                                                  foreach ($featuresMerged as $key => $value) {
                                                                    if($featNamesOrdered[$key] == 'Sunucu')
                                                                        continue;
                                                                        echo '
                                                                    <p class="card-text">
                                                                        <span style="font-weight:bold">'.
                                                                        $featNamesOrdered[$key] .
                                                                        '</span> : ' .
                                                                        trim($value,',') .
                                                                        '
                                                                    </p>
                                                                    ';
                                                                    } 
                                                                /* foreach ($features as $feature) {
                                                                    if($featNamesOrdered[$feature->feature] == 'Sunucu')
                                                                        continue;
                                                                    echo '
                                                                    <p class="card-text">
                                                                        ' .
                                                                        $featNamesOrdered[$feature->feature] .
                                                                        ' : ' .
                                                                        ucfirst(str_replace("hayir","hayır",$feature->value)) .
                                                                        '
                                                                    </p>
                                                                    ';
                                                                } */
                                                            }
                                                    @endphp
                                                    @foreach ($features = DB::select("SELECT games_titles_features.title,pazar_yeri_ilan_features.value FROM `pazar_yeri_ilan_features` INNER join games_titles_features on games_titles_features.id = pazar_yeri_ilan_features.feature WHERE pazar_yeri_ilan_features.ilan ='$u->id' ") as $feature)
                                                        @if (strtolower($feature->title) != 'sunucu')
                                                            <p class="card-text">
                                                                <span style="font-weight:bold">{{ $feature->title }}</span> : {{ ucfirst(str_replace('hayir','hayır',$feature->value)) }}
                                                            </p>
                                                        @endif
                                                    @endforeach

                                                </div>
                                                @if ($u->text != '')
                                                    <div class="card-column mt-3">
                                                        <h3 class="card-title">Ürün Açıklaması</h3>
                                                        <p class="card-text">{{ $u->text }}</p>
                                                        @if ($u->teslimat != '')
                                                            <h3 class="card-title">Teslimat Saati</h3>
                                                            <p class="card-text">{{ $u->teslimat }}</p>
                                                        @endif
                                                    </div>
                                                @endif
                                                @if ($u->toplu == 1)
                                                    <div class="card-column mt-3">
                                                        <h3 class="card-title">İlan İçeriği</h3>
                                                        @foreach (DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u->id)->get() as $t)
                                                            <?php
                                                            $ilan = DB::table('pazar_yeri_ilanlar')
                                                                ->where('id', $t->ilan)
                                                                ->first();
                                                            $item = DB::table('games_titles')
                                                                ->where('id', $ilan->pazar)
                                                                ->first();
                                                            ?>
                                                            <p class="card-text">
                                                                <a href="{{ route('item_ic_detay', [$item->link, $ilan->sunucu, Str::slug($ilan->title) . '-' . $ilan->id]) }}"
                                                                    target="_blank">
                                                                    {{ mb_substr($ilan->title, 0, 30) }}
                                                                    @if (strlen($ilan->title) > 30)
                                                                        ...
                                                                    @endif
                                                                </a>
                                                            </p>
                                                        @endforeach

                                                    </div>
                                                @endif
                                                <div class="card-footer mt-3 space-b flex-order">
                                                    <span class="card-text">₺{{ MF($u->price) }}

                                                    </span>
                                                    <div class="kazanc">
                                                        <p>Satıştan elde edeceğiniz kazanç
                                                            <span>₺{{ MF($u->price - $u->moment_komisyon) }}</span> </p>
                                                    </div>
                                                    @if ($u->status == 1)
                                                        <button class="btn-inline color-darkgreen"
                                                            onclick="location.href='{{ route('item_buy_ic_detay_satin_al', [$item->link, $u->sunucu, Str::slug($u->title) . '-' . $u->id]) }}'">
                                                            Sat
                                                        </button>
                                                    @else
                                                        <button class="btn-inline color-blue">
                                                            Bu ilana İtem Satılmış
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-12">

                                        <div class="card border-radius-20 full-h borderoff" style="width: 100%;">
                                            <div class="card-body card-flex-body card-right">
                                                <div class="card-column display-none">
                                                    <div class="card-column-container">
                                                        <h3 class="card-title">Alıcıya Dair Bilgiler</h3>
                                                        <p class="card-text">Başarılı Allış :
                                                            <strong>{{ DB::table('pazar_yeri_ilanlar_buy')->where('user', $u->user)->where('status', '6')->count() }}</strong>
                                                        </p>
                                                        <p class="card-text">Başarısız Alış :
                                                            <strong>{{ DB::table('pazar_yeri_ilanlar_buy')->where('user', $u->user)->where('status', '2')->count() }}</strong>
                                                        </p>
                                                        <p class="card-text">Devam Eden Alış :
                                                            <strong>{{ DB::table('pazar_yeri_ilanlar_buy')->where('user', $u->user)->where('status', '1')->count() }}</strong>
                                                        </p>

                                                        <p class="card-text">Alıcı Durumu
                                                            : {!! userSeeControl($u->user) !!}</p>
                                                    </div>
                                                    <div class="card-footer flex-e">
                                                        <button class="btn-inline color-blue small border"
                                                            onclick="location.href='{{ route('satici', [$u->user]) }}'">
                                                            Alıcıyı Gör
                                                        </button>

                                                    </div>
                                                </div>

                                                <style>
                                                    .yorum-name span {
                                                        font-size: 12px !important;
                                                    }
                                                </style>
                                                <div class="card-column">
                                                    <h4 class="card-title">Yorumlar</h4>
                                                    <div class="comment-wrapper">
                                                        @if (DB::table('ilan_yorumlar')->where('buy', '1')->whereNull('deleted_at')->where('ilan', $u->id)->where('status', '1')->count() > 0)
                                                            @foreach (DB::table('ilan_yorumlar')->where('buy', '1')->whereNull('deleted_at')->where('ilan', $u->id)->where('status', '1')->get() as $yy)
                                                                <div
                                                                    class="comment-container @if ($yy->user == $u->user) satici @endif">
                                                                    <div class="this-comment">
                                                                        <div class="commenter">
                                                                            <h6 class="yorum-name">**** {!! userLastSeen($yy->user) !!}</h6>
                                                                            <div class="c_date">
                                                                                {{ $yy->created_at }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="cmnt-text">
                                                                            {{ $yy->text }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                    </div>
                                                    <div class="comments-container">
                                                        <p><span class="w_ico"><i class="fal fa-comment-alt"></i></span>
                                                            Henüz bu ilana bir yorum gelmemiş, ilk yorum yapan siz
                                                            olun!</p>

                                                    </div>
                                                    @endif


                                                </div>

                                                <div class="comment-form closed">
                                                    <div class="comment-form-header">
                                                        <h4 class="card-title">Yorum
                                                            Yap</h4> <span id="message-send-close"><i
                                                                class="fal fa-times-circle"></i></span>
                                                    </div>

                                                    @if (isset(Auth::user()->id))
                                                        <form method="post" action="{{ route('ilan_yorum_yap') }}">
                                                            <input type="hidden" name="buy" value="1">
                                                            @csrf
                                                            <input type="hidden" name="ilan"
                                                                value="{{ $u->id }}">
                                                            <div class="row">
                                                                <div class="comment-send-wrapper">
                                                                    <div class="comment-send-form">
                                                                        <div class="col-12">
                                                                            <label for="1"
                                                                                class="form-label">Mesajınız</label>
                                                                            <textarea class="form-control" name="text" id="1" required style="height: 100px"></textarea>
                                                                        </div>
                                                                            <div class="col-12 form-label">
                                                                                Yorumunuz onaylandığında karşı taraf sms ile bilgilendirilecektir.<br>(Sms gönderim ücreti: ücretsiz)
                                                                            </div>
                                                                        <div class="col-12 mt-2">
                                                                            <button type="submit"
                                                                                class="btn-inline color-blue">
                                                                                Yorumu Gönder
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    @else
                                                        <div class="comment-info">
                                                            <p><span class="w_ico"><i
                                                                        class="fal fa-exclamation-triangle"></i></span>
                                                                Yorum yapabilmek için lütfen giriş yapın.</p>
                                                            <button type="button" class="btn-inline color-blue small"
                                                                onclick="location.href='{{ route('giris') }}'">
                                                                Giriş Yap
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="card-column send">
                                                <span id="message-send">Yorum Gönder <i
                                                        class="fas fa-comment-lines"></i></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
