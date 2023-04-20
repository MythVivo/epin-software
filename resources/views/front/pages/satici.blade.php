@extends('front.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('body')
    <?php
    $satici = DB::table('users')->where('id', $satici)->first();
    $ilanlar = DB::table('pazar_yeri_ilanlar')
        ->where('status', '1')
        ->where('user', $satici->id)
        ->whereNull('deleted_at')
        ->where('userStatus', '1')
        ->paginate(9);
    $alislar = DB::table('pazar_yeri_ilanlar_buy')
        ->where('status', '1')
        ->where('user', $satici->id)
        ->whereNull('deleted_at')
        ->where('userStatus', '1')
        ->paginate(9, ['*'], 'alislar');
    ?>
    <section class="bg-gray pb-40">
        <div class="container ">
            <div class="row title-area">
                <div class="col-sm-12 col-md-9 title">
                    <h1 class="heading-primary style-2">Satıcıya Dair Bilgiler</h1>
                </div>

            </div>
            <div class="row ">
                <div class="col-12">

                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="profile-table">
                                <img class="img w-100 img-fluid mb-4"
                                     src="{{asset('public/front/avatars/'.$satici->avatar)}}" alt=""/>
                                <div class="profile-head">

                                    <h4 class="card-title">
                                        ****** {!! userLastSeen($satici->id) !!}
                                    </h4>
                                </div>

                                <p class="card-text">Satıcı Puanı :
                                    <strong>{{getSaticiPuani($satici->id)}}</strong>
                                </p>
                                <p class="card-text">Başarılı Satış :
                                    <strong>{{DB::table('pazar_yeri_ilanlar')->where('user', $satici->id)->whereNull('deleted_at')->where('userStatus', '1')->where('status', '6')->count()}}</strong>
                                </p>
                                <p class="card-text">Başarısız Satış :
                                    <strong>{{DB::table('pazar_yeri_ilanlar')->where('user', $satici->id)->whereNull('deleted_at')->where('userStatus', '1')->where('status', '9')->count()}}</strong>
                                </p>
                                <p class="card-text">Devam Eden Satış :
                                    <strong>{{DB::table('pazar_yeri_ilanlar')->where('user', $satici->id)->whereNull('deleted_at')->where('userStatus', '1')->where('status', '1')->count()}}</strong>
                                </p>
                                <p class="card-text">Email Doğrulanma Durumu :
                                    <strong>{!! getUserEmailStatus($satici->id) !!}</strong></p>
                                <p class="card-text">Telefon Doğrulanma Durumu :
                                    <strong>{!! getUserPhoneStatus($satici->id) !!}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-9">

                            <div class="">
                                <ul class="nav nav-pills custom-nav mb-3" id="v-pills-tab" role="tablist"
                                    aria-orientation="vertical">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if(!isset($_GET['alislar'])) active @endif"
                                                id="v-pills-ilanlar-tab" data-bs-toggle="pill"
                                                data-bs-target="#v-pills-ilanlar" type="button" role="tab"
                                                aria-controls="v-pills-ilanlar"
                                                @if(!isset($_GET['alislar'])) aria-selected="true"
                                                @else aria-selected="false" @endif>Satış İlanları
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if(isset($_GET['alislar'])) active @endif"
                                                id="v-pills-alislar-tab" data-bs-toggle="pill"
                                                data-bs-target="#v-pills-alislar" type="button" role="tab"
                                                aria-controls="v-pills-alislar"
                                                @if(isset($_GET['alislar'])) aria-selected="true"
                                                @else aria-selected="false" @endif>Alış İlanları
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="v-pills-yorumlar-tab" data-bs-toggle="pill"
                                                data-bs-target="#v-pills-yorumlar" type="button" role="tab"
                                                aria-controls="v-pills-yorumlar" aria-selected="false">Yorumlar
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="v-pills-rozetler-tab" data-bs-toggle="pill"
                                                data-bs-target="#v-pills-rozetler" type="button" role="tab"
                                                aria-controls="v-pills-rozetler" aria-selected="false">Rozetler
                                        </button>
                                    </li>

                                </ul>
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade @if(!isset($_GET['alislar'])) show active @endif"
                                         id="v-pills-ilanlar" role="tabpanel"
                                         aria-labelledby="v-pills-ilanlar-tab">
                                        <div class="row crn_pay">
                                            @foreach($ilanlar as $u)
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="card mb-3 style-item-card" style="width: 100%;">
                                                        @if($u->toplu == 1)
                                                            @if($u->type == 0)
                                                                <div class="item-image profil-page">
                                                                    <figure>
                                                                        <div id="carouselExampleIndicators"
                                                                             class="carousel slide"
                                                                             data-bs-ride="carousel">
                                                                            <div class="carousel-inner">
                                                                                @foreach(DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u->id)->get() as $t)
                                                                                    <div class="carousel-item @if($loop->iteration == 1) active @endif">
                                                                                        <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', DB::table('pazar_yeri_ilan_icerik')->where('ilan', $t->ilan)->first()->item)->first()->image)}}"
                                                                                             class="card-img-top">
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                            <button class="carousel-control-prev"
                                                                                    type="button"
                                                                                    data-bs-target="#carouselExampleIndicators"
                                                                                    data-bs-slide="prev">
                                                                    <span class="carousel-control-prev-icon"
                                                                          aria-hidden="true"></span>
                                                                                <span class="visually-hidden">Önceki</span>
                                                                            </button>
                                                                            <button class="carousel-control-next"
                                                                                    type="button"
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
                                                                    <div id="carouselExampleControls"
                                                                         class="carousel slide"
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
                                                                        <button class="carousel-control-prev"
                                                                                type="button"
                                                                                data-bs-target="#carouselExampleControls"
                                                                                data-bs-slide="prev">
                                                                    <span class="carousel-control-prev-icon"
                                                                          aria-hidden="true"></span>
                                                                            <span class="visually-hidden">Önceki</span>
                                                                        </button>
                                                                        <button class="carousel-control-next"
                                                                                type="button"
                                                                                data-bs-target="#carouselExampleControls"
                                                                                data-bs-slide="next">
                                                                    <span class="carousel-control-next-icon"
                                                                          aria-hidden="true"></span>
                                                                            <span class="visually-hidden">Sonraki</span>
                                                                        </button>
                                                                    </div>
                                                                @else
                                                                    <div class="item-image profil-page">
                                                                        <figure>
                                                                            @if(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->count() > 0)
                                                                                <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->first()->item)->first()->image)}}"
                                                                                     class="card-img-top" alt="...">
                                                                            @endif
                                                                        </figure>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <img src="{{asset('public/front/ilanlar/'.$u->image)}}"
                                                                     class="card-img-top" alt="{{$u->title}} Görseli">
                                                            @endif
                                                        @endif

                                                        <div class="card-body">
                                                            <h6 class="card-title">
                                                                <?php
                                                                $item = DB::table('games_titles')->where('id', $u->pazar)->first();
                                                                ?>
                                                                <a href="{{route('item_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}">
                                                                    {{substr($u->title, 0, 15)}} @if(strlen($u->title) > 14)
                                                                        ...@endif
                                                                </a>
                                                            </h6>
                                                            <p class="card-text">{{$u->sunucu}}
                                                                /
                                                                @if(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->count() > 1)
                                                                    Set
                                                                @else
                                                                    Item
                                                                @endif
                                                            </p>
                                                            <div class="card-footer">
                                                                <span class="card-text price"><span class="moneysymbol">₺</span>{{$u->price}}</span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <nav aria-label="Page navigation example">
                                                <ul class="pagination justify-content-center">
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{$ilanlar->url(1)}}">{{"|<<"}}</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                           href="{{$ilanlar->previousPageUrl()}}">{{"<"}}</a>
                                                    </li>
                                                    @for($i = 1; $i < $ilanlar->lastPage()+1; $i++)
                                                        <li class="page-item @if($ilanlar->currentPage() == $i) active @endif">
                                                            <a
                                                                    class="page-link"
                                                                    href="{{$ilanlar->url($i)}}">{{$i}}</a></li>
                                                    @endfor
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                           href="{{$ilanlar->nextPageUrl()}}">{{">"}}</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                           href="{{$ilanlar->url($ilanlar->lastPage())}}">{{">>|"}}</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade @if(isset($_GET['alislar'])) show active @endif"
                                         id="v-pills-alislar" role="tabpanel"
                                         aria-labelledby="v-pills-alislar-tab">
                                        <div class="row crn_pay">
                                            @foreach($alislar as $u)
                                                <div class="col-md-2 col-sm-6">
                                                    <div class="card mb-3 style-item-card" style="width: 100%;">

                                                        @if($u->type == 0)
                                                            @if(DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u->id)->count() > 1)
                                                                <div id="carouselExampleControls"
                                                                     class="carousel slide"
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
                                                                    <button class="carousel-control-prev"
                                                                            type="button"
                                                                            data-bs-target="#carouselExampleControls"
                                                                            data-bs-slide="prev">
                                                                    <span class="carousel-control-prev-icon"
                                                                          aria-hidden="true"></span>
                                                                        <span class="visually-hidden">Önceki</span>
                                                                    </button>
                                                                    <button class="carousel-control-next"
                                                                            type="button"
                                                                            data-bs-target="#carouselExampleControls"
                                                                            data-bs-slide="next">
                                                                    <span class="carousel-control-next-icon"
                                                                          aria-hidden="true"></span>
                                                                        <span class="visually-hidden">Sonraki</span>
                                                                    </button>
                                                                </div>
                                                            @else
                                                                <div class="item-image profil-page">
                                                                    <figure>
                                                                        @if(DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u->id)->count() > 0)
                                                                            <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u->id)->first()->item)->first()->image)}}"
                                                                                 class="card-img-top" alt="...">
                                                                        @endif
                                                                    </figure>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <img src="{{asset('public/front/ilanlar/'.$u->image)}}"
                                                                 class="card-img-top" alt="{{$u->title}} Görseli">
                                                        @endif

                                                        <div class="card-body">
                                                            <h6 class="card-title">
                                                                <?php
                                                                $item = DB::table('games_titles')->where('id', $u->pazar)->first();
                                                                ?>
                                                                <a href="{{route('item_buy_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}">
                                                                    {{substr($u->title, 0, 15)}} @if(strlen($u->title) > 14)
                                                                        ...@endif
                                                                </a>
                                                            </h6>
                                                            <p class="card-text">{{$u->sunucu}}
                                                                /
                                                                @if(DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u->id)->count() > 1)
                                                                    Set
                                                                @else
                                                                    Item
                                                                @endif
                                                            </p>
                                                            <div class="card-footer">
                                                                <span class="card-text price"><span class="moneysymbol">₺</span>{{$u->price}}</span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <nav aria-label="Page navigation example">
                                                <ul class="pagination justify-content-center">
                                                    <li class="page-item">
                                                        <a class="page-link" href=?alislar=1">{{"|<<"}}</a>
                                                    </li>
                                                    <?php
                                                    $prevPage = $alislar->currentPage() - 1;
                                                    if ($prevPage < '1') {
                                                        $prevPage = '1';
                                                    }
                                                    ?>
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                           href="?alislar={{$prevPage}}">{{"<"}}</a>
                                                    </li>
                                                    @for($i = 1; $i < $alislar->lastPage()+1; $i++)
                                                        <li class="page-item @if($alislar->currentPage() == $i) active @endif">
                                                            <a class="page-link" href="?alislar={{$i}}">{{$i}}</a></li>
                                                    @endfor
                                                    <?php
                                                    $nextPage = $alislar->currentPage() + 1;
                                                    if ($nextPage > $alislar->lastPage()) {
                                                        $nextPage = $alislar->lastPage();
                                                    }
                                                    ?>
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                           href="?alislar={{$nextPage}}">{{">"}}</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                           href="?alislar={{$alislar->lastPage()}}">{{">>|"}}</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="v-pills-yorumlar" role="tabpanel"
                                         aria-labelledby="v-pills-yorumlar-tab">

                                        @if(DB::table('satici_yorumlar')->whereNull('deleted_at')->where('satici', $satici->id)->where('status', '1')->count() > 0)
                                            <div class="row">
                                                @foreach(DB::table('satici_yorumlar')->whereNull('deleted_at')->where('satici', $satici->id)->where('status', '1')->orderBy('created_at', 'desc')->get() as $yy)
                                                    <div class="col-12 mb-3">
                                                        <div class="comment-container">
                                                            <div class="this-comment">
                                                                <div class="commenter">
                                                                    <h6>{{substr(DB::table('users')->where('id', $yy->yapan)->first()->name, 0, 2)}}
                                                                        *** *****</h6>
                                                                    <span class="cmnt-stars">
                                                                @for($i = 1; $i <= $yy->puan; $i++)
                                                                            <i class="fas fa-star"></i>
                                                                        @endfor
                                                                        @for($i= $yy->puan;$i< 5; $i++)
                                                                            <i class="far fa-star"></i>
                                                                        @endfor
                                                                </span>
                                                                </div>

                                                                <div class="cmnt-text">
                                                                    {{$yy->text}}
                                                                </div>
                                                                <div class="cmnt-date">
                                                                    {{$yy->created_at}}
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>

                                                @endforeach
                                            </div>
                                        @else
                                            <div class="comments-container">
                                                <p><span class="w_ico"><i
                                                                class="fal fa-comment-alt"></i></span>
                                                    Henüz bu satıcıya bir yorum gelmemiş</p>
                                            </div>
                                        @endif
                                        @if(isset(Auth::user()->id))
                                            <form method="post"
                                                  action="{{route('satici_yorum_yap')}}">
                                                @csrf
                                                <input type="hidden" name="satici"
                                                       value="{{$satici->id}}">
                                                <div class="row">
                                                    <div class="comment-send-wrapper">
                                                        <div class="comment-send-form">
                                                            <div class="col-12">
                                                                <label for="1"
                                                                       class="form-label">Mesajınız</label>
                                                                <textarea
                                                                        class="form-control"
                                                                        name="text"
                                                                        id="1"
                                                                        required></textarea>
                                                            </div>
                                                            <div class="vote-stars">
                                                                <label>
                                                                    <input type="radio" name="rate"
                                                                           value="1"
                                                                           required>
                                                                    <input type="radio" name="rate"
                                                                           value="2" required>
                                                                    <input type="radio" name="rate"
                                                                           value="3" required>
                                                                    <input type="radio" name="rate"
                                                                           value="4" required>
                                                                    <input type="radio" name="rate"
                                                                           value="5" required>
                                                                    <div class="stars">
                                                                        <span></span>
                                                                        <span></span>
                                                                        <span></span>
                                                                        <span></span>
                                                                        <span></span>
                                                                    </div>
                                                                </label>
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
                                                        onclick="location.href='{{route('giris')}}'">
                                                    Giriş Yap
                                                </button>
                                            </div>
                                        @endif


                                    </div>
                                    <div class="tab-pane fade" id="v-pills-rozetler" role="tabpanel"
                                         aria-labelledby="v-pills-rozetler-tab">
                                        <div class="alert alert-danger" role="alert">
                                            <h4 class="alert-heading">Rozetler Çok Yakında Burada!</h4>
                                            <p>Çok yakında bu alanda rozetlerinizi görüntüleyebileceksiniz. Rozetler
                                                sayesinde bir çok avantaj elde edebilir ve daha düşük komisyonlarla
                                                alışveriş yapabilirsiniz.</p>
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
