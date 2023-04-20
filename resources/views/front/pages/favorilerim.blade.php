@extends('front.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
@endsection
@section('body')
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @if(session('success'))
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">{{session('success')}}</h4>
                        </div>
                    @endif
                </div>
                @include('front.modules.user-menu')
                <div class="col-md-9">
                    <div class="row">
                        <?php
                        $favoriler = DB::table('favoriler')->where('user', Auth::user()->id)->whereNull('deleted_at')->orderBy('created_at', 'desc');
                        ?>
                        @if($favoriler->count() > 0)
                            <div class="col-12">
                                <table id="datatable" class="table table-hover table-striped ">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Başlık</th>
                                        <th>Fiyat</th>
                                        <th>Favori Türü</th>
                                        <th>Favori Eklenme Tarihi</th>
                                        <th>İşlem</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($favoriler->get() as $u)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            @if($u->type == 1)
                                                @php
                                                    $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $u->favoriId)->whereNull('deleted_at');
                                                @endphp
                                                @if($ilan->count() > 0)
                                                    @php
                                                        $item = DB::table('games_titles')->where('id', $ilan->first()->pazar)->first();
                                                    @endphp
                                                    <td>
                                                        <a href="{{route('item_ic_detay', [$item->link, $ilan->first()->sunucu, Str::slug($ilan->first()->title).'-'.$ilan->first()->id])}}">
                                                            {{$ilan->first()->title}}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{$ilan->first()->price}}
                                                    </td>
                                                    <td>Satış İlanı</td>
                                                @else
                                                    <td>Eski Satış İlanı</td>
                                                    <td>XXX TL</td>
                                                    <td>Satış İlanı</td>
                                                @endif
                                            @endif
                                            @if($u->type == 2)
                                                @php
                                                    $ilan = DB::table('pazar_yeri_ilanlar_buy')->where('id', $u->favoriId)->whereNull('deleted_at');
                                                @endphp
                                                @if($ilan->count() > 0)
                                                    @php
                                                        $item = DB::table('games_titles')->where('id', $ilan->first()->pazar)->first();
                                                    @endphp
                                                    <td>
                                                        <a href="{{route('item_buy_ic_detay', [$item->link, $ilan->first()->sunucu, Str::slug($ilan->first()->title).'-'.$ilan->first()->id])}}">
                                                            {{$ilan->first()->title}}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{$ilan->first()->price}}
                                                    </td>
                                                    <td>Alış İlanı</td>
                                                @else
                                                    <td>Eski Alış İlanı</td>
                                                    <td>XXX TL</td>
                                                    <td>Alış İlanı</td>
                                                @endif
                                            @endif
                                            <td>{{$u->created_at}}</td>
                                            <td>
                                                <button class="btn-inline color-red small confirm-btn"
                                                        confirm-data="{{route('favori_kaldir', [$u->type, $u->favoriId])}}">
                                                    Kaldır
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Başlık</th>
                                        <th>Fiyat</th>
                                        <th>Favori Türü</th>
                                        <th>Favori Eklenme Tarihi</th>
                                        <th>İşlem</th>
                                    </tr>
                                    </tfoot>

                                </table>
                            </div>
                        @else
                            <div class="col-12">

                                <div class="alert alert-danger fade show d-flex align-items-center"
                                     role="alert">
                                    <h5>
                                        Henüz bir favoriniz bulunmuyor. İlk favorinizi ekledikten sonra bu ekrandan
                                        favorileriniz
                                        hakkında bilgi alabilirsiniz.
                                    </h5>
                                </div>
                            </div>


                        @endif

                    </div>
                </div>


            </div>
        </div>
    </section>
@endsection
@section('js')
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#datatable').DataTable({
                columnDefs: [{orderable: false, targets: [5]}],
                pageLength: 10,
                "order": [[4, "desc"]],
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
@endsection
