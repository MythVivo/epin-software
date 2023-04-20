@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}"
          rel="stylesheet">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}"
          rel="stylesheet" type="text/css">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/select2/select2.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link type="text/css"
          href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/x-editable/css/bootstrap-editable.css')}}"
          rel="stylesheet">

    <style>
        table.dataTable.nowrap td:nth-child(2) {
            white-space: unset !important;
        }
    </style>
@endsection
@section('body')

    @if(session('success'))
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-success alert-success-shadow" role="alert">
                    <i class="mdi mdi-check-all alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{__('admin.basarili')}}</strong> {{__('admin.basariliMetin')}}
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-danger alert-danger-shadow" role="alert">
                    <i class="mdi mdi-crosshairs alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{__('admin.hata-2')}}</strong>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row" data-lang="{{getLang()}}">

        <div class="col-12 pb-3">
            <div class="card">
                <div class="card-body oyun-toplu-paket">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="topluGuncelle" method="post" action="{{route('oyun_paket_kod_edit_post')}}"
                                  enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                {!! getLangInput() !!}
                            </form>
                            <div class="row" id="bilgiler">
                                <?php
                                $paket = \App\Models\GamesPackages::where('id', $paket)->first();
                                $sorgu = DB::table('games_packages_codes')->where('package_id', $paket->id)->orderBy('created_at', 'DESC')->paginate(20);
                                ?>
                                @foreach($sorgu as $pk)
                                    <div class="col-lg-3 col-md-3 col-12">
                                        <div class="form-group">
                                            @if($loop->iteration == 1)
                                                <label for="111">Id</label>
                                            @endif
                                            <input form="topluGuncelle" name="id" type="text" class="form-control"
                                                   id="111"
                                                   value="{{$pk->id}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-12">
                                        <div class="form-group">
                                            @if($loop->iteration == 1)
                                                <label for="111">Paket</label>
                                            @endif
                                            <input form="topluGuncelle" name="title" type="text" class="form-control"
                                                   id="111"
                                                   value="{{$paket->title}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            @if($loop->iteration == 1)
                                                <label for="444">Kod</label>
                                            @endif
                                            <input form="topluGuncelle" type="text"
                                                   class="form-control"
                                                   id="444" value="{{\epin::DEC($pk->code)}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            @if($loop->iteration == 1)
                                                <label for="222">Alış Fiyatı</label>
                                            @endif
                                            <input form="topluGuncelle" name="alis_fiyati_{{$pk->id}}" type="number"
                                                   class="form-control"
                                                   id="222" step="0.00001"
                                                   value="{{$pk->alis_fiyati}}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            @if($loop->iteration == 1)
                                                <label for="333">Kdv</label>
                                            @endif
                                            <input form="topluGuncelle" name="kdv_{{$pk->id}}" type="number"
                                                   class="form-control"
                                                   id="333" step="0.00001"
                                                   value="{{$pk->kdv}}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            @if($loop->iteration == 1)
                                                <label for="333">Tedarikçi</label>
                                            @endif
                                            <select name="tedarikci_{{$pk->id}}" class="form-control" form="topluGuncelle">
                                                <option value="0">Belirsiz</option>
                                                @foreach(DB::table('games_packages_codes_suppliers')->whereNull('deleted_at')->get() as $uu)
                                                    <option value="{{$uu->id}}"
                                                            @if($uu->id == $pk->tedarikci) selected @endif>{{$uu->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            @if($loop->iteration == 1)
                                                <label for="444">Durum</label>
                                            @endif
                                            @if($pk->is_used == 1)
                                                <input form="topluGuncelle" type="text"
                                                       class="form-control border-danger"
                                                       id="444" value="Kullanılmış" readonly>
                                            @else
                                                <input form="topluGuncelle" type="text"
                                                       class="form-control border-success"
                                                       id="444" value="Kullanılmamış" readonly>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-group">
                                            @if($loop->iteration == 1)
                                                <label for="555">Eklenme Tarihi</label>
                                            @endif
                                            <input form="topluGuncelle" type="text"
                                                   class="form-control"
                                                   id="555" value="{{$pk->created_at}}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-12"></div>

                                @endforeach
                                <div class="col-md-12 text-center">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item">
                                                <a class="page-link" href=?page=1">{{"|<<"}}</a>
                                            </li>
                                            <?php
                                            $prevPage = $sorgu->currentPage() - 1;
                                            if ($prevPage < '1') {
                                                $prevPage = '1';
                                            }
                                            ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                   href="?page={{$prevPage}}">{{"<"}}</a>
                                            </li>
                                            @for($i = 1; $i < $sorgu->lastPage()+1; $i++)
                                                <li class="page-item @if($sorgu->currentPage() == $i) active @endif">
                                                    <a class="page-link" href="?page={{$i}}">{{$i}}</a></li>
                                            @endfor
                                            <?php
                                            $nextPage = $sorgu->currentPage() + 1;
                                            if ($nextPage > $sorgu->lastPage()) {
                                                $nextPage = $sorgu->lastPage();
                                            }
                                            ?>
                                            <li class="page-item">
                                                <a class="page-link"
                                                   href="?page={{$nextPage}}">{{">"}}</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link"
                                                   href="?page={{$sorgu->lastPage()}}">{{">>|"}}</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>

                                <?php /*
                                    <div class="col-12">
                                        <div class="spinner-grow text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-secondary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-success" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-danger" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-warning" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-info" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-light" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-dark" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        Yükleniyor... Lütfen bekleyiniz
                                    </div>
                                    */ ?>
                            </div>

                            <input type="hidden" form="topluGuncelle" name="paketId" value="{{$paket->id}}">

                            <button type="submit" form="topluGuncelle"
                                    class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        /*
         * Bilgiler Ajax

        $.ajax({
            type: 'GET', url: "{{route('panelEpinler')}}",
        success: function (data) {
            $("#bilgiler").html(data);
        }
    });
    */
    </script>
@endsection

