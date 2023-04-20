<?
#-----mükerrer işlem için durum check
if(@$_GET['razer_state']&&$_GET['s']){$p = (object) $_GET;
    if($p->s=='off') {$r=DB::table('razer_kod')->where('id',$p->razer_state)->update(['durum' => 1]);}
    if($p->s=='on') {$r=DB::table('razer_kod')->where('durum',1)->where('id',$p->razer_state)->update(['durum' => -1]);}
    echo "s->$r";
    die();
}

#------------Razer işlem red
if(@$_GET['razer_red']){
    $p = (object) $_GET;

    DB::table('razer_kod')->where('id',$p->razer_red)->update([
                        'durum' => 3,
                        'notx' => $p->notx,
                        'opr'=> Auth::user()->name,
                        'updated_at' => date('YmdHis')
                    ]);

    DB::table('odemeler')->insert([ // odemeler table insert
        'user' => $p->user,
        'amount' => $p->tutar,
        'channel' => '17',
        'status' =>'2',
        'description'=> $p->notx,
        'created_at' => date('YmdHis')
    ]);

    sendSms(DB::select("select telefon from users where id='$p->user'")[0]->telefon, "Razer kod trade işleminiz onaylanmamıştır. ($p->notx)");

die();
}
#------------Razer işlem onay



if(@$_GET['razer_ok']) { // razer table update
    $p = (object)$_GET;
    DB::table('razer_kod')->where('id',$p->razer_ok)->update([
        'durum' => 2,
        'notx' => $p->notx,
        'odeme'=>$p->tutar,
        'opr'=> Auth::user()->name,
        'updated_at' => date('YmdHis')
    ]);

    DB::table('odemeler')->insert([ // odemeler table insert
                        'user' => $p->user,
                        'amount' => $p->tutar,
                        'channel' => 17,
                        'status' =>1,
                        'description'=>'Razer kod trade',
                        'created_at' => date('YmdHis')
                    ]);
    DB::select("update users set bakiye_cekilebilir=bakiye_cekilebilir+'$p->tutar' where id='$p->user'");

sendSms(DB::select("select telefon from users where id='$p->user'")[0]->telefon,"Razer kod trade işleminiz karşılığında bakiyenize $p->tutar TL eklenmiştir.");

die();
}
?>


@extends('back.layouts.app')
@section('css')
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        table.dataTable.nowrap td:nth-child(2) {
            white-space: unset !important;
        }
    </style>
@endsection
@section('body')

    <div class="row" data-lang="{{ getLang() }}">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 text-left">
                                Razer Kod Trade İşlemleri
                            </div>
                        </div>
                    </h4>
                    <div class="table-responsive">


                        <? #---------------------------------------------------------------------------------------Tablo datatable----------------------------------------------------------------------------------?>

                        <div class="col-md-12 mb-4 mt-4">
                            @if (isset($_GET['date1']) and isset($_GET['date2']))
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

                            <form class="row" method="get">
                                <div class="col-sm-12 col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="userinput1">İlk Tarih</label>
                                        <input type="date" id="userinput1" class="form-control style-input"
                                            name="date1" value="{{ $date1 }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label" for="userinput2">Son Tarih</label>
                                        <input type="date" id="userinput2" class="form-control style-input"
                                            name="date2" value="{{ $date2 }}" required>
                                    </div>
                                </div>
                                <div>
                                    <div class="col-sm-12 mb-3">
                                        <label class="form-label">Durum</label>
                                        <?php if (isset($_GET['status'])) {
                                            $status = $_GET['status'];
                                        } else {
                                            $status = '0';
                                        } ?>
                                        <select name="status" onchange="this.form.submit()" class="form-control ">
                                            <option value="0" @if ($status == 0) selected @endif>Tümü </option>
                                            <option value="1" @if ($status == 1) selected @endif>Onay Bekleyen</option>
                                            <option value="2" @if ($status == 2) selected @endif>Tamamlanan</option>
                                            <option value="3" @if ($status == 3) selected @endif>İptal</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-1 d-flex justify-content-sm-start justify-content-md-end align-items-center">
                                    <button type="submit" class="btn btn-outline-success btn-block mt-2 color-blue">Sorgula</button>
                                </div>
                            </form>


                        </div>


                        <table lang="tr" id="datatable" class="font-12 table-sm table-bordered table-hover nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>İsim</th>
                                    <th>Platform</th>
                                    <th>Razer Kod</th>
                                    <th>Kod Tutarı</th>
                                    <th>Oran</th>
                                    <th>Son Tutar</th>
                                    <th>Not</th>
                                    <th>Durum</th>
                                    <th>Onaylayan</th>
                                    <th>Tarih</th>

                                    <th>Eylem</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?
                                         if($status=='0') {$ek=" ";}
                                    else if($status=='1') {$ek=" and durum='1' ";}
                                    else if($status=='2') {$ek=" and durum='2' ";}
                                    else if($status=='3') {$ek=" and durum='3' ";}

                                    $sor=DB::select("
                                select rz.*, u.name,u.email,u.id uid
                                from razer_kod rz
                                left join users u on u.id=rz.user
                                where date(rz.created_at) between '$date1' and '$date2' ".$ek." order by rz.created_at");


                                ?>

                                @foreach ($sor as $i)

                                    <?
                                        if($i->plt=='Razer TL'){$oran=getCacheSetings()->razertl;}
                                        elseif($i->plt=='Razer USD'){$oran=getCacheSetings()->razerusd;}
                                        else{$oran=getCacheSetings()->razercsto;}

                                        if($i->durum==1){$durum='Bekliyor'; }
                                        elseif($i->durum==2){$durum='Tamamlandı';}
                                        elseif($i->durum==3){$durum='Red edildi';}
                                        elseif($i->durum==-1){$durum='İşlemde';}

                                        ?>
                                    <tr @if($i->durum==1) style="background-color: #14d13a7d" @endif>
                                        <td>{{ $i->user }}</td>
                                        <td> <a class="text-reset" href="{{ route('uye_detay', [$i->email]) }}" target="_blank">{{ $i->name }}</a></td>
                                        <td>{{ $i->plt }}</td>
                                        <td class="cpy" dur="{{$i->durum }}" iid="{{$i->id}}" uid="{{$i->uid}}" id="{{$i->btutar}}" or="{{$oran}}" style="cursor: pointer">{{ $i->kod }}</td>
                                        <td style="text-align: right">{{ $i->btutar }}</td>
                                        <td style="text-align: center">%{{ $oran }}</td>
                                        <td style="text-align: right">{{ $i->odeme }}</td>
                                        <td>{{ $i->notx }}</td>
                                        <td>{{ $durum }}</td>
                                        <td>{{ $i->opr }}</td>
                                        <td>{{ $i->created_at }}</td>
                                        <td style="text-align: center">
                                            @if($i->durum==-1) <i title="Askıda kalan işlem durumunu temizler" id="{{$i->id}}" class="btn fa fa-recycle stt"></i> @endif
                                            <i title="Sil" class="btn far fa-trash-alt sil"></i>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/sweet-alert2/sweetalert2.min.js') }}">
    </script>
    <script src="//cdn.datatables.net/plug-ins/1.11.3/api/sum().js"></script>

    <script type="text/javascript">
        var or,btut,iid,son,uid,ex;
        function thes() {son=($('#tutar').val() - (or * $('#tutar').val() / 100)).toFixed(2);$('.aktar').text(son);}

        $('.stt').click(function (){
            iid=$(this).attr('id');
            if(confirm('Durum bilgisi Bekliyor olarak değiştirilecek\nDevam edilsin mi ?')) {
                $.get('', {razer_state : iid , s:'off'},function (){swal.fire({icon:'success',title:'İşlemden başarılı',showConfirmButton: false,timer:1500});
                setTimeout(function (){location.reload()},2000);
                });
            } else {swal.fire({icon:'error',title:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});}
        });


        $('.cpy').click(function(event) {
            btut=$(this).attr('id');or=$(this).attr('or');iid=$(this).attr('iid');uid=$(this).attr('uid');dur=$(this).attr('dur');
            if(dur==3){swal.fire({icon:'error',html:'Kullanıcıya RED bilgisi iletildiği için reddedilen talep üzerinde tekrardan işlem yapılamaz.'});return false}
            if(dur==2){swal.fire({icon:'error',html:'Kullanıcıya ONAY bilgisi ve bakiyesi iletildiği için onaylanan talep üzerinde tekrardan işlem yapılamaz.'});return false}
            $.get('', {razer_state : iid , s:'on'},function (x){if(x=='s->0'){ex=1;swal.fire({icon:'error',html:'Başka bir operatör bu kayıt üzerinde işlem yapıyor. İşlemin askıda kaldığını düşünüyorsanız sayfayı yenileyip tablodan işlem durumunu temizleyin'});}});
            event.preventDefault();
            navigator.clipboard.writeText($(this).text());
            swal.fire({icon: 'success', html: 'Kod kopyalandı :)', showConfirmButton: false, timer: 1000});


                setTimeout(function () {
                    if(ex!=1) {
                        Swal.fire({
                            html: '<small>Kod karşılığı gelen tutarı doğrulayın<br>Kullanıcının belirttiği tutar = ' + btut +
                                '<input type="number" id="tutar" onkeyup="thes()" onchange="thes()" class="border-danger form-control swal2-input text-center text-danger" value="' + btut + '"> Kullanıcı Bakiyesine Akarılacak T. -> <i class="aktar text-white"></i>' +
                                '<input type="text" id="notxx" maxlength="200" class="swal2-input form-control border-success" placeholder="İşlem sonucunu aşağıdan seçiniz yada giriniz"></small>' +
                                '<small class="font-12"><i style="cursor:pointer; color: white" onclick="yaz(`Hatalı kod`)">Hatalı kod</i> - <i style="cursor:pointer; color: white" onclick="yaz(`Kod tutarı belirtilenden farklı`)">Tutar belirtilenden farklı</i> - <i style="cursor:pointer; color: white" onclick="yaz(`İşlem başarılı`)">İşlem başarılı</i></small>' +
                                '<div class="row"><div class="col mt-2 mb-4" style="display: flex; justify-content: center"> ' +
                                '<button class="btn btn-success mr-3 ok">İşlemi Onayla</button>' +
                                '<button class="btn btn-danger mr-3 red">İşlemi Reddet</button>' +
                                '<button class="btn btn-info vz">Vazgeç</button>' +
                                '</div></div>',
                            icon: "info",
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        })
                        thes();
                    }

                }, 1200);

        });


$(document).on('click','.ok',function(){
    if($('#notxx').val()==''){Swal.showValidationMessage(`İşlem sonucunu belirtmeniz gerekiyor`);return}
    if($('#tutar').val()==''){Swal.showValidationMessage(`Tutar alanına geçerli bir değer girin`);return}
    if(confirm('İşlem kayıt edilecek ve kullanıcı bakiyesine aşağıdaki tutar kadar ekleme yapılacak.\nİşlem durumu SMS ile kullanıcıya iletilecek.\nDevam edilsin mi ?')){
        $.get('?', {razer_ok:iid, tutar: son, user:uid, notx:$('#notxx').val() }, function (x) {
        location.reload();
        });
    } else {if(dur==1){$.get('?', {s:'off',razer_state:iid}); }swal.fire({icon:'error',title:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});}
})
$(document).on('click','.red',function(){
    if($('#notxx').val()==''){Swal.showValidationMessage(`İşlem sonucunu belirtmeniz gerekiyor`);return}
    if(confirm('Bu işlem talebi REDDEDİLECEK !\nKullanıcıya aşağıda belirtilen açıklama ile olumsuz dönüş yapılacak.\nİşlem durumu SMS ile kullanıcıya iletilecek.\nDevam edilsin mi ?')){
        $.get('?', {razer_red:iid, tutar: son, user:uid, notx:$('#notxx').val() }, function (x) {
        location.reload();
        });
    } else {if(dur==1){$.get('?', {s:'off',razer_state:iid});} swal.fire({icon:'error',title:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});}
})
        $(document).on('click','.vz',function(){
            if(dur==1){$.get('?', {s:'off',razer_state:iid});}
            swal.close();
            swal.fire({icon:'error',title:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});
        })

        function yaz(e){$('#notxx').val(e)}
        $('.sil').click(function (){swal.fire({icon:'error',html:'Razer modülünde kayıt silme işlevsel değildir. :(',showConfirmButton: false,timer:2500});});
        $(document).ready(function() {
            $('#datatable thead tr').clone(true).appendTo('#datatable thead');
            $('#datatable thead tr:eq(1) th').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="' + title + '" />');
                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });
            });

            $('#datatable input[type="text"]').css({
                'width': '100%',
                'display': 'inline-block'
            });
            var table = $('#datatable').DataTable({
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api();
                    nb_cols = api.columns().nodes().length;
                    var j = 2;
                    var e = 3;
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\Adet,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    var intVal2 = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\TL,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    while (j < 3) {
                        total = api.column(j).data().reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        pageTotal = api.column(j, {
                            page: 'current'
                        }).data().reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        sumCol4Filtered = display.map(el => data[el][j]).reduce((a, b) => intVal(a) +
                            intVal(b), 0);
                        $(api.column(j).footer()).html(sumCol4Filtered);
                        j++;
                    }
                    while (e < 4) {
                        total = api.column(e).data().reduce(function(a, b) {
                            return intVal2(a) + intVal2(b);
                        }, 0);
                        pageTotal = api.column(e, {
                            page: 'current'
                        }).data().reduce(function(a, b) {
                            return intVal2(a) + intVal2(b);
                        }, 0);
                        sumCol4Filtered = display.map(el => data[el][e]).reduce((a, b) => intVal2(a) +
                            intVal2(b), 0);
                        $(api.column(e).footer()).html(sumCol4Filtered.toLocaleString());
                        e++;
                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: [1]
                }],
                pageLength: 100,
                orderCellsTop: true,
                fixedHeader: true,
                lengthChange: false,
                //ajax: '{{ route('getGameGold') }}',
                buttons: [{
                        extend: 'excelHtml5',
                        title: '{{ getPageTitle(getPage(), getLang()) }} - Excel',
                        text: '<i class="far fa-file-excel"></i>',
                        className: 'btn btn-outline-success',
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        title: '{{ getPageTitle(getPage(), getLang()) }} - Csv',
                        text: '<i class="fas fa-file-csv"></i>',
                        className: 'btn btn-outline-info',
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: '{{ getPageTitle(getPage(), getLang()) }} - Pdf',
                        className: 'btn btn-outline-danger',
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        text: '<i class="far fa-file-pdf"></i>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },

                ],
                "order": [
                    [9, "desc"]
                ],
                "language": {
                    "decimal": "",
                    "emptyTable": "{{ __('admin.hic-veri-yok') }}",
                    "info": "{{ __('admin.adet-veri-gosterim', ['TOTAL' => '_TOTAL_', 'START' => '_START_', 'END' => '_END_']) }}",
                    "infoEmpty": "{{ __('admin.sifir-veri-var') }}",
                    "infoFiltered": "{{ __('admin.adet-veri-araniyor', ['MAX' => '_MAX_']) }}",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "{{ __('admin.veri-gosteriliyor', ['MENU' => '_MENU_']) }}",
                    "loadingRecords": "{{ __('admin.yukleniyor') }}",
                    "processing": "{{ __('admin.isleniyor') }}",
                    "search": "{{ __('admin.ara') }}",
                    "zeroRecords": "{{ __('admin.eslesen-veri-bulunamadi') }}",
                    "paginate": {
                        "first": "{{ __('admin.ilk') }}",
                        "last": "{{ __('admin.son') }}",
                        "next": "{{ __('admin.sonraki') }}",
                        "previous": "{{ __('admin.onceki') }}"
                    },
                }
            });
            //setTimeout(function () {
            table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(1)');
            //}, 3000);
            /*setInterval( function () {
                table.ajax.reload();
            }, 3000 );*/
            $('#datatable_filter').css({
                'display': 'none'
            });



        });


    </script>
@endsection
