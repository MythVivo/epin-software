<? error_reporting(E_PARSE);

#-------------------------------------------comment delete
if(@$_GET['ysil']!='' && is_numeric($_GET['ysil'])){
$p = (object) $_GET;$user=Auth::user()->id; $ind='';
#Kullanıcıya ait ilanın yorumlarını tespit edip indexle
$sor=DB::select("SELECT iy.id FROM ilan_yorumlar iy join pazar_yeri_ilanlar_buy pyi on pyi.id=iy.ilan where iy.ilan='$p->ysil' and pyi.user='$user'");
if(count($sor)<1) {\Illuminate\Support\Facades\Session::put('error', 'Hata oluştu.. :(');} else {
foreach ($sor as $y) {$ind .= $y->id . ',';}
$ind = substr($ind, 0, -1);
#indexlenen yorumları sil
DB::select("delete from ilan_yorumlar where id in($ind)");

\Illuminate\Support\Facades\Session::put('success', 'İlan yorumları temizlendi.');
}
}
?>

@if(isset($_GET['sil']))
    <?php
    if (DB::table('pazar_yeri_ilanlar_buy')->where('user',Auth::user()->id)->where('id', $_GET['sil'])->first()->status < 2) {
        $ilan = DB::table('pazar_yeri_ilanlar_buy')->where('id', $_GET['sil'])->first();
        if ($ilan->money_status == 0 and $ilan->deleted_at == NULL) {
            #--------------------------------------------------------------------------------------------------------------------------------
            $iade=DB::table("iade_bakiye")->where('iid',$_GET['sil'])->where('id',$ilan->iade_id)->where('uid',Auth::user()->id)->first();

            DB::table('users')->where('id', Auth::user()->id)->update([
                'bakiye' => Auth::user()->bakiye+$iade->tutar  # burada özel bir işlem yapıyoruz N.Ş.A. bu iade modülü her 2 bakiyeyi etkiliyor ama burada
                #işlem biraz karışık olduğu için alış düzenleme sonrası yeni hesaplanan bloke miktarını silinme sonrasında bakiyeye ekleyip geçiyoruz.
                #Bu durumda ç. bakiye etkilenmiyor varsa bakiyeler arası transfer oluyor.
            ]);
            #--------------------------------------------------------------------------------------------------------------------------------
            LogCall(Auth::user()->id, '1', $_GET['sil'] . " ID li ALIŞ ilanını yayından kaldırdı. $iade->tutar TL Bakiyesine iade edildi.");
        }
    }
    DB::table('pazar_yeri_ilanlar_buy')->where('user',Auth::user()->id)->where('id', $_GET['sil'])->update(['deleted_at' => date('YmdHis')]);
    header('Location: ?okey');
    exit;
    ?>
@endif
<?
    if (@$_GET['pasif']) {
        if (DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $_GET['pasif'])->count() > 0) {
            // silinmek istenen ilan islem gordu ise
            LogCall(Auth::user()->id, '1', $_GET['pasif'] . " ID li ALIŞ ilanını yayından kaldırmak istedi. Satış sürecinde olduğu için sistem izin vermedi.");
            header('Location: ?err=İşlem Gören İlan Pasif Edilemez..');
            die();
        }

        $ilan = DB::table('pazar_yeri_ilanlar_buy')->where('id', $_GET['pasif'])->first();
        if ($ilan->userStatus == 1) {$status = 0;LogCall(Auth::user()->id, '1', $_GET['pasif'] . " ID li aktif olan ALIŞ ilanını yayından kaldırdı.");
        } else {
            $status = 1;LogCall(Auth::user()->id, '1', $_GET['pasif'] . " ID li pasif olan ALIŞ ilanını yayına aldı.");
        }
        DB::table('pazar_yeri_ilanlar_buy')->where('user', Auth::user()->id)->where('id', $_GET['pasif'])->update(['userStatus' => $status,'updated_at' => date('YmdHis'),
            'red_neden' => ''  # kull. tarafından tekrar yayına alınırken burayı temizliyoruz yayın süresi bitiminde oto 7 oluyor
        ]);
        header('Location: ?okey');
        exit();
    }


?>

@extends('front.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <style> .modal {z-index: 9999;}</style>
@endsection
@section('body')
    @if(isset($_GET['olumsuz']) and $_GET['olumsuz'] == 1)
        <div class="modal fade" id="olumsuz" tabindex="-1" aria-labelledby="staticBackdropLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Alış İlanı Açamazsınız</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="fas fa-check me-2"></i>
                                <div>Dikkat! Alış ilanı açma yetkiniz bulunmuyor, bir yanlışlık olduğunu düşünüyorsanız canlı destek üzerinden bizimle iletişime geçebilirsiniz.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-outline-success">Anladım
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">
                @include('front.modules.user-menu')
                <div class="col-md-9">
                    @if(isset($_GET['date1']) and isset($_GET['date2']))
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
                                           value="{{$date1}}" required>
                                </div>

                            </div>


                            <div class="col-sm-12 col-md-5 mb-4">

                                <label class="col-sm-3 col-form-label" for="userinput2">Son Tarih</label>
                                <div class="col-sm-9">
                                    <input type="date" id="userinput2" class="form-control style-input" name="date2"
                                           value="{{$date2}}" required>
                                </div>

                            </div>

                            <div class="col-sm-12 col-md-2 mb-4 d-flex justify-content-sm-start justify-content-md-end align-items-end">

                                <button type="submit" class="btn-inline color-blue">Sorgula
                                </button>

                            </div>

                        </div>
                    </form>

                    @if(session('success'))
                    <!--Mesaj bildirimi--->
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fas fa-check me-2"></i>
                            <div>{{session('success')}}</div>
                                <? Session::forget('success') ; ?>
                        </div>
                        <!--Mesaj bildirim END--->
                    @endif
                    @if(session('error'))
                    <!--Mesaj bildirimi--->
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="fas fa-check me-2"></i>
                            <div>{{session('error')}}</div>
                                <? Session::forget('error') ; ?>
                        </div>
                        <!--Mesaj bildirim END--->
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if(!session('panel') or (session('panel') and session('panel') != 3)) active @endif" id="pills-satista-tab" data-bs-toggle="pill" data-bs-target="#pills-satista" type="button" role="tab" aria-controls="pills-satista" @if(!session('panel') or (session('panel') and session('panel') != 3)) aria-selected="true" @else aria-selected="false"@endif>Yayında Olanlar</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-sattiklarim-tab" data-bs-toggle="pill" data-bs-target="#pills-sattiklarim" type="button" role="tab" aria-controls="pills-sattiklarim" aria-selected="false">Aldıklarım</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if(session('panel') and session('panel') == 3)) active @endif" id="pills-alis-sattiklarim-tab" data-bs-toggle="pill" data-bs-target="#pills-alis-sattiklarim" type="button" role="tab" aria-controls="pills-alis-sattiklarim" @if(session('panel') and session('panel') == 3)) aria-selected="true" @else aria-selected="false" @endif>Sattıklarım</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" type="button" onclick="location.href='https://oyuneks.com/yeni-satis-buy?market=7'">Yeni Alış</button>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-end">
                            <?php /*
                            <button data-bs-toggle="modal"
                                    data-bs-target="#ilanBirlestir" class="btn-inline color-blue merge-button"
                                    disabled>İlan Birleştir
                            </button>
                            <div class="modal fade" id="ilanBirlestir"
                                 tabindex="-1"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"
                                                id="exampleModalLabel">
                                                İlan Birleştir</h5>
                                            <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="{{route('satici_panelim_birlestir_post')}}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="form-label">Toplu Fiyat</label>
                                                        <input class="form-control" name="toplu_fiyat" type="number"
                                                               required>
                                                    </div>
                                                    <div class="col-12 mt-3">
                                                        <label class="form-label">Toplu Açıklama</label>
                                                        <textarea class="form-control" name="toplu_aciklama"
                                                                  required></textarea>
                                                    </div>
                                                    <div class="col-12 mt-5">
                                                        <label class="form-label">Seçilen İlanlar</label>
                                                        <div class="ilan-alani">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="ilanlar_id[]">
                                                    <div class="col-12 mt-5 text-end">
                                                        <button type="submit" class="btn-inline color-blue">Birleştir
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        */ ?>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade @if(!session('panel') and !session('panel') == 3)) show active @endif"
                                     id="pills-satista" role="tabpanel"
                                     aria-labelledby="pills-genel-tab">
                                    <div class="row g-3">

                                        <div class="table-responsive">
                                            <table lang="{{getLang()}}" id="datatable"
                                                   class="table table-bordered nowrap table-list">
                                                <thead>
                                                <tr>
                                                    <th>İlan No</th>
                                                    <th>Pazar</th>
                                                    <th>Başlık</th>
                                                    <th>Sunucu</th>
                                                    <th>Fiyat</th>
                                                    <th>Eklenme Tarihi</th>
                                                    <th>Durum</th>
                                                    <th>Aksiyon</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if(isset($_GET['date1']) and isset($_GET['date2'])) {
                                                    $sorgu = DB::table('pazar_yeri_ilanlar_buy')->whereDate('created_at', '>=', $date1)->whereDate('created_at', '<=', $date2)->where('user', Auth::user()->id)->where('status', '!=', '6')->whereNull('deleted_at')->get();
                                                } else {
                                                    $sorgu = DB::table('pazar_yeri_ilanlar_buy')->where('user', Auth::user()->id)->where('status', '!=', '6')->whereNull('deleted_at')->get();
                                                }
                                                ?>
                                                @foreach($sorgu as $u)
                                                    <tr>
                                                        <td>{{$u->id}}</td>
                                                        <td>{{DB::table('games_titles')->where('id', $u->pazar)->first()->title}}</td>
                                                        <td>
                                                            <?php
                                                            $item = DB::table('games_titles')->where('id', $u->pazar)->first();
                                                            ?>
                                                            <a href="{{route('item_buy_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}"
                                                               target="_blank">
                                                                {{mb_substr($u->title, 0, 30)}}
                                                                @if(strlen($u->title) > 30)...@endif</a>
                                                        </td>
                                                        <td>{{$u->sunucu}}</td>
                                                        <td>₺{{MF($u->price)}}</td>
                                                        <td>{{$u->created_at}}</td>
                                                        <td @if($u->status==2) style="cursor: help" title="{{$u->red_neden}}" @endif>
                                                            {{ $u->status != 1 ? findIlanStatus($u->status) : ($u->userStatus == 1 ? 'Yayında' : 'Pasif') }}
                                                        </td>
                                                        <td style="text-align: center">
                                                            <button data-bs-toggle="modal" title="Yorumları Gör" data-bs-target="#yorumlar{{$u->id}}" type="button" class="btn btn-sm btn-outline-info waves-effect waves-light"><i class="far fa-comments"></i></button>
{{--                                                            #-------------------------------------------------------------------------------------------------------}}
                                                            @if($u->status ==1 || $u->status ==2)
                                                                <button data-bs-toggle="modal" title="İlanı Düzenle" data-bs-target="#duzenle{{$u->id}}" onclick="selectCalistir({{$u->id}})" type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light"><i class="far fa-edit"></i></button>


                                                                @if ($u->userStatus == 1)
                                                                    <button type="button" title="Pasif Hale Getir" class="btn btn-sm btn-outline-success waves-effect waves-light confirm-btn" onclick="alert('İlan için bloke edilen miktarın hesabınıza iadesi sadece ilanı silmeniz durumunda gerçekleşmektedir.');location.href='?pasif={{ $u->id }}'"><i class="far fa-eye"></i></button>
                                                                @else
                                                                    <button type="button" title="Aktif Hale Getir" class="btn btn-sm btn-outline-warning waves-effect waves-light confirm-btn" onclick="location.href='?pasif={{ $u->id }}'"><i class="far fa-eye"></i></button>
                                                                @endif

                                                                <button confirm-data='?sil={{$u->id}}' type="button" title="İlanı Sil" class="btn btn-sm btn-outline-danger waves-effect waves-light confirm-btn"><i class="far fa-trash-alt"></i></button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <div class="modal fade" id="yorumlar{{$u->id}}"
                                                         tabindex="-1"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="exampleModalLabel">
                                                                        Yorumlar</h5>
                                                                    <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if(DB::table('ilan_yorumlar')->where('buy', '1')->whereNull('deleted_at')->where('ilan', $u->id)->where('status', '1')->count() > 0)
                                                                        @foreach(DB::table('ilan_yorumlar')->where('buy', '1')->whereNull('deleted_at')->where('ilan', $u->id)->where('status', '1')->get() as $yy)
                                                                            <div class="comment-container @if($yy->user == $u->user) satici @endif">
                                                                                <div class="this-comment">
                                                                                    <div class="commenter">
                                                                                        <h6>{{substr(DB::table('users')->where('id', $yy->user)->first()->name, 0, 2)}}
                                                                                            *** *****</h6>
                                                                                        <div class="c_date">
                                                                                            {{$yy->created_at}}
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="cmnt-text">
                                                                                        {{$yy->text}}
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
                                                                          action="{{route('ilan_yorum_yap')}}">
                                                                        @csrf
                                                                        <input type="hidden" name="ilan"
                                                                               value="{{$u->id}}">
                                                                        <input type="hidden" name="buy" value="1">
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
                                                                                                style="height: 120px"
                                                                                                required></textarea>
                                                                                    </div>
                                                                                    <style>
                                                                                        .yorum-info{
                                                                                            color: black;
                                                                                        }
                                                                                        body.dark .yorum-info{
                                                                                            color:white;
                                                                                        }
                                                                                    </style>
                                                                                    <div class="col-12 yorum-info" >
                                                                                        Yorumunuz onaylandığında karşı taraf sms ile bilgilendirilecektir.<br>(Sms gönderim ücreti: ücretsiz)
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
                                                    @if($u->status < 2)
                                                        <div class="modal fade" id="duzenle{{$u->id}}" tabindex="-1"
                                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="exampleModalLabel">
                                                                            {{$u->title}} İlanını Düzenle</h5>
                                                                        <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form method="post"
                                                                              action="{{route('alis_duzenle_post')}}"
                                                                              enctype="multipart/form-data"
                                                                              autocomplete="off">
                                                                            @csrf
                                                                            <input type="hidden" name="ilan"
                                                                                   value="{{$u->id}}">
                                                                            @if($u->toplu == 1)
                                                                                <div class="row">
                                                                                    <div class="col-6">
                                                                                        <label class="form-label"
                                                                                               for="fiyat{{$u->id}}">Toplu
                                                                                            Fiyat</label>
                                                                                        <input class="form-control"
                                                                                               id="fiyat{{$u->id}}"
                                                                                               name="price"
                                                                                               value="{{$u->price}}">
                                                                                    </div>
                                                                                    <div class="col-6">
                                                                                        <label class="form-label"
                                                                                               for="aciklama">Toplu
                                                                                            Açıklama</label>
                                                                                        <textarea
                                                                                                class="form-control"
                                                                                                id="aciklama"
                                                                                                name="text"
                                                                                                rows="3">{{$u->text}}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                @if(DB::table('games_titles_special')->where('games_titles', $u->pazar)->count() > 0)
                                                                                    <div class="row">
                                                                                        <div class="col-6">
                                                                                            <label class="form-label"
                                                                                                   for="title{{$u->id}}">Başlık</label>
                                                                                            <input class="form-control"
                                                                                                   id="title{{$u->id}}"
                                                                                                   name="title"
                                                                                                   type="text"
                                                                                                   value="{{$u->title}}"
                                                                                                   required>
                                                                                        </div>
                                                                                        <div class="col-6">
                                                                                            <label class="form-label"
                                                                                                   for="price{{$u->id}}">Fiyat</label>
                                                                                            <input class="form-control"
                                                                                                   id="price{{$u->id}}"
                                                                                                   name="price"
                                                                                                   type="number"
                                                                                                   value="{{$u->price}}"
                                                                                                   required>
                                                                                        </div>
                                                                                        @foreach(DB::table('games_titles_features')->where('game_title', $u->pazar)->whereNull('deleted_at')->get() as $p)
                                                                                            <?php $value = DB::table('pazar_yeri_ilan_features')->where('ilan', $u->id)->where('feature', $p->id)->first(); ?>
                                                                                            <div class="col-6 mt-3">
                                                                                                <label class="form-label"
                                                                                                       for="{{Str::slug($p->title)}}{{$u->id}}">{{$p->title}}</label>
                                                                                                <br>
                                                                                                @if($p->type == 1)
                                                                                                    <select id="{{Str::slug($p->title)}}{{$u->id}}"
                                                                                                            class="form-control select2 w-100"
                                                                                                            name="{{Str::slug($p->title)}}"
                                                                                                            required>
                                                                                                        <option value="0"
                                                                                                                selected>
                                                                                                            Belirtilmemiş
                                                                                                        </option>
                                                                                                        @foreach(json_decode($p->value) as $deger)
                                                                                                            <option value="{{Str::slug($deger)}}"
                                                                                                                    @if(Str::slug($deger) == $value->value) selected @endif>{{$deger}}</option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                @endif
                                                                                                @if($p->type == 2)
                                                                                                    <select id="{{Str::slug($p->title)}}{{$u->id}}"
                                                                                                            class="form-control select2 w-100"
                                                                                                            name="{{Str::slug($p->title)}}"
                                                                                                            multiple
                                                                                                            required>
                                                                                                        <option value="0"
                                                                                                                selected>
                                                                                                            Belirtilmemiş
                                                                                                        </option>
                                                                                                        @foreach(json_decode($p->value) as $deger)
                                                                                                            <option value="{{Str::slug($deger)}}"
                                                                                                                    @if(Str::slug($deger) == $value->value) selected @endif >{{$deger}}</option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                @endif
                                                                                            </div>
                                                                                        @endforeach
                                                                                        <div class="col-12 mt-3">
                                                                                            <div class="row">
                                                                                                <div class="col-6">
                                                                                                    <label class="form-label">Resim</label><br>
                                                                                                    <img class="w-100"
                                                                                                         src="{{asset('public/front/ilanlar/'.$u->image)}}">
                                                                                                </div>
                                                                                                <div class="col-6">
                                                                                                    <label class="form-label"
                                                                                                           for="image{{$u->id}}">Yeni
                                                                                                        Resim
                                                                                                        Seç</label>
                                                                                                    <input type="file"
                                                                                                           class="form-control"
                                                                                                           id="image{{$u->id}}"
                                                                                                           name="image"
                                                                                                           accept="image/*">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-12 mt-3">
                                                                                            <label for="text{{$u->id}}"
                                                                                                   class="form-label">Açıklama</label>
                                                                                            <textarea
                                                                                                    class="form-control"
                                                                                                    name="text"
                                                                                                    id="text{{$u->id}}">{{$u->text}}</textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="row">
                                                                                        <div class="col-6">
                                                                                            <label class="form-label"
                                                                                                   for="fiyat{{$u->id}}">Fiyat</label>
                                                                                            <input class="form-control girilenFiyat"
                                                                                                   id="fiyat{{$u->id}}"
                                                                                                   name="price{{$u->id}}"
                                                                                                   value="{{$u->price}}">
                                                                                            <br>
                                                                                            <label class="form-label"
                                                                                                   for="fiyat{{$u->id+time()}}">Yeni
                                                                                                Bloke Edilecek Para</label>
                                                                                            <input class="form-control guncelFiyat"
                                                                                                   id="fiyat{{$u->id+time()}}"
                                                                                                   name="priceNew"
                                                                                                   value="0" readonly>
                                                                                            <small class="text-danger">
                                                                                                * Hesabınızda bulunması
                                                                                                gereken para
                                                                                                hesaplanırken
                                                                                                yeni yazdığınız fiyat'ın
                                                                                                %{{findUserKomisyonAlis(Auth::user()->id)}}
                                                                                                kadarından
                                                                                                ilanı açtığınızda bloke
                                                                                                edilen para
                                                                                                çıkartılır.
                                                                                                <br>
                                                                                                Şu anda yapmış olduğunuz
                                                                                                işlem de
                                                                                                yeni komisyon <span
                                                                                                        class="text-info yeniKomisyon">{{$u->price*0.1}}</span>
                                                                                                TL - eski komisyon <span
                                                                                                        class="text-info eskiKomisyon">{{$u->price*0.1}}</span>
                                                                                                TL şeklinde
                                                                                                hesaplanmakta.
                                                                                                <br>
                                                                                                <span class="text-success eksiKomisyon"></span>
                                                                                            </small>
                                                                                        </div>
                                                                                        <div class="col-6">
                                                                                            <label class="form-label"
                                                                                                   for="aciklama">Açıklama</label>
                                                                                            <textarea style="height: 129px;"
                                                                                                    class="form-control"
                                                                                                    id="aciklama"
                                                                                                    name="text{{$u->id}}"
                                                                                                    rows="5">{{$u->text}}</textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endif

                                                                            <div class="text-end">
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
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th>İlan No</th>
                                                    <th>Pazar</th>
                                                    <th>Başlık</th>
                                                    <th>Sunucu</th>
                                                    <th>Fiyat</th>
                                                    <th>Eklenme Tarihi</th>
                                                    <th>Durum</th>
                                                    <th>Aksiyon</th>
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
                                            <table lang="{{getLang()}}" id="datatable2"
                                                   class="table table-bordered nowrap">
                                                <thead>
                                                <tr>
                                                    <th>Başlık</th>
                                                    <th>Eklenme Tarihi</th>
                                                    <th>Durum</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach(DB::table('pazar_yeri_ilanlar_buy')->where('user', Auth::user()->id)->where('status', 6)->whereNull('deleted_at')->get() as $u)
                                                    <tr>
                                                        <td>{{$u->title}}</td>
                                                        <td>{{$u->created_at}}</td>
                                                        <td>
                                                            {{findIlanStatus($u->status)}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th>Başlık</th>
                                                    <th>Eklenme Tarihi</th>
                                                    <th>Durum</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane fade @if(session('panel') and session('panel') == 3)) show active @endif "
                                     id="pills-alis-sattiklarim" role="tabpanel"
                                     aria-labelledby="pills-alis-sattiklarim-tab">
                                    <div class="row g-3">
                                        <div class="table-responsive">
                                            <table lang="{{getLang()}}" id="datatable3"
                                                   class="table table-bordered nowrap table-list">
                                                <thead>
                                                <tr>
                                                    <th>İlan No</th>
                                                    <th>Pazar</th>
                                                    <th>Başlık</th>
                                                    <th>Sunucu</th>
                                                    <th>Elde Edilen Kazanç</th>
                                                    <th>Satış Tarihi</th>
                                                    <th>Durum</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if(isset($_GET['date1']) and isset($_GET['date2'])) {
                                                    $sorgu = DB::table('pazar_yeri_ilan_satis_buy')->whereDate('created_at', '>=', $date1)->whereDate('created_at', '<=', $date2)->where('satin_alan', Auth::user()->id)->whereNull('deleted_at')->get();
                                                } else {
                                                    $sorgu = DB::table('pazar_yeri_ilan_satis_buy')->where('satin_alan', Auth::user()->id)->whereNull('deleted_at')->get();
                                                }
                                                ?>
                                                @foreach($sorgu as $uu)
                                                    <?php $u = DB::table('pazar_yeri_ilanlar_buy')->where('id', $uu->ilan)->first(); ?>
                                                    <tr>
                                                        <td>{{$u->id}}</td>
                                                        <td>{{DB::table('games_titles')->where('id', $u->pazar)->first()->title}}</td>
                                                        <td>
                                                            <?php
                                                            $item = DB::table('games_titles')->where('id', $u->pazar)->first();
                                                            ?>
                                                            <a href="{{route('item_buy_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}"
                                                               target="_blank">
                                                                {{mb_substr($u->title, 0, 30)}}
                                                                @if(strlen($u->title) > 30)...@endif</a>
                                                        </td>
                                                        <td>{{$u->sunucu}}</td>
                                                        <td>{{$u->price - $u->moment_komisyon}}</td>
                                                        <td>{{$uu->created_at}}</td>
                                                        <td>
                                                            {{findIlanStatus($u->status)}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th>İlan No</th>
                                                    <th>Pazar</th>
                                                    <th>Başlık</th>
                                                    <th>Sunucu</th>
                                                    <th>Elde Edilen Kazanç</th>
                                                    <th>Eklenme Tarihi</th>
                                                    <th>Durum</th>
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
                                                    <a class="del">Sil</a>
                                                    <a class="cancel">Vazgeç</a>
                                                </div>
                                            </article>
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
@section('js')
    @if(isset($_GET['olumsuz']) and $_GET['olumsuz'] == 1)
        <script type="text/javascript">
            $(window).on('load', function () {
                $('#olumsuz').modal('show');
            });
        </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
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
        }

        $(document).ready(function () {
            $(".girilenFiyat").keyup(function () {
                var komisyonOran = 0.1;
                var gelenFiyat = this.value * komisyonOran;
                var eskiKomisyon = $(this).parent().find('.eskiKomisyon').text();
                var guncelFiyat = $(this).parent().find('.guncelFiyat');
                var yeniKomisyon = $(this).parent().find('.yeniKomisyon');
                guncelFiyat[0].value = Number((gelenFiyat - eskiKomisyon).toFixed(2));
                yeniKomisyon[0].innerText = Number(gelenFiyat.toFixed(2));

                var eksiKomisyon = $(this).parent().find('.eksiKomisyon');
                if (Number(gelenFiyat - eskiKomisyon) < 0) {
                    $(eksiKomisyon).show();
                    eksiKomisyon[0].innerText = 'Bu durumda ' + Number(-1 * (gelenFiyat - eskiKomisyon)) + ' TL tutarında bakiye blokeniz kaldırılacaktır.';
                } else {
                    $(eksiKomisyon).hide();
                }
            });


            $('#datatable').DataTable({
                columnDefs: [
                    {orderable: false, targets: [7]},
                ],
                paging: false,
                "order": [[5, "desc"]],
                "language": {
                    "decimal": "",
                    "emptyTable": "{{__('admin.hic-veri-yok')}}",
                    "info": "{{__('admin.adet-veri-gosterim', ['TOTAL' => '_TOTAL_', 'START' => '_START_', 'END' => '_END_'])}}",
                    "infoEmpty": "{{__('admin.sifir-veri-var')}}",
                    "infoFiltered": "{{__('admin.adet-veri-araniyor', ['MAX' => '_MAX_'])}}",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "{{__('admin.veri-gosteriliyor', ['MENU' => '_MENU_'])}}",
                    "loadingRecords": "{{__('admin.yukleniyor')}}",
                    "processing": "{{__('admin.isleniyor')}}",
                    "search": "{{__('admin.ara')}}",
                    "zeroRecords": "{{__('admin.eslesen-veri-bulunamadi')}}",
                    "paginate": {
                        "first": "{{__('admin.ilk')}}",
                        "last": "{{__('admin.son')}}",
                        "next": "{{__('admin.sonraki')}}",
                        "previous": "{{__('admin.onceki')}}"
                    },
                }
            });
            $('#datatable2').DataTable({
                paging: false,
                "order": [[1, "desc"]],
                "language": {
                    "decimal": "",
                    "emptyTable": "{{__('admin.hic-veri-yok')}}",
                    "info": "{{__('admin.adet-veri-gosterim', ['TOTAL' => '_TOTAL_', 'START' => '_START_', 'END' => '_END_'])}}",
                    "infoEmpty": "{{__('admin.sifir-veri-var')}}",
                    "infoFiltered": "{{__('admin.adet-veri-araniyor', ['MAX' => '_MAX_'])}}",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "{{__('admin.veri-gosteriliyor', ['MENU' => '_MENU_'])}}",
                    "loadingRecords": "{{__('admin.yukleniyor')}}",
                    "processing": "{{__('admin.isleniyor')}}",
                    "search": "{{__('admin.ara')}}",
                    "zeroRecords": "{{__('admin.eslesen-veri-bulunamadi')}}",
                    "paginate": {
                        "first": "{{__('admin.ilk')}}",
                        "last": "{{__('admin.son')}}",
                        "next": "{{__('admin.sonraki')}}",
                        "previous": "{{__('admin.onceki')}}"
                    },
                }
            });
            $('#datatable3').DataTable({
                paging: false,
                "order": [[5, "desc"]],
                "language": {
                    "decimal": "",
                    "emptyTable": "{{__('admin.hic-veri-yok')}}",
                    "info": "{{__('admin.adet-veri-gosterim', ['TOTAL' => '_TOTAL_', 'START' => '_START_', 'END' => '_END_'])}}",
                    "infoEmpty": "{{__('admin.sifir-veri-var')}}",
                    "infoFiltered": "{{__('admin.adet-veri-araniyor', ['MAX' => '_MAX_'])}}",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "{{__('admin.veri-gosteriliyor', ['MENU' => '_MENU_'])}}",
                    "loadingRecords": "{{__('admin.yukleniyor')}}",
                    "processing": "{{__('admin.isleniyor')}}",
                    "search": "{{__('admin.ara')}}",
                    "zeroRecords": "{{__('admin.eslesen-veri-bulunamadi')}}",
                    "paginate": {
                        "first": "{{__('admin.ilk')}}",
                        "last": "{{__('admin.son')}}",
                        "next": "{{__('admin.sonraki')}}",
                        "previous": "{{__('admin.onceki')}}"
                    },
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
            $("#2").keyup(function () {
                gelen = $("#2").val();
                komisyon = {{DB::table('settings')->first()->pazar_komisyon}};
                $("#5").val(gelen - (gelen * komisyon / 100));
            });
            $("#pazar").change(function () {
                pazar = $("#pazar").val();
                $.ajax({
                    url: "?pazar=" + pazar,
                    success: function (result) {
                        $(".area").html(result);
                        $('.select2').select2();
                    }
                });
            });
        });
    </script>
@endsection
