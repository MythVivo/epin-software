<?php

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

$item = DB::table('games_titles')->where('link', $item)->first();

if (isset($_GET['sunucu'])) {
    if ($_GET['sunucu'] == 'bos') {$sunucu = '';} else {$sunucu = $_GET['sunucu'];}
} else {
    $sunucu = '';
}

if (isset($_GET['q'])) {$q = $_GET['q'];} else {$q = '';}

if (isset($_GET['order'])) {
    $order = $_GET['order'];
    if ($order == 'az')               {$col = 'pazar_yeri_ilanlar.title';$type = 'asc';      }
    elseif ($order == 'za')           {$col = 'pazar_yeri_ilanlar.title';$type = 'desc';     }
    elseif ($order == 'price_asc')    {$col = 'pazar_yeri_ilanlar.price';$type = 'asc';      }
    elseif ($order == 'price_desc')   {$col = 'pazar_yeri_ilanlar.price';$type = 'desc';     }
    elseif ($order == 'create_asc')   {$col = 'pazar_yeri_ilanlar.created_at';$type = 'asc'; }
    elseif ($order == 'create_desc')  {$col = 'pazar_yeri_ilanlar.created_at';$type = 'desc';}
    else {
        $col = 'pazar_yeri_ilanlar.created_at';$type = 'desc';
    }
} else {
    $col = 'pazar_yeri_ilanlar.updated_at';
    $type = 'desc';
}
/*
$ilanlar = DB::table('pazar_yeri_ilanlar')
    ->where('pazar', $item->id)
    ->where('pazar_yeri_ilanlar.status', '1')
    ->whereNull('pazar_yeri_ilanlar.deleted_at')
    ->where('pazar_yeri_ilanlar.sunucu', 'like', '%' . $sunucu . '%')
    ->where('pazar_yeri_ilanlar.title', 'like', '%' . $q . '%')
    ->where('userStatus', '1')
    ->orderBy($col, $type)
    ->select('pazar_yeri_ilanlar.id as ilanId', 'pazar_yeri_ilanlar.*')
    ->get();
*/


if (isset($_GET['user'])) {
    $rt = $_GET['user'];
    $rt = str_replace(['&', '<', '>', '%', '`', '*', "'", '"', '/', '\\', '|', '.'], '', $rt);
    $ek = " and user='$rt' ";
} else {
    $ek = '';
}

$q = str_replace(['&', '<', '>', '%', '`', '*', "'", '"', '/', '\\', '|', '.'], '', $q);
$sunucu = str_replace(['&', '<', '>', '%', '`', '*', "'", '"', '/', '\\', '|', '.'], '', $sunucu);


#----------------------------------------------Yayın süresi kontrol tl (Time Limit) yoxa dead line 168 saat 7 gün
//if(@$_GET['test']) {
    $ilanlar = (object)DB::select("select u.id uid, u.telefon, pyi.* from pazar_yeri_ilanlar pyi left JOIN users u on u.id=pyi.user where isnull(pyi.deleted_at) and pyi.userStatus=1 and pyi.status=1 #and pyi.user=12562");
//$sayi=0;
    foreach ($ilanlar as $ilan) {

        $ilan->tl = $ilan->tl == ''?0:$ilan->tl;

        $s = $ilan->tl < 1 ? 167 : $ilan->tl - 1;$d = $s == 0 ? 58 : 0;                      #Kullanıcı TL girmediyse  al
        $fdate = strtotime($ilan->updated_at . ' +' . $s . ' hours +' . $d . ' minutes');   #Son güncelleme tarihine TL ekle
        $hours = (($fdate - time()) / 3600);                                                #Saati bul
        $mins = number_format((($fdate - time()) % 3600) / 60, 0);                          #kalan dakikayı bul
        //echo "$ilan->id = $s /";


        if($hours<2 && $ilan->red_neden!=':'){  # 2 saat kala hatırlatma mesajı gönder
            if(Auth::check() && kullaniciBildirimKategorisi($ilan->uid, 5)) {
                sendSms($ilan->telefon, "$ilan->title başlıklı ilanınızın süresi 2 saat içinde dolacaktır. Kullanıcı panelinizden ilan tarihinizi güncelleyerek yayında kalmasını sağlayabilirsiniz.");
            }
            DB::table('pazar_yeri_ilanlar')->where('id', $ilan->id)->update(['red_neden' => ':']);

        }

        if ($hours<=0 && $mins <= 0) {                  // saat 0 dakika < 0 ise süre doldu ilanı kaldır bilgi ver
            if(Auth::check() && kullaniciBildirimKategorisi($ilan->uid, 5)) {
                sendSms($ilan->telefon, "$ilan->title başlıklı ilanınızın süresi dolmuştur. Kullanıcı panelinizden tekrar yayına alabilirsiniz.");
            }
            setBildirim($ilan->user, '3', 'İlanınızın süreniz bitti', $ilan->title. ' başlıklı ilanınızın süresi dolmuştur. Kullanıcı panelinizden tekrar yayına alabilirsiniz.', route('satici_panelim'));
            DB::table('pazar_yeri_ilanlar')->where('id', $ilan->id)->update(['userStatus' => 0, 'red_neden' => 7]);

            LogCall('0', '1', "$ilan->id - $ilan->title başlıklı ilan yayın süresini ($ilan->tl saat) doldurduğu için yayından kaldırıldı. Kullanıcıya SMS ile bilgi verildi.");

        }

    }
    //echo "etkilenen -> $sayi";


//}
#----------------------------------------------Yayın süresi kontrol


#----------------------------------------------------------------------------------------------SWX
if (@$_GET['swx'] == 'swx') {
    $ilanlar = DB::select(
        "select *, id as ilanId from pazar_yeri_ilanlar
    where pazar='$item->id'
    and pazar_yeri_ilanlar.status=1
    and isnull(pazar_yeri_ilanlar.deleted_at)
    and pazar_yeri_ilanlar.sunucu like '%$sunucu%'
    and pazar_yeri_ilanlar.title like '%$q%'
    " . $ek . " and userStatus=1 order by " . $col . ' ' . $type
    );

    foreach ($ilanlar as $items) {
        echo $items->type . '<br>';
        //$veriler->ilanInfo[] = ['id' => $items->id, 'pazar' => $items->pazar, 'user' => $items->user, 'price' => $items->price, 'title' => $items->title, 'sunucu' => $items->sunucu, 'created_at' => $items->created_at, 'type' => $items->type, 'image' => $items->image, 'toplu' => $items->toplu];
        echo $items->type . '<br>';

        foreach (DB::table('games_titles_features')->where('game_title', $items->pazar)->whereNull('deleted_at')->get() as $p)
        {
             $featureName = Str::slug($p->title);
            if (isset($_GET[$featureName]) and (isset($_GET[$featureName]) and $_GET[$featureName] != 'bos') and $featureName != 'sunucu') {
                $ozellikKontrol = DB::table('pazar_yeri_ilan_features')->where('ilan', $items->id)->where('feature', $p->id);
                if ($ozellikKontrol->count() > 0) {
                    if ($ozellikKontrol->first()->value != $_GET[$featureName]) {
                        /* $veriler->ilanInfo[$a]['itemBilgi']['itemFiltre'] = 0;
                        unset($veriler->ilanInfo[$a]); */
                    }
                }
            }
        }
    }
    exit();

    $where = " where pazar='$item->id' and pazar_yeri_ilanlar.status=1 and isnull(pazar_yeri_ilanlar.deleted_at)  and userStatus=1 $ek ";
    $where .= $sunucu ? " and pazar_yeri_ilanlar.sunucu like '%$sunucu%'" : '';
    $where .= $q ? " and pazar_yeri_ilanlar.title like '%$q%'" : '';
    $query =
        "select pazar_yeri_ilanlar.*, pazar_yeri_ilanlar.id as ilanId,
            games_titles_items_info.id as bilgiId , games_titles_items_info.title as bilgiTitle
            from pazar_yeri_ilanlar
            left outer join pazar_yeri_ilan_icerik on pazar_yeri_ilan_icerik.ilan = pazar_yeri_ilanlar.id
            left outer join games_titles_items_info on games_titles_items_info.id = pazar_yeri_ilan_icerik.item
            $where
            order by " .
        $col .
        ' ' .
        $type;

    echo $query;
    exit();
}#----------------------------------------------------------------------------------------------SWX SON

if (intval($q) == $q) {
    $ilanlar = DB::select('select *, id as ilanId from pazar_yeri_ilanlar where userStatus=1 and status=1 and deleted_at is null and id=' . intval($q));
} else {
    if(@$_GET['set-mi']=='evet') { $set=" and LENGTH(grup)>2 ";} else {$set="";}

$ilanlar = DB::select(
        "select *, id as ilanId from pazar_yeri_ilanlar
    where pazar='$item->id'
    and status=1
    and userStatus=1
    and isnull(deleted_at)
    and sunucu like '%$sunucu%'
    and title like '%$q%' "
    .$set.$ek."
     order by " . $col .' '.$type);
}

$veriler = (object) [];
$a = 0;

foreach ($ilanlar as $items) {
    $veriler->ilanInfo[] = ['id' => $items->id, 'pazar' => $items->pazar, 'user' => $items->user, 'price' => $items->price, 'title' => $items->title, 'sunucu' => $items->sunucu,'grup'=>$items->grup, 'tl'=>$items->tl,'updated_at' => $items->updated_at, 'created_at' => $items->created_at, 'type' => $items->type, 'image' => $items->image, 'toplu' => $items->toplu];
    if ($items->type == 0) {
        foreach (DB::table('pazar_yeri_ilan_icerik')->where('ilan', $items->id)->get() as $ilanicerik) {
            foreach (DB::table('games_titles_items_info')->where('id', $ilanicerik->item)->get() as $itemBilgileri) {
                $veriler->ilanInfo[$a]['itemBilgi'] = ['id' => $itemBilgileri->id, 'title' => $itemBilgileri->title];
                foreach (DB::table('games_titles_features')->where('game_title', $items->pazar)->whereNull('deleted_at')->get() as $pazarOzellikler) {
                    $featureName = Str::slug($pazarOzellikler->title);
                    if (isset($_GET[$featureName]) and (isset($_GET[$featureName]) and $_GET[$featureName] != 'bos') and $featureName != 'sunucu' and $featureName != 'set-mi') {
                        $ozellikKontrol = DB::table('games_titles_items')->where('item', $itemBilgileri->id)->where('feature', $pazarOzellikler->id);
                        if ($ozellikKontrol->count() > 0) {
                            if ($ozellikKontrol->count() > 1) {
                                $var = 1;
                                foreach ($ozellikKontrol->get() as $topluGelenOzellik) {
                                    if ($topluGelenOzellik->value == $_GET[$featureName]) {$var += 1;} else {$var -= 1;}
                                }
                                if ($var < 1) {$veriler->ilanInfo[$a]['itemBilgi']['itemFiltre'] = 0;unset($veriler->ilanInfo[$a]);}
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
            if (isset($_GET[$featureName]) and (isset($_GET[$featureName]) and $_GET[$featureName] != 'bos') and $featureName != 'sunucu') {
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
if (isset($veriler->ilanInfo)) {
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = null;
    }
    $perPage = 20;
    $options = [];
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $veriler->ilanInfo;
    $items = $items instanceof Collection ? $items : Collection::make($items);
    $donen = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    //var_dump($donen);
}

?>

<style>
    /*
    .sure1 {cursor: help; color: #ff0000;width: 66px;background-color: #ffffff;border-top-left-radius: 10px;border-bottom-right-radius: 10px;padding: 8px 8px;margin: 8px -9px;display: flex;position: absolute;height: 22px;align-items: center;font-size: .8em;font-weight: bold;}


    */

    .sure1 {text-align: center;font-size: 1em; color: red;}
    body.dark .sure1 {text-align: center;font-size: 1em; color: gold;}
    @media only screen and (max-width: 1400px) {
        .priceContainer {flex-direction: column;}
        .sure2 {text-align: center;font-size: .7em; font-weight: normal; color: red;}
         body.dark .sure2 {text-align: center;font-size: .7em; font-weight: normal; color: gold;}
    }
    .progress-bar {background-color: #cccccc;animation: progressBar 3s ease-out;animation-fill-mode:forwards;}

</style>

@if (isset($donen) and count($donen) > 0)
    @foreach ($donen as $u)
        @if ($u['type'] == 1)
            @if (!isset($u['itemBilgi']['itemFiltre']))
                <div class="colflex col-full">
                    <div class="col_cell">
                        <div class="card style-item-card closed" data-set-id="{{ $u['id'] }}" style="width: 100%;">
                            <? #------------------------------------RISE ONLINE için yayın süresi

//                                if(@$_GET['test']) {
                                    $s = $u['tl'] < 1 ? 167 : $u['tl'] - 1;
                                    $d = $s==0?58:0;
                                    $fdate = strtotime($u['updated_at'] . ' +' . $s . ' hours +'.$d.' minutes');
                                    $day = number_format((($fdate - time()) / 3600/24),0);
                                    $hours = number_format((($fdate - time()) / 3600), 0);
                                    $mins = number_format((($fdate - time()) % 3600) / 60, 0);
                                    $mins = strlen($mins) == 1 ? '0' . $mins : $mins;
                                    $hours = strlen($hours) == 1 ? '0' . $hours : $hours;
                                    $hours = $s==0?'00':$hours;
                                    if($mins<=0){ $mins='00';}
                                    if($day>0){$day.=' Gün ' . $hours%24 . ' Saat';} else { if($hours=='00'){$day=$mins." dk.";} else {$day=$hours. " Saat ". $mins." dk.";} }
                                    echo "<div title='Kalan Süre' class='sure1 sure2'><i class='fal fa-hourglass-half'></i> &nbsp&nbsp  $day</div>";
  //                              }

                            ?>

                            <div class="item-image">

                                <figure style="border-radius: unset">
                                    @if ($u['toplu'] == 1)
                                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-indicators">
                                                @foreach (DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u['id'])->get() as $t)
                                                    <button type="button" data-bs-target="#carouselExampleIndicators"
                                                        data-bs-slide-to="{{ $loop->iteration }}"
                                                        @if ($loop->iteration == 1) class="active"
                                aria-current="true" @endif
                                                        aria-label="Slide {{ $loop->iteration }}"></button>
                                                @endforeach
                                            </div>
                                            <div class="carousel-inner">
                                                @foreach (DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u['id'])->get() as $t)
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
                                            <div class="toplu_ilan"><span>Toplu İlan</span></div>
                                        </div>
                                    @else
                                        <a target="_blank"
                                            href="{{ route('item_ic_detay', [$item->link, $u['sunucu'], Str::slug($u['title']) . '-' . $u['id']]) }}">
                                            <img src="{{ asset('public/front/ilanlar/' . $u['image']) }}" class="card-img-top" alt="{{ $u['title'] }} görseli">
                                        </a>
                                    @endif

                                </figure>

                            </div>
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a target="_blank"
                                        href="{{ route('item_ic_detay', [$item->link, $u['sunucu'], Str::slug($u['title']) . '-' . $u['id']]) }}">
                                        {{ substr($u['title'], 0, 50) }} @if (strlen($u['title']) > 50) ... @endif
                                    </a>

                                </h6>
                                <p class="card-text">{{ ucfirst($u['sunucu']) }}
                                    /
                                    @if (DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u['id'])->count() > 1)
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
                            <div class="card-footer  priceContainer">
                                <span class="card-text price"><span class="moneysymbol">₺</span>{{ MF($u['price']) }}</span>

                                <span class="card-text price">
                                                                    <label style=" font-size: x-small; font-weight: normal; display: flex; margin-bottom: -8px; ">Kullanıcı Aktivite</label>
                                    {!! userLastSeen($u['user']) !!}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            @if (!isset($u['itemBilgi']['itemFiltre']))
                <div class="colflex col-full">
                    <div class="col_cell">
                        <div class="card style-item-card closed" data-set-id="{{ $u['id'] }}"
                            style="width: 100%;">
                            @if (DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u['id'])->count() > 1)
                                <div id="itemSlide{{ $u['id'] }}" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach (DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u['id'])->get() as $uu)
                                            <div class="carousel-item @if ($loop->first) active @endif">
                                                <div class="item-image">
                                                    <figure style="border-radius: unset">
                                                        <img src="{{ asset('public/front/games_items/' .DB::table('games_titles_items_photos')->where('item', $uu->item)->first()->image) }}"
                                                            class="d-block w-100"
                                                            alt="{{ DB::table('games_titles_items_info')->where('id', $uu->item)->first()->title }} görseli">
                                                    </figure>
                                                </div>

                                            </div>
                                        @endforeach

                                    </div>
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#itemSlide{{ $u['id'] }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Önceki</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#itemSlide{{ $u['id'] }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Sonraki</span>
                                    </button>
                                    <div class="toplu_ilan"><span>Toplu İlan</span></div>
                                </div>
                            @else
                                @if ($u['toplu'] == 1)
                                    <div class="item-image">

                                        <figure style="border-radius: unset">
                                            <div id="itemSlide{{ $u['id'] }}" class="carousel slide"
                                                data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    @foreach (DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u['id'])->get() as $t)
                                                        <div
                                                            class="carousel-item @if ($loop->iteration == 1) active @endif">
                                                            <?php
                                                            $ilanicerik = DB::table('pazar_yeri_ilan_icerik')
                                                                ->where('ilan', $t->ilan)
                                                                ->first();
                                                            ?>
                                                            @if ($ilanicerik)
                                                                <?php
                                                                $photo = DB::table('games_titles_items_photos')
                                                                    ->where('item', $ilanicerik->item)
                                                                    ->first();
                                                                ?>
                                                                @if ($photo)
                                                                    <img src="{{ asset('public/front/games_items/' . $photo->image) }}"
                                                                        class="card-img-top">
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button class="carousel-control-prev" type="button"
                                                    data-bs-target="#itemSlide{{ $u['id'] }}"
                                                    data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Önceki</span>
                                                </button>
                                                <button class="carousel-control-next" type="button"
                                                    data-bs-target="#itemSlide{{ $u['id'] }}"
                                                    data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Sonraki</span>
                                                </button>
                                            </div>
                                        </figure>
                                        <div class="toplu_ilan"><span>Toplu İlan</span></div>
                                    </div>
                                @else
                                    <?php
                                    $itemBilgi = DB::table('pazar_yeri_ilan_icerik')
                                        ->where('ilan', $u['id'])
                                        ->first();
                                    if (
                                        DB::table('games_titles_items_photos')
                                            ->where('item', $itemBilgi->item)
                                            ->count() > 0
                                    ) {
                                        $itemPhoto = DB::table('games_titles_items_photos')
                                            ->where('item', $itemBilgi->item)
                                            ->first()->image;
                                    } else {
                                        $itemPhoto = '';
                                    }
                                #----------------KO ve Cypher için yayın süresi
//                                        if(@$_GET['test']) {
                                            $s = $u['tl'] < 1 ? 167 : $u['tl'] - 1;
                                            $d = $s==0?58:0;
                                            $fdate = strtotime($u['updated_at'] . ' +' . $s . ' hours +'.$d.' minutes');
                                            $day = number_format((($fdate - time()) / 3600/24),0);
                                            $hours = number_format((($fdate - time()) / 3600), 0);
                                            $mins = number_format((($fdate - time()) % 3600) / 60, 0);
                                            $mins = strlen($mins) == 1 ? '0' . $mins : $mins;
                                            $hours = strlen($hours) == 1 ? '0' . $hours : $hours;
                                            $hours = $s==0?'00':$hours;
                                            if($mins<=0){ $mins='00';}

                                        if($day>0){$day.=' Gün ' . $hours%24 . ' Saat';} else { if($hours=='00' || $hours<0 ){$day=$mins." dk.";} else {$day=$hours. " Saat ". $mins." dk.";} }
                                        echo "<div title='Kalan Süre' class='sure1 sure2'><i class='fal fa-hourglass-half'></i> &nbsp&nbsp  $day</div>";



//                                        }
                                        ?>




                                    <div class="item-image">
                                        <a target="_blank" href="{{ route('item_ic_detay', [$item->link, $u['sunucu'], Str::slug($u['title']) . '-' . $u['id']]) }}">
                                            <figure style="border-radius: unset"><img src="{{ asset('public/front/games_items/' . $itemPhoto) }}" class="card-img-top"

                                                         <? if($u['pazar']==9){$ozellik=DB::table('pazar_yeri_ilanlar')->where('id',$itemBilgi->ilan)->whereNull('deleted_at')->first()->ozellik;
                                                            if($ozellik!=''){?>style="filter: blur(3px) drop-shadow(2px 4px 6px black);"
                                                                <?}}?>
                                                ></figure>
{{------------------------------------------------------Cypher resim üzeri özellikler----------------------------------------------}}
                                            @if($u['pazar']==9)
                                                <? if($ozellik!=''){
                                                        $kr = (object) json_decode($ozellik);?>
                                                        <div style="display: flex;justify-content: center;align-items: flex-end;">
                                                            <div style="z-index: 10;position: absolute;background-color: #000000aa;color: white;padding: 9px 14px;margin: 8px auto;border-radius: 10px;display: flex;width: 87%;flex-direction: column;align-items: center;height: 90%;/* line-height: 24px; */justify-content: center;">
                                                                <div class="detay" style="display: flex;flex-direction: column;align-items: center;line-height: 7px;">
                                                            <?
                                                                $ekle='';$km=isset($kr->k11)?'K':'M';
                                                                foreach($kr as $ad => $ic) {
                                                                    if($ad=='karakter'){$kar=$ic;}
                                                                    if($ad=='irk'){$irk=$ic;}
                                                                    if($ad=='level'){$lev=$ic;}
                                                                    if($ad=='np'){$np=$ic;}
                                                                    if($ad!='k11' && $ic=='on'){ $ekle.= $ad.' / ';}
                                                                   // if($ad=='k11'){$km=$ic;}
                                                                }
                                                                     ?>
                                                            <h6> {{@$lev}}</h6>
                                                            <p>{{@$irk}} / {{@$kar}}</p>
                                                            <p>NP : {{@$np.@$km}}</p>
                                                            <small style="font-size: 80%">{{@$ekle}}</small>

                                                        </div>
                                                            <p style="margin: 5px 0;">Yüzde</p>
                                                                <div class="progress" style="position: relative; z-index: 9;width: 100%;padding: 1px;height: 17px;display: flex;justify-content: flex-start;border-radius: 10px;">
                                                        <div class="progress-bar" role="progressbar" aria-valuenow="{{$kr->yuzde}}" aria-valuemin="0" aria-valuemax="100"
                                                             style="width:{{$kr->yuzde}}%;background-color: #00ff80;font-size: small;font-weight: bold;overflow: initial;display: flex;justify-content: space-around; color: teal"
                                                        > {{$kr->yuzde}}%</div>
                                                        </div>
                                                        </div>
                                                        </div>
                                                    <?}?>
                                            @endif
{{------------------------------------------------------Cypher resim üzeri özellikler----------------------------------------------}}

                                        </a>
                                    </div>

                                @endif
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a target="_blank"
                                        href="{{ route('item_ic_detay', [$item->link, $u['sunucu'], Str::slug($u['title']) . '-' . $u['id']]) }}">
                                        {{ substr($u['title'], 0, 50) }} @if (strlen($u['title']) > 50)
                                            ...
                                        @endif
                                    </a> @if($u['grup']!=0) - <i class="fa fa-cube" style="font-weight: unset;color: #00ff00;"></i> SET @endif
                                </h6>
                                <p class="card-text">{{ ucfirst($u['sunucu']) }}
                                    /
                                    @if (DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u['id'])->count() > 1)
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

                            <div class="card-footer priceContainer">
                                <span class="card-text price"><span class="moneysymbol">₺</span>{{ MF($u['price']) }}</span>

                                <span class="card-text price">
                                    <label class="" style=" font-size: x-small; font-weight: normal; display: flex; margin-bottom: -8px; ">Kullanıcı Aktivite</label>
                                    {!! userLastSeen($u['user']) !!}
                                </span>
                            </div>

{{--@if(@$_GET['test'])--}}
{{--<div class="card-footer" style=" display: flex; position: absolute; font-style: italic; z-index: 10; justify-content: flex-end; width: inherit;">--}}
{{--<span title="İlanın yayından kalkmasına kalan süre" class="sure1 sure">--}}
{{--     <?--}}
{{--             $s=$u['tl']<1?71:$u['tl']-1;--}}

{{--             $fdate = strtotime( $u['updated_at'].' +'.$s.' hours' );--}}
{{--             $hours   = number_format((($fdate - time()) / 3600),0);--}}
{{--             $mins    = number_format((($fdate - time()) % 3600) / 60,0);--}}
{{--             $mins=strlen($mins)==1?'0'.$mins:$mins;$hours=strlen($hours)==1?'0'.$hours:$hours;--}}
{{--             echo "<li class='fa fa-clock me-2'></li> $hours:$mins";--}}
{{--    ?>--}}
{{--</span>--}}
{{--</div>--}}
{{--@endif--}}
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endforeach





    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" data-page="page=1">{{ '|<<' }}</a>
            </li>
            <li class="page-item">
                <?php
                $prevPage = $donen->currentPage() - 1;
                if ($prevPage < '1') {
                    $prevPage = '1';
                }
                ?>
                <a class="page-link" data-page="page={{ $prevPage }}">{{ '<' }}</a>
            </li>
            @if ($donen->lastPage() + 1 > 7)
                @if ($donen->currentPage() < 3)
                    @for ($i = 1; $i < 4; $i++)
                        <li class="page-item @if ($donen->currentPage() == $i) active @endif">
                            <a class="page-link" data-page="page={{ $i }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item">
                        <a class="page-link">...</a>
                    </li>
                    @for ($i = $donen->lastPage() - 2; $i < $donen->lastPage() + 1; $i++)
                        <li class="page-item @if ($donen->currentPage() == $i) active @endif">
                            <a class="page-link" data-page="page={{ $i }}">{{ $i }}</a>
                        </li>
                    @endfor
                @endif
                @if ($donen->currentPage() >= 3 and $donen->currentPage() < $donen->lastPage() + 1 - 2)
                    <li class="page-item @if ($donen->currentPage() == 1) active @endif">
                        <a class="page-link" data-page="page=1">1</a>
                    </li>
                    @if ($donen->currentPage() - 1 != 2)
                        <li class="page-item">
                            <a class="page-link">...</a>
                        </li>
                    @endif
                    @for ($i = $donen->currentPage() - 1; $i <= $donen->currentPage() + 1; $i++)
                        <li class="page-item @if ($donen->currentPage() == $i) active @endif">
                            <a class="page-link" data-page="page={{ $i }}">{{ $i }}</a>
                        </li>
                    @endfor
                    @if ($donen->currentPage() + 1 != $donen->lastPage() - 1)
                        <li class="page-item">
                            <a class="page-link">...</a>
                        </li>
                    @endif
                    <li class="page-item @if ($donen->currentPage() == $donen->lastPage()) active @endif">
                        <a class="page-link" data-page="page={{ $donen->lastPage() }}">{{ $donen->lastPage() }}</a>
                    </li>
                @endif
                @if ($donen->currentPage() >= $donen->lastPage() + 1 - 2)
                    @for ($i = 1; $i < 3; $i++)
                        <li class="page-item @if ($donen->currentPage() == $i) active @endif">
                            <a class="page-link" data-page="page={{ $i }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item">
                        <a class="page-link">...</a>
                    </li>
                    @for ($i = $donen->lastPage() - 2; $i < $donen->lastPage() + 1; $i++)
                        <li class="page-item @if ($donen->currentPage() == $i) active @endif">
                            <a class="page-link" data-page="page={{ $i }}">{{ $i }}</a>
                        </li>
                    @endfor
                @endif
            @else
                @for ($i = 1; $i < $donen->lastPage() + 1; $i++)
                    <li class="page-item @if ($donen->currentPage() == $i) active @endif">
                        <a class="page-link" data-page="page={{ $i }}">{{ $i }}</a>
                    </li>
                @endfor
            @endif
            <li class="page-item">
                <?php
                $nextPage = $donen->currentPage() + 1;
                if ($nextPage > $donen->lastPage()) {
                    $nextPage = $donen->lastPage();
                }
                ?>
                <a class="page-link" data-page="page={{ $nextPage }}">{{ '>' }}</a>
            </li>
            <li class="page-item">
                <a class="page-link" data-page="page={{ $donen->lastPage() }}">{{ '>>|' }}</a>
            </li>
        </ul>
    </nav>
@else
    <div class="alert alert-danger fade show d-flex align-items-center" role="alert">
        <h5>Eşleşen ilan bulunamadı</h5>
    </div>
@endif
