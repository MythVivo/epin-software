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
                                    <label class="form-label" for="userinput1">İlk Tarih</label>
                                    <div class="col-sm-9">
                                        <input type="date" id="userinput1" class="form-control style-input" name="date1"
                                               value="{{$date1}}" required>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-12 col-md-5">
                                <div class="mb-3 row">
                                    <label class="form-label" for="userinput2">Son Tarih</label>
                                    <div class="col-sm-9">
                                        <input type="date" id="userinput2" class="form-control style-input" name="date2"
                                               value="{{$date2}}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-2 d-flex justify-content-sm-start justify-content-md-end align-items-center">

                                <button type="submit" class="mt-1 btn-inline color-blue">Sorgula</button>


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
                        $siparisler = DB::table('muve_games_satis')->whereDate('created_at', '>=', $date1)->whereDate('created_at', '<=', $date2)->where('user', Auth::user()->id)->orderBy('created_at');
                        ?>
                        @if($siparisler->count() > 0)
                            <table id="datatable" class="table table-hover table-striped ">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Oyun</th>
                                    <th>İşlem Tutarı</th>
                                    <th>Adet</th>
                                    <th>İşlem Durumu</th>
                                    <th>Tarih</th>
                                    <th>Detay</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($siparisler->get() as $u)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{DB::table('muve_games')->where('id', $u->gameId)->first()->title}}</td>
                                        <?php /*@if($u->transId == 0)
                                            <td>{{DB::table('games_packages')->where('id', $u->paketId)->first()->title}}</td>
                                        @else
                                            @if(DB::table('games_packages_epin')->where('epinPaket', $u->paketId)->count() > 0)
                                                <td>{{DB::table('games_packages_epin')->where('epinPaket', $u->paketId)->first()->title}}</td>
                                            @else
                                                <td>{{findApiGameProduct($u->game_title, $u->paketId)}}</td>
                                            @endif
                                        @endif */
                                        ?>
                                        <td>{{$u->price}} TL</td>
                                        <td>{{$u->adet}}</td>
                                        <td>
                                            @if($u->status == 0)
                                                Siparişiniz İşleniyor
                                            @elseif($u->status == 1)
                                                Siparişiniz Başarılı
                                            @else
                                                {{$u->note}}
                                            @endif
                                        </td>
                                        <td>{{$u->created_at}}</td>
                                        <td>
                                            @if($u->status == 1)
                                                <button class="btn btn-outline-success" data-bs-toggle="modal"
                                                        data-bs-target="#detay{{$u->id}}">Detay
                                                </button>
                                            @else
                                                <button class="btn btn-outline-warning" style="cursor: not-allowed;">
                                                    Detay
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="detay{{$u->id}}" tabindex="-1"
                                         aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                        {{DB::table('muve_games')->where('id', $u->gameId)->first()->title}}
                                                        Ait Kodlarınız
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">

                                                                <div class="col-12 mb-4">
                                                                    <h5 class="card-title clipboard"><span class="code">
                                                                            {{ DB::select("select * from muve_keys where trans='$u->transId' and user= ?",[Auth::user()->id])[0]->mkey }}
                                                                        </span><span class="btn-inline color-darkgreen cpy-code">Kodu Kopyala</span> </h5>
                                                                  </div>

{{--                                                                <?php--}}
{{--                                                                $ch = curl_init();--}}
{{--                                                                $headers = array(--}}
{{--                                                                    'Authorization: Bearer ' . muveAuth(),--}}
{{--                                                                );--}}
{{--                                                                curl_setopt($ch, CURLOPT_URL, env('MUVE_BASE_URL') . '/api/orders/' . $u->transId . '/keys');--}}
{{--                                                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);--}}
{{--                                                                curl_setopt($ch, CURLOPT_HEADER, 0);--}}
{{--                                                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");--}}
{{--                                                                curl_setopt($ch, CURLOPT_POSTFIELDS, '');--}}
{{--                                                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);--}}
{{--                                                                curl_setopt($ch, CURLOPT_TIMEOUT, 30);--}}
{{--                                                                $response = curl_exec($ch);--}}
{{--                                                                $result_game = json_decode($response);--}}
{{--                                                                curl_close($ch);--}}
{{--                                                                if ($result_game->code == 200) {--}}
{{--                                                                    $bilgiler = $result_game->data;--}}
{{--                                                                } else {--}}
{{--                                                                    $bilgiler = array();--}}
{{--                                                                }--}}
{{--                                                                ?>--}}
{{--                                                                @foreach($bilgiler as $bilgi)--}}
{{--                                                                        <div class="col-12 mb-4">--}}
{{--                                                                            <h5 class="card-title clipboard"><span class="code">{{$bilgi->key_value}}</span><span class="btn-inline color-darkgreen cpy-code">Kodu Kopyala</span> </h5>--}}
{{--                                                                        </div>--}}

{{--                                                                @endforeach--}}

                                                            </div>
                                                        </div>
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
                                    <th>Oyun</th>
                                    <th>İşlem Tutarı</th>
                                    <th>Adet</th>
                                    <th>İşlem Durumu</th>
                                    <th>Tarih</th>
                                    <th>Detay</th>
                                </tr>
                                </tfoot>

                            </table>

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
                                            onclick="location.href='{{route('cd_key')}}'">
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

    </script>
    <script>
        $(document).ready(function () {
            @if(session('show'))
            var modalSayi = $(".modal").length;
            var name = $(".modal").eq(modalSayi - 1)[0].id;
            $("#" + name).modal("show");
            @endif
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
