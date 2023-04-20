<div class="col-md-12 mb-4 mt-4">
    @if(isset($_GET['date1']) and isset($_GET['date2']))
        <?php $date1 = $_GET['date1']; $date2 = $_GET['date2']; ?>
    @else
        <?php $date1 = date('Y-m-d', strtotime('-0 days')); $date2 = date('Y-m-d'); ?>
    @endif
    <form class="row mb-3" method="get">
        <div class="col-sm-12 col-md-5">
            <div class="mb-3">
                <label class="form-label" for="userinput1">İlk Tarih</label>
                <input type="date" id="userinput1" class="form-control style-input" name="date1" value="{{$date1}}" required>
            </div>
        </div>
        <div class="col-sm-12 col-md-5">
            <div class="mb-3">
                <label class="form-label" for="userinput2">Son Tarih</label>
                <input type="date" id="userinput2" class="form-control style-input" name="date2" value="{{$date2}}" required>
            </div>
        </div>
        <div class="col-sm-12 col-md-2 d-flex justify-content-sm-start justify-content-md-end align-items-center">
            <button type="submit" class="btn btn-outline-success btn-block mt-2 color-blue">Sorgula</button>
        </div>
    </form>
</div>

<table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-bordered table-hover nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead><tr><th>Id</th><th>Yapan Kullanıcı</th><th>Oyun</th><th>Paket</th><th>İşlem Tutarı</th><th>Adet</th><th>İşlem Durumu</th><th>Tarih</th><th>Detay</th></tr></thead>
    <tbody>
    <?php
    //$sorgu = DB::table('epin_satis')->whereDate('created_at', '>=', $date1)->whereDate('created_at', '<=', $date2)->whereNull('deleted_at')->get();
    $sorgu = DB::select("select es.*, u.name,u.email,gp.title pid, gt.title gtit
    from epin_satis es
    left join users u on u.id=es.user
    left join games_packages gp on gp.id=es.paketId
    left join games_titles gt on gt.id=es.game_title
    where date(es.created_at) between '$date1' and '$date2'
    and es.deleted_at is null
    ");
    ?>
    @foreach($sorgu as $u)
        <tr>
            <td>{{$u->id}}</td>
            <td><a href="{{route('uye_detay', [$u->email])}}" target="_blank">{{$u->name}}</a></td>
            <td>@if($u->gtit) {{$u->gtit}} @else Başlık Bulunamadı @endif</td>
            <td>@if($u->pid){{$u->pid}} @else Paket Bulunamadı @endif</td>
            <td style="text-align: center">{{$u->price}}</td>
            <td style="text-align: center">{{$u->adet}}</td>
            <td>
                @if($u->status == 0) Sipariş İşleniyor
                @elseif($u->status == 1) Sipariş Başarılı
                @else Sipariş İptal Edildi
                @endif
            </td>
            <td>{{$u->created_at}}</td>
            <td style="text-align: center"><i id="#detay_{{$u->id}}" class="btn fa fa-eye goster"></i></td>
        </tr>
    @endforeach
    </tbody>
</table>
