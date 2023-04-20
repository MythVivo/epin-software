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
                            $date1 = date('Y-m-d', strtotime('-5 days'));
                            $date2 = date('Y-m-d');
                            ?>
                        @endif
                        @if(session('durum'))
                            @php
                                $durum = session('durum')
                            @endphp
                        @else
                            @php
                                $durum = 0;
                            @endphp
                        @endif
                        <?php
                        $siparisler = DB::table('game_gold_satis')->whereDate('created_at', '>=', $date1)->whereDate('created_at', '<=', $date2)->where('user', Auth::user()->id)->whereNull('deleted_at')->orderBy('created_at');
                        ?>
                        @if($siparisler->count() > 0)
                            <div class="col-12 mb-4">
                                <select class="form-control style-select" id="table-filter">
                                    <option value="" @if($durum == 0) selected @endif>Tümü</option>
                                    <option value="Alış İşlemi" @if($durum == 2) selected @endif>Alış İşlemi</option>
                                    <option value="Satış İşlemi" @if($durum == 1) selected @endif>Satış İşlemi</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <table id="datatable" class="table table-hover table-striped ">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Paket</th>
                                        <th>Nickname</th>
                                        <th>İşlem Tutarı</th>
                                        <th>Adet</th>
                                        <th>İşlem Türü</th>
                                        <th>İşlem Durumu</th>
                                        <th>Tarih</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($siparisler->get() as $u)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{DB::table('games_packages_trade')->where('id', $u->paket)->first()->title}}</td>
                                            <td>{{$u->note}}</td>
                                            <td>{{$u->price}} TL</td>
                                            <td>{{$u->adet}}</td>
                                            <td>
                                                @if($u->tur == 'bizden-al')
                                                    Alış İşlemi
                                                @else
                                                    Satış İşlemi
                                                @endif
                                            </td>
                                            <td @if($u->tur == "bize-sat" and $u->status == 0) class="text-danger islemDurumu"
                                                @endif data-id="{{$u->id}}">
                                                @if($u->tur == "bize-sat")
                                                    @if($u->teslim_nick == NULL)
                                                        {!! findGameGoldStatus($u->status, "bize-sat") !!}
                                                    @else
                                                        {!! findGameGoldStatus($u->status, "bize-sat", $u->teslim_nick) !!}
                                                    @endif
                                                @else
                                                    {!! findGameGoldStatus($u->status) !!}
                                                @endif
                                            </td>
                                            <td>{{$u->created_at}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Paket</th>
                                        <th>Nickname</th>
                                        <th>İşlem Tutarı</th>
                                        <th>Adet</th>
                                        <th>İşlem Türü</th>
                                        <th>İşlem Durumu</th>
                                        <th>Tarih</th>
                                    </tr>
                                    </tfoot>
                                </table>
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
            var table = $('#datatable').DataTable({
                columnDefs: [{orderable: false, targets: [6]}],
                //dom: 'lrtip',
                lengthChange: false,
                pageLength: 10,
                "order": [[7, "desc"]],
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
                },
            });
            $('#table-filter').on('change', function () {
                table.search(this.value).draw();
            });
            $('#datatable').dataTable().fnFilter($("#table-filter").val());


            $(".islemDurumu").each(function (index, value) {
                var gelenMetin = $(".islemDurumu")[index].innerHTML;
                var ses = $(".ses");
                if (ses.length > 0) {
                    if ($(".ses")[0]) {
                        var notifySound = new Audio('https://widget-v4.tidiochat.com//tururu.mp3');
                        notifySound.loop = false;

                        function updateSound() {
                            notifySound.play();
                        }

                        var loopCount = 1;
                        var fonksiyon = setInterval(function () {
                            updateSound();
                            if (loopCount > 1) {
                                clearInterval(fonksiyon);
                            }
                            loopCount++;
                        }, 1500);
                    }
                } else {
                    var fonksiyon = setInterval(function () {
                        var id = $(".islemDurumu")[index].getAttribute('data-id');
                        $.get("{{route('game_gold_durum_sorgula')}}?id=" + id, function (data) {
                            $(".islemDurumu")[index].innerHTML = data;
                            if (data.indexOf('audio') > -1) {
                                clearInterval(fonksiyon);
                                var notifySound = new Audio('https://widget-v4.tidiochat.com//tururu.mp3');
                                notifySound.loop = false;
                                function updateSound() {
                                    notifySound.play();
                                }
                                var loopCount = 1;
                                var fonksiyon2 = setInterval(function () {
                                    updateSound();
                                    if (loopCount > 1) {
                                        clearInterval(fonksiyon2);
                                    }
                                    loopCount++;
                                }, 1500);
                            }
                        });
                    }, 2000);
                }
            });

        })
        ;
    </script>
@endsection
