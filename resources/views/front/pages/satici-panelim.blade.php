<? error_reporting(E_PARSE);

#-------------------------------------------comment delete
if(@$_GET['ysil']!='' && is_numeric($_GET['ysil'])){
    $p = (object) $_GET;$user=Auth::user()->id; $ind='';
    #Kullanıcıya ait ilanın yorumlarını tespit edip indexle
    $sor=DB::select("SELECT iy.id FROM ilan_yorumlar iy join pazar_yeri_ilanlar pyi on pyi.id=iy.ilan where iy.ilan='$p->ysil' and pyi.user='$user'");
    if(count($sor)<1) {\Illuminate\Support\Facades\Session::put('error', 'Hata oluştu.. :(');} else {
        foreach ($sor as $y) {$ind .= $y->id . ',';}
        $ind = substr($ind, 0, -1);
        #indexlenen yorumları sil
        DB::select("delete from ilan_yorumlar where id in($ind)");

        \Illuminate\Support\Facades\Session::put('success', 'İlan yorumları temizlendi.');
    }
}


#-------------------------------------------edit
if (isset($_GET['cyphered'])){
    $p = (object) $_GET;
    #--------Gelenleri kontrol edelim
    if($p->npc!='M' && $p->npc!='K') {die('Beklenmeyen Parametre');}
    parse_str(base64_decode($p->veri), $form);
    $js = json_encode(array_diff_key($form, ['server'=>1,'fiyat'=>1,'aciklama'=>1]));
    $form=(object) $form;
    if($form->level=='' || $form->yuzde=='' || $form->karakter=='' || $form->irk=='' || $form->server=='' || $form->baslik=='' || $form->fiyat<1) {LogCall(Auth::user()->id, '1', "(CYPHER ilan girişi) Kullanıcı beklenmeyen parametreler girerek sistem kontrol mekanizmasını atlatmaya çalışıyor."); die('Beklenmeyen Parametre'); }

    DB::table('pazar_yeri_ilanlar')
        ->where('user', Auth::user()->id)
        ->where('id', $p->cyphered)
        ->where('money_status', 0)
        ->update([
                'price'=>$form->fiyat,
                'moment_komisyon'=>$form->fiyat-$form->fiyat*1/100,
                'title'=>$form->baslik,
                'ozellik'=>$js,
                'text'=>$form->aciklama,
                'sunucu'=>$form->server,
                'type'=>0,
                'status'=>0,
                'tl'=>$form->sure,
                'toplu'=>0,
                'userStatus'=>1,
                'updated_at' => date('YmdHis')
        ]);
    LogCall(Auth::user()->id, '1', "Kullanıcı ".$p->cyphered. " ID li CYPHER ilanını düzenledi.");
echo "200";
die();
}

#---------------------------------ilan tarihi update ilan aktif

if(@$_GET['update']>1 && is_numeric($_GET['update'])) {
    $tt=DB::table('pazar_yeri_ilanlar')->where('user', Auth::user()->id)->where('id', $_GET['update'])->whereNull('deleted_at')->where('status',1)->update(['updated_at'=>date('YmdHis'),'userStatus'=>1, 'red_neden'=>'']);
    if($tt>0){LogCall(Auth::user()->id, '1', $_GET['update'] ." ID li ilan tarihini güncelledi ve yayına aldı.");}
}


#---------------------------------duzen response
if (@$_GET['cypedit'] && is_numeric($_GET['cypedit'])){
    $p = (object) $_GET;
$veriler=  DB::table('pazar_yeri_ilanlar')->where('user', Auth::user()->id)->where('id',$p->cypedit)->whereNull('deleted_at')->where('status', '!=', '6')->first();
echo "$veriler->ozellik^$veriler->title^$veriler->sunucu^$veriler->price^$veriler->text";
die();
}
?>




@if (isset($_GET['sil']))
    <?php

    if (DB::table('pazar_yeri_ilan_satis')->where('ilan', $_GET['sil'])->count() > 0) {
        // silinmek istenen ilan islem gordu ise
        LogCall(Auth::user()->id, '1', $_GET['sil'] ." ID li satıştaki ilanını silmek istedi. Sistem izin vermedi.");
        header('Location: ?err=İşlem Gören İlanı Silemezsiniz..');
        die();
    }
    $silinen_adet = DB::table('pazar_yeri_ilanlar')->where('user', Auth::user()->id)->where('id', $_GET['sil'])->update(['deleted_at' => date('YmdHis')]);

    if ($silinen_adet > 0) {
        if (DB::table('pazar_yeri_ilan_toplu')->where('ilan', $_GET['sil'])->count() > 0) {
            //eğer silinen ilan bir toplu ilan içindeyse
            $topluIlanId = DB::table('pazar_yeri_ilan_toplu')->where('ilan', $_GET['sil'])->get();
            foreach ($topluIlanId as $toplu) {
                DB::table('pazar_yeri_ilanlar')->where('id', $toplu->toplu)->update(['deleted_at' => date('YmdHis')]);
            }
            $link = route('satici_panelim');
            setBildirim(Auth::user()->id, '2', 'Toplu ilanınız bozuldu', 'Toplu ilanınız bir içeriğini sildiğiniz için bozulmuştur.', $link);
            LogCall(Auth::user()->id, '1', $toplu->toplu. "ID li toplu ilanını bozdu. İçerik silindi.");
        }
    } else {
        header('Location: ?err=Olmadı.. :) ');
        die();
    }
    header('Location: ?okey');
    exit();
    ?>
@endif


<?
if (@$_GET['pasif']) {
    if (DB::table('pazar_yeri_ilan_satis')->where('ilan', $_GET['pasif'])->whereNull('deleted_at')->count() > 0) {
        // silinmek istenen ilan islem gordu ise
        LogCall(Auth::user()->id, '1', $_GET['pasif'] . " ID li satıştaki ilanını yayından kaldırmak istedi. Sistem izin vermedi.");
        header('Location: ?err=İşlem Gören İlan Pasif Edilemez..');
        die();
    }
    $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $_GET['pasif'])->first();
    if ($ilan->userStatus == 1) {
        $status = 0;
        LogCall(Auth::user()->id, '1', $_GET['pasif'] . " ID li aktif olan ilanını yayından kaldırdı.");
    } else {
        $status = 1;
        LogCall(Auth::user()->id, '1', $_GET['pasif'] . " ID li pasif olan ilanını yayına aldı.");
    }
    DB::table('pazar_yeri_ilanlar')->where('user', Auth::user()->id)->where('id', $_GET['pasif'])->update(['userStatus' => $status,'updated_at' => date('YmdHis'),
            'red_neden' => ''  # kull. tarafından tekrar yayına alınırken burayı temizliyoruz yayın süresi bitiminde oto 7 oluyor
        ]);
    header('Location: ?okey');
    exit();
}
    ?>

@extends('front.layouts.app')
@section('css')
    <link href="/back/assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('body')
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">
                @include('front.modules.user-menu')
                <div class="col-md-9">
                    @if (isset($_GET['date1']) and isset($_GET['date2']))
                        <?php
                        $date1 = $_GET['date1'];
                        $date2 = $_GET['date2'];
                        ?>
                    @else
                        <?php
                        $date1 = '';
                        $date2 = '';
                        ?>
                    @endif

                    <form class="page-in-form" method="get">
                        <div class="row">

                            <div class="col-sm-12 col-md-5 mb-4">

                                <label class="col-sm-3 col-form-label" for="userinput1">İlk Tarih</label>
                                <div class="col-sm-9">
                                    <input type="date" id="userinput1" class="form-control style-input" name="date1"
                                        value="{{ $date1 }}" required>
                                </div>

                            </div>


                            <div class="col-sm-12 col-md-5 mb-4">

                                <label class="col-sm-3 col-form-label" for="userinput2">Son Tarih</label>
                                <div class="col-sm-9">
                                    <input type="date" id="userinput2" class="form-control style-input" name="date2"
                                        value="{{ $date2 }}" required>
                                </div>

                            </div>

                            <div
                                class="col-sm-12 col-md-2 mb-4 d-flex justify-content-sm-start justify-content-md-end align-items-end">

                                <button type="submit" class="btn-inline color-blue">Sorgula
                                </button>

                            </div>

                        </div>
                    </form>

                    @if (session('success'))
                        <!--Mesaj bildirimi--->
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fas fa-check me-2"></i>
                            <div>{{ session('success') }}</div>
                                <? Session::forget('success') ; ?>
                        </div>
                        <!--Mesaj bildirim END--->
                    @endif
                    @if (session('error') || $_GET['err'])
                        <!--Mesaj bildirimi--->
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="fas fa-check me-2"></i>
                            <div>{{ session('error') }} {{ $_GET['err'] }}</div>
                            <? Session::forget('error') ; ?>
                        </div>
                        <!--Mesaj bildirim END--->
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link  @if (!session('panel') or session('panel') and session('panel') != 3) active @endif"
                                        id="pills-satista-tab" data-bs-toggle="pill" data-bs-target="#pills-satista"
                                        type="button" role="tab" aria-controls="pills-satista"
                                        @if (!session('panel') or session('panel') and session('panel') != 3) aria-selected="true"
                                            @else
                                            aria-selected="false" @endif>Satışta
                                        Olanlar
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-sattiklarim-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-sattiklarim" type="button" role="tab"
                                        aria-controls="pills-sattiklarim" aria-selected="false">Sattıklarım
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" type="button"
                                        onclick="location.href='{{ route('yeni_satis') }}'">
                                        Yeni Satış
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="tab-content" id="pills-tabContent">

                                <div class="tab-pane fade @if (!session('panel') or session('panel') and session('panel') != 3) show active @endif"
                                    id="pills-satista" role="tabpanel" aria-labelledby="pills-genel-tab">
                                    <div class="row">
                                        <div class="col-12 d-flex align-items-center justify-content-end">
                                            <div class="modal fade" id="ilanBirlestir" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                İlan Birleştir</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post"
                                                                action="{{ route('satici_panelim_birlestir_post') }}"
                                                                autocomplete="off">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <label class="form-label">Toplu Fiyat</label>
                                                                        <input class="form-control" name="toplu_fiyat"
                                                                            type="number" required>
                                                                    </div>
                                                                    <div class="col-12 mt-3">
                                                                        <label class="form-label">Toplu Açıklama</label>
                                                                        <textarea class="form-control" name="toplu_aciklama" required></textarea>
                                                                    </div>
                                                                    <div class="col-12 mt-5">
                                                                        <label class="form-label">Seçilen
                                                                            İlanlar</label>
                                                                        <div class="ilan-alani">
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="ilanlar_id[]">
                                                                    <div class="col-12 mt-5 text-end">
                                                                        <button type="submit"
                                                                            class="btn-inline color-blue">Birleştir
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table lang="{{ getLang() }}" id="datatable"
                                                class="table table-bordered nowrap table-list">
                                                <thead>
                                                    <tr>
                                                        <th title="İlan tarihini güncellemek ve Aktif etmek için simgeye tıklayınız" style="cursor: help">?</th>
                                                        <th>İ.No</th>
                                                        <th>Pazar</th>
                                                        <th>Başlık</th>
                                                        <th>Sunucu</th>
                                                        <th>Fiyat</th>
{{--                                                        <th>Eklenme T.</th>--}}
                                                        <th>Son Etkileşim</th>
                                                        <th>Durum</th>
                                                        <th>Aksiyon</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (isset($_GET['date1']) and isset($_GET['date2'])) {
                                                        $sorgu = DB::table('pazar_yeri_ilanlar')
                                                            ->whereDate('created_at', '>=', $date1)
                                                            ->whereDate('created_at', '<=', $date2)
                                                            ->where('user', Auth::user()->id)
                                                            ->where('status', '!=', '6')
                                                            ->whereNull('deleted_at')
                                                            ->get();
                                                    } else {
                                                        $sorgu = DB::table('pazar_yeri_ilanlar')
                                                            ->where('user', Auth::user()->id)
                                                            ->where('status', '!=', '6')
                                                            ->whereNull('deleted_at')
                                                            ->get();
                                                    }
                                                    ?>
                                                    @foreach ($sorgu as $u)
                                                        <tr>
                                                            <td>
                                                                @if ($u->toplu != 1)
{{--                                                                    <input onchange="hepsiniSec(this)" type="checkbox" value="{{ $u->id }}" name="ilanlar[]" autocomplete="off">--}}

                                                                    @if($u->status == 1 && $u->userStatus==1)
                                                                        <li class="fa fa-spin fa-spinner" style="cursor: pointer" onclick="location.href='?update={{$u->id}}'" title="İlan Tarihini Güncelle ve Aktif et"></li>
                                                                    @else
                                                                        <li class="fa fa-spin fa-spinner" style="cursor: pointer" onclick="
                                                                        swal.fire({icon:'question',html:'Bu ilan pasif, devam ederseniz ilan tarihi güncellenerek aktif edilecek.',showCancelButton: true, confirmButtonText: 'Devam Et', cancelButtonText: 'Vazgeç', reverseButtons: true
                                                                        }).then((result) => {if (result.value) {location.href='?update={{$u->id}}';} else if (result.dismiss === Swal.DismissReason.cancel) {swal.fire({icon:'error',html:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});}})
                                                                        " title="İlan Tarihini Güncelle ve Aktif et"></li>
                                                                    @endif

                                                                @else
                                                                    Toplu İlan
                                                                @endif
                                                            </td>
                                                            <td>{{ $u->id }}</td>
                                                            <td>{{ $u->pazar == 7? 'Knight Online Item': ($u->pazar == 9? 'Knight Online Cypher Ring': DB::table('games_titles')->where('id', $u->pazar)->first()->title) }}
                                                                @if($u->grup!=0 && !is_null($u->grup) ) (SET) @endif
                                                            </td>
                                                            <td style="max-width: 180px;overflow:hidden">
                                                                <?php
                                                                $item = DB::table('games_titles')
                                                                    ->where('id', $u->pazar)
                                                                    ->first();
                                                                if ($u->sunucu == null) {
                                                                    $u->sunucu = 'a';
                                                                }
                                                                ?>
                                                                <a href="{{ route('item_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title) . '-' . $u->id]) }}"
                                                                    target="_blank">
                                                                    {{ mb_substr($u->title, 0, 36) }}
                                                                    @if (strlen($u->title) > 36)
                                                                        ...
                                                                    @endif
                                                                </a>
                                                            </td>
                                                            <td>{{ ucfirst($u->sunucu) }}</td>
                                                            <td>₺{{ $u->price }}</td>
{{--                                                            <td>{{ $u->created_at }}</td>--}}
                                                            <td>{{ $u->updated_at }}</td>
                                                            <td @if($u->status==2) style="cursor: help" title="{{$u->red_neden}}" @endif>

                                                                {{ $u->status != 1 ? findIlanStatus($u->status) : ($u->userStatus == 1 ? 'Yayında' : 'Pasif') }}

                                                            </td>
                                                            <td>
                                                                @if ($u->status < 3)
                                                                    <button data-bs-toggle="modal" title="Yorumları Gör"
                                                                        data-bs-target="#yorumlar{{ $u->id }}"
                                                                        type="button"
                                                                        class="btn btn-sm btn-outline-info waves-effect waves-light">
                                                                        <i class="far fa-comments"></i>
                                                                    </button>
                                                                @if($u->pazar==9 && $u->ozellik!='')
                                                                    <button <? if($u->status == 1 && $u->userStatus==1) {$ek='cypdz';} else {echo "disabled";$ek='';} ?>
                                                                            title="İlanı Düzenle" type="button" id="duz_{{$u->id}}"  class="btn btn-sm btn-outline-primary waves-effect waves-light {{$ek}}">
                                                                        <i class="far fa-edit"></i>
                                                                    </button>
                                                                @else
                                                                    <button
                                                                            @if($u->status == 1 && $u->userStatus==1) onclick="selectCalistir({{ $u->id }})" @else disabled @endif
                                                                            data-bs-toggle="modal" title="İlanı Düzenle" data-bs-target="#duzenle{{ $u->id }}"  type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light"><i class="far fa-edit"></i></button>
                                                                @endif

                                                                    @if ($u->userStatus == 1)
                                                                        <button type="button" title="Pasif Hale Getir"
                                                                            class="btn btn-sm btn-outline-success waves-effect waves-light confirm-btn"
                                                                            onclick="location.href='?pasif={{ $u->id }}'">
                                                                            <i class="far fa-eye"></i>
                                                                        </button>
                                                                    @else
                                                                        <button type="button" title="Aktif Hale Getir"
                                                                            class="btn btn-sm btn-outline-warning waves-effect waves-light confirm-btn"
                                                                            onclick="location.href='?pasif={{ $u->id }}'">
                                                                            <i class="far fa-eye"></i>
                                                                        </button>
                                                                    @endif
                                                                    @if ($u->status < 3)
                                                                        <button confirm-data='?sil={{ $u->id }}'
                                                                            type="button" title="İlanı Sil"
                                                                            class="btn btn-sm btn-outline-danger waves-effect waves-light confirm-btn">
                                                                            <i class="far fa-trash-alt"></i>
                                                                        </button>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <div class="modal fade" id="yorumlar{{ $u->id }}"
                                                            tabindex="-1" aria-labelledby="exampleModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Yorumlar</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @if (DB::table('ilan_yorumlar')->where('buy', '0')->whereNull('deleted_at')->where('ilan', $u->id)->where('status', '1')->count() > 0)
                                                                            @foreach (DB::table('ilan_yorumlar')->where('buy', '0')->whereNull('deleted_at')->where('ilan', $u->id)->where('status', '1')->get() as $yy)
                                                                                <div
                                                                                    class="comment-container @if ($yy->user == $u->user) satici @endif">
                                                                                    <div class="this-comment">
                                                                                        <div class="commenter">
                                                                                            <h6>
                                                                                                **** {!! userLastSeen($yy->user) !!}
                                                                                            </h6>
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
                                                                            <div class="comments-container">
                                                                                <p>
                                                                                    <span class="w_ico">
                                                                                        <i class="fal fa-comment-alt"></i>
                                                                                    </span>
                                                                                    Henüz bu ilana bir yorum
                                                                                    gelmemiş.
                                                                                </p>

                                                                            </div>
                                                                        @endif
                                                                        <form method="post"
                                                                            action="{{ route('ilan_yorum_yap') }}"
                                                                            autocomplete="off">
                                                                            @csrf
                                                                            <input type="hidden" name="ilan"
                                                                                value="{{ $u->id }}">
                                                                            <div class="row">
                                                                                <div class="comment-send-wrapper">
                                                                                    <div class="comment-send-form">
                                                                                        <div class="col-12">
                                                                                            <label for="1"
                                                                                                class="form-label">Mesajınız</label>
                                                                                            <textarea class="form-control" name="text" id="1" style="height: 120px" required></textarea>
                                                                                        </div>
                                                                                        <style>
                                                                                            .yorum-info {
                                                                                                color: black;
                                                                                            }

                                                                                            body.dark .yorum-info {
                                                                                                color: white;
                                                                                            }
                                                                                        </style>
                                                                                        <div class="col-12 yorum-info">
                                                                                            Yorumunuz onaylandığında karşı
                                                                                            taraf sms ile
                                                                                            bilgilendirilecektir.<br>(Sms
                                                                                            gönderim ücreti: ücretsiz)
                                                                                        </div>
                                                                                        <div class="col-12 mt-2" style="display: flex; justify-content: space-between;">
                                                                                            <button type="submit" class="btn-inline color-blue">Yorumu Gönder</button>
                                                                                            <a confirm-data="?ysil={{$u->id}}" class="btn-inline color-red text-white ysil confirm-btn" >Yorumları Sil</a>
                                                                                        </div>
                                                                                        <br><br><br>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal fade" id="duzenle{{ $u->id }}"
                                                            tabindex="-1" aria-labelledby="exampleModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            {{ $u->title }} İlanını Düzenle.</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body" style="display: flex; flex-direction: column; align-items: center;">
                                                                        <form method="post" action="{{ route('satis_duzenle_post') }}" enctype="multipart/form-data" autocomplete="off">
                                                                            @csrf
                                                                            <input type="hidden" name="ilan" value="{{ $u->id }}"> @if ($u->toplu == 1)
                                                                                <div class="row">
                                                                                    <div class="col-6">
                                                                                        <label class="form-label" for="fiyat{{ $u->id }}">Toplu Fiyat</label>
                                                                                        <input class="form-control" id="fiyat{{ $u->id }}" name="price" value="{{ $u->price }}">
                                                                                    </div>
                                                                                    <div class="col-6">
                                                                                        <label class="form-label" for="aciklama">Toplu Açıklama</label>
                                                                                        <textarea class="form-control" id="aciklama" name="text" rows="3">{{ $u->text }}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                @if (DB::table('games_titles_special')->where('games_titles', $u->pazar)->count() > 0)
                                                                                    <div class="row">
                                                                                        <div class="col-6">
                                                                                            <label class="form-label" for="title{{ $u->id }}">Başlık</label>
                                                                                            <input class="form-control" id="title{{ $u->id }}" name="title" type="text" value="{{ $u->title }}" required>
                                                                                        </div>
                                                                                        <div class="col-6">
                                                                                            <label class="form-label" for="price{{ $u->id }}">Fiyat.</label>
                                                                                            <input class="form-control" id="price{{ $u->id }}" name="price" type="number" value="{{ $u->price }}" required>
                                                                                        </div>
                                                                                        @foreach (DB::table('games_titles_features')->where('game_title', $u->pazar)->whereNull('deleted_at')->get() as $p)
                                                                                            <?php $value = DB::table('pazar_yeri_ilan_features')->where('ilan', $u->id)->where('feature', $p->id)->first(); ?>
                                                                                            <div class="col-6 mt-3">
                                                                                                <label class="form-label"
                                                                                                    for="{{ Str::slug($p->title) }}{{ $u->id }}">{{ $p->title }}</label>
                                                                                                <br>
                                                                                                @if ($p->type == 1)
                                                                                                    <select
                                                                                                        id="{{ Str::slug($p->title) }}{{ $u->id }}"
                                                                                                        class="form-control select2 w-100"
                                                                                                        name="{{ Str::slug($p->title) }}"
                                                                                                        required>
                                                                                                        <option
                                                                                                            value="0"
                                                                                                            selected>
                                                                                                            Belirtilmemiş
                                                                                                        </option>
                                                                                                        @foreach (json_decode($p->value) as $deger)
                                                                                                            @if (isset($value->value))
                                                                                                                <option
                                                                                                                    value="{{ Str::slug($deger) }}"
                                                                                                                    @if (Str::slug($deger) == $value->value) selected @endif>
                                                                                                                    {{ $deger }}
                                                                                                                </option>
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                @endif
                                                                                                @if ($p->type == 2)
                                                                                                    <select
                                                                                                        id="{{ Str::slug($p->title) }}{{ $u->id }}"
                                                                                                        class="form-control select2 w-100"
                                                                                                        name="{{ Str::slug($p->title) }}"
                                                                                                        multiple required>
                                                                                                        <option
                                                                                                            value="0"
                                                                                                            selected>
                                                                                                            Belirtilmemiş
                                                                                                        </option>
                                                                                                        @foreach (json_decode($p->value) as $deger)
                                                                                                            <option
                                                                                                                value="{{ Str::slug($deger) }}"
                                                                                                                @if (Str::slug($deger) == $value->value) selected @endif>
                                                                                                                {{ $deger }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                @endif
                                                                                            </div>
                                                                                        @endforeach
                                                                                        <div class="col-12 mt-3">
                                                                                            <div class="row">
                                                                                                <div class="col-6">
                                                                                                    <label
                                                                                                        class="form-label">Resim</label><br>
                                                                                                    <img class="w-100"
                                                                                                        src="{{ asset('public/front/ilanlar/' . $u->image) }}">
                                                                                                </div>
                                                                                                <div class="col-6">
                                                                                                    <label class="form-label" for="image{{ $u->id }}">Yeni Resim Seç</label>
                                                                                                    <input type="file" class="form-control" id="image{{ $u->id }}" name="image" accept="image/*">

                                                                                                    <div class="col-4 mt-3"><label class="form-label">Yayın Süresi</label><br>
                                                                                                        <div class="col-md-12" style="display: flex; justify-content: flex-start; align-items: center">
                                                                                                            <select name="sure" id="sure" class="form-control" style="width: 80px">
                                                                                                                @for($f=72;$f>0;$f--)
                                                                                                                    <option <?if($u->tl==$f){echo 'selected';}?> value="{{$f}}">{{$f}} Saat</option>
                                                                                                                @endfor
                                                                                                            </select>

                                                                                                        </div>
                                                                                                    </div>

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <?if($u->grup!=0) {
                                                                                            $sets = str_replace('#', ',', substr($u->grup, 0, -1));
                                                                                            $res = DB::select("SELECT gt.title, gt.description, gp.image FROM games_titles_items_info gt LEFT JOIN games_titles_items_photos gp on gp.item=gt.id where gt.id in($sets)");
                                                                                        ?>
                                                                                        <div class="col-12 mt-3">
                                                                                            @foreach($res as $r)
                                                                                                <div class="border btn btn-outline-secondary m-2 p-2 radius20 ykp" style="display: flex; align-items: center">
                                                                                                    {{$r->title}}
                                                                                                </div>
                                                                                            @endforeach
                                                                                        </div>
                                                                                        <?}?>



                                                                                        <div class="col-12 mt-3">
                                                                                            <label
                                                                                                for="text{{ $u->id }}"
                                                                                                class="form-label">Açıklama</label>
                                                                                            <textarea class="form-control" name="text" id="text{{ $u->id }}">{{ $u->text }}</textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="row">
                                                                                        <div >
                                                                                            <label class="form-label" for="fiyat{{ $u->id }}">Fiyat</label>
                                                                                            <input class="form-control girilenFiyat" id="fiyat{{ $u->id }}" name="price" value="{{ $u->price }}">
                                                                                        </div>
                                                                                        <div >
                                                                                            <label for="5" class="form-label">Hesaba Geçecek Tutar</label><br>
                                                                                            <div class="input-group mb-3">
                                                                                                <span class="input-group-text style-input" id="basic-addon1">{{ findUserKomisyon(Auth::user()->id) }}%komisyon</span>
                                                                                                <input id="5" type="number" name="kazanc" step="0.01" class="form-control style-input-pd guncelFiyat" value="{{ $u->moment_komisyon }}" readonly>
                                                                                                <span class="input-group-text style-input">₺</span>
                                                                                            </div>

                                                                                        </div>
                                                                                        <div ><label for="teslimat{{ $u->id }}" class="form-label">Teslimat Zamanı</label><br><input class="form-control " id="teslimat{{ $u->id }}" name="teslimat" value="{{ $u->teslimat }}" readonly></div>
                                                                                        <div ><label class="form-label">Yayın Süresi</label><br>
                                                                                            <div class="col-md-12" style="display: flex; justify-content: flex-start; align-items: center">
                                                                                                <select name="sure" id="sure" class="form-control" style="width: 80px">
                                                                                                    @for($f=72;$f>0;$f--)
                                                                                                        <option <?if($u->tl==$f){echo 'selected';}?> value="{{$f}}">{{$f}} Saat</option>
                                                                                                    @endfor
                                                                                                </select>

                                                                                            </div>
                                                                                        </div>

            <?if($u->grup!=0 && !is_null($u->grup)) {  #--------- Burası SET olarak açılan ilanların düzenlemesi için
            $sets = str_replace('#', ',', substr($u->grup, 0, -1));  #---- SET içeriği al SQL e hazur hale getir
            $ilk=DB::table('games_titles_items_info')->where('title',$u->title)->first()->id; #---Ana ilan silinemez onu bul ilan başlığı vs. ona göre
            $res = DB::select("SELECT gt.id, gt.title, gt.description, gp.image FROM games_titles_items_info gt LEFT JOIN games_titles_items_photos gp on gp.item=gt.id where gt.id in($sets)"); #--- isimleri al gel
            #------------ Aşağıda sadece SET içeriği listeleme ve silme muhabbeti dönüyor silinenler hidden içine atılıp  form ile post edilir
            ?>
        <div class="col-12 mt-3" style="display: flex; flex-wrap: wrap">
            @foreach($res as $r)
                <div class="border btn btn-outline-secondary m-2 p-2 radius20 ykp_{{$u->id}}" iid="{{$r->id}}"
                     @if($ilk!=$r->id) onclick="if($('.ykp_'+{{$u->id}}).length>2) {if(confirm('Set içinden çıkartılacak, emin misiniz ?')){this.remove();ekle({{$u->id}});}}else{alert('SET ilan için en az 2 item olmalı.');}" title="İtemi kaldır" @else title="SET İlan başlığı kaldırılamaz" @endif style="display: flex; align-items: center">
                     @if($ilk!=$r->id) <span class="me-2 text-danger">X</span>  @endif
                     {{$r->title}}
                </div>
            @endforeach
        </div>
        <input type="hidden" name="grup" id="sgrup_{{$u->id}}" value="0">
        <?}?>


                                                                                        <div class="col-12">
                                                                                            <label class="form-label"
                                                                                                for="aciklama">Açıklama</label>
                                                                                            <textarea class="form-control" id="aciklama" name="text" rows="3" style="height:100px">{{ $u->text }}</textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endif

                                                                            <div class="text-end" style="margin-bottom: 50px">
                                                                                <button type="submit"
                                                                                    class="btn-inline color-darkgreen mt-5">
                                                                                    Değişiklikleri Kaydet
                                                                                </button>
                                                                            </div>

                                                                        </form>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
{{--                                                        <th>Eklenme T.</th>--}}
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="control_popup hide">
                                            <article>
                                                <div class="pp_header">
                                                    <h4>Dikkat!</h4>
                                                </div>
                                                <div class="pp_center">
                                                    <h6>Silmek istediğinize emin misiniz?</h6>
                                                </div>
                                                <div class="pp_buttons">
                                                    <a class="btn-inline color-red small del">Sil</a>
                                                    <a class="btn-inline color-blue small cancel">Vazgeç</a>
                                                </div>
                                            </article>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-sattiklarim" role="tabpanel"
                                    aria-labelledby="pills-sattiklarim-tab">

                                    <div class="row g-3">


                                        <div class="table-responsive">
                                            <table lang="{{ getLang() }}" id="datatable2"
                                                class="table table-bordered nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Başlık</th>
                                                        <th>Eklenme Tarihi</th>
                                                        <th>Fiyat</th>
                                                        <th>Durum</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach (DB::table('pazar_yeri_ilanlar')->where('user', Auth::user()->id)->where('status', 6)->whereNull('deleted_at')->get() as $u)
                                                        <tr>
                                                            <td>{{ $u->title }}</td>
                                                            <td>{{ $u->created_at }}</td>
                                                            <td>₺{{ $u->price }}</td>
                                                            <td>
                                                                {{ findIlanStatus($u->status) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>Başlık</th>
                                                        <th>Eklenme Tarihi</th>
                                                        <th>Fiyat</th>
                                                        <th>Durum</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--                              ----------------------------------------------------------------------------------         --}}
        <div class="modal modal-xl-centered fade" id="duzen" tabindex="-1"  aria-hidden="true" >
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">İlan Düzenle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body">



                        <style>.xd {padding-top: 20px;} .swal2-container {z-index: 999991 ;}</style>
                        <div class="container ">
                            <form id="cypher">

                                <div class="row xd">
                                    <div class="col-xxl-2 d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                                        <label style="font-size: large ;color: aquamarine;" >İlan Başlığı *</label>
                                    </div>
                                    <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                                        <input type="text" maxlength="200" class="form-control" name="baslik" id="baslik" placeholder="İlanınız için başlık girin">
                                    </div>
                                </div>

                                <div class="row xd">
                                    <div class="col-xxl-2 d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                                        <label style="font-size: large ;color: aquamarine;" >Server *</label>
                                    </div>
                                    <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                                        <select class="form-control form-select" required name="server" id="server">
                                            <option value="">Server Seçin</option>
                                            <?              $sor=DB::select("select value from games_titles_features where game_title=9 and title='Sunucu' and isnull(deleted_at)");
                                            $r = json_decode($sor[0]->value);
                                            sort($r);
                                            foreach($r as $t) {
                                                echo "<option value='$t'>$t</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row xd">
                                    <div class="col-xxl-2 d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                                        <label style="font-size: large ;color: aquamarine;">Karakter *</label>
                                    </div>
                                    <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center" style="text-align: center;">
                                        <select class="form-control form-select" name="karakter" id="karakter" required>
                                            <option value="">Karakter ?</option>
                                            <?              $sor=DB::select("select value from games_titles_features where game_title=9 and title='Karakter Türü' and isnull(deleted_at)");
                                            $r = json_decode($sor[0]->value);
                                            sort($r);
                                            foreach($r as $t) {
                                                echo "<option value='$t'>$t</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center" style="text-align: center;">
                                        <select class="form-control form-select" name="irk" id="irk" required>
                                            <option value=""> Irk ?</option>
                                            <?              $sor=DB::select("select value from games_titles_features where game_title=9 and title='Irk Türü' and isnull(deleted_at)");
                                            $r = json_decode($sor[0]->value);
                                            foreach($r as $t) {
                                                echo "<option value='$t'>$t</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center" style="text-align: center;">
                                        <select class="form-control form-select" required name="level" id="level">
                                            <option value="">Level ?</option>
                                            <?              $sor=DB::select("select value from games_titles_features where game_title=9 and title='Level' and isnull(deleted_at)");
                                            $r = json_decode($sor[0]->value);
                                            foreach($r as $t) {
                                                echo "<option value='$t'>$t</option>";
                                            }
                                            ?>

                                        </select>
                                    </div>

                                    <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center" style="text-align: center;">
                                        <select class="form-control form-select" name="yuzde" id="yuzde" required>
                                            <option value="">Yüzde ? </option>
                                            <? for($t=99;$t>-1;$t--){echo "<option value='$t'>%$t</option>";} ?>
                                        </select>
                                    </div>

                                </div>
                                <div class="row xd">
                                    <div class="col-xxl-2 d-flex d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                                        <label style="font-size: large;color: aquamarine;">NP *</label>
                                    </div>
                                    <div class="col-auto d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                                        <input class="form-control np" required type="number" min="0" pattern="\d*" maxlength="6" placeholder="NP Değeri" id="np" name="np">
                                    </div>
                                    <div class="col-auto align-items-xxl-center" style="align-self: center">
                                        <div class="form-the-switch">
                                            <label>
                                                <input class="change-them" type="checkbox" id="k11" name="k11" style="width: 20px;height: 20px" >
                                                <span><i class="">M</i><i class="">K</i></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto" style="padding-top: 15px">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text style-input" id="basic-addon1"> NP :</span>
                                            <input type="text" step="0.01" id="NP" class="form-control style-input-pd" style="text-align: center" readonly="">
                                            <span class="input-group-text style-input" id="mk">K</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="row xd">
                                    <div class="col-xxl-2 d-xxl-flex justify-content-xxl-center align-items-xxl-center"><label style="font-size: large;color: aquamarine;">Fiyat *</label></div>
                                    <div class="col-auto" style="align-self: center; padding-top: 15px"><input required min="0" class="form-control rkm" type="number" maxlength="6" placeholder="Fiyatı Girin" id="fiyat" name="fiyat"></div>
                                    <div class="col-auto" style="padding-top: 10px">
                                        <label for="5" class="form-label">Hesabınıza Geçecek Tutar :</label><br>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text style-input" id="basic-addon1"> 1   % komisyon</span>
                                            <input type="number" step="0.01" class="form-control style-input-pd net" style="text-align: center" readonly="">
                                            <span class="input-group-text style-input">₺</span>
                                        </div>
                                    </div>

                                    <div class="col-auto" style="display: flex; flex-direction: row-reverse; justify-content: flex-start; align-items: center; padding-top: 15px">
                                        <select name="sure" id="sure" class="form-control" style="width: 80px">
                                            @for($f=72;$f>0;$f--)
                                                <option value="{{$f}}">{{$f}} Saat</option>
                                            @endfor
                                        </select>
                                        <label class="form-label me-2">Yayın Süresi</label><br>
                                    </div>
                                </div>

                                <div class="row xd">
                                    <div class="col-xxl-2 d-flex d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                                        <label style="font-size: large;color: aquamarine;">Achievement</label>
                                    </div>
                                    <div class="col d-inline-flex flex-wrap" style="align-content: center">
                                        <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="100DEF" id="100DEF"><label class="mt-1 form-check-label" for="100DEF">100 DEF</label></div>
                                        <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="90DEF" id="90DEF"><label class="mt-1 form-check-label"  for="90DEF">90 DEF</label></div>
                                        <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="40DEF" id="40DEF"><label class="mt-1 form-check-label"  for="40DEF">40 DEF</label></div>
                                        <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="10DEX" id="10DEX"><label class="mt-1 form-check-label"  for="10DEX">10 DEX</label></div>
                                        <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="7DEX"  id="7DEX"><label class="mt-1 form-check-label"   for="7DEX">7 DEX</label></div>
                                        <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="6DEX"  id="6DEX"><label class="mt-1 form-check-label"   for="6DEX">6 DEX</label></div>
                                        <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="9STR"  id="9STR"><label class="mt-1 form-check-label"   for="9STR">9 STR</label></div>
                                        <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="20INT" id="20INT"><label class="mt-1 form-check-label"  for="20INT">20 INT</label></div>
                                        <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="14INT" id="14INT"><label class="mt-1 form-check-label"  for="14INT">14 INT</label></div>
                                        <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="9HP"   id="9HP"><label class="mt-1 form-check-label"    for="9HP"  >9 HP</label></div>
                                    </div>
                                </div>
                                <div class="row xd">
                                    <div class="col-xxl-2 d-flex d-xxl-flex justify-content-xxl-center ">
                                        <label style="font-size: large;color: aquamarine;">Açıklama</label>
                                    </div>
                                    <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center"><textarea id="aciklama" class="form-control" style="height: 90px" placeholder="Opsiyonel" name="aciklama"></textarea></div>
                                </div>
                                <div class="row p-4" style="align-items: end">
                                    <button class="btn col-3 color-darkgreen m-auto okey">Düzenle</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--                                ----------------------------------------------------------------------------------           --}}




    </section>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/dataTables.bootstrap4.min.js') }}">    </script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap4.min.js"></script>
    <script src="/back/assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
    <script>
        function hepsiniSec(a) {

        }
    </script>
    <script>
        function selectCalistir(id) {
            $(".select2").select2({
                dropdownParent: $("#duzenle" + id),
                width: '100%'
            });
            ekle(id);
        }

        function ekle(id){var x='';setTimeout(function (){$('.ykp_'+id).each(function(){x+=$(this).attr('iid')+"#" });$('#sgrup_'+id).val(x);},1000);}


        $(document).ready(function() {

            $(".girilenFiyat").keyup(function() {
                var komisyonOran = {{ findUserKomisyon(Auth::user()->id) }} / 100;
                var gelenFiyat = this.value * komisyonOran;
                var guncelFiyat = $(this).parent().parent().find('.guncelFiyat');
                guncelFiyat[0].value = Number((this.value - gelenFiyat).toFixed(2));
            });

            $('#datatable').DataTable({
                columnDefs: [{
                        orderable: false,
                        targets: [8]
                    },
                    {
                        orderable: false,
                        className: 'select-checkbox',
                        targets: 0
                    }
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                "dom": '<"row"<"col-6"B><"col-2"l><"col-4"f>>rtip',
                buttons: [
                    /* {
                                        text: 'İlan Birleştir',
                                        className: "btn-inline color-blue merge-button",
                                        init: function(api, node, config) {
                                            $(node).removeClass('btn-secondary')
                                        },
                                        attr: {
                                            'data-bs-toggle': 'modal',
                                            'data-bs-target': '#ilanBirlestir',
                                            'disabled': 'true'
                                        }
                                    } */
                ],
                paging: false,
                "order": [
                    [6, "desc"]
                ],
                "language": {
                    "decimal": "",
                    "emptyTable": "{{ __('admin.hic-veri-yok') }}",
                    "info": "{{ __('admin.adet-veri-gosterim', ['TOTAL' => '_TOTAL_', 'START' => '_START_', 'END' => '_END_']) }}",
                    "infoEmpty": "{{ __('admin.sifir-veri-var') }}",
                    "infoFiltered": "{{ __('admin.adet-veri-araniyor', ['MAX' => '_MAX_']) }}",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "{{ __('admin.veri-gosteriliyor', ['MENU' => '_MENU_']) }}",
                    "loadingRecords": "{{ __('admin.yukleniyor') }}",
                    "processing": "{{ __('admin.isleniyor') }}",
                    "search": "{{ __('admin.ara') }}",
                    "zeroRecords": "{{ __('admin.eslesen-veri-bulunamadi') }}",
                    "paginate": {
                        "first": "{{ __('admin.ilk') }}",
                        "last": "{{ __('admin.son') }}",
                        "next": "{{ __('admin.sonraki') }}",
                        "previous": "{{ __('admin.onceki') }}"
                    },
                }
            });
            $('#datatable2').DataTable({
                paging: false,
                "order": [
                    [1, "desc"]
                ],
                "language": {
                    "decimal": "",
                    "emptyTable": "{{ __('admin.hic-veri-yok') }}",
                    "info": "{{ __('admin.adet-veri-gosterim', ['TOTAL' => '_TOTAL_', 'START' => '_START_', 'END' => '_END_']) }}",
                    "infoEmpty": "{{ __('admin.sifir-veri-var') }}",
                    "infoFiltered": "{{ __('admin.adet-veri-araniyor', ['MAX' => '_MAX_']) }}",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "{{ __('admin.veri-gosteriliyor', ['MENU' => '_MENU_']) }}",
                    "loadingRecords": "{{ __('admin.yukleniyor') }}",
                    "processing": "{{ __('admin.isleniyor') }}",
                    "search": "{{ __('admin.ara') }}",
                    "zeroRecords": "{{ __('admin.eslesen-veri-bulunamadi') }}",
                    "paginate": {
                        "first": "{{ __('admin.ilk') }}",
                        "last": "{{ __('admin.son') }}",
                        "next": "{{ __('admin.sonraki') }}",
                        "previous": "{{ __('admin.onceki') }}"
                    },
                }
            });
        });
    </script>
    <script>
        var pid;
        $(document).ready(function() {
            $('.select2').select2();
            $("#2").keyup(function() {
                gelen = $("#2").val();
                komisyon = {{ DB::table('settings')->first()->pazar_komisyon }};
                $("#5").val(gelen - (gelen * komisyon / 100));
            });
            $("#pazar").change(function() {
                pazar = $("#pazar").val();
                $.ajax({
                    url: "?pazar=" + pazar,
                    success: function(result) {
                        $(".area").html(result);
                        $('.select2').select2();
                    }
                });
            });
        });

        $('.cypdz').click(function () {
            pid=$(this).attr('id').split('_')[1];
            $('#aciklama').empty();
            $('#cypher').trigger('reset');

            $.get('?', {cypedit:pid, _token: $('meta[name="csrf-token"]').attr('content')}, function (x) {
//console.log(x);
                let q=JSON.parse(x.split('^')[0]);
                $('#baslik').val(x.split('^')[1]);
                $('#server').val(x.split('^')[2]);
                $('#fiyat').val(x.split('^')[3]);
                $('#aciklama').text(x.split('^')[4]);

                for (c in q){
                    //console.log(c+ '----' +q[c]);
                    if(q[c]=='on'){$('#'+c).prop('checked',true);} else $('#'+c).val(q[c]);
                }

            });
            $('#duzen').modal('show');

            setTimeout(function (){
                $('#mk').text($('#k11').is(':checked')?'K':'M');
            },1000);

        });

        $('.np').keyup(function(){
            if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);
            $('#NP').val($('.np').val().toString());
        })

        $('#k11, #m11').click(function () {
            let kk= $('#k11').is(':checked')?'K':'M';
            $('#NP').val($('.np').val().toString());
            $('#mk').text(kk);
        })

        $('.rkm').keyup(function(){
            if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);
            $('.net').val(($('.rkm').val()-($('.rkm').val()*1/100)).toFixed(2) );
        })

        $('.okey').click(function (){
            if (kont()===false) {
                swal.fire({icon:'error',text:'Zorunlu Alanlar Boş Bırakılmış',showConfirmButton: false,timer:1500});
                return false;
            } else {
                swal.fire({icon: 'success', text: 'Form Gönderiliyor..', showConfirmButton: false, timer: 2000});
                $.get('?', {cyphered: pid, npc: $('#k11').is(':checked') ? 'K' : 'M', veri: btoa($('#cypher').serialize())}, function (x) {
                    if(x!=200){
                        swal.fire({icon:'error',text: x ,showConfirmButton: false,timer:5000});$('#duzen').modal('hide');location.reload()
                    } else{ swal.fire({icon:'success',text:'Kayıt Başarılı',showConfirmButton: false,timer:2000});$('#duzen').modal('hide');location.reload()}
                });
                return false;
            }

        })

        function kont(){
            if($('#baslik').val()=='') {return false}
            if($('#server').val()=='') {return false}
            if($('#karakter').val()=='') {return false}
            if($('#irk').val()=='') {return false}
            if($('#yuzde').val()=='') {return false}
            if($('#level').val()=='') {return false}
            if($('#fiyat').val()=='') {return false}
            if($('#np').val()=='') {return false}
            return true;
        }

    </script>
@endsection
