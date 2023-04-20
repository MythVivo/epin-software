@extends('front.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <style>
		.modal {
			z-index: 9999;
		}
    </style>
@endsection
@section('body')
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">
                @include('front.modules.user-menu')
                <div class="col-md-9">
                    {{-- @if(isset($_GET['date1']) and isset($_GET['date2']))
                        <?php
                        $date1 = $_GET['date1'];
                        $date2 = $_GET['date2'];
                        ?>
                    @else
                        <?php
                        $date1 = date('Y-m-d', strtotime('-30 days'));
                        $date2 = date('Y-m-d');
                        ?>
                    @endif --}}
                    <form method="get">
                        <div class="row mb-3">

                            <div class="col-sm-12 col-md-5">
                                <div class="mb-3 row">

                                    <div class="col-sm-9">
                                        <label class="form-label" for="userinput1">İlk Tarih</label>
                                        <input type="date" id="userinput1" class="form-control style-input" name="date1"
                                               value="{{$date1}}" required>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-12 col-md-5">
                                <div class="mb-3 row">

                                    <div class="col-sm-9">
                                        <label class="form-label" for="userinput2">Son Tarih</label>
                                        <input type="date" id="userinput2" class="form-control style-input" name="date2"
                                               value="{{$date2}}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-2 d-flex justify-content-sm-start justify-content-md-end align-items-center">

                                <button type="submit" class="mt-1 btn-inline color-blue w-100">Sorgula
                                </button>

                            </div>

                        </div>
                    </form>
                    <div class="row">
                        @if(isset($_GET['date1']) and isset($_GET['date2']))
                            <?php
                            $date1 = $_GET['date1'];
                            $date2 = $_GET['date2'];
                            ?>
                        @else
                            <?php
                            $date1 = date('Y-m-d', strtotime('-30 days'));
                            $date2 = date('Y-m-d');
                            ?>
                        @endif
                        <?php
                        $siparisler = DB::table('pazar_yeri_ilan_satis')->whereDate('created_at', '>=', $date1)->whereDate('created_at', '<=', $date2)->where('satin_alan', Auth::user()->id)->orderBy('created_at');
                        ?>
                        @if($siparisler->count() > 0)
                            <div class="col-12">
                                <div class="table-col">
                                    <table id="datatable" class="table table-hover table-striped nowrap ">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>İlan No</th>
                                            <th>Pazar</th>
                                            <th>Başlık</th>
                                            <th>Sunucu</th>
                                            <th>Karakter Adı</th>
                                            <th>Fiyat</th>
                                            <th>İşlem Durumu</th>
                                            <th>Tarih</th>
                                            <th>İçerik</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($siparisler->get() as $u)
                                            <?php
                                            $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $u->ilan)->first();
                                            $item = DB::table('games_titles')->where('id', $ilan->pazar)->first();
                                            ?>
                                            
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$u->ilan}}</td>
                                                <td>{{ $ilan->pazar == 7? 'Knight Online Item': ($ilan->pazar == 9? 'Knight Online Cypher Ring': DB::table('games_titles')->where('id', $ilan->pazar)->first()->title) }}
                                                <td>
                                                    <a href="{{route('item_ic_detay', [$item->link, $ilan->sunucu, Str::slug($ilan->title).'-'.$ilan->id])}}"
                                                       target="_blank">{{DB::table('pazar_yeri_ilanlar')->where('id', $u->ilan)->first()->title}}</a>
                                                </td>
                                                <td>{{ ucfirst($ilan->sunucu) }}</td>
                                                <td>{{$u->note}}</td>
                                                <td>{{$u->price}} TL</td>
                                                <td>{{findIlanStatus(DB::table('pazar_yeri_ilanlar')->where('id', $u->ilan)->first()->status)}}</td>
                                                <td>{{$u->created_at}}</td>
                                                <td>
                                                    <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#detay{{$u->id}}">
                                                        <i class="far fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="detay{{$u->id}}" tabindex="-1"
                                                 aria-labelledby="exampleModalLabel"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable item-siparis-modal">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                {{DB::table('pazar_yeri_ilanlar')->where('id', $u->ilan)->first()->title}}
                                                                ilan içeriği
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="row">
                                                                @if($ilan->toplu == 1)
                                                                    <div class="card-column mt-3">
                                                                        <h3 class="card-title">İlan İçeriği</h3>
                                                                        @foreach(DB::table('pazar_yeri_ilan_toplu')->where('toplu', $ilan->id)->get() as $t)
                                                                            <?php
                                                                            $ilans = DB::table('pazar_yeri_ilanlar')->where('id', $t->ilan)->first();
                                                                            $item = DB::table('games_titles')->where('id', $ilans->pazar)->first();
                                                                            ?>
                                                                            <p class="card-text">
                                                                                <a href="{{route('item_ic_detay', [$item->link, $ilans->sunucu, Str::slug($ilans->title).'-'.$ilans->id])}}"
                                                                                   target="_blank">
                                                                                    {{mb_substr($ilans->title, 0, 30)}}
                                                                                    @if(strlen($ilans->title) > 30)
                                                                                        ...
                                                                                    @endif
                                                                                </a>
                                                                            </p>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    @if($ilan->type == 0)
                                                                        @foreach(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->ilan)->get() as $a)
                                                                            <?php
                                                                            $item_info = DB::table('games_titles_items_info')->where('id', $a->item)->first();
                                                                            $item_photo = DB::table('games_titles_items_photos')->where('item', $a->item)->get();
                                                                            ?>
                                                                            <div class="col-12 ilan-siparis">

                                                                                <div class="card mb-3">
                                                                                    @foreach($item_photo as $ip)
                                                                                        <img src="{{asset('public/front/games_items/' . $ip->image)}}"
                                                                                             class="card-img-top"
                                                                                             alt="{{$item_info->title}} görseli">
                                                                                    @endforeach
                                                                                    <div class="card-body">
                                                                                        <h5 class="card-title">{{$item_info->title}}</h5>
                                                                                        <p class="card-text">{{$item_info->description}}</p>
                                                                                        <ul class="list-group list-group-flush">
                                                                                            @foreach(DB::table('games_titles_features')->where('game_title', DB::table('pazar_yeri_ilanlar')->where('id', $u->ilan)->first()->pazar)->whereNull('deleted_at')->get() as $i)
                                                                                                <?php
                                                                                                $value = DB::table('games_titles_items')->where('item', $a->item)->where('feature', $i->id)->first()->value;
                                                                                                ?>
                                                                                                <li class="list-group-item">
                                                                                                    <p>{{$i->title}}
                                                                                                        :
                                                                                                        @if($value == "0")
                                                                                                            @if($i->title == 'Sunucu')
                                                                                                                {{DB::table('pazar_yeri_ilanlar')->where('id', $u->ilan)->first()->sunucu}}
                                                                                                            @endif
                                                                                                        @else
                                                                                                            {{$value}}
                                                                                                        @endif
                                                                                                    </p>
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <div class="col-md-12">
                                                                            <div class="card mb-3">
                                                                                <div class="row">
                                                                                    <div class="col-md-4">
                                                                                        <img src="{{asset('public/front/ilanlar/' . $ilan->image)}}"
                                                                                             class="card-img-top"
                                                                                             alt="{{$ilan->title}} görseli">
                                                                                    </div>
                                                                                    <div class="col-md-8">
                                                                                        <div class="card-body">
                                                                                            <h5 class="card-title">{{$ilan->title}}</h5>
                                                                                            <p class="card-text">{{$ilan->text}}</p>
                                                                                        </div>
                                                                                        <ul class="list-group list-group-flush">
                                                                                            @foreach(DB::table('games_titles_features')->where('game_title', $ilan->pazar)->whereNull('deleted_at')->get() as $i)
                                                                                                <li class="list-group-item">{{$i->title}}
                                                                                                    :
                                                                                                    {{DB::table('pazar_yeri_ilan_features')->where('ilan', $ilan->id)->where('feature', $i->id)->first()->value}}
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>


                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Kapat
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>İlan No</th>
                                            <th>Pazar</th>
                                            <th>Başlık</th>
                                            <th>Sunucu</th>
                                            <th>Karakter Adı</th>
                                            <th>Fiyat</th>
                                            <th>İşlem Durumu</th>
                                            <th>Tarih</th>
                                            <th>İçerik</th>
                                        </tr>
                                        </tfoot>

                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                <div class="alert alert-danger  fade show d-flex align-items-center"
                                     role="alert">
                                    <i class="fas fa-exclamation-triangle me-3"></i>
                                    <h5>
                                        Henüz bir siparişiniz bulunmuyor. İlk siparişinizi verdikten sonra bu ekrandan
                                        siparişiniz
                                        hakkında bilgi alabilirsiniz.
                                    </h5>

                                    <button type="button" class="btn-inline color-blue border"
                                            onclick="location.href='{{route('oyunlarTum')}}'">
                                        Oyunlar
                                    </button>
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
                columnDefs: [{orderable: false, targets: [6]}],
                pageLength: 10,
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
@endsection
