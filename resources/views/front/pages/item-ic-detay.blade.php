@extends('front.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'css/icons.min.css')}}" rel="stylesheet" type="text/css"/> --}}
@endsection
<?php

$ilan = str_replace(['&', '<', '>', '%', '`', '*', "'", '"', '/', '\\', '|', '.'], '', $ilan);
$itemName = $item;
$item = str_replace(['&', '<', '>', '%', '`', '*', "'", '"', '/', '\\', '|', '.'], '', $item);

$item = DB::table('games_titles')->where('link', $item)->first();
$ilan = explode('-', $ilan);
$u = DB::table('pazar_yeri_ilanlar')->where('id', end($ilan))->first();
array_pop($ilan);
$ilanIsmi = implode('-', $ilan);
if (!$u or $ilanIsmi != Str::slug($u->title) or $u->deleted_at != null) {
    header('Location: ' . URL::to(route('item_detay', ['item' => $itemName, 'sunucu' => $sunucu])), true);
    /* header('Location: ' . URL::to(route('errors_404')), true, 302); */
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
    <section class="pb-100 bg-gray">
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
                                            <h3 class="card-title">Satıcıya Dair Bilgiler</h3>
                                            <p class="card-text">Satıcı Puanı :
                                                <strong>{{ getSaticiPuani($u->user) }}</strong>
                                            </p>
                                            <p class="card-text">Başarılı Satış :
                                                <strong>{{ DB::table('pazar_yeri_ilanlar')->where('user', $u->user)->whereNull('deleted_at')->where('userStatus', '1')->where('status', '6')->count() }}</strong>
                                            </p>
                                            <p class="card-text">Başarısız Satış :
                                                <strong>{{ DB::table('pazar_yeri_ilanlar')->where('user', $u->user)->whereNull('deleted_at')->where('userStatus', '1')->where('status', '9')->count() }}</strong>
                                            </p>
                                            <p class="card-text">Devam Eden Satış :
                                                <strong>{{ DB::table('pazar_yeri_ilanlar')->where('user', $u->user)->whereNull('deleted_at')->where('userStatus', '1')->where('status', '1')->count() }}</strong>
                                            </p>

                                        </div>
                                        <div class="card-footer flex-e">
                                            <button class="btn-inline color-darkgreen small"
                                                onclick="location.href='{{ route('satici', [$u->user]) }}'">Satıcıyı
                                                Gör
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item-detay-page">
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">

                                        <div class="card border-radius-20 item-detay-sol" style="width: 100%; ">
                                            @if ($u->toplu == 1)
                                                @if ($u->type == 0)
                                                    <div class="item-image">
                                                        <figure>
                                                            <div id="carouselExampleIndicators" class="carousel slide"
                                                                data-bs-ride="carousel">
                                                                <div class="carousel-inner">
                                                                    @foreach (DB::table('pazar_yeri_ilan_toplu')->where('toplu', $u->id)->get() as $t)
                                                                        <?php
                                                                        $ilanIcerik = DB::table('pazar_yeri_ilan_icerik')
                                                                            ->where('ilan', $t->ilan)
                                                                            ->first();
                                                                        ?>

                                                                        <div
                                                                            class="carousel-item @if ($loop->iteration == 1) active @endif">
                                                                            @if ($ilanIcerik)
                                                                                <?php
                                                                                $photo = DB::table('games_titles_items_photos')
                                                                                    ->where('item', $ilanIcerik->item)
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
                                                                    <div class="carousel-item @if ($loop->first) active @endif">
                                                                        <img src="{{ asset('public/front/games_items/' .DB::table('games_titles_items_photos')->where('item', $uu->item)->first()->image) }}" class="d-block w-100" alt="{{ DB::table('games_titles_items_info')->where('id', $uu->item)->first()->title }} görseli">
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
{{--*****************************Cypher yeni satis ekranı ********************************--}}
@if($u->pazar==9 && $u->ozellik!='')
<? $kr = (object) json_decode($u->ozellik);?>
<style>.progress-bar {background-color: #cccccc;animation: progressBar 3s ease-out;animation-fill-mode:forwards;} @keyframes progressBar { 0% { width: 0; } 100% { width: {{$kr->yuzde}}%; } }</style>
<div style="display: flex;align-items: center">
<img src="{{ asset('public/front/games_items/c_' .strtolower($kr->karakter). '.jpg') }}" class="card-img-top border-radius-20" alt="..." style= "filter: blur(3px) drop-shadow(2px 4px 6px black);" >
<div style="position: absolute;background-color: #000000aa;color: white;padding: 20px;margin: 75px 16px;border-radius: 10px;display: flex;flex-direction: column;width: 90%;align-items: center; justify-content: center;height: 50%">
    <div class="detay" style="display: flex; flex-direction: column; align-items: center"></div>
    <p style="margin-bottom: 1px">Yüzde</p>
    <div class="progress" style="z-index: 9; width: 100%; padding: 1px;">
        <div class="progress-bar" role="progressbar" aria-valuenow="{{$kr->yuzde}}" aria-valuemin="0" aria-valuemax="100" style="width: 82%;background-color: #00ff80;color: black;font-size: small;font-weight: bold;"> {{$kr->yuzde}}%</div>
    </div>
</div>
</div>
{{--*****************************Cypher yeni satis ekranı ********************************--}}
                                                        @else
                                                            <img id="pht" src="{{ asset('public/front/games_items/' .DB::table('games_titles_items_photos')->where('item',DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->first()->item)->first()->image) }}" class="card-img-top border-radius-20" alt="...">
                                                        @endif
                                                    @endif
                                                @else
                                                    <img src="{{ asset('public/front/ilanlar/' . $u->image) }}"
                                                        class="card-img-top" alt="{{ $u->title }} Görseli">
                                                @endif
                                            @endif

                                        </div>
                                        <div class="card-column flex-b mobile-frame">
                                            <div class="card-column-container flex-b">
                                            </div>
                                            <div class="card-footer flex-e">
                                                <button class="btn-inline color-blue small"
                                                    onclick="location.href='{{ route('satici', [$u->user]) }}'">
                                                    Satıcıyı
                                                    Gör
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">

                                        <div class="card border-radius-20 full-h borderoff" style="width: 100%;">
                                            <div class="card-body card-left card-flex-body">
                                                <div class="card-column">
                                                    <h3 class="card-title titleContainer"><bas class="bas ">{{ $u->title }} </bas>
                                                        @if (isset(Auth::user()->id))
                                                            @if (DB::table('favoriler')->where('user', Auth::user()->id)->where('favoriId', $u->id)->where('type', '1')->whereNull('deleted_at')->count() > 0)
                                                                <a href="{{ route('favori_kaldir', ['1', $u->id]) }}"><i class="fas fa-star text-warning ps-3"></i></a>
                                                            @else
                                                                <a  href="{{ route('favori_ekle', ['1', $u->id]) }}"><i class="far fa-star text-warning ps-3"></i></a>
                                                            @endif
                                                        @else
                                                            <a href="{{ route('favori_ekle', ['1', $u->id]) }}"><i class="far fa-star text-warning ps-3"></i></a>
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

{{--*****************************Cypher yeni satis ekranı ********************************--}}
                                                    <?
                                                    if($u->pazar==9 && isset($kr)){
                                                        $ekle='';
                                                        $km=isset($kr->k11)?'K':'M';
                                                        foreach($kr as $ad => $ic) {
                                                            if($ad=='karakter'){$kar=$ic; echo '<p class="card-text"><span style="font-weight:bold">' . ucfirst($ad) . '</span> : ' . ucfirst($ic) . '</p>';}
                                                            if($ad=='irk'){$irk=$ic;echo '<p class="card-text"><span style="font-weight:bold">' . ucfirst($ad) . '</span> : ' . ucfirst($ic) . '</p>';}
                                                            if($ad=='level'){$lev=$ic;echo '<p class="card-text"><span style="font-weight:bold">' . ucfirst($ad) . '</span> : ' . ucfirst($ic) . '</p>';}
                                                            if($ad=='np'){$np=$ic;echo '<p class="card-text"><span style="font-weight:bold">' . ucfirst($ad) . '</span> : ' . ucfirst($ic) . $km . '</p>';}
                                                            if($ad!='k11' && $ic=='on'){ $ekle.= $ad.' / ';}
                                                            //if($ad=='k11'){$km=$ic;}
                                                        }
                                                        echo '<p class="card-text"><span style="font-weight:bold">Achievement</span> : ' .$ekle. '</p>';


?>
                                                    @section('js')
                                                    <script>
                                                        $('.detay').html('<h4>{{@$lev}}</h4>' +
                                                            '<h5>{{@$irk}} / {{@$kar}}</h5>' +
                                                            '<h5>NP : {{@$np.@$km}}</h5>' +
                                                            '<p>Arch.: {{@$ekle}}</p>');
                                                    </script>
                                                    @endsection

                                                    <? }
# {{--*************************************************************--}}


                                                        $i_icerik = DB::table('pazar_yeri_ilan_icerik')
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
                                                                  if(!isset($kr)) {
                                                                      foreach ($featuresMerged as $key => $value) {
                                                                          if ($featNamesOrdered[$key] == 'Sunucu'){continue;}
                                                                          if ($featNamesOrdered[$key] == 'Set mi?' && $u->grup!=0){$value='Evet';}
                                                                          echo '
                                                                    <p class="card-text">
                                                                        <span style="font-weight:bold">' .$featNamesOrdered[$key].'</span> : ' .trim($value, ',') .'
                                                                    </p>
                                                                    ';
                                                                      }
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

                                                    ?>
                                                    @foreach ($features = DB::select("SELECT games_titles_features.title,pazar_yeri_ilan_features.value FROM `pazar_yeri_ilan_features` INNER join games_titles_features on games_titles_features.id = pazar_yeri_ilan_features.feature WHERE pazar_yeri_ilan_features.ilan ='$u->id' ") as $feature)
                                                        @if (strtolower($feature->title) != 'sunucu')
                                                            <p class="card-text">
                                                                <span style="font-weight:bold">{{ $feature->title }}</span> : {{ ucfirst(str_replace('hayir','hayır',$feature->value)) }}
                                                            </p>
                                                        @endif
                                                    @endforeach

                                                </div>
                                                <div class="card-column mt-3">
                                                    <h3 class="card-title">Ürün Açıklaması</h3>
                                                    <p class="card-text">{{ $u->text }}</p>
                                                    @if ($u->teslimat != '')
                                                        <h3 class="card-title">Teslimat Saati</h3>
                                                        <p class="card-text">{{ $u->teslimat }}</p>
                                                    @endif
                                                </div>
                                            <div class="card-footer mt-3 space-b">
                                                <span class="card-text">{{ MF($u->price) }} TL</span>
                                                @if ($u->status == 1 && isset($item->link))
                                                    <button class="btn-inline color-darkgreen"
                                                        onclick="location.href='{{ route('item_ic_detay_satin_al', [$item->link, $u->sunucu, Str::slug($u->title) . '-' . $u->id]) }}'">Satın
                                                        Al</button>
                                                @else
                                                    <button class="btn-inline color-blue">Bu ilan satılmış</button>
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
                                                    <h3 class="card-title">Satıcıya Dair Bilgiler</h3>
                                                    <p class="card-text">Başarılı Satış :
                                                        <strong>{{ DB::table('pazar_yeri_ilanlar')->where('user', $u->user)->where('status', '6')->count() }}</strong>
                                                    </p>
                                                    <p class="card-text">Başarısız Satış :
                                                        <strong>{{ DB::table('pazar_yeri_ilanlar')->where('user', $u->user)->where('status', '2')->count() }}</strong>
                                                    </p>
                                                    <p class="card-text">Devam Eden Satış :
                                                        <strong>{{ DB::table('pazar_yeri_ilanlar')->where('user', $u->user)->where('status', '1')->count() }}</strong>
                                                    </p>

                                                    <p class="card-text">Son Görülme
                                                        : {!! userLastSeen($u->user) !!}</p>
                                                </div>
                                                <div class="card-footer flex-e">
                                                    <button class="btn-inline color-blue small border"
                                                        onclick="location.href='{{ route('satici', [$u->user]) }}'">
                                                        Satıcıyı Gör
                                                    </button>

                                                </div>
                                            </div>
                                            <style>
                                                .yorum-name span {font-size: 12px !important;}
                                            </style>
                                            <div class="card-column">
                                                <h4 class="card-title">Yorumlar</h4>
                                                <div class="comment-wrapper">
                                                    @if (DB::table('ilan_yorumlar')->where('buy', '0')->whereNull('deleted_at')->where('ilan', $u->id)->where('status', '1')->count() > 0)
                                                        @foreach (DB::table('ilan_yorumlar')->where('buy', '0')->whereNull('deleted_at')->where('ilan', $u->id)->where('status', '1')->get() as $yy)
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

                                            <div class="comment-form closed" style="z-index: 2; padding-bottom: 345px">
                                                <div class="comment-form-header">
                                                    <h4 class="card-title">Yorum Yap</h4>
                                                    <span id="message-send-close"><i class="fal fa-times-circle"></i></span>
                                                </div>

                                                @if (isset(Auth::user()->id))
{{--                                                    @if( $u->user == Auth::user()->id) <h6 class="form-label">Kendi ilanınıza yorum yapamazsınız.</h6> @else--}}
                                                    <form method="post" action="{{ route('ilan_yorum_yap') }}">
                                                        @csrf
                                                        <input type="hidden" name="ilan" value="{{ $u->id }}">
                                                        <div class="row">
                                                            <div class="comment-send-wrapper">
                                                                <div class="comment-send-form">
                                                                    <div class="col-12">
                                                                        <label for="1" class="form-label">Mesajınız</label>
                                                                        <textarea class="form-control" name="text" id="1" required style="height: 100px"></textarea>
                                                                    </div>
                                                                        <div class="col-12 form-label">
                                                                            Yorumunuz onaylandığında karşı taraf sms ile bilgilendirilecektir.<br>(Sms gönderim ücreti: ücretsiz)
                                                                        </div>
                                                                    <div class="col-12 mt-2">
                                                                        <button type="submit" class="btn-inline color-blue small">Yorumu Gönder</button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </form>
{{--                                                    @endif--}}
                                                @else
                                                    <div class="comment-info">
                                                        <p><span class="w_ico"><i class="fal fa-exclamation-triangle"></i></span>
                                                            Yorum yapabilmek için lütfen giriş yapın.</p>
                                                        <button type="button" class="btn-inline color-blue small" onclick="location.href='{{ route('giris') }}'"> Giriş Yap</button>
                                                    </div>
                                                @endif
                                            </div>

                                        </div>
                                        @if ($u->status == 1 && isset($item->link))
                                        <div class="card-column send">
                                            <span id="message-send">Yorum Gönder <i class="fas fa-comment-lines"></i></span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
{{-----------------------------------------------------------------------------Set ilan içeriği-------------------------------------}}


    @if($u->grup!=0)
        <?
            $sets=str_replace('#',',',substr($u->grup, 0, -1));$dz=explode(',',$sets);$adet=count($dz); $cift=array_count_values($dz);
            $res=DB::select("SELECT gt.id, gt.title, gt.description, gp.image FROM games_titles_items_info gt LEFT JOIN games_titles_items_photos gp on gp.item=gt.id where gt.id in($sets)");
        ?>
        <div class="border-radius-20 borderoff card col-md-12 full-h mt-4" style="width: 100%;display: flex;border: solid;color: azure;padding: 2px;align-items: center;"><h5 style="padding-top: 10px">Set İçeriği  ({{$adet}})</h5>
        <div class="col-md-12 full-h" style="width: 100%;display: flex;padding-left: 2px;flex-direction: row;align-items: center;justify-content: center; flex-wrap: wrap ">
        @foreach($res as $r)
            <div class="border btn btn-outline-secondary m-2 p-2 radius20 ykp" style="display: flex; align-items: center">
            <div class="col-md-6 me-lg-2" style="float:left; background-image:url(https://oyuneks.com/public/front/games_items/{{$r->image}});width:50px; height:50px;background-repeat:no-repeat; background-position:-4px -24px; margin-top: 4px"></div>
            {{$r->title}} <br> {{$cift[$r->id]}} Adet
            </div>
        @endforeach
        </div>
        </div>
    @endif

{{-----------------------------------------------------------------------------Set ilan içeriği-------------------------------------}}

                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12">
                <div class="card grap item-detay">
                    <div class="card-body">
                        <h5 class="card-title">
                            Fiyat Değişim Grafiği
                        </h5>
                        <div class="mt-5" id="item_grafik"></div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card benzerilan item-detay">
                    <div class="card-body">
                        <h5 class="card-title">
                            Benzer İlanlar
                        </h5>
                        <div class="row">
                            @php
                                if (sizeof($i_icerik) > 0) {
                                    $itemId = $i_icerik[0]->item;
                                    $q = "select * from pazar_yeri_ilanlar where pazar_yeri_ilanlar.id in (select ilan from pazar_yeri_ilan_icerik where pazar_yeri_ilan_icerik.item in (select item from games_titles_items where feature = 6 and value in ( SELECT value FROM `games_titles_items` WHERE item = $itemId and feature = 6))) and deleted_at is null and userStatus = 1 and status = 1 and pazar = $u->pazar and sunucu = '$u->sunucu' order by RAND() limit 5";
                                    $q_fetch = DB::select($q);
                                    if (sizeof($q_fetch) < 1) {
                                        $q_fetch = DB::table('pazar_yeri_ilanlar')
                                            ->where('pazar', $u->pazar)
                                            ->whereNull('deleted_at')
                                            ->orderBy('created_at', 'desc')
                                            ->where('userStatus', '1')
                                            ->where('status', '1')
                                            ->take(5)
                                            ->get();
                                    }
                                }
                                if (!@$q_fetch) {
                                    $q_fetch = DB::table('pazar_yeri_ilanlar')
                                        ->where('pazar', $u->pazar)
                                        ->whereNull('deleted_at')
                                        ->orderBy('created_at', 'desc')
                                        ->where('userStatus', '1')
                                        ->where('status', '1')
                                        ->take(5)
                                        ->get();
                                }
                            @endphp
                            @foreach ($q_fetch as $a)
                                <div class="colflex col-full">
                                    <div class="col_cell">
                                        <div class="card style-item-card" style="width: 100%;">
                                            @if (DB::table('pazar_yeri_ilan_icerik')->where('ilan', $a->id)->count() > 1)
                                                <div id="carouselExampleControls" class="carousel slide"
                                                    data-bs-ride="carousel">
                                                    <div class="carousel-inner">
                                                        @foreach (DB::table('pazar_yeri_ilan_icerik')->where('ilan', $a->id)->get() as $uu)
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
                                            @else
                                                @if ($a->toplu == 1)
                                                    <div class="item-image">
                                                        <a target="a_blank"
                                                            href="{{ route('item_ic_detay', [$item->link, $a->sunucu, Str::slug($a->title) . '-' . $a->id]) }}">
                                                            <figure>
                                                                <div id="itemSlide{{ $a->id }}"
                                                                    class="carousel slide" data-bs-ride="carousel">
                                                                    <div class="carousel-inner">
                                                                        @foreach (DB::table('pazar_yeri_ilan_toplu')->where('toplu', $a->id)->get() as $t)
                                                                            <div
                                                                                class="carousel-item @if ($loop->iteration == 1) active @endif">
                                                                                <?php
                                                                                $ilanIcerik = DB::table('pazar_yeri_ilan_icerik')
                                                                                    ->where('ilan', $t->ilan)
                                                                                    ->first();
                                                                                ?>
                                                                                @if ($ilanIcerik)
                                                                                    <?php
                                                                                    $photo = DB::table('games_titles_items_photos')
                                                                                        ->where('item', $ilanIcerik->item)
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
                                                                        data-bs-target="#itemSlide{{ $a->id }}"
                                                                        data-bs-slide="prev">
                                                                        <span class="carousel-control-prev-icon"
                                                                            aria-hidden="true"></span>
                                                                        <span class="visually-hidden">Önceki</span>
                                                                    </button>
                                                                    <button class="carousel-control-next" type="button"
                                                                        data-bs-target="#itemSlide{{ $a->id }}"
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
                                                    if (
                                                        DB::table('pazar_yeri_ilan_icerik')
                                                            ->where('ilan', $a->id)
                                                            ->count() < 1
                                                    ) {
                                                        $itemPhoto = $a->image;
                                                        $itemPhotoNew = true;
                                                    } else {
                                                        $itemBilgi = DB::table('pazar_yeri_ilan_icerik')
                                                            ->where('ilan', $a->id)
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
                                                        $itemPhotoNew = false;
                                                    }

                                                    ?>
                                                    <div class="item-image">
                                                        <a target="a_blank"
                                                            href="{{ route('item_ic_detay', [$item->link, $a->sunucu, Str::slug($a->title) . '-' . $a->id]) }}">
                                                            <figure>
                                                                @if ($itemPhotoNew)
                                                                    <img src="{{ asset('public/front/ilanlar/' . $itemPhoto) }}"
                                                                        class="card-img-top" alt="...">
                                                                @else
                                                                    <img src="{{ asset('public/front/games_items/' . $itemPhoto) }}"
                                                                        class="card-img-top" alt="...">
                                                                @endif
                                                            </figure>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endif
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <a
                                                        href="{{ route('item_ic_detay', [$item->link, $a->sunucu, Str::slug($a->title) . '-' . $a->id]) }}">
                                                        {{ substr($a->title, 0, 50) }} @if (strlen($a->title) > 50)
                                                            ...
                                                        @endif
                                                    </a>
                                                </h6>
                                                <p class="card-text">{{ ucfirst($a->sunucu) }}
                                                    /
                                                    @if (DB::table('pazar_yeri_ilan_icerik')->where('ilan', $a->id)->count() > 1)
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
                                                <span class="card-text price"><span
                                                        class="moneysymbol">₺</span>{{ MF($a->price) }}</span>
                                                <span class="card-text price">
                                                    {!! userSeeControl($a->user) !!}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
        </div>
    </section>
@endsection
@section('js')

    @if ($u->toplu == 0 and $u->type == 0)
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {
                'packages': ['line']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Gün');
                data.addColumn('number', 'Fiyat (₺)');
                data.addRows([
                    @for ($i = 30; $i >= 0; $i--)
                        <?php
                        $date = date('Y-m-d', strtotime('-' . $i . ' days'));
                        ?>
                            ['{{ $date }}',
                                {{ findSuccessSellItem($date,DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->first()->item) }}
                            ],
                    @endfor
                ]);
                var options = {
                    theme: 'material',
                    legend: {
                        position: 'none'
                    },
                    backgroundColor: 'transparent',
                    axes: {
                        x: {
                            0: {
                                side: 'bottom'
                            }
                        }
                    }
                };
                var chart = new google.charts.Line(document.getElementById('item_grafik'));
                chart.draw(data, google.charts.Line.convertOptions(options));

            }

            $('.ykp').click(function (){
                if(/Android|iPhone/i.test(navigator.userAgent)){$('html, body').animate({scrollTop: $('html').offset().top}, 200);}
                $('#pht').attr('src',$(this).find('div').css('background-image').replace(/(url\(|\)|")/g, ''));
                $('.bas').text($(this).text());

            })
        </script>
    @endif
@endsection
