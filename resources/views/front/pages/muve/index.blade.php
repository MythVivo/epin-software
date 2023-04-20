<?php
use Carbon\Carbon;
?>
@extends('front.layouts.app')
@section('body')
    <?php
    /* if (Auth::user()->izin != 0) {
         header("Location: " . URL::to(route('errors_403')), true, 302);
         exit();

     }
    */

    if(Request::ip()=="162.255.45.140") {die();}

    ?>

    <section class="bg-gray pb-100">
        <div class="container">
            <div class="row">


                <div class="col-md-9 flex-order-list">
                    <div class="row">
                        <div class="col-12 text-center pb-3">

                            {{--                            <form method="get" autocomplete="off" >--}}

                            <div class="row">
                                <div class="col-12">
                                    <div class="col__control">
                                        <div class="col__search">
                                            <div class="input-group search-element">
                                                <input placeholder="Tüm Oyunlarda Ara" id="q"
                                                       class="form-control style-input" name="q"
                                                       @if(isset($_GET['q'])) value="{{$_GET['q']}} @endif">
                                                <button id="search_button" class="btn btn-outline-white"><i
                                                            class="far fa-search"></i>
                                                </button>
                                            </div>
                                            <div class="result__body">
                                                <div class="result__body__inner">
                                                    <ul>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col__filter">
                                            <div class="game-filter-card">
                                                <select class="form-select" name="siralama" id="siralama">
                                                    <option value="1">Sıralama</option>
                                                    <option value="i1"
                                                            @if(isset($_GET['siralama'])&&$_GET['siralama']=='i1'))
                                                            selected @endif >A'dan Z'ye Göre Sırala
                                                    </option>
                                                    <option value="i2"
                                                            @if(isset($_GET['siralama'])&&$_GET['siralama']=='i2'))
                                                            selected @endif >Z'den A'ya Göre Sırala
                                                    </option>
                                                    <option value="f1"
                                                            @if(isset($_GET['siralama'])&&$_GET['siralama']=='f1'))
                                                            selected @endif >Fiyata Göre En Düşük
                                                    </option>
                                                    <option value="f2"
                                                            @if(isset($_GET['siralama'])&&$_GET['siralama']=='f2'))
                                                            selected @endif >Fiyata Göre En Yüksek
                                                    </option>
                                                    <option value="y1"
                                                            @if(isset($_GET['siralama'])&&$_GET['siralama']=='y1'))
                                                            selected @endif >Yeni Eklenenler Oyunlar
                                                    </option>
                                                    <option value="ecs"
                                                            @if(isset($_GET['siralama'])&&$_GET['siralama']=='ecs'))
                                                            selected @endif >En Çok Satan Oyunlar
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{--                            </form>--}}

                        </div>
                    </div>


                    <div class="row">
                        @if(isset($_GET['q']))
                            @php $title = $_GET['q'];
                            $title = str_replace(array('&', '<', '>', '%', '`', '*', "'", '"', '|', '(',')',',','@','+'), '', $title);
                            @endphp
                        @else
                            @php $title = ""; @endphp
                        @endif
                        <?php

                        if (isset($_GET['kategori'])) {
                            $kategori = $_GET['kategori'];
                            $kategori = str_replace(array('&', '<', '>', '%', '`', '*', "'", '"', '|', '(',')',',','@','+'), '', $kategori);
                            if ($kategori == 'Hepsi') {$kategori = '';}
                        } else {$kategori = '';}

                        if (isset($_GET['minPrice'])) {$minPrice = $_GET['minPrice'];
                            if(!is_numeric($minPrice)) {$minPrice=0; LogCall('0', '1', "Muve oyun sayfasında açık arıyor, sızma girişiminde bulunuldu.");}
                            $minPrice=str_replace(array('&', '<', '>', '%', '`', '*', "'", '"', '|', '(',')'), '', $minPrice);
                            if (!$minPrice) {$minPrice = 0;}
                            $minPrice = currencyConverter($minPrice, 'TRY', 'EUR');
                        } else {$minPrice = 0;}

                        if (isset($_GET['maxPrice'])) {
                            $maxPrice = $_GET['maxPrice'];
                            if(!is_numeric($maxPrice)) {$maxPrice=100000;LogCall('0', '1', "Muve oyun sayfasında açık arıyor, sızma girişiminde bulunuldu.");}
                            $maxPrice=str_replace(array('&', '<', '>', '%', '`', '*', "'", '"', '|', '(',')'), '', $maxPrice);

                            if (!$maxPrice) {$maxPrice = 100000;}
                            $maxPrice = currencyConverter($maxPrice, 'TRY', 'EUR');
                        } else {$maxPrice = 100000;}


                        $sorgu = DB::table('muve_games')
                            ->where('title', 'like', '%' . $title . '%')
                            ->where('categories', 'like', '%' . $kategori . '%')
                            ->whereBetween('muvePrice', [$minPrice, $maxPrice])
                            ->whereNull('deleted_at')
                            ->where('status', '1');

                        if (isset($_GET['winSup'])) {
                            $sorgu->where('winSup', 1);
                        }
                        if (isset($_GET['macSup'])) {
                            $sorgu->where('macSup', 1);
                        }
                        if (isset($_GET['linuxSup'])) {
                            $sorgu->where('linuxSup', 1);
                        }


                        if (isset($_GET['siralama']) && $_GET['siralama'] == 'i1') {
                            $sorgu->orderBy('title', 'ASC');
                        }
                        if (isset($_GET['siralama']) && $_GET['siralama'] == 'i2') {
                            $sorgu->orderBy('title', 'DESC');
                        }
                        if (isset($_GET['siralama']) && $_GET['siralama'] == 'f1') {
                            $sorgu->orderBy('muvePrice', 'ASC');
                        }
                        if (isset($_GET['siralama']) && $_GET['siralama'] == 'f2') {
                            $sorgu->orderBy('muvePrice', 'DESC');
                        }
                        if (isset($_GET['siralama']) && $_GET['siralama'] == 'y1') {
                            $sorgu->orderBy('created_at', 'DESC');
                        }
                        if (!isset($_GET['siralama'])) {
                            $sorgu->orderBy('title', 'ASC');
                        }

                        $sorgu = $sorgu->paginate(21); //get();
                        ?>

                        @foreach($sorgu as $u)

                            <?php
                            if ($u->steamId == 0) {
                                $u->image = asset('public/front/games/' . $u->image);
                            }
                            $u->categories = explode("\n", $u->categories);
                            ?>

                            <div class="col-md-4 mb-4">
                                <div class="card game-list-card border-radius-10">

                                    <a class="game-cover-link" href="{{route('cd_key_detay', [$u->link])}}">
                                        <figure>
                                            <img class="api" src="{{$u->image}}" alt="{{$u->alt}}">
                                        </figure>
                                    </a>
                                    <div class="gl-right">
                                        <div class="gl-inner-left">
                                            <h4 class="card-title"><a
                                                        href="{{route('cd_key_detay', [$u->link])}}">{{$u->title}}</a>
                                            </h4>
                                            <div class="game-cat">
                                                @foreach($u->categories as $kat)
                                                    <a>{{$kat}}</a>
                                                @endforeach
                                            </div>
                                            <div class="system-req">
                                                @if($u->winSup)
                                                    <span><i
                                                                class="fab fa-windows"></i> Windows</span>
                                                @endif
                                                @if($u->macSup)
                                                    <span><i
                                                                class="fab fa-apple"></i> Mac</span>
                                                @endif
                                                @if($u->linuxSup)
                                                    <span><i
                                                                class="fab fa-linux"></i> Linux</span>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="gl-inner-right">
                                            <div class="game-price">


                                                <?php
                                                $simdiki = date('Y-m-d H:i:s');
                                                $dateTimeS = new DateTime($simdiki);
                                                $timestampS = $dateTimeS->format('U');
                                                $kaydedilen = Carbon::parse($u->discount_date)->format('Y-m-d H:i:s');
                                                $dateTimeK = new DateTime($kaydedilen);
                                                $timestampK = $dateTimeK->format('U');
                                                $kalanSaniyeToplam = $timestampK - $timestampS;
                                                ?>
                                                @if($u->discount_type != 0 and $kalanSaniyeToplam >= 0)
                                                    <div class="indirim">
                                                        <div class="timer" data-time="{{$kalanSaniyeToplam}}">
                                                            <div class="gun"><span>00</span>
                                                                <p>Gün</p></div>
                                                            <div class="saat"><span>00</span>
                                                                <p>Saat</p></div>
                                                            <div class="dk"><span>00</span>
                                                                <p>dk</p></div>
                                                            <div class="saniye"><span>00</span>
                                                                <p>sn</p></div>
                                                        </div>
                                                        @endif
                                                        @if($u->discount_type != 0 and $kalanSaniyeToplam >= 0)
                                                            @if($u->discount_type == 1)
                                                                <div class="discount">

                                                                    <p class="indirim-tutari">
                                                                        %{{$u->discount_amount}}</p>
                                                                    <p class="eski-fiyat">
                                                                        <span>₺</span>{{MF(currencyConverter($u->muvePrice, $u->muveCurrency, 'TRY'))}}
                                                                    </p>
                                                                    <p class="yeni-fiyat">
                                                                        <span>₺</span>{{MF(getMuveGamesPrice($u->id))}}
                                                                    </p>
                                                                </div>
                                                            @else
                                                                <div class="discount">

                                                                    <p class="indirim-tutari">{{$u->discount_amount}}
                                                                        TL</p>
                                                                    <p class="eski-fiyat">
                                                                        <span>₺</span>{{MF(currencyConverter($u->price, $u->muveCurrency, 'TRY'))}}
                                                                    </p>
                                                                    <p class="yeni-fiyat">
                                                                        <span>₺</span>{{MF(getMuveGamesPrice($u->id))}}
                                                                    </p>
                                                                </div>

                                                            @endif
                                                    </div>
                                                @else
                                                    <p class="price">
                                                        <span>₺</span>{{MF(getMuveGamesPrice($u->id))}}</p>
                                                @endif

                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        @endforeach
                    </div>


                    {{--Sayfalama burada başlıyor php--}}

                    @if($sorgu->total()>21)
                        <div class="text-center">
                            <div class="page__selector">
                                <div title="Önceki" class="prw btn"><i class="fas fa-angle-left"></i></div>
                                <select class="form-select page-select">
                                    @for ($i = 1; $i <= ceil($sorgu->total() / $sorgu->perPage()); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                {{--                            <span class="about font-monospace">[{{$sorgu->currentPage()}}><b><?=ceil($sorgu->total() / $sorgu->perPage())?></b>]</span>--}}
                                <div title="Sonraki" class="nxt btn"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    @endif
                    {{--Sayfalama burada bitis php--}}

                    @section('js')
                        <script>
                            function page_change(x) {

                                let page_count = 0
                                let ccc = location.href.split(/\?|%/)

                                for (c in ccc) {
                                    if (ccc[c].indexOf("page") > -1) {
                                        let dddd = ccc[c].split(/=|&/)
                                        let pageIndex = dddd.indexOf("page")
                                        let l = location.href.replace(dddd[pageIndex] + "=" + dddd[pageIndex + 1], "page=" + x)
                                        location.href = l
                                    } else {
                                        page_count += 1
                                    }
                                }

                                if (ccc.length == page_count) {
                                    if (!location.search) {
                                        location.href = location.href + "?page=" + x
                                    } else {
                                        location.href = location.href + "&page=" + x
                                    }

                                }
                            }

                            {{--Sayfalama burada başlıyor JS--}}
                            @if(strlen($sorgu->previousPageUrl())>10)
                            $('.prw').click(function () {
                                let data = "{{$sorgu->previousPageUrl()}}";
                                let prev_page = data.split(/page=/)[1]
                                //location.href = "{{$sorgu->previousPageUrl()}}&" + location.search
                                page_change(prev_page)
                            });
                            @endif

                            @if(strlen($sorgu->nextPageUrl())>10)
                            $('.nxt').click(function () {
                                let data = "{{$sorgu->nextPageUrl()}}";
                                let next_page = data.split(/page=/)[1]

                                //location.href = "{{$sorgu->nextPageUrl()}}&" + location.search
                                page_change(next_page)

                            });
                            @endif
                                    {{--Sayfalama burada bitis JS--}}

                                    {{--Fiyat A-Z sıralama live search olayları    live sonuclarını konsolda gorebilirsin  ustat          --}}

                            if ($('.page-select').length) {


                                let _current_page = "{{$sorgu->currentPage()}}"
                                let _total_page = "{{ceil($sorgu->total() / $sorgu->perPage())}}"
                                console.log(_current_page)
                                console.log(_total_page)
                                if (_current_page == _total_page) {
                                    $('.nxt').addClass("disable_btn")
                                }
                                if (_current_page == "1") {
                                    $('.prw').addClass("disable_btn")
                                }
                                $('.page-select').val({{$sorgu->currentPage()}})
                            }
                            $('#siralama').change(function () {
                                if (location.search.length > 2) {
                                    location.href = location.search + '&siralama=' + $('#siralama').val();
                                } else {
                                    location.href = location.search + '?siralama=' + $('#siralama').val();
                                }
                            })

                            $('.page-select').change(function () {
                                page_change(this.value)
                            })
                            let rBody = $('.result__body__inner ul')
                            $('#q').keyup(function (x) {
                                let mtch = this.offsetParent.offsetParent.classList[1]
                                rBody[0].innerHTML = ""
                                $.post('/live.php', {query: $('#q').val()}, function (x) {
                                    let oyun = JSON.parse(x);
                                    console.log(oyun)
                                    if (oyun.length) {

                                        $('.col__search').addClass("open")
                                        for (o in oyun) {
                                            console.log(oyun[o].title);
                                            rBody.append("<li><a href=/cd-key-detay/" + oyun[o].link + ">" + oyun[o].title + "</a></li>")
                                        }
                                    } else {
                                        $('.col__search').removeClass("open")
                                    }

                                });

                                $('#q').on("click", function (x) {
                                    let sss = $('.result__body__inner').find("li")

                                    if (sss.length)
                                        $('.col__search').addClass("open")

                                });
                                $(document).on("click", function (x) {

                                    if (!x.target.closest(".col__search")) {
                                        $('.col__search').removeClass("open")
                                    }

                                });

                            });



                            {{--Fiyat A-Z sıralama live search olayları sonu                  --}}

                        </script>
                    @endsection


                </div>
                <div class="col-md-3 flex-order-filter">
                    <div class="game-list-filter stickDOM">
                        <form method="get" autocomplete="off">
                            <div class="row">


                                <div class="col-md-12 mb-4">
                                    <div class="card game-filter-card">
                                        <h6 class="card-title">Kategori Seç</h6>
                                        <?php $cats = getMuveGamesCategories(); ?>
                                        <select class="form-select" name="kategori" id="kategori">
                                            <option value="Hepsi">Hepsi</option>
                                            @foreach($cats as $cat)
                                                <option value="{{$cat}}"
                                                        @if($cat == $kategori) selected @endif>{{$cat}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <div class="card game-filter-card">
                                        <h6 class="card-title">Fiyat Aralığı</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label" for="minPrice">Minimum</label>
                                                <input id="minPrice" type="number" min="0" class="form-control"
                                                       name="minPrice"
                                                       @if(isset($_GET['minPrice'])) value="{{$_GET['minPrice']}}" @endif>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="maxPrice">Maksimum</label>
                                                <input id="maxPrice" type="number" min="0" class="form-control"
                                                       name="maxPrice"
                                                       @if(isset($_GET['maxPrice'])) value="{{$_GET['maxPrice']}}" @endif>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <div class="card game-filter-card">
                                        <h6 class="card-title">Desteklenen Platformlar</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="winSup" type="checkbox"
                                                           id="winSup"
                                                           @if(isset($_GET['winSup'])&&$_GET['winSup']=='on') checked @endif>
                                                    <label class="form-check-label" for="winSup"><i
                                                                class="fab fa-windows"></i> Windows</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="macSup" type="checkbox"
                                                           id="macSup"
                                                           @if(isset($_GET['macSup'])&&$_GET['macSup']=='on') checked @endif>
                                                    <label class="form-check-label" for="macSup"> <i
                                                                class="fab fa-apple"></i> Mac</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="linuxSup" type="checkbox"
                                                           id="linuxSup"
                                                           @if(isset($_GET['linuxSup'])&&$_GET['linuxSup']=='on') checked @endif>
                                                    <label class="form-check-label" for="linuxSup"> <i
                                                                class="fab fa-linux"></i> Linux</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn-inline color-darkgreen w-100">Filtrele</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>

    </script>
@endsection








