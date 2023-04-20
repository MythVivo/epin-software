<?
#-------------------------------yakup excel ve txt kod download self api
if(@$_GET['download']==2 || @$_GET['download']==3) {
    $p = (object) $_GET;
    if($p->download==2){$ex="csv";} else {$ex="txt";}
    $file = "kodlar.".$ex;
    $txt = fopen($file, "w") or die("Dosya hatası");
    $user= Auth::user()->id;
    if($p->ord==2) {$sorqu = DB::select("SELECT esc.*, es.user FROM `epin_satis_kodlar` esc left join epin_satis es on es.id=esc.epin_satis where epin_satis= ? and es.user= ?", [$p->id, $user]);}
    if($p->ord==1) {$sorqu = DB::select("SELECT kod code FROM epin_siparisler  where id= ? and user= ?", [$p->id, $user]);}
    foreach($sorqu as $a){$satir.=epin::DEC($a->code)."\n";}
    fwrite($txt, $satir);
    fclose($txt);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.$file);
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    header("Content-Type: text/plain");
    ob_clean();
    flush();
    readfile($file);
die();
}
?>

@extends('front.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}"  rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">

@endsection
@section('body')

    <section class="bg-gray pb-40">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success d-flex text-center" role="alert">
                    <i class="fas fa-check me-2"></i>
                    <h5>{{session('success')}}</h5>
                </div>
            @endif


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
                            # yakup tarafından sipariş modülü tabloya entegre ediliyor

                            $id=Auth::user()->id;
                            $siparisler = DB::table('epin_satis')->
                            #whereBetween('created_at', [$date1, $date2])
                            whereDate('created_at', '>=', $date1)->whereDate('created_at', '<=', $date2)->
                            where('user', $id)->orderBy('created_at','desc');
                            $dtb =DB::select("select es.*, gp.title, gp.games_titles, gt.title oyun from epin_siparisler es, games_packages gp, games_titles gt where es.oyun=gp.id and gp.games_titles=gt.id and date(es.created_at) between '$date1' and '$date2' and es.user='$id' order by es.created_at desc");
                            ?>
                            @if($siparisler->count() > 0 || count($dtb)>0)
                            <div class="col-12">
                                <div class="table-col">
                                    <table id="datatable" class="table table-hover table-striped sss table-sm nowrap ">
                                        <thead>
                                        <tr>

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
                                        @foreach($dtb as $al)
                                            <tr>
                                                    <? $al->kod=$al->durum=='İptal'?'ip':\epin::DEC($al->kod) ; ?>
                                                <td>{{$al->oyun}}</td>
                                                <td id="{{$al->kod}}" iid="{{$al->id}}" class="detay">{{$al->title}}</td>
                                                <td>{{$al->tutar}} TL</td>
                                                <td>{{$al->adet}}</td>
                                                <td id="{{$al->kod}}" iid="{{$al->id}}" class="detay" style="cursor: pointer; text-align: center" title="Detay için tıklayın.">{{$al->durum}}

                                                <?
                                                if($al->tbilgi!='') {
                                                    $nick = explode('/', $al->tbilgi);
                                                    $nick=str_replace('User ','',$nick[0]);
                                                    echo '<br>('.$nick.')';
                                                    }
                                                        ?>
                                                </td>
                                                <td>{{$al->created_at}}</td>
                                                <td>

                                                    <button id="{{$al->kod}}" iid="{{$al->id}}" title="Kodları gör" class="btn btn-sm btn-outline-secondary detay"><i class="far fa-eye"></i></button>
                                                    <button id="{{$al->id}}" tip="ord" ne="ex" title="Excel indir" class="btn btn-outline-secondary btn-sm ex"><i class="far fa-file-excel"></i></button>
                                                    <button id="{{$al->id}}" tip="ord" ne="tx" title="TXT indir" class="btn btn-outline-secondary btn-sm tx"><i class="far fa-text"></i></button>
                                                </td>
                                            </tr>

                                        @endforeach

                                        @foreach($siparisler->get() as $u)
                                            <tr>

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
                                                <td data-bs-toggle="modal" data-bs-target="#detay{{$u->id}}">{{DB::table('games_packages')->where('id', $u->paketId)->first()->title}}</td>
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
                                                <td style="text-align: center">
                                                    @if($u->status == 1)
                                                        <button title="Kodları gör" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#detay{{$u->id}}"><i class="far fa-eye"></i></button>
                                                        <button id="{{$u->id}}" tip="stk" ne="ex" title="Excel indir" class="btn btn-outline-secondary btn-sm ex"><i class="far fa-file-excel"></i></button>
                                                        <button id="{{$u->id}}" tip="stk" ne="tx" title="TXT indir" class="btn btn-outline-secondary btn-sm tx"><i class="far fa-text"></i></button>
                                                    @else
                                                        <button class="btn btn-outline-warning"
                                                                style="cursor: not-allowed;">
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
                                                                        <div class="col-12 mb-4" style="text-align: center ">
                                                                            <span class="btn-inline color-darkgreen tumunukopyala">Tümünü Kopyala</span>
                                                                        </div>
                                                                        @foreach(DB::table('epin_satis_kodlar')->where('epin_satis', $u->id)->get() as $a)
                                                                            <div class="col-12 mb-1">
                                                                                <h5 class="card-title clipboard"><span class="code">{{\epin::DEC($a->code)}}</span><span class="btn-inline color-darkgreen cpy-code">Kodu Kopyala</span></h5>
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
                                            <? $sira++;?>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>

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

                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                <div class="alert alert-danger  fade show d-flex align-items-center"
                                     role="alert">
                                    <i class="fas fa-exclamation-triangle me-3"></i>
                                    <h5>
                                        Henüz bir siparişiniz bulunmuyor. İlk siparişinizi verdikten sonra bu
                                        ekrandan
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
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>

    <script>

        $('.ex, .tx').click(function(x){var ex,ord;
            if($(this).attr('ne')=='ex') {ex='csv';} else {ex='txt';}
            if($(this).attr('tip')=='ord'){ord=1;} else{ord=2;}
            let id=$(this).attr('id');
                $.ajax({
                    url: '?download=2&id='+id+'&ord='+ord,
                    method: 'GET',xhrFields: {responseType: 'blob'},
                    success: function (data) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = 'Oyuneks_Kodlar.'+ex;
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    }
                });
            });


        $('.tumunukopyala').click(function(){
            navigator.clipboard.writeText([...$(this).closest('.modal-body').find('.code')].map(e=>e.innerText).join("\n"));
            $(".clipboard").addClass("copied");
        });

        let kod='',th='';

        $('.detay').click(function(){
            th=$("body").hasClass("dark");
            console.log(th);
            kod=$(this).attr('id');
            iid=$(this).attr('iid');
            if(kod=='ip'){
                $.post('/ykp.php',{sebep:iid},function(x){
                    if(th) {Swal.fire({background: '#20212b',icon:'info',html: '<span style="color:white">Siparişiniz aşağıdaki sebepten dolayı onaylanmamıştır. <br><b>'+ x+'</b></span>'} );}
                    else {Swal.fire({icon:'info',html: 'Siparişiniz aşağıdaki sebepten dolayı onaylanmamıştır. <br><b>'+ x+'</b>'} ); }
                })
            }
            else if(kod==''){
                if(th){Swal.fire({background: '#20212b',icon:'info',html: '<span style="color:white">Siparişiniz henüz onaylanmamış. <br>Hazır olduğunda bildirim alacaksınız.</span>'} );}
                else {Swal.fire({icon:'info',html: 'Siparişiniz henüz onaylanmamış. <br>Hazır olduğunda bildirim alacaksınız.'} );}
            }else{
                if(th){Swal.fire({ background: '#20212b', width:'40%' , html: '<span style="color:white;display: flex;justify-content: center;flex-direction: column;align-content: center;align-items: center;">Siparişe ait detaylar <br><br><br> <pre><h5 style= "height: 500px;overflow-y: auto;width: fit-content;" class="card-title clipboard"><span class="code"><br>'+kod+'</span></h5><span class="btn-inline color-blue cpy">Kodu Kopyala</span></pre>'} );}
                else {Swal.fire({ width:'40%' , html: '<span style="display: flex;justify-content: center;flex-direction: column;align-content: center;align-items: center;">Siparişe ait detaylar <br><br><br> <pre><h5 style= "height: 500px;overflow-y: auto;width: fit-content;" class="card-title clipboard"><span class="code"><br>'+kod+'</span></h5><span class="btn-inline color-blue cpy">Kodu Kopyala</span></pre></span>'} );}
            }
        });

        $(document).on('click','.cpy', function (e){navigator.clipboard.writeText(kod); $(this).text('Kopyalandı'); $(this).text('Kopyalandı'); $(this).removeClass('color-blue') ; $(this).addClass('color-darkgreen')  });

        $(document).ready(function () {
            @if(session('show'))
            var modalSayi = $(".modal").length;
            var name = $(".modal").eq(0)[0].id;
            $("#"+name).modal("show");
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
