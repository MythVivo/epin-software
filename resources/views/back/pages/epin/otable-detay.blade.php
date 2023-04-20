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
<table lang="{{getLang()}}" id="datatable" class="font-12 table-bordered table-hover nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Id</th>
        <th>Paket</th>
        <th>Satış Fiyatı</th>
        <th>Alış Fiyatı</th>
        <th>Kdv</th>
        <th>Adet</th>
        <th>Tarih</th>
    </tr>
    </thead>
    <tbody>
    <?php

    $sorgu = DB::table('epin_satis_kodlar')->select(['epin_satis','code'])->get();


    $uAll = DB::table('epin_satis')->whereIn('id', $sorgu->pluck('epin_satis'))->whereDate('created_at', '>=', $date1)->whereDate('created_at', '<=', $date2)->orderBy('id')->whereNull('deleted_at')->get();


    $packageAll = DB::table('games_packages')->whereIn('id', $uAll->pluck('paketId'))->get();
 
    $codeAll = DB::table('games_packages_codes')->whereIn('code', $sorgu->pluck('code'))->get();

 
    ?>
    @foreach($sorgu as $uu)
        <?php
        if($u = $uAll->where('id',$uu->epin_satis)->first()) {
        ?>
        <tr>
            <td>{{$u->id}}</td>
            <td>
                @if($package = $packageAll->where('id',$u->paketId)->first())
                    {{$package->title}}
                @else
                    Paket Bulunamadı
                @endif
            </td>
            <td align='center'>{{$u->price / $u->adet}}</td>
            <?php
        
            if($code = $codeAll->where('code',$uu->code)->first()) {
            ?>
            <td align='center'>{{$code->alis_fiyati}}</td>
            <td align='center'>{{$code->kdv}}</td>
            <?php } else { ?>
            <td align='center'>0</td>
            <td align='center'>0</td>
            <?php } ?>
            <td align='center'>1</td>
            <td>
                {{$u->created_at}}
            </td>
        </tr>
        <?php } ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Id</th>
        <th>Paket</th>
        <th>Satış Fiyatı</th>
        <th>Alış Fiyatı</th>
        <th>Kdv</th>
        <th>Adet</th>
        <th>Tarih</th>
    </tr>
    </tfoot>
</table>

