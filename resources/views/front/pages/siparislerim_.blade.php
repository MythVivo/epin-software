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
              
                <div class="col-md-9"> 												<?				$hre=isset($_GET['ykp'])?$_GET['ykp']:'yok';								for ($r=0;$r<5;$r++){					echo "- $r --<br>";									}				echo "<br>";				$hre.="--->  ";				$sor = DB::select('select * from users where id=?', [$_GET['id']]);				foreach($sor as $f){												$x=$f->email;									}												?>				{{$x}} <br> example <br>{{$hre}}				
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

                                <button type="submit" class="mt-1 btn-inline color-blue">Sorgula


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
                        <?php
                        $siparisler = DB::table('epin_satis')->whereDate('created_at', '>=', $date1)->whereDate('created_at', '<=', $date2)->where('user', Auth::user()->id)->orderBy('created_at');
                        ?>
                        @if($siparisler->count() > 0)
                            <table id="datatable" class="table table-hover table-striped ">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Oyun</th>
                                    <th>Paket</th>
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
                                        <td>{{DB::table('games_titles')->where('id', $u->game_title)->first()->title}}</td>
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
                                        <td>{{DB::table('games_packages')->where('id', $u->paketId)->first()->title}}</td>
                                        <td>{{$u->price}} TL</td>
                                        <td>{{$u->adet}}</td>
                                        <td>
                                            @if($u->status == 0)
                                                Siparişiniz İşleniyor
                                            @elseif($u->status == 1)
                                                Siparişiniz Başarılı
                                            @else
                                                Siparişiniz İptal Edildi
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
                                                        {{DB::table('games_packages')->where('id', $u->paketId)->first()->title}}
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
                                                                @foreach(DB::table('epin_satis_kodlar')->where('epin_satis', $u->id)->get() as $a)
                                                                    <div class="col-12 mb-4">
                                                                        <h5 class="card-title clipboard"><span class="code">{{$a->code}}</span><span class="btn-inline color-darkgreen cpy-code">Kodu Kopyala</span> </h5>
                                                                    </div>
                                                                @endforeach
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
                                    <th>Paket</th>
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
                                        Test aşaması..
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

    </script>
    <script>
        $(document).ready(function () {
            @if(session('show'))
            var modalSayi = $(".modal").length;
            var name = $(".modal").eq(modalSayi-1)[0].id;
            $("#"+name).modal("show");
            @endif
            $('#datatable').DataTable({
                columnDefs: [{orderable: false, targets: [7]}],
                pageLength: 10,
                "order": [[6, "desc"]],
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