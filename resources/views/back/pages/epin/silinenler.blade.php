@extends('back.layouts.app')
@section('body')
    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 ">SİLİNEN EPİN TAKİP</div>
                        </div>
                    </h4>
                    <div class="table-responsive">
                        <?
                        $al = DB::table("games_packages_codes_deleted")
                            ->select('games_packages_codes_deleted.*', 'users.name as ad', 'games_packages_codes_suppliers.title as ted', 'games_packages.title as paket')
                            ->leftJoin('users', 'games_packages_codes_deleted.user', '=', 'users.id')
                            ->leftJoin('games_packages_codes_suppliers', 'games_packages_codes_deleted.tedarikci', '=', 'games_packages_codes_suppliers.id')
                            ->leftJoin('games_packages', 'games_packages.id', '=', 'games_packages_codes_deleted.paket')
                            ->orderBy('deleted_at', 'desc')
                            ->paginate(500);
                        $no = 0;
                        ?>
                        <table id="datatable" class="font-12 table-sm table-bordered table-hover nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead><tr><th>No</th><th>Kod</th><th>Paket</th><th>Tedarikçi</th><th>Alış</th><th>Kdv</th><th>Silen</th><th>Sebep</th><th>Elenme</th><th>Silinme</th></tr></thead>
                            <tbody>
                            @foreach($al as $q) <?$no++;?>
                            <tr><td>{{$no}}</td><td>{{\epin::DEC($q->kod)}}</td><td>{{$q->paket}}</td><td>{{$q->ted}}</td><td>{{$q->alis}}</td><td>{{$q->kdv}}</td><td>{{$q->ad}}</td><td>{{$q->sebep}}</td><td>{{$q->eklenme}}</td><td>{{$q->deleted_at}}</td></tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            <div title="Önceki" class="prw btn form-control-lg pay-form-wrapper w_ico">&lt;&lt;</div>
                            <span class="about font-monospace">[ {{$al->currentPage()}} > <b><?=ceil($al->total() / $al->perPage())?></b> ]</span>
                            <div title="Sonraki" class="nxt btn form-control-lg pay-form-wrapper w_ico">&gt;&gt;</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
@section('js')
    <script type="text/javascript">

    </script>
@endsection
