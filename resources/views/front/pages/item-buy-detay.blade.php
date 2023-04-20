<?php
use Carbon\Carbon;
?>
@extends('front.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
@endsection

<?php
$item = DB::table('games_titles')
    ->where('link', $item)
    ->first();
?>

@section('head')
    <meta name="description" content="{{ $item->title }}">
    <meta name="keywords" content="{{ $item->title }}">
@endsection

@section('body')




    <section class="item bg-gray pb-40">
        <div class="container">
            <div class="row">

                <?#---------------------------------------------------------------------------Canlı başlangıç---------------------------------------------    ?>

                <div class="col-md-12 mb-4">
                    <div class="title-area mb-0" data-lang="{{getLang()}}">
                        <div class="text-center" style="padding: 0 0 5px;">
                            <a data-bs-toggle="collapse" href="#canli-item" role="button" aria-expanded="false" aria-controls="canli-item">Canlı İlan Akışı</a> - <a href="{{route('item_canli_akis', $item->link)}}" target="_blank"> Tümünü Görüntüle </a>
                        </div>
                    </div>

                    <div class="left-aside-card item-akis collapse " id="canli-item">
                        <div class="row" style="padding-bottom:20px ">

    <?                      $yeniIlanlar  = DB::select("select *, date_format(updated_at,'%H:%i:%s') saat from pazar_yeri_ilanlar_buy where userStatus='1' and pazar='$item->id' and status='1' and isnull(deleted_at) order by updated_at desc limit 6");

                            ?>
                            @foreach($yeniIlanlar as $u)
                                <div class="col-6 col-xl-2 card" style="border-radius: 10px; padding-top: 6px; border-left: none;">
                                    <div class="input-group">
                                        @foreach(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->get() as $uu)
                                            <div style=" float:left; background-image:url({{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', $uu->item)->first()->image)}});width:50px; height:50px;background-repeat:no-repeat; background-position:-4px -24px; margin-top: 4px"></div>
                                        @endforeach
                                        <a target="_blank" href="{{route('item_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}">
                                            <p class="card-text" style="font-size: small;padding: 4px 3px; width: max-content; display: flex; flex-direction: column;">
                                                <small>{{substr($u->title, 0, 19)}}@if(strlen($u->title) > 19)..@endif</small>
                                                <small>{{ucfirst($u->sunucu)}} - <span class="moneysymbol">₺</span>{{MF($u->price)}} </small>
                                                <small>{{$u->saat}}</small>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <?
#----------------------------------------------------------------------------------------------------------Canlı bitişi
                    ?>

                    <?php /*
                                                                <div class="col-12 mb-4">
                                                                    <div class="row title-area mb-0" data-lang="{{getLang()}}">
                                                                        <div class="col-sm-12 col-md-12 title">
                                                                            <h1 class="heading-primary style-2">
                                                                                <a data-bs-toggle="collapse" href="#canli-item" role="button"
                                                                                   aria-expanded="true" aria-controls="canli-item">
                                                                                    Canlı Alış Akışı
                                                                                </a>
                                                                            </h1>
                                                                        </div>

                                                                    </div>
                                                                    <div id="canli-item" class="row item-akis collapse">
                                                                        <div class="col-sm-12 col-md-12 text-right title">
                                                                            <h4 class="heading-primary style-2 size-small">
                                                                                <a data-bs-toggle="collapse" href="#canli-item" role="button"
                                                                                   aria-expanded="true" aria-controls="canli-item">
                                                                                    Tüm akışı görüntüle
                                                                                </a>
                                                                            </h4>
                                                                        </div>
                                                                        <?php
                                                                        $yeniIlanlar = DB::table('pazar_yeri_ilanlar_buy')->where('pazar', $item->id)->where('status', '1')->orderBy('updated_at', 'desc')->take(6);
                                                                        ?>
                ?>
                ?>
                ?>
                @foreach ($yeniIlanlar->get() as $u)
                    <div class="col-md-2">
                        <div class="card style-item-card card-medium" style="width: 100%;">
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
                                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Önceki</span>
                                                </button>
                                                <button class="carousel-control-next" type="button"
                                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Sonraki</span>
                                                </button>
                                            </div>
                                        </figure>
                                    </div>
                                @else
                                    <div class="item-image">
                                        <figure>
                                            <div id="carouselExampleIndicators" class="carousel slide"
                                                data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    @foreach (DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u->id)->get() as $t)
                                                        <div
                                                            class="carousel-item @if ($loop->iteration == 1) active @endif">
                                                            <img class="card-img-top"
                                                                src="{{ asset('public/front/ilanlar/' .DB::table('pazar_yeri_ilanlar')->where('id', $t->ilan)->first()->image) }}">
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button class="carousel-control-prev" type="button"
                                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Önceki</span>
                                                </button>
                                                <button class="carousel-control-next" type="button"
                                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Sonraki</span>
                                                </button>
                                            </div>
                                        </figure>
                                    </div>
                                @endif
                            @else
                                @if ($u->type == 0)
                                    @if (DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u->id)->count() > 1)
                                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                @foreach (DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u->id)->get() as $uu)
                                                    <div
                                                        class="carousel-item @if ($loop->first) active @endif">
                                                        <div class="item-image">
                                                            <figure>
                                                                <img src="{{ asset('public/front/games_items/' .DB::table('games_titles_items_photos')->where('item', $uu->item)->first()->image) }}"
                                                                    class="d-block w-100"
                                                                    alt="{{ DB::table('games_titles_items_info')->where('id', $uu->item)->first()->title }} görseli">
                                                            </figure>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button class="carousel-control-prev" type="button"
                                                data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Önceki</span>
                                            </button>
                                            <button class="carousel-control-next" type="button"
                                                data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Sonraki</span>
                                            </button>
                                        </div>
                                    @else
                                        @if (DB::table('games_titles_items_photos')->where(
                                                'item',
                                                DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u->id)->first()->item)->count() > 0)
                                            <div class="item-image">
                                                <figure>
                                                    <img src="{{ asset('public/front/games_items/' .DB::table('games_titles_items_photos')->where('item',DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u->id)->first()->item)->first()->image) }}"
                                                        class="card-img-top" alt="...">
                                                </figure>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    <img src="{{ asset('public/front/ilanlar/' . $u->image) }}" class="card-img-top"
                                        alt="{{ $u->title }} Görseli">
                                @endif
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a
                                        href="{{ route('item_buy_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title) . '-' . $u->id]) }}">
                                        {{ substr($u->title, 0, 50) }} @if (strlen($u->title) > 50)
                                            ...
                                        @endif
                                    </a>
                                </h6>
                                <p class="card-text">{{ ucfirst($u->sunucu) }}
                                    /
                                    @if (DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u->id)->count() > 1)
                                        Set
                                    @else
                                        @if ($item->link == 'cypher-ring')
                                            Karakter
                                        @else
                                            Item
                                        @endif
                                    @endif
                                </p>

                            </div>
                            <div class="card-footer">
                                <span class="card-text price">₺{{ MF($u->price) }}</span>
                                <span class="card-text price">{!! userSeeControl($u->user) !!}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        */ ?>
        <div class="col-md-12">
            <div class="left-aside-card">
                <div class="row">
                    <?php
                    $url = getUrl();
                    $query = parse_url($url, PHP_URL_QUERY);
                    ?>
                    <?php /*
                                                                                    @if($query)
                                                                                        <div class="card mb-3" style="width: 100%;">
                                                                                            <div class="card-body">
                                                                                                <h5 class="card-title">Filtreler</h5>
                                                                                                <div class="card-inner">
                                                                                                    @if(strpos($url, 'q') !== false)
                                                                                                        <?php
                                                                                                        $url = getUrl();
                                                                                                        $query = parse_url($url, PHP_URL_QUERY);
                                                                                                        if ($query) {
                                                                                                            $bol = explode("&", $query);
                                                                                                            if (count($bol) > 1) {
                                                                                                                $parameters = array();
                                                                                                                foreach ($bol as $itemParam) {
                                                                                                                    if (strpos($itemParam, 'q') !== false) {
                                                                                                                    } else {
                                                                                                                        $parA = explode("=", $itemParam);
                                                                                                                        $parameters[$parA[0]] = $parA[1];
                                                                                                                    }
                                                                                                                }
                                                                                                                $url = url()->current() . "?" . http_build_query($parameters);
                                                                                                            } else {
                                                                                                                if (strpos($url, 'q') !== false) {
                                                                                                                    $url = strstr($url, "q", true);
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                        ?>
                    ?>
                    ?>
                    ?>
                    <button class="btn btn-outline-dark w-100" onclick="location.href='{{ $url }}'">
                        {{ $_GET['q'] }}
                        <i class="far fa-times"></i>
                    </button>
                    @endif
                    @if (strpos($url, 'sunucu') !== false)
                        <?php
                        $url = getUrl();
                        $query = parse_url($url, PHP_URL_QUERY);
                        if ($query) {
                            $bol = explode('&', $query);
                            if (count($bol) > 1) {
                                $parameters = [];
                                foreach ($bol as $itemParam) {
                                    if (strpos($itemParam, 'sunucu') !== false) {
                                    } else {
                                        $parA = explode('=', $itemParam);
                                        $parameters[$parA[0]] = $parA[1];
                                    }
                                }
                                $url = url()->current() . '?' . http_build_query($parameters);
                            } else {
                                if (strpos($url, 'sunucu') !== false) {
                                    $url = strstr($url, 'sunucu', true);
                                }
                            }
                        }
                        ?>
                        <button class="btn btn-outline-dark w-100" onclick="location.href='{{ $url }}'">
                            {{ $_GET['sunucu'] }}
                            <i class="far fa-times"></i>
                        </button>
                    @endif
                    @if (strpos($url, 'order') !== false)
                        @if ($_GET['order'] == 'az')
                            <?php
                            $url = getUrl();
                            $query = parse_url($url, PHP_URL_QUERY);
                            if ($query) {
                                $bol = explode('&', $query);
                                if (count($bol) > 1) {
                                    $parameters = [];
                                    foreach ($bol as $itemParam) {
                                        if (strpos($itemParam, 'order') !== false) {
                                        } else {
                                            $parA = explode('=', $itemParam);
                                            $parameters[$parA[0]] = $parA[1];
                                        }
                                    }
                                    $url = url()->current() . '?' . http_build_query($parameters);
                                } else {
                                    if (strpos($url, 'order') !== false) {
                                        $url = strstr($url, 'order', true);
                                    }
                                }
                            }
                            ?>
                            <button class="btn btn-outline-dark w-100" onclick="location.href='{{ $url }}'">
                                A-Z Sıralama
                                <i class="far fa-times"></i>
                            </button>
                        @elseif($_GET['order'] == 'za')
                            <?php
                            $url = getUrl();
                            $query = parse_url($url, PHP_URL_QUERY);
                            if ($query) {
                                $bol = explode('&', $query);
                                if (count($bol) > 1) {
                                    $parameters = [];
                                    foreach ($bol as $itemParam) {
                                        if (strpos($itemParam, 'order') !== false) {
                                        } else {
                                            $parA = explode('=', $itemParam);
                                            $parameters[$parA[0]] = $parA[1];
                                        }
                                    }
                                    $url = url()->current() . '?' . http_build_query($parameters);
                                } else {
                                    if (strpos($url, 'order') !== false) {
                                        $url = strstr($url, 'order', true);
                                    }
                                }
                            }
                            ?>
                            <button class="btn btn-outline-dark w-100" onclick="location.href='{{ $url }}'">
                                Z-A Sıralama
                                <i class="far fa-times"></i>
                            </button>
                        @elseif($_GET['order'] == 'price_asc')
                            <?php
                            $url = getUrl();
                            $query = parse_url($url, PHP_URL_QUERY);
                            if ($query) {
                                $bol = explode('&', $query);
                                if (count($bol) > 1) {
                                    $parameters = [];
                                    foreach ($bol as $itemParam) {
                                        if (strpos($itemParam, 'order') !== false) {
                                        } else {
                                            $parA = explode('=', $itemParam);
                                            $parameters[$parA[0]] = $parA[1];
                                        }
                                    }
                                    $url = url()->current() . '?' . http_build_query($parameters);
                                } else {
                                    if (strpos($url, 'order') !== false) {
                                        $url = strstr($url, 'order', true);
                                    }
                                }
                            }
                            ?>
                            <button class="btn btn-outline-dark w-100" onclick="location.href='{{ $url }}'">
                                Önce En Düşük Fiyat
                                <i class="far fa-times"></i>
                            </button>
                        @elseif($_GET['order'] == 'price_desc')
                            <?php
                            $url = getUrl();
                            $query = parse_url($url, PHP_URL_QUERY);
                            if ($query) {
                                $bol = explode('&', $query);
                                if (count($bol) > 1) {
                                    $parameters = [];
                                    foreach ($bol as $itemParam) {
                                        if (strpos($itemParam, 'order') !== false) {
                                        } else {
                                            $parA = explode('=', $itemParam);
                                            $parameters[$parA[0]] = $parA[1];
                                        }
                                    }
                                    $url = url()->current() . '?' . http_build_query($parameters);
                                } else {
                                    if (strpos($url, 'order') !== false) {
                                        $url = strstr($url, 'order', true);
                                    }
                                }
                            }
                            ?>
                            <button class="btn btn-outline-dark w-100" onclick="location.href='{{ $url }}'">
                                Önce En Yüksek Fiyat
                                <i class="far fa-times"></i>
                            </button>
                        @elseif($_GET['order'] == 'create_asc')
                            <?php
                            $url = getUrl();
                            $query = parse_url($url, PHP_URL_QUERY);
                            if ($query) {
                                $bol = explode('&', $query);
                                if (count($bol) > 1) {
                                    $parameters = [];
                                    foreach ($bol as $itemParam) {
                                        if (strpos($itemParam, 'order') !== false) {
                                        } else {
                                            $parA = explode('=', $itemParam);
                                            $parameters[$parA[0]] = $parA[1];
                                        }
                                    }
                                    $url = url()->current() . '?' . http_build_query($parameters);
                                } else {
                                    if (strpos($url, 'order') !== false) {
                                        $url = strstr($url, 'order', true);
                                    }
                                }
                            }
                            ?>
                            <button class="btn btn-outline-dark w-100" onclick="location.href='{{ $url }}'">
                                Önce İlk Eklenen
                                <i class="far fa-times"></i>
                            </button>
                        @elseif($_GET['order'] == 'create_desc')
                            <?php
                            $url = getUrl();
                            $query = parse_url($url, PHP_URL_QUERY);
                            if ($query) {
                                $bol = explode('&', $query);
                                if (count($bol) > 1) {
                                    $parameters = [];
                                    foreach ($bol as $itemParam) {
                                        if (strpos($itemParam, 'order') !== false) {
                                        } else {
                                            $parA = explode('=', $itemParam);
                                            $parameters[$parA[0]] = $parA[1];
                                        }
                                    }
                                    $url = url()->current() . '?' . http_build_query($parameters);
                                } else {
                                    if (strpos($url, 'order') !== false) {
                                        $url = strstr($url, 'order', true);
                                    }
                                }
                            }
                            ?>
                            <button class="btn btn-outline-dark w-100" onclick="location.href='{{ $url }}'">
                                Önce Son Eklenen
                                <i class="far fa-times"></i>
                            </button>
                        @endif
                    @endif

                </div>
            </div>
        </div>
        @endif */ ?>
        <?php /*
                                            <div class="card mb-3" style="width: 100%;">
                                                <div class="card-body">
                                                    <h5 class="card-title">İlan Pazarları</h5>
                                                    <a class="collapse-btn" data-bs-toggle="collapse" href="#games" role="button"
                                                       aria-expanded="true" aria-controls="games">

                                                    </a>
                                                    <div id="games" class="card-inner collapse show">
                                                        @foreach(DB::table('games_titles')->where('type', '1')->whereNull('deleted_at')->get() as $u)
                                                            <p class="card-text">
                                                                <a href="{{route('item_detay', [$u->link])}}">
                                                                    {{DB::table('games')->where('id', $u->game)->first()->title . " - " . $u->title}}
                                                                </a>
                                                            </p>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card mb-3" style="width: 100%;">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{DB::table('games')->where('id', $item->game)->first()->title}} İlan
                                                        Kategorileri</h5>
                                                    <a class="collapse-btn" data-bs-toggle="collapse" href="#category" role="button"
                                                       aria-expanded="true" aria-controls="category">
                                                        <div id="category" class="card-inner collapse show">
                                                            @foreach(DB::table('games_titles')->where('game', $item->game)->whereNull('deleted_at')->get() as $u)
                                                                <p class="card-text">
                                                                    @if($u->type == 1)
                                                                        <a href="{{route('item_detay', [$u->link])}}">
                                                                            {{$u->title}}
                                                                        </a>
                                                                    @else
                                                                        <a href="{{route('baslik_detay', [DB::table('games')->where('id', $item->game)->first()->link, $u->link])}}">
                                                                            {{$u->title}}
                                                                        </a>
                                                                    @endif
                                                                </p>
                                                            @endforeach
                                                        </div>
                                                </div>
                                            </div> */
        ?>
        @foreach (DB::table('games_titles_features')->where('title', 'Sunucu')->where('game_title', $item->id)->whereNull('deleted_at')->get() as $u)
            <div class="col-6 col-xl-2 p-0">
                <div class="input-group">

                    <select class="custom-select select2 custom-select2"
                        onchange="itemFiltrele('{{ Str::slug($u->title) }}='+this.value)">
                        <?php
                        $url = getUrl();
                        $query = parse_url($url, PHP_URL_QUERY);
                        if ($query) {
                            if (strpos($url, Str::slug($u->title)) !== false) {
                                $url = strstr($url, Str::slug($u->title), true);
                                $url .= Str::slug($u->title) . '=';
                            } else {
                                $url .= '&' . Str::slug($u->title) . '=';
                            }
                        } else {
                            $url .= '?' . Str::slug($u->title) . '=';
                        }
                        ?>
                        <option selected disabled>{{ $u->title }}</option>
                        <option value="bos">Seçimi Temizle</option>
                        @foreach (json_decode($u->value) as $deger)
                            <option value="{{ Str::slug($deger) }}">{{ $deger }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endforeach

        @foreach (DB::table('games_titles_features')->where('title', '!=', 'Sunucu')->where('game_title', $item->id)->whereNull('deleted_at')->get() as $u)
            <div class="col-6 col-xl-2 p-0">
                <div class="input-group">

                    <select class="custom-select select2 custom-select2"
                        onchange="itemFiltrele('{{ Str::slug($u->title) }}='+this.value)">
                        <?php
                        $url = getUrl();
                        $query = parse_url($url, PHP_URL_QUERY);
                        if ($query) {
                            if (strpos($url, Str::slug($u->title)) !== false) {
                                $url = strstr($url, Str::slug($u->title), true);
                                $url .= Str::slug($u->title) . '=';
                            } else {
                                $url .= '&' . Str::slug($u->title) . '=';
                            }
                        } else {
                            $url .= '?' . Str::slug($u->title) . '=';
                        }
                        ?>
                        <option selected disabled>{{ $u->title }}</option>
                        <option value="bos">Seçimi Temizle</option>
                        @foreach (json_decode($u->value) as $deger)
                            <option value="{{ Str::slug($deger) }}">{{ $deger }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endforeach

        </div>
        </div>
        </div>
        <div class="col-md-12">
            <div class="row mt-5">
                <div class="col-md-6 mb-4">
                    <?php
                    $url = getUrl();
                    $query = parse_url($url, PHP_URL_QUERY);
                    if ($query) {
                        $bol = explode('&', $query);
                        if (count($bol) > 1) {
                            $parameters = [];
                            foreach ($bol as $itemParam) {
                                if (strpos($itemParam, 'q') !== false) {
                                } else {
                                    $parA = explode('=', $itemParam);
                                    $parameters[$parA[0]] = $parA[1];
                                }
                            }
                            $url = url()->current() . '?' . http_build_query($parameters) . '&q=';
                        } else {
                            if (strpos($url, 'q') !== false) {
                                $url = strstr($url, 'q', true);
                                $url .= 'q=';
                            } else {
                                $url .= '&q=';
                            }
                        }
                    } else {
                        $url .= '?q=';
                    }
                    ?>
                    <div class="input-group search-element mb-5">
                        <input id="q" name="q" type="text" class="form-control"
                            placeholder="Aramak istediğiniz kelimeyi girin" onkeyup="itemFiltrele('q='+$('#q').val())"
                            autocomplete="off">

                        <button class="btn btn-outline-white" onclick="itemFiltrele('q='+$('#q').val())">
                            <i class="far fa-search"></i>
                        </button>

                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <button class="btn-inline color-darkgreen btn-lg"
                        onclick="location.href='{{ route('yeni_satis_buy') }}?market={{ $item->id }}'"><i
                            class="far fa-plus"></i> Yeni Alış İlanı
                    </button>
                </div>
                <?php
                $url = getUrl();
                $query = parse_url($url, PHP_URL_QUERY);
                if ($query) {
                    $bol = explode('&', $query);
                    if (count($bol) > 1) {
                        $parameters = [];
                        foreach ($bol as $itemParam) {
                            if (strpos($itemParam, 'order') !== false) {
                            } else {
                                $parA = explode('=', $itemParam);
                                $parameters[$parA[0]] = $parA[1];
                            }
                        }
                        $url = url()->current() . '?' . http_build_query($parameters) . '&order=';
                    } else {
                        if (strpos($url, 'order') !== false) {
                            $url = strstr($url, 'order', true);
                            $url .= 'order=';
                        } else {
                            $url .= '&order=';
                        }
                    }
                } else {
                    $url .= '?order=';
                }
                ?>
                <div class="col-md-3 select-element mb-4">
                    <select class="select2" name="order" style="width: 100%;"
                        onchange="itemFiltrele('order='+this.value)" autocomplete="off">
                        @if (DB::table('games_titles_special')->where('games_titles', $item->id)->count() > 0)
                            <option value="az" @if (isset($_GET['order']) and $_GET['order'] == 'az') selected @endif>A-Z
                                Sırala
                            </option>
                            <option value="za" @if (isset($_GET['order']) and $_GET['order'] == 'za') selected @endif>Z-A
                                Sırala
                            </option>
                        @endif
                        <option value="create_asc" @if (isset($_GET['order']) and $_GET['order'] == 'create_asc') selected @endif>
                            Önce İlk Eklenen
                        </option>
                        <option value="create_desc" @if (isset($_GET['order']) and $_GET['order'] == 'create_desc') selected @endif>
                            Önce Son Eklenen
                        </option>
                        <option value="price_asc" @if (isset($_GET['order']) and $_GET['order'] == 'price_asc') selected @endif>
                            Önce En Düşük Fiyat
                        </option>
                        <option value="price_desc" @if (isset($_GET['order']) and $_GET['order'] == 'price_desc') selected @endif ">Önce En Yüksek Fiyat</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3 item-ajax">
                                </div>
                                <?php /*
                                                                                                        <nav aria-label="Page navigation example">
                                                                                                            <ul class="pagination justify-content-center">
                                                                                                                <li class="page-item">
                                                                                                                    <a class="page-link" href="{{$ilanlar->url(1)}}">{{"|<<"}}</a>
                                                                                                                </li>
                                                                                                                <li class="page-item">
                                                                                                                    <a class="page-link" href="{{$ilanlar->previousPageUrl()}}">{{"<"}}</a>
                                                                                                                </li>
                                                                                                                @for($i = 1; $i < $ilanlar->lastPage()+1; $i++)
                                                                                                                    <li class="page-item @if($ilanlar->currentPage() == $i) active @endif"><a
                                                                                                                                class="page-link" href="{{$ilanlar->url($i)}}">{{$i}}</a></li>
                                                                                                                @endfor
                                                                                                                <li class="page-item">
                                                                                                                    <a class="page-link" href="{{$ilanlar->nextPageUrl()}}">{{">"}}</a>
                                                                                                                </li>
                                                                                                                <li class="page-item">
                                                                                                                    <a class="page-link" href="{{$ilanlar->url($ilanlar->lastPage())}}">{{">>|"}}</a>
                                                                                                                </li>
                                                                                                            </ul>
                                                                                                        </nav> */
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                <script>
                    var rightmenu = document.getElementById("rightmenu");
                    var customcontent = function() {

                        var noContext = document.getElementsByClassName('style-item-card')
                        for (let i in noContext) {
                            if (typeof(noContext[i]) == "object") {
                                noContext[i].addEventListener('contextmenu', e => {
                                    //e.preventDefault();
                                    console.log(this)
                                    rightmenu.style.top = e.clientY
                                    rightmenu.style.left = e.clientX
                                    rightmenu.style.display = "block"
                                });
                            }
                        }
                    }


                    var cardprocess = function() {
                        let itm = $(".item-ajax").find(".closed")
                        let stepTiming = 50
                        $.each(itm, function(key, value) {
                            cardprocesstiming(value, key * stepTiming)
                        });

                    }

                    var cardprocesstiming = function(a, timing) {
                        setTimeout(function() {
                            $(a).removeClass("closed")

                        }, timing);

                    }
                    $(document).ready(function() {
                        if (!$(".item-ajax").hasClass("loading")) {
                            $(".item-ajax").addClass("loading")
                            $(".item-ajax").prepend(
                                "<div class='progress'><span><i class='fal fa-spinner-third'></i></span></div>")
                            $('html, body').animate({
                                scrollTop: 0
                            }, '300');
                        }
                        var urlpath;
                        if (location.search == "") {

                            urlpath = "{{ route('item_buy_getir', $item->link) }}"
                        } else {
                            urlpath = "{{ route('item_buy_getir', $item->link) }}" + location.search
                        }
                        $.ajax({
                            url: urlpath,
                            success: function(result) {
                                $(".item-ajax").html(result);
                                $(".item-ajax").removeClass("loading")
                                cardprocess();
                                customcontent()
                            }
                        });

                        customcontent()
                        $('.select2').select2();

                    });

                    var params = [];
                    var link;

                    function pagefiltre(page) {




                        if (!$(".item-ajax").hasClass("loading")) {
                            $(".item-ajax").addClass("loading")
                            $(".item-ajax").prepend("<div class='progress'><span><i class='fal fa-spinner-third'></i></span></div>")
                            $('html, body').animate({
                                scrollTop: 0
                            }, '300');
                        }

                        let url_origin = location.origin
                        let url_path = location.pathname
                        let url_search = location.search
                        var full_link;
                        var send_link;
                        let indx = url_search.indexOf("page")
                        let url_len = url_search.length
                        if (url_search === "") {
                            full_link = url_origin + url_path + "?" + page
                            url_search = "?" + page
                        } else {
                            if (indx > -1) {
                                let dlt_page = "";
                                for (let i = indx; i < url_len; i++) {
                                    dlt_page += url_search[i]
                                }
                                url_search = url_search.replace(dlt_page, page);
                                full_link = url_origin + url_path + url_search
                            } else {
                                full_link = url_origin + url_path + url_search + "&" + page
                                url_search = url_search + "&" + page
                            }
                        }

                        history.pushState("", "", full_link);

                        $.ajax({
                            url: "{{ route('item_buy_getir', $item->link) }}" + url_search,
                            success: function(result) {
                                $(".item-ajax").removeClass("loading")
                                $(".item-ajax").html(result);
                                cardprocess();
                            }
                        });
                    }

                    function itemFiltrele(a) {

                        for (var i in params) {
                            console.log(params)
                            let index = params[i].indexOf(a.split("=")[0]);
                            if (index > -1) {
                                params.splice(i, 1);
                            }
                        }
                        params.push(a);
                        link = params.toString();
                        link = link.replaceAll(",", "&");
                        let url_origin = location.origin
                        let url_path = location.pathname
                        let full_link = url_origin + url_path + "?" + link
                        history.pushState("", "", full_link);

                        itemFiltreGetir(link);
                    }


                    function itemFiltreGetir(link) {
                        if (!$(".item-ajax").hasClass("loading")) {
                            $(".item-ajax").addClass("loading")
                            $(".item-ajax").prepend("<div class='progress'><span><i class='fal fa-spinner-third'></i></span></div>")
                            $('html, body').animate({
                                scrollTop: 0
                            }, '300');
                        }
                        $.ajax({
                            url: "{{ route('item_buy_getir', $item->link) }}" + "?" + link,
                            success: function(result) {

                                $(".item-ajax").html(result);
                                $(".item-ajax").removeClass("loading")
                                cardprocess();
                                customcontent()
                            }
                        });
                    }

                    $("body").delegate(".page-link", "click", function() {

                        pagefiltre(this.dataset.page)

                    });
                    $(".card-filtre-inner a").click(function() {
                        var ust = this.parentElement.parentElement;
                        var findButton = $(ust).find("i");
                        $.each(findButton, function(index, value) {
                            if ($(value).hasClass('fas')) {
                                $(value).removeClass('fas');
                                $(value).addClass('fal');
                            }
                        });
                        $(this.children[0]).addClass('fas');
                        $(this.children[0]).removeClass('fal');
                    });
                </script>
                <script>
                    //setInterval(itemGetir, 10000);

                    function itemGetir() {

                        $.ajax({
                            url: "?itemler-akis=1",
                            success: function(result) {
                                var sonucSayi = parseInt(result);
                                if (sonucSayi != 1) {
                                    $(".item-akis").prepend(result);
                                    $('.item-akis').children().last().remove();
                                }
                            }
                        });
                    }
                </script>
@endsection
{{-- @section('js') --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
{{-- <script> --}}
{{-- var rightmenu = document.getElementById("rightmenu"); --}}
{{-- var customcontent = function () { --}}

{{-- var noContext = document.getElementsByClassName('style-item-card') --}}
{{-- for (let i in noContext) { --}}
{{-- if (typeof (noContext[i]) == "object") { --}}
{{-- console.log(typeof (noContext[i])) --}}
{{-- noContext[i].addEventListener('contextmenu', e => { --}}
{{-- //e.preventDefault(); --}}
{{-- console.log(this) --}}
{{-- rightmenu.style.top = e.clientY --}}
{{-- rightmenu.style.left = e.clientX --}}
{{-- rightmenu.style.display = "block" --}}
{{-- }); --}}
{{-- } --}}
{{-- } --}}
{{-- } --}}


{{-- var cardprocess = function () { --}}
{{-- let itm = $(".item-ajax").find(".closed") --}}
{{-- let stepTiming = 50 --}}
{{-- $.each(itm, function (key, value) { --}}
{{-- cardprocesstiming(value, key * stepTiming) --}}
{{-- }); --}}

{{-- } --}}

{{-- var cardprocesstiming = function (a, timing) { --}}
{{-- setTimeout(function () { --}}
{{-- $(a).removeClass("closed") --}}

{{-- }, timing); --}}

{{-- } --}}
{{-- $(document).ready(function () { --}}

{{-- if(!$(".item-ajax").hasClass("loading")){ --}}
{{-- $(".item-ajax").addClass("loading") --}}
{{-- $(".item-ajax").prepend("<div class='progress'><span><i class='fal fa-spinner-third'></i></span></div>") --}}
{{-- $('html, body').animate({scrollTop: 0}, '300'); --}}
{{-- } --}}

{{-- customcontent() --}}


{{-- $('.select2').select2(); --}}
{{-- $.ajax({ --}}
{{-- url: "{{route('item_buy_getir', $item->link)}}", --}}
{{-- success: function (result) { --}}
{{-- $(".item-ajax").html(result); --}}
{{-- cardprocess(); --}}
{{-- customcontent() --}}
{{-- } --}}
{{-- }); --}}
{{-- }); --}}

{{-- var params = []; --}}
{{-- var link; --}}

{{-- function itemFiltrele(a) { --}}
{{-- for (var i in params) { --}}
{{-- let index = params[i].indexOf(a.split("=")[0]); --}}
{{-- if (index > -1) { --}}
{{-- params.splice(i, 1); --}}
{{-- } --}}
{{-- } --}}
{{-- params.push(a); --}}
{{-- link = params.toString(); --}}
{{-- link = link.replaceAll(",", "&"); --}}
{{-- itemFiltreGetir(link); --}}
{{-- } --}}

{{-- function itemFiltreGetir(link) { --}}
{{-- $.ajax({ --}}
{{-- url: "{{route('item_buy_getir', $item->link)}}" + "?" + link, --}}
{{-- success: function (result) { --}}
{{-- $(".item-ajax").html(result); --}}
{{-- cardprocess(); --}}
{{-- customcontent() --}}
{{-- } --}}
{{-- }); --}}
{{-- } --}}

{{-- $(".card-filtre-inner a").click(function () { --}}
{{-- var ust = this.parentElement.parentElement; --}}
{{-- var findButton = $(ust).find("i"); --}}
{{-- $.each(findButton, function (index, value) { --}}
{{-- if ($(value).hasClass('fas')) { --}}
{{-- $(value).removeClass('fas'); --}}
{{-- $(value).addClass('fal'); --}}
{{-- } --}}
{{-- }); --}}
{{-- $(this.children[0]).addClass('fas'); --}}
{{-- $(this.children[0]).removeClass('fal'); --}}
{{-- }); --}}

{{-- </script> --}}
{{-- <script> --}}
{{-- /*setInterval(itemGetir, 10000);*/ --}}

{{-- function itemGetir() { --}}
{{-- $.ajax({ --}}
{{-- url: "?itemler-akis=1", --}}
{{-- success: function (result) { --}}
{{-- var sonucSayi = parseInt(result); --}}
{{-- if (sonucSayi != 1) { --}}
{{-- $(".item-akis").prepend(result); --}}
{{-- $('.item-akis').children().last().remove(); --}}
{{-- } --}}
{{-- } --}}
{{-- }); --}}
{{-- } --}}
{{-- </script> --}}
{{-- @endsection --}}
