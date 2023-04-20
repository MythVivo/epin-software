<div class="col-md-12 mb-4 mt-4">
    @if (isset($_GET['date1']) and isset($_GET['date2']))
        <?php
        $date1 = $_GET['date1'];
        $date2 = $_GET['date2'];
        ?>
    @else
        <?php
        $date1 = date('Y-m-d', strtotime('-0 days'));
        $date2 = date('Y-m-d');
        ?>
    @endif
    <form class="row mb-3" method="get">

        <div class="col-sm-12 col-md-5">
            <div class="mb-3">
                <label class="form-label" for="userinput1">İlk Tarih</label>
                <input type="date" id="userinput1" class="form-control style-input" name="date1"
                    value="{{ $date1 }}" required>
            </div>
        </div>


        <div class="col-sm-12 col-md-5">
            <div class="mb-3">
                <label class="form-label" for="userinput2">Son Tarih</label>
                <input type="date" id="userinput2" class="form-control style-input" name="date2"
                    value="{{ $date2 }}" required>
            </div>
        </div>

        <div class="col-sm-12 col-md-2 d-flex justify-content-sm-start justify-content-md-end align-items-center">

            <button type="submit" class="btn btn-outline-success btn-block mt-2 color-blue">Sorgula


        </div>


    </form>

</div>
<div class="col-12 mb-4">
    <select class="form-control style-select" id="table-filter">
        <option value="">Tümü</option>
        <option value="Onay Bekliyor" selected>Onay Bekleyen</option>
        <option value="Ödeme Onaylandı">Onaylananlar</option>
        <option value="Reddedildi">Reddedilenler</option>
        <option value="API Islemde">API Islemde</option>
    </select>
</div>
<table lang="{{ getLang() }}" id="datatable" class="table table-bordered nowrap"
    style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

        <tr>

            <th>Id</th>

            <th>Kullanıcı</th>

            <th>Tutar</th>
            <th>Kesinti</th>
            <th>Ödeme</th>

            <th>Ödeme Bilgileri</th>

            <th>Açıklama</th>

            <th>Durumu</th>

            <th>Tarihi</th>

            <th>@lang('admin.aksiyon')</th>

        </tr>

    </thead>

    <tbody>
        <?php
        $ayar = DB::table('settings')->first();
        $sorgu = DB::table('para_cek')
            ->whereDate('para_cek.updated_at', '>=', $date1)
            ->whereDate('para_cek.updated_at', '<=', $date2)
            ->whereNull('para_cek.deleted_at')
            ->orWhereNull('para_cek.updated_at')
            ->get();
        ?>

        @foreach ($sorgu as $u)
            <?php
            //gecici
            $apiReq = DB::table('integrations_finance_bulut')
                ->where('para_cek_id', $u->id)
                ->first();
            ?>
            <tr>

                <td>{{ $u->id }}</td>

                <td>
                    <?php $uye = DB::table('users')
                        ->where('id', $u->user)
                        ->first();
                    if ($uye->para_cek_kom == 1) {
                        $kom = $ayar->yayin_komisyon;
                    } else {
                        $kom = '0';
                    }
                    ?>
                    <a href="{{ route('uye_detay', [$uye->email]) }}" target="_blank">
                        {{ $uye->name }}
{{--                        <br> <i title="Çekilebilir Bakiyesi">Ç.B: ({{ $uye->bakiye_cekilebilir }})</i>--}}
                    </a>
                </td>


<? ////papara
            if(Str::contains($u->text, 'PAPARA')) {$kom='%1.5'; $net=$u->amount-$u->amount/100*$u->kesinti;preg_match('/\((.*?)\)/s',$u->text,$r);
            if(sizeof($r) > 1)
            $idd='id='.$uye->id.'_'.$u->id.'_'.$net.'_'.$r[1];
            } else {$net=$u->amount - $kom; $idd='';}
?>
                <td > {{ $u->amount }}</td>

                <td align='center'>{{ $kom }}</td>
                <td><?= $net ?></td>

                <td>
                    @if (DB::table('odeme_kanallari')->where('id', $u->odeme_kanali)->count() > 0)
                        <?php $odemeKanallari = DB::table('odeme_kanallari')
                            ->where('id', $u->odeme_kanali)
                            ->first(); ?>

                        Alıcı : {{ $odemeKanallari->alici }} <br>
                        Hes : {{ $odemeKanallari->iban }} <br>
                        BANKA : {{ $odemeKanallari->title }} <br>

                        <? //papara
//                            if(Str::contains($odemeKanallari->title, 'PAPARA')) {$kom='%1.5'; $net=$u->amount-$u->amount/100*$u->kesinti;$idd='id='.$uye->id.'_'.$u->id.'_'.$net.'_'.$odemeKanallari->iban;
//                        } else {$net=$u->amount - $kom; $idd='';}
                        ?>

                    @else
                        Ödeme Kanalı Seçmemiş
                    @endif

                </td>
                <td>{{ $apiReq && $apiReq->state != 1 ? $apiReq->response : $u->text }}</td>

                <td>

                    @if ($u->status == '0')
                        Onay Bekliyor
                    @elseif($u->status == '1')
                        Ödeme Onaylandı
                    @elseif($u->status == '-1')
                        API Islemde
                    @else
                        Reddedildi
                    @endif

                </td>

                <td>

                    {{ $u->updated_at ? $u->updated_at : $u->created_at }}

                </td>

                <td>

                    @if ($u->status != 1)
                        @if(Str::contains($u->text, 'PAPARA'))
                            <button title="Onayla ve Parayı ({{$net}} TL) gönder" type="button" {{$idd}} class="btn btn-outline-dark waves-effect waves-light pgon"><i class="fa-paper-plane fas"></i></button>
                        @else
                            <button onclick="location.href='{{ route('para_cek_yonetim_onayla', [1, $u->id]) }}'" type="button" class="btn btn-outline-success waves-effect waves-light"><i class="fas fa-check"></i></button>
                        @endif
                    @endif

                    @if ($u->status != 2)
                        <button data-toggle="modal" data-target=".goruntule{{ $u->id }}" type="button"
                            class="btn btn-outline-warning waves-effect waves-light">

                            <i class="fas fa-times"></i>

                        </button>

                        <div class="modal fade goruntule{{ $u->id }}" tabindex="-1" role="dialog"
                            aria-hidden="true">

                            <div class="modal-dialog">

                                <div class="modal-content">

                                    <div class="modal-header">

                                        <h5 class="modal-title mt-0" id="myModalLabel">Reddet</h5>

                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X

                                        </button>

                                    </div>

                                    <div class="modal-body">

                                        <div class="row">

                                            <div class="col-12">

                                                <input type="text" class="form-control" id="red{{ $u->id }}"
                                                    placeholder="Red Nedeni">

                                                <br>

                                                <button type="button" class="btn btn-outline-warning w-100 mt-3"
                                                    onclick="location.href='{{ route('para_cek_yonetim_onayla', [2, $u->id]) }}'+'?red='+$('#red{{ $u->id }}').val()">Reddet

                                                </button>

                                            </div>

                                        </div>





                                    </div>

                                </div>

                            </div>

                        </div>
                    @endif


                        <button onclick="deleteContent('para_cek', {{ $u->id }})" type="button" class="btn btn-outline-danger waves-effect waves-light">
                            <i class="far fa-trash-alt"></i>
                        </button>

                </td>

            </tr>
        @endforeach

    </tbody>

    <tfoot>

        <tr>

            <th>Id</th>

            <th>Talep Eden Kullanıcı</th>

            <th>Tutar</th>
            <th>Kesinti</th>
            <th>Ödeme</th>

            <th>Ödeme Bilgileri</th>

            <th>Açıklama</th>

            <th>Durumu</th>

            <th>İşlem Tarihi</th>

            <th>@lang('admin.aksiyon')</th>

        </tr>

    </tfoot>

</table>
