<div class="col-md-12 mb-4 mt-4">
    @if(isset($_GET['date1']) and isset($_GET['date2']))
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
                       value="{{$date1}}" required>
            </div>
        </div>


        <div class="col-sm-12 col-md-5">
            <div class="mb-3">
                <label class="form-label" for="userinput2">Son Tarih</label>
                <input type="date" id="userinput2" class="form-control style-input" name="date2"
                       value="{{$date2}}" required>
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
        <option value="Diğer Ödeme|Kripto Para" selected>Manuel İşlemler</option>
        <option value="Ozan|Online Ödeme|PayTR Kredi / Banka Kartı|Gpay Kredi / Banka Kartı|Hediye Kodu|Havale / EFT|Manuel Bakiye Ekleme|Manuel Bakiye Çıkarma|Papara|Bkm Express|İninal|PayTR Yurtdışı Kredi / Banka Kartı|Gpay Yurtdışı Kredi / Banka Kartı|Gpay Havale / EFT">
            Otomatik İşlemler
        </option>
    </select>
</div>

<div class="col-12 mb-4">
    <table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-hover table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
        <tr>
            <th>Id</th>
            <th>Kullanıcı</th>
            <th>Tutar</th>
            <th>Ödeme Kanalı</th>
            <th>Durumu</th>
            <th>Açıklama</th>
            <th>İşlem Tarihi</th>
            <th>@lang('admin.aksiyon')</th>
        </tr>
        </thead>
        <tbody>
        <?php
        #$sorgu = DB::table('odemeler')->whereDate('created_at', '>=', $date1)->whereDate('created_at', '<=', $date2)->whereNull('deleted_at')->get();
        $sorgu =DB::select("SELECT *, (select timestampdiff(minute,created_at, (select now()))) sure FROM odemeler where isnull(deleted_at) and date(created_at) BETWEEN '$date1' and '$date2' order by id desc");
        ?>
        @foreach($sorgu as $u)
        <?
        if(720-$u->sure<1) {DB::select("update odemeler set status=2 where id='$u->id' and status=0");}
        ?>
            <tr>
                <td>{{$u->id}}</td>
                <td>
                    <?php $uye = DB::table('users')->where('id', $u->user)->first(); ?>
                    <a href="{{route('uye_detay', [$uye->email])}}" target="_blank">
                        {{$uye->name}}
                    </a>
                </td>
                <td>{{$u->amount}}</td>
                <td class="text-center">
                    {{findPaymentChannel($u->channel)}}
                </td>
                <td>
                    @if($u->status == 0)
                        Onay Aşamasında <i class="text-danger" title="Otomatik red için kalan süre">(<?=720-$u->sure<1?'RED':720-$u->sure?>) dk</i>
                    @elseif($u->status == 1)
                        Onaylandı
                    @elseif($u->status == 2)
                        Ödeme İşlemi İptal Edildi
                    @else
                        Bir Sorun Oluştu
                    @endif
                </td>
                <td>
                    @if($u->channel == 2)
                        @if($u->status == 0)
                            <?php
                            $banka = DB::table('payment_channels_eft')->where('id', $u->description)->first();
                            ?>
                            {{$banka->title}}
                        @else
                            {{$u->description}}
                        @endif
                    @elseif($u->channel == 6)
                        @if($u->status == 0)
                            <?php $para = DB::table('payment_channels_crypto')->where('id', $u->description)->first(); ?>
                            {{$para->title}}
                        @endif
                    @else
                        {{$u->description}}
                    @endif
                </td>
                <td>
                    {{$u->created_at}}
                </td>
                <td nowrap>
                    @if($u->status == 0)
                            <i ttr="{{number_format($u->amount,2,',','.')}}" id="{{$u->id}}" class="btn fas fa-check onay"></i>
                            <i id="{{$u->id}}" class="btn fas fa-times red"></i>
                    @endif
                    @if(userRoleIsAdmin(Auth::user()->id))
                            <i onclick="deleteContent('odemeler', {{$u->id}})" class="btn far fa-trash-alt"></i>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Kullanıcı</th>
            <th>Tutar</th>
            <th>Ödeme Kanalı</th>
            <th>Durumu</th>
            <th>Açıklama</th>
            <th>İşlem Tarihi</th>
            <th>@lang('admin.aksiyon')</th>
        </tr>
        </tfoot>
    </table>
</div>
