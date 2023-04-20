<?php
use Carbon\Carbon;

?>
@if(isset($_GET['itemler-akis']))
        <?php
        $item = DB::table('games_titles')->where('link', $item)->first();
        $sor=DB::select("select value from games_titles_features where game_title=7 and title='Sunucu' and isnull(deleted_at)"); $r = json_decode($sor[0]->value);

        $ekleme=NULL;
        if(isset($_GET['s'])){
            $par=explode(',',$_GET['s']);
            foreach ($par as $p){if(in_array($p,$r)){$ekleme.="'".$p."',";}}
            $ekleme=substr($ekleme, 0, -1);
            $ek= " and sunucu in($ekleme)";
        } else {$ek='';}
#      $yeniIlanlar = DB::select("select * from pazar_yeri_ilanlar where userStatus='1' and pazar='$item->id' and status='1' $ek order by updated_at desc limit 40");
#      $yeniIlanlar = DB::table('pazar_yeri_ilanlar')->where('userStatus', '1')->where('pazar', $item->id)->where('status', '1')->where('updated_at', '>=', Carbon::now()->subSeconds(9)->toDateTimeString());
        $sn=Carbon::now()->subSeconds(9)->toDateTimeString();
        $yeniIlanlar = DB::select("select * from pazar_yeri_ilanlar where userStatus='1' and isnull(deleted_at) and pazar='$item->id' and status='1'  $ek and updated_at >='$sn'");

        ?>


    @if(count($yeniIlanlar) > 0)
        @foreach($yeniIlanlar as $u)

            <div class="colflex col-full">
                <div class="card style-item-card card-medium fade show" style="width: 100%;">
                    @if($u->type == 0)
                        @if(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->count() > 1)
                            <div id="itemSlide{{$u->id}}" class="carousel slide"
                                 data-bs-ride="carousel">
                                <div class="carousel-inner">

                                    @foreach(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->get() as $uu)
                                        <div class="carousel-item @if($loop->first) active @endif">
                                            <a href="{{route('item_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}">
                                                <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', $uu->item)->first()->image)}}" class="d-block w-100" alt="{{DB::table('games_titles_items_info')->where('id', $uu->item)->first()->title}} görseli">
                                            </a>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        @else
                            @if(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->count() > 0)
                                <a href="{{route('item_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}">
                                    <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->first()->item)->first()->image)}}" class="card-img-top" alt="...">
                                </a>
                            @endif
                        @endif
                    @else
                        <img src="{{asset('public/front/ilanlar/'.$u->image)}}" pb-40 class="card-img-top" alt="{{$u->title}} Görseli">
                    @endif
                    <div class="card-body">
                        <h6 class="card-title">
                            <a href="{{route('item_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}"> {{substr($u->title, 0, 50)}} @if(strlen($u->title) > 50)...@endif </a>
                        </h6>
                        <p class="card-text">{{ucfirst($u->sunucu)}} /
                            @if(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->count() > 1) Set @else @if($item->link == "cypher-ring") Karakter @else Item @endif @endif
                        </p>
                    </div>
                    <div class="card-footer">
                        <span class="card-text price"><span class="moneysymbol">₺</span>{{MF($u->price)}}</span>
                        <span class="card-text price text-success">{{ date_format(date_create($u->updated_at),'H:i:s') }}</span>

                    </div>
                </div>
            </div>

        @endforeach

    @else
        1
    @endif
        <? die(); ?>
@endif

@extends('front.layouts.app')
@section('body')
    <?php
    $item = DB::table('games_titles')->where('link', $item)->first();
    ?>
    <section class="item bg-gray pb-40">
        <div class="container">
            <div class="row">


                <?#-----------------------------------------------------------------------Arama ?>
                <div class="row mb-4">
                    <?
                    $sor=DB::select("select value from games_titles_features where game_title=7 and title='Sunucu' and isnull(deleted_at)");
                    $r = json_decode($sor[0]->value);
                    sort($r);
                    foreach($r as $t) {
                        if (isset($_GET['s'])) {if (strpos($_GET['s'], $t) > -1) {$cl = 'background-color:forestgreen';} else {$cl = '';}} else {$cl = '';}
                        echo " <div class='col-auto mb-2'> <button style='$cl' class='btn btn-outline-light sec'>$t</button> </div> ";
                    }
                    ?>
                </div>
                <?#-----------------------------------------------------------------------Arama SON ?>


                <div class="col-12 mb-4">
                    <div id="canli-item" class="row item-akis">
                        <?php
                        $ekleme=NULL;
                        if(isset($_GET['s'])){
                            $par=explode(',',$_GET['s']);
                            foreach ($par as $p){if(in_array($p,$r)){$ekleme.="'".$p."',";}}
                            $ekleme=substr($ekleme, 0, -1);
                            $ek= " and sunucu in($ekleme)";
                        } else {$ek='';}
                        if($item->id>0) {
                            $yeniIlanlar = DB::select("select * from pazar_yeri_ilanlar where isnull(deleted_at) and  userStatus='1' and pazar='$item->id' and status='1' $ek order by updated_at desc limit 40");
                        }
                        ?>
                        @foreach($yeniIlanlar as $u)
                            <div class="colflex col-full">
                                <div class="col_cell">
                                    <div class="card style-item-card" style="width: 100%;">
                                        @if($u->toplu == 1)
                                            @if($u->type == 0)
                                                <div class="item-image">

                                                    <figure>
                                                        <div id="itemSlide{{$u->id}}" class="carousel slide"
                                                             data-bs-ride="carousel">
                                                            <div class="carousel-inner">
                                                                @foreach(DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u->id)->get() as $t)
                                                                    <div class="carousel-item @if($loop->iteration == 1) active @endif">
                                                                        <a href="{{route('item_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}">
                                                                            <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', DB::table('pazar_yeri_ilan_icerik')->where('ilan', $t->ilan)->first()->item)->first()->image)}}"
                                                                                 class="card-img-top">
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </figure>
                                                </div>
                                            @else
                                                <div class="item-image">
                                                    <figure>
                                                        <div id="itemSlide{{$u->id}}" class="carousel slide"
                                                             data-bs-ride="carousel">
                                                            <div class="carousel-inner">
                                                                @foreach(DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u->id)->get() as $t)
                                                                    <div class="carousel-item @if($loop->iteration == 1) active @endif">
                                                                        <a href="{{route('item_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}">
                                                                            <img class="card-img-top"
                                                                                 src="{{asset('public/front/ilanlar/'.DB::table('pazar_yeri_ilanlar')->where('id', $t->ilan)->first()->image)}}">
                                                                        </a>
                                                                    </div>

                                                                @endforeach
                                                            </div>

                                                        </div>
                                                    </figure>
                                                </div>
                                            @endif
                                        @else
                                            @if($u->type == 0)
                                                @if(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->count() > 1)

                                                    <div id="itemSlide{{$u->id}}" class="carousel slide"
                                                         data-bs-ride="carousel">
                                                        <div class="carousel-inner">
                                                            @foreach(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->get() as $uu)
                                                                <div class="carousel-item @if($loop->first) active @endif">
                                                                    <div class="item-image">
                                                                        <figure>
                                                                            <a href="{{route('item_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}">
                                                                                <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', $uu->item)->first()->image)}}"
                                                                                     class="d-block w-100"
                                                                                     alt="{{DB::table('games_titles_items_info')->where('id', $uu->item)->first()->title}} görseli">
                                                                            </a>
                                                                        </figure>
                                                                    </div>

                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                @else



                                                    {{---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                                                      --}}



                                                    @if(DB::table('games_titles_items_photos')->where('item', DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->first()->item)->count() > 0)
                                                        <div class="item-image">
                                                            <a href="{{route('item_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}">
                                                                <figure>
                                                                    <img src="{{asset('public/front/games_items/'.DB::table('games_titles_items_photos')->where('item', DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->first()->item)->first()->image)}}"
                                                                         class="card-img-top" alt="...">
                                                                </figure>
                                                            </a>
                                                        </div>

                                                    @endif
                                                @endif
                                            @else
                                                <img src="{{asset('public/front/ilanlar/'.$u->image)}}" class="card-img-top" alt="{{$u->title}} Görseli">
                                            @endif
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <a href="{{route('item_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}">{{substr($u->title, 0, 50)}} @if(strlen($u->title) > 50)...@endif </a>
                                            </h6>
                                            <p class="card-text">{{ucfirst($u->sunucu)}}
                                                / <span class="card-text" style="background-color: forestgreen; color: black; border-radius: 5px; padding: 2px; font-weight: bold;">{{ date_format(date_create($u->updated_at),'H:i:s')  }} </span>
                                                @if(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->count() > 1)
                                                    Set
                                                @else
                                                    @if($item->link == "cypher-ring")
                                                        Karakter@elseItem
                                                    @endif
                                                @endif
                                            </p>

                                        </div>
                                        <div class="card-footer">
                                            <span class="card-text price"><span class="moneysymbol">₺</span>{{MF($u->price)}}</span>

                                        </div>
                                    </div>
                                </div></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        setInterval(itemGetir, 10000);

        function itemGetir() {
            var url;

            if(location.href.indexOf('=')>-1) {url=location.href+"&itemler-akis=1"} else {url="?itemler-akis=1";}
            $.ajax({
                url: url,
                success: function (result) {
                    var sonucSayi = parseInt(result);
                    if (sonucSayi != 1) {
                        $(".item-akis").prepend(result);
                        $('.item-akis').children().last().remove();
                        location.reload();
                    }
                }
            });
        }

        $('.sec').click(function (){ var par='';
            if(location.href.indexOf('=')>-1) {
                par = location.href.split('?')[1].split('=')[1].split(',');
                if (par.indexOf($(this).text()) > -1) {
                    par.splice(par.indexOf($(this).text()), 1);
                    if(par.length==0) {location.href='?'; return
                    }
                } else { par+=','+$(this).text();}
            } else {par=$(this).text()}
            if(location.href.split('=')[1]=='') {location.href='?'} else {location.href='?s='+par;}
        })

    </script>
@endsection
