<?php
if (isset($_GET['oyun'])) {
    $oyun = $_GET['oyun'];
} else {
    $oyun = DB::table('games')->whereNull('deleted_at')->first()->id;
}
?>
<form method="get" autocomplete="off">
    <select name="oyun" onchange="this.form.submit()" class="form-control select2 mt-3 mb-3">
        @foreach(DB::table('games')->whereNull('deleted_at')->get() as $u)
            <option value="{{$u->id}}" @if($oyun==$u->id) selected @endif>{{$u->title}}</option>
        @endforeach
    </select>
</form>
<table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-hover table-bordered nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Oyun İsmi</th>
        <th>Başlık İsmi</th>
        <th>Paket İsmi</th>
        <th>Kalan Stok</th>
        <th>Stok Alış</th>
        <th>Stok Satış</th>
        <th>Stok Değeri</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sorgu = DB::table('games_packages')
        ->select('games_packages.*', 'games_titles.title as gamesTitlesTitle', 'games.title as gameTitle', 'games.link as link')
        ->join('games_titles', 'games_packages.games_titles', '=', 'games_titles.id')
        ->join('games', 'games_titles.game', '=', 'games.id')
        ->where('games.id', $oyun)
        ->orderBy('games_titles.id', 'asc')
        ->whereNull('games_titles.deleted_at')
        ->whereNull('games_packages.deleted_at')
        ->whereNull('games.deleted_at')
        ->get();

    ?>
    @foreach($sorgu as $u)
        <tr>
            <?php
            $stokHam = findRemainingStock($u->id);
            if ($stokHam != 0) {
                $stok = $stokHam;
                $alisDegeri = findValueOfBuyingStock($u->id);
                $satisDegeri = $stok * findGamesPackagesPrice($u->id);
                $kar = $satisDegeri - $alisDegeri;
            } else {
                $stok = 0;
                $alisDegeri = 0;
                $satisDegeri = $stok * findGamesPackagesPrice($u->id);
                $kar = $satisDegeri - $alisDegeri;
            }

            ?>
            <td>({{$u->id}}) {{$u->gameTitle}}</td>
            <td>{{$u->gamesTitlesTitle}}</td>
            <td>{{$u->title}}</td>
            <td>{{$stok}}</td>
            <td>{{number_format($alisDegeri,2)}}</td>
            <td>{{number_format($satisDegeri,2)}}</td>
            <td>{{number_format($kar,2)}}</td>
            <td>{{$u->created_at}}</td>
            <td>
                
                    <i onclick="window.open('{{route('oyun_detay', $u->link)}}')" class="btn far fa-edit"></i>
                
            </td>


        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Oyun İsmi</th>
        <th>Başlık İsmi</th>
        <th>Paket İsmi</th>
        <th>Kalan Stok</th>
        <th>Stok Alış</th>
        <th>Stok Satış</th>
        <th>Stok Değeri</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

