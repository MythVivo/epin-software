<?php
$item = DB::table('games_titles')->where('link', $item)->first();

if (isset($_GET['sunucu'])) {
    if ($_GET['sunucu'] == "bos") {
        $sunucu = "";
    } else {
        $sunucu = $_GET['sunucu'];
    }

} else {
    $sunucu = "";
}
if (isset($_GET['q'])) {
    $q = $_GET['q'];
} else {
    $q = "";
}
if (isset($_GET['order'])) {
    $order = $_GET['order'];
    if ($order == 'az') {
        $col = "pazar_yeri_ilanlar_buy.title";
        $type = "asc";
    } elseif ($order == 'za') {
        $col = "pazar_yeri_ilanlar_buy.title";
        $type = "desc";
    } elseif ($order == 'price_asc') {
        $col = "pazar_yeri_ilanlar_buy.price";
        $type = "asc";
    } elseif ($order == 'price_desc') {
        $col = "pazar_yeri_ilanlar_buy.price";
        $type = "desc";
    } elseif ($order == 'create_asc') {
        $col = "pazar_yeri_ilanlar_buy.created_at";
        $type = "asc";
    } elseif ($order == 'create_desc') {
        $col = "pazar_yeri_ilanlar_buy.created_at";
        $type = "desc";
    } else {
        $col = "pazar_yeri_ilanlar_buy.created_at";
        $type = "desc";
    }
} else {
    $col = "pazar_yeri_ilanlar_buy.created_at";
    $type = "desc";
}

$q=str_replace(array('&','<','>', '%', '`', '*',"'",'"','/','\\','|','.'), '', $q);
$sunucu=str_replace(array('&','<','>', '%', '`', '*',"'",'"','/','\\','|','.'), '', $sunucu);

$ilanlar = DB::table('pazar_yeri_ilanlar_buy')
    ->where('pazar', $item->id)
    ->where('pazar_yeri_ilanlar_buy.status', '1')
    ->where('userStatus', '1')
    ->whereNull('pazar_yeri_ilanlar_buy.deleted_at')
    ->where('pazar_yeri_ilanlar_buy.sunucu', 'like', '%' . $sunucu . '%')
    ->where('pazar_yeri_ilanlar_buy.title', 'like', '%' . $q . '%')
    ->orderBy($col, $type)
    ->select('pazar_yeri_ilanlar_buy.id as ilanId', 'pazar_yeri_ilanlar_buy.*')
    ->get();

$veriler = (object)[];
$a = 0;

foreach ($ilanlar as $items) {
    $veriler->ilanInfo[] = ['id' => $items->id, 'pazar' => $items->pazar, 'user' => $items->user, 'price' => $items->price, 'title' => $items->title, 'sunucu' => $items->sunucu, 'created_at' => $items->created_at, 'type' => $items->type, 'image' => $items->image, 'toplu' => $items->toplu];
    if ($items->type == 0) {
        foreach (DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $items->id)->get() as $ilanicerik) {
            foreach (DB::table('games_titles_items_info')->where('id', $ilanicerik->item)->get() as $itemBilgileri) {
                $veriler->ilanInfo[$a]['itemBilgi'] = ['id' => $itemBilgileri->id, 'title' => $itemBilgileri->title];
                foreach (DB::table('games_titles_features')->where('game_title', $items->pazar)->whereNull('deleted_at')->get() as $pazarOzellikler) {
                    $featureName = Str::slug($pazarOzellikler->title);
                    if (isset($_GET[$featureName]) and (isset($_GET[$featureName]) and $_GET[$featureName] != "bos") and $featureName != "sunucu") {
                        $ozellikKontrol = DB::table('games_titles_items')->where('item', $itemBilgileri->id)->where('feature', $pazarOzellikler->id);
                        if ($ozellikKontrol->count() > 0) {
                            if ($ozellikKontrol->count() > 1) {
                                $var = 1;
                                foreach ($ozellikKontrol->get() as $topluGelenOzellik) {
                                    if ($topluGelenOzellik->value == $_GET[$featureName]) {
                                        $var += 1;
                                    } else {
                                        $var -= 1;
                                    }
                                }
                                if ($var < 1) {
                                    $veriler->ilanInfo[$a]['itemBilgi']['itemFiltre'] = 0;
                                    unset($veriler->ilanInfo[$a]);
                                }
                            } else {
                                if ($ozellikKontrol->first()->value != $_GET[$featureName]) {
                                    $veriler->ilanInfo[$a]['itemBilgi']['itemFiltre'] = 0;
                                    unset($veriler->ilanInfo[$a]);
                                }
                            }
                        }

                    }
                }
            }
        }
        $a++;
    } else {
        foreach (DB::table('games_titles_features')->where('game_title', $items->pazar)->whereNull('deleted_at')->get() as $p) {
            $featureName = Str::slug($p->title);
            if (isset($_GET[$featureName]) and (isset($_GET[$featureName]) and $_GET[$featureName] != "bos") and $featureName != "sunucu") {
                $ozellikKontrol = DB::table('pazar_yeri_ilan_features')->where('ilan', $items->id)->where('feature', $p->id);
                if ($ozellikKontrol->count() > 0) {
                    if ($ozellikKontrol->first()->value != $_GET[$featureName]) {
                        $veriler->ilanInfo[$a]['itemBilgi']['itemFiltre'] = 0;
                        unset($veriler->ilanInfo[$a]);
                    }
                }

            }
        }

        $a++;

    }

}

?>
@if(isset($veriler->ilanInfo) and count($veriler->ilanInfo) > 0)
    @foreach($veriler->ilanInfo as $u)
        @if($u['type'] == 1)
            @if(!isset($u['itemBilgi']['itemFiltre']))
                <div class="col-md-3">
                    <div class="card style-item-card closed" style="width: 100%;">
                        <div class="item-image">
                            <a target="a_blank"
                               href="{{route('item_ic_detay', [$item->link, $u['sunucu'], Str::slug($u['title']).'-'.$u['id']])}}">
                                <figure>
                                    @if($u['toplu'] == 1)
                                        <div id="carouselExampleIndicators" class="carousel slide"
                                             data-bs-ride="carousel">
                                            <div class="carousel-indicators">
                                                @foreach(DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u['id'])->get() as $t)
                                                    <button type="button" data-bs-target="#carouselExampleIndicators"
                                                            data-bs-slide-to="{{$loop->iteration}}"
                                                            @if($loop->iteration == 1) class="active"
                                                            aria-current="true"
                                                            @endif  aria-label="Slide {{$loop->iteration}}"></button>
                                                @endforeach
                                            </div>
                                            <div class="carousel-inner">
                                                @foreach(DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u['id'])->get() as $t)
                                                    <div class="carousel-item @if($loop->iteration == 1) active @endif">
                                                        <img class="card-img-top"
                                                             src="{{asset('public/front/ilanlar/'.DB::table('pazar_yeri_ilanlar')->where('id', $t->ilan)->first()->image)}}">
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
                                    @else
                                        <img src="{{asset('public/front/ilanlar/'.$u['image'])}}"
                                             class="card-img-top" alt="{{$u['title']}} görseli">
                                    @endif

                                </figure>
                            </a>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">
                                <a href="{{route('item_ic_detay', [$item->link, $u['sunucu'], Str::slug($u['title']).'-'.$u['id']])}}">
                                    {{substr($u['title'], 0, 50)}} @if(strlen($u['title']) > 50)
                                        ...@endif
                                </a>
                            </h6>
                            <p class="card-text">{{ucfirst($u['sunucu'])}}
                                /
                                @if(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u['id'])->count() > 1)
                                    Set
                                @else
                                    @if($item->link == "cypher-ring")
                                        Karakter
                                    @else
                                        Item
                                    @endif
                                @endif
                            </p>

                        </div>
                        <div class="card-footer priceContainer" >
                            <span class="card-text price"><span class="moneysymbol">₺</span>{{MF($u['price'])}}</span>
                            <span class="card-text price">
                                {!! userLastSeen($u['user']) !!}
                                </span>
                        </div>
                    </div>
                </div>
            @endif
        @else
            @if(!isset($u['itemBilgi']['itemFiltre']))
                <div class="colflex">
                    <div class="col_cell">
                        <div class="card style-item-card closed" style="width: 100%;">
                            @if(DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u['id'])->count() > 1)
                                <div id="carouselExampleControls" class="carousel slide"
                                     data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u['id'])->get() as $uu)
                                            <div class="carousel-item @if($loop->first) active @endif">
                                                <div class="item-image">
                                                    <figure>
                                                        <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', $uu->item)->first()->image)}}"
                                                             class="d-block w-100"
                                                             alt="{{DB::table('games_titles_items_info')->where('id', $uu->item)->first()->title}} görseli">
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
                                @if($u['toplu'] == 1)
                                    <div class="item-image">
                                        <a target="a_blank"
                                           href="{{route('item_ic_detay', [$item->link, $u['sunucu'], Str::slug($u['title']).'-'.$u['id']])}}">
                                            <figure>
                                                <div id="itemSlide{{$u['id']}}" class="carousel slide"
                                                     data-bs-ride="carousel">
                                                    <div class="carousel-inner">
                                                        @foreach(DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u['id'])->get() as $t)
                                                            <div class="carousel-item @if($loop->iteration == 1) active @endif">
                                                                <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', DB::table('pazar_yeri_ilan_icerik')->where('ilan', $t->ilan)->first()->item)->first()->image)}}"
                                                                     class="card-img-top">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button class="carousel-control-prev" type="button"
                                                            data-bs-target="#itemSlide{{$u['id']}}"
                                                            data-bs-slide="prev">
                                                                    <span class="carousel-control-prev-icon"
                                                                          aria-hidden="true"></span>
                                                        <span class="visually-hidden">Önceki</span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button"
                                                            data-bs-target="#itemSlide{{$u['id']}}"
                                                            data-bs-slide="next">
                                                                    <span class="carousel-control-next-icon"
                                                                          aria-hidden="true"></span>
                                                        <span class="visually-hidden">Sonraki</span>
                                                    </button>
                                                </div>
                                            </figure>
                                        </a>
                                    </div>
                                @else
                                    <?php
                                    $itemBilgi = DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u['id'])->first();
                                    if (DB::table('games_titles_items_photos')->where('item', $itemBilgi->item)->count() > 0) {
                                        $itemPhoto = DB::table('games_titles_items_photos')->where('item', $itemBilgi->item)->first()->image;
                                    } else {
                                        $itemPhoto = "";
                                    }
                                    ?>
                                    <div class="item-image">
                                        <a target="a_blank"
                                           href="{{route('item_buy_ic_detay', [$item->link, $u['sunucu'], Str::slug($u['title']).'-'.$u['id']])}}">
                                            <figure>
                                                <img src="{{asset('public/front/games_items/'.$itemPhoto)}}"
                                                     class="card-img-top" alt="...">
                                            </figure>
                                        </a>
                                    </div>
                                @endif
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a href="{{route('item_buy_ic_detay', [$item->link, $u['sunucu'], Str::slug($u['title']).'-'.$u['id']])}}">
                                        {{substr($u['title'], 0, 50)}} @if(strlen($u['title']) > 50)
                                            ...@endif
                                    </a>
                                </h6>
                                <p class="card-text">{{ucfirst($u['sunucu'])}}
                                    /
                                    @if(DB::table('pazar_yeri_ilan_icerik_buy')->where('ilan', $u['id'])->count() > 1)
                                        Set
                                    @else
                                        @if($item->link == "cypher-ring")
                                            Karakter
                                        @else
                                            Item
                                        @endif
                                    @endif
                                </p>

                            </div>
                             <div class="card-footer priceContainer" >
                                <span class="card-text price"><span class="moneysymbol">₺</span>{{MF($u['price'])}}</span>
                                <span class="card-text price">
                                    {!! userLastSeen($u['user']) !!}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

    @endforeach
@else
    <div class="alert alert-danger fade show d-flex align-items-center"
         role="alert">
        <h5>Eşleşen ilan bulunamadı</h5>
    </div>
@endif
<style>@media  only screen and (max-width: 1400px) {  .priceContainer {flex-direction: column;}  }</style>
