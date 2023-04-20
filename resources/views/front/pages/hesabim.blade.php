@extends('front.layouts.app')
@section('body')
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">
                @include('front.modules.user-menu')
                <div class="col-md-9 c-card-panel">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="account-balance">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <h5>Toplam Bakiye</h5>
                                        <p>{{ MF(Auth::user()->bakiye + Auth::user()->bakiye_cekilebilir) }}<span>TL</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <h5>Bloke Bakiye</h5>
                                        <p>{{ MF(findUserBloke(Auth::user()->id)) }}<span>TL</span></p>
                                    </div>
                                </div>



                            </div>
                        </div>
                        @if (Auth::user()->id == 27504 || Auth::user()->id == 66254 || Auth::user()->id == 4462)


                            @if (isset($_GET['date1']) and isset($_GET['date2']))
                                    <?php $date1 = $_GET['date1']; $date2 = $_GET['date2']; $yok=1;?>
                            @else
                                    <?php $date1 ='2023-01-13'; $date2 = date('Y-m-d'); $yok=2; ?>
                            @endif

<?php

    if($date1<'2023-01-13') {$date1='2023-01-13';}

                            $sales = DB::select(
                                DB::raw("
                                (select sum(price) as satis,sum(alis*adet) as alis, sum(price) - sum(alis*adet) as kar
                                    from epin_satis where game_title in (218,292,309,409,443) and status = 1 and date(created_at) between '$date1' and '$date2' ORDER BY `epin_satis`.`id` DESC)

                                    union (select sum(tutar) as satis,sum(alis) as alis , sum(tutar) - sum(alis) as kar
                                    from epin_siparisler where epin_siparisler.oyun in (SELECT id FROM games_packages where games_titles in (218,292,309,409,443)) and durum = 'Başarılı' and  date(created_at) between '$date1' and '$date2')

                                    union (SELECT sum(price) as satis,sum(alis*adet) as alis, sum(price) - sum(alis*adet) as kar
                                    FROM `epin_satis` where user in (SELECT id FROM `users` WHERE `refId` = 66254) and game_title not in (218,292,309,409,443) and status = 1 and  date(created_at) between '$date1' and '$date2')

                                    union (select sum(tutar) as satis,sum(alis) as alis, sum(tutar) - sum(alis) as kar
                                    from epin_siparisler
                                    where epin_siparisler.user in (SELECT id FROM `users` WHERE `refId` = 66254) and durum = 'Başarılı' and  date(created_at) between '$date1' and '$date2' and oyun not in (SELECT id FROM games_packages where games_titles in (218,292,309,409,443)))"));
                            $satislar = [];
                            $karlar = [];
                            foreach ($sales as $sale) {
                                $karlar[] = floatval($sale->kar);
                                $satislar[] = floatval($sale->satis);
                            }
                            $sat = round(array_sum($satislar), 2);
                            $kar = round(array_sum($karlar), 2);

                           // if($yok==2) {Session::forget('sbak'); Session::put('sbak', $kar/2);}
                            ?>

                            <div class="col-md-12" style="background-color: #0c0c0c; border-radius: 20px; padding:20px;">
                                <h5 style="color: white; display: flex; justify-content: center">Ortaklık Programı</h5>

                                <form class="row" method="get">
                                    <div class="col-sm-12 col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label" for="userinput1">İlk Tarih</label>
                                            <input type="date" id="userinput1" class="form-control style-input" name="date1" value="{{ $date1 }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label" for="userinput2">Son Tarih</label>
                                            <input type="date" id="userinput2" class="form-control style-input" name="date2" value="{{ $date2 }}" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-success mt-2 w-auto" style="align-self: center;">Getir</button>
                                </form>
<?
$ode=DB::select("select * from cariy_fisler where hedef_cari=14");
?>
<div style="color: white; border-radius: 20px; padding:20px;font-weight: bold"><pre>
Toplam Satış  = {{ number_format($sat,2,',','.') }} TL
Kar = {{ number_format($kar,2,',','.') }} TL
Pay = {{ number_format($kar/2,2,',','.') }} TL
{{--<hr>Güncel Bakiye = {{number_format(Session::get('sbak')-$ode[0]->top,2,',','.')}} TL--}}
<hr>Ödemeler
@foreach($ode as $o)
{{$o->created_at}}      {{$o->aciklama}}      {{number_format($o->cikan_tutar,2,',','.')}} TL
@endforeach

</pre>
</div>
                            </div>
                        @else
                            <div class="col-md-6 mb-4">
                                <div class="account-more-balance">
                                    <h4>Şak diye bakiye yükle</h4>
                                    <h6>Kartından hemen TL yükle,
                                        avantajlı alışverişin
                                        keyfini çıkart.</h6>
                                    <div class="btn-footer"><a class="btn-inline color-blue"
                                            href="{{ route('bakiye_ekle') }}">TL yükle</a></div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="account-admin">
                                    <h4>Oyuneks
                                        hesabını yönet</h4>
                                    <h6>Hesabını kişiselleştir ve
                                        giriş tercihlerini
                                        güncelle.</h6>
                                    <div class="btn-footer"><a class="btn-inline color-blue"
                                            href="{{ route('hesap_onayla') }}">
                                            @if (getUserVerifiyStep(Auth::user()->id) >= 2)
                                                Hesabın Onaylı!
                                            @else
                                                Hesabını Onayla
                                            @endif
                                        </a></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
