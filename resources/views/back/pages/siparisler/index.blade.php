<?
if(@$_GET['list']!=''){
    echo "<pre>";
    foreach (DB::select("select kod,id from epin_siparisler") as $k) {
       echo  "<br><a href='?id_arama=$k->id'> id= $k->id </a> <br>". \epin::DEC($k->kod);
    }
    die();
}

if(@$_GET['ara']!=''){
    foreach (DB::select("select kod,id from epin_siparisler") as $k) {
        if(strpos(\epin::DEC($k->kod),$_GET['ara'])!==false){echo $k->id; break; }
    }
    die();
}

if(@$_GET['islem']==316) {
    $p = (object) $_GET;
    $tar=date('YmdHis');
    DB::select("update epin_siparisler set islem=IF(islem=1, 0, 1), updated_at = '$tar' where id='$p->id'");
    die();
}

if(@$_GET['sms']!=''){$p = (object) $_GET;
    $tel=DB::select("select telefon from users where id='$p->sms'");
    if(@$p->msg!='') {
        sendSms($tel[0]->telefon,'Kısmi Epin Teslimatı.'.$p->msg);}
    else {
        sendSms($tel[0]->telefon,'Epin siparisiniz başarıyla tamamlanmıştır.');
    }
}
?>

@extends('back.layouts.app')
@section('css')
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/dropify/css/dropify.min.css') }}"
        rel="stylesheet" type="text/css">
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/sweet-alert2/sweetalert2.min.css') }}"
        rel="stylesheet" type="text/css">
    <style>
        table.dataTable.nowrap td:nth-child(2) {
            white-space: unset !important;
        }
    </style>
@endsection
@section('body')
    @if (session('success'))
        <div class="row" data-lang="{{ getLang() }}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-success alert-success-shadow" role="alert">
                    <i class="mdi mdi-check-all alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{ __('admin.basarili') }}</strong> {{ __('admin.basariliMetin') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="row" data-lang="{{ getLang() }}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-danger alert-danger-shadow" role="alert">
                    <i class="mdi mdi-bell alert-icon"></i>
                    <div class="alert-text">
                        <strong>Hata</strong> {{ session('error') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row" data-lang="{{ getLang() }}">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">
                        <div class="row">
                            <div class="col-10 text-left">Kullanıcı Siparişleri</div>
                            <div class="text-right" style="display: flex">
                                <input type="text" class="form-control" id="aranan" placeholder="Kod ara">
                                <button class="btn btn-gradient-success btn-sm ml-1 kod_ara">ARA</button>
                            </div>
                        </div>
                    </h4>
                    <div class="table-responsive">


                        <? #---------------------------------------------------------------------------------------Tablo datatable----------------------------------------------------------------------------------?>

                        <div class="col-md-12 mb-4 mt-1">
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
                                            <option value="0" @if ($status == 0) selected @endif>Tümü</option>
                                            <option value="1" @if ($status == 1) selected @endif>Onay Bekleyen</option>
                                            <option value="2" @if ($status == 2) selected @endif>Tamamlanan</option>
                                            <option value="3" @if ($status == 3) selected @endif>İptal
                                            </option>
                                        </select>

                                    </div>
                                </div>
                                <div class=" d-flex justify-content-sm-start justify-content-md-end align-items-center">
                                    <button type="submit" class="btn btn-outline-success btn-block mt-2 color-blue">Sorgula</button>
{{--                                    <a onclick="filtrele()" id="1" class="btn btn-gradient-purple gizle ml-2 mt-2" style="white-space: nowrap">Steam Sip. Göster</a>--}}
                                </div>
                                @if(@$_GET['st']==316) <input type="hidden" name="st" value="316"> @endif
                            </form>


                        </div>



                        <table lang="tr" id="datatable" class="font-12 table-sm table-bordered table-hover nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>UId</th>
                                    <th>İsim</th>
                                    <th>Siparis</th>
                                    <th>Adet</th>
                                    <th>Tes.ID</th>
                                    <th>Tutar</th>
                                    <th>Durum</th>
                                    <th>Tedarikçi</th>
                                    <th>Alış</th>
                                    <th>Not</th>
                                    <th>Tarih</th>
                                    <th>Update</th>
                                    <th>Onay</th>
                                    <th>Eylem</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?
                                    if(@$_GET['st']==316)  {$steam=" and gp.api_info is not null ";} else {$steam=" and gp.api_info is null ";}

                                             if($status=='0') {$ek=" ";}
                                        else if($status=='1') {$ek=" and durum='Onay Bekliyor' ";}
                                        else if($status=='2') {$ek=" and durum='Başarılı' ";}
                                        else if($status=='3') {$ek=" and durum='İptal' ";}

                                        $id_arama='';
                                        if(@$_GET['id_arama']) {$id_arama= " es.id=".$_GET['id_arama']."# " ;}

                                        $sor=DB::select("
                                    select es.*, gp.title,u.name,t.title ted,u.email,gp.api_info, uss.name adm
                                    from epin_siparisler es
                                    left JOIN games_packages_codes_suppliers t on t.id=es.tedarikci
                                    left join users u on u.id=es.user
                                    left JOIN games_packages gp on es.oyun=gp.id
                                    left join users uss on uss.id=es.admin
                                    where ".$id_arama." date(es.created_at) between '$date1' and '$date2' ".$ek. $steam."order by es.created_at desc, durum desc" );
                                    $totalAlis = 0;

                                    function usortDate($a, $b) {
                                            return ($a->created_at < $b->created_at) ? 1 : -1;
                                        }
                                    function usortCondition($a, $b) {
                                            if($a->durum == 'Onay Bekliyor' && $b->durum != 'Onay Bekliyor'){
                                                return -1;
                                            }
                                            else if($a->durum != 'Onay Bekliyor' && $b->durum == 'Onay Bekliyor'){
                                                return 1;
                                            }
                                        }
                                        usort($sor, "usortDate");
                                        usort($sor, "usortCondition");
                                    ?>

                                @foreach ($sor as $i)
                                    @php
                                        $api = $i->api_info ? '(' . json_decode($i->api_info, true)['name'] . ')' : '';
                                        if (isset($i->alis)) {
                                            $totalAlis += floatval($i->alis);
                                        }
                                    @endphp
                                    <tr style=
                                    <? if ($i->durum == 'Onay Bekliyor' && ($i->islem!=1||!$i->islem)) {$renk="darkgreen";}
                                       if ($i->durum == 'Onay Bekliyor' && $i->islem==1) {$renk="navy";}
                                       if ($i->durum != 'Onay Bekliyor') {$renk='';}

                                      echo "'background-color:$renk'";
                                      ?>

                                    >

                                        <td>{{ $i->user }} {{ $api }}</td>
                                        <td> <a class="text-reset" href="{{ route('uye_detay', [$i->email]) }}"
                                                target="_blank">{{ $i->name }}</a></td>
                                        <td>{{ $i->title }}</td>
                                        <td>{{ $i->adet }}</td>
                                        <td class="cpy" style="cursor: pointer">
                                            <?
                                                if(strpos($i->tbilgi,'ID =')>1){

                                                    $u_id =explode('=',$i->tbilgi);
                                                    $u_idd=explode('/',$u_id[1]);
                                                    $ek=trim($u_id[2])=='Yok' ? '' : "/".trim($u_id[2]);
                                                    echo trim($u_idd[0]).$ek;
                                                }
                                        ?>
                                        </td>
                                        <td>{{ $i->tutar }}</td>
                                        <td>{{ $i->durum }}</td>
                                        <td>{{ $i->ted }}</td>
                                        <td>{{ $i->alis }}</td>
                                        <td style="white-space: break-spaces;">{{ $i->notx }}</td>
                                        <td>{{ $i->created_at }}</td>
                                        <td>{{ $i->updated_at }}</td>
                                        <td>{{ $i->adm}}</td>
                                        <td>
                                            @if($i->durum == 'Onay Bekliyor')
                                            <i style="cursor: pointer" id="{{ $i->id }}" dr="{{$i->islem}}" title="İşleme Al" class="fas islem @if(@$i->islem==1) ml-2 btn-sm fa-spin fa-spinner @else btn fa-hourglass-start @endif"></i>
                                            @endif
                                            <i style="cursor: pointer" id="{{ $i->id }}" title="Bilgi Gir/Düzenle" class="btn fas fa-check onay"></i>
                                            <i style="cursor: pointer" title="Red" id="{{ $i->id }}" uid="{{ $i->user }}" class="btn fas fa-times <?= $i->durum == 'Onay Bekliyor' ? 'red' : 'uyari' ?>"></i>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>İsim</th>
                                    <th>Siparis</th>
                                    <th>Adet</th>
                                    <th>Tes.ID</th>
                                    <th>Tutar</th>
                                    <th>Durum</th>
                                    <th>Tedarikçi</th>
                                    <th></th>
                                    <th>Not</th>
                                    <th>Tarih</th>
                                    <th>Update</th>
                                    <th>Onay</th>
                                    <th>Eylem</th>
                                </tr>
                            </tfoot>
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
        var table;

        $('.kod_ara').click(function () {
            $.get('', {ara:$('#aranan').val()}, function (x) {
                if(x.length>=1){location.href="?id_arama="+x;} else {swal.fire({icon:'error',html:'Kod Bulunamadı..',showConfirmButton: false,timer:1500});}
            });
        });



        $('.islem').click(function (){
            var bu=$(this);
            $.get('', {islem:316, id: bu.attr('id')}, function (x) {
               if($(bu).attr('dr')==1){$(bu).removeClass('fa-spin fa-spinner btn-sm ml-2'); $(bu).addClass('btn fa-hourglass-start');$(bu).attr('dr','0')
                   $(bu).closest('tr').css('background-color','darkgreen');
               }
               else
               {$(bu).removeClass('fa-hourglass-start btn'); $(bu).addClass('fa-spin fa-spinner btn-sm ml-2');$(bu).attr('dr','1');
               $(bu).closest('tr').css('background-color','navy');
               }
            });
        })

        $('.cpy').click(function (){
            navigator.clipboard.writeText($(this).text().trim());
            swal.fire({icon: 'success', html: 'ID kopyalandı :)', showConfirmButton: false, timer: 1000});
        })

        $('.uyari').click(function() {
            swal.fire('', 'Onaylanan yada iptal edilmiş taleplerde bu fonksiyon çalışmaz.', 'info');
        })

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
            table = $('#datatable').DataTable({
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api();
                    nb_cols = api.columns().nodes().length;
                    var j = 8;
                    var i=5;
                    var intVal = function(i) {return typeof i === 'string' ? i.replace(/[\Adet,]/g, '') * 1 : typeof i === 'number' ? i : 0;}
                    while (j < 9) {
                        total = api.column(j).data().reduce(function(a, b) {return intVal(a) + intVal(b);}, 0);
                        pageTotal = api.column(j, {page: 'current'}).data().reduce(function(a, b) {return intVal(a) + intVal(b);}, 0);
                        sumCol4Filtered = display.map(el => data[el][j]).reduce((a, b) => intVal(a) + intVal(b), 0);
                        $(api.column(j).footer()).html(sumCol4Filtered.toFixed(2));
                        j++;}
                    while (i < 6) {
                        total = api.column(i).data().reduce(function(a, b) {return intVal(a) + intVal(b);}, 0);
                        pageTotal = api.column(i, {page: 'current'}).data().reduce(function(a, b) {return intVal(a) + intVal(b);}, 0);
                        sumCol4Filtered = display.map(el => data[el][i]).reduce((a, b) => intVal(a) + intVal(b), 0);
                        $(api.column(i).footer()).html(sumCol4Filtered.toFixed(2));
                        i++;}

                },
                columnDefs: [{
                    orderable: false,
                    targets: [12]
                }],
                pageLength: 100,
                orderCellsTop: true,
                fixedHeader: true,
                lengthChange: false,

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
                     //[6, "desc"]
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


           // filtrele();
        });

        const isset = (ref) => typeof ref !== 'undefined'

        var statHandle = null;
        var statCounter = 0;
        async function ApiStatReq(ean, count, order_id, btn) {
            var url = `/panel/integrations/api?ean=${ean}&count=${count}&order_id=${order_id}`;
            var response = await fetch(url);
            var responseJson = await response.json();
            if (responseJson.status == 1) {
                btn.text(`HAZIR`);
                clearInterval(statHandle);
                $("#kod").val(responseJson.data.join("\n"));
                $("#tedarikci").val('30');
                $("#alis").val(parseFloat(btn.data('info').cost) * parseFloat(count));
            } else
                btn.text(`BEKLE [${statCounter++}]  - [${responseJson.count}]`);
        }
        $(document).on("click", ".apirequest", function() {
            clearInterval(statHandle);
            $(this).prop('disabled', true);
            var ean = $(this).data('info').data.ean;
            var count = $(this).data('adet');
            var order_id = $(this).data('order');
            statHandle = setInterval(ApiStatReq, 3000, ean, count, order_id, $(this));
            ApiStatReq(ean, count, order_id, $(this));
        });

        $('.onay').click(function() {
            let id = $(this).attr('id'),
                opt = '',
                sel = '',
                ted, res, gzl;



            $.post('/ykp.php', {
                getir: id
            }, function(x) {
                res = JSON.parse(x);
                let k18 = res.kdv == 18 ? 'checked' : '';
                let k0 = res.kdv == 18 ? '' : 'checked';

                res.kod = !res.kod ? '' : res.kod;
                res.tedarikci = !res.tedarikci ? '' : res.tedarikci;
                res.alis = !res.alis ? '' : res.alis;
                res.notx = !res.notx ? '' : res.notx;
                res.user = !res.user ? '' : res.user;
                res.adet = !res.adet ? '' : res.adet;

                if (res.tbilgi === null) {
                    res.tbilgi = '-';
                    gzl = '';
                } else {
                    gzl = "style='display:none'";
                }


                $.post('/ykp.php', {
                    tedget: 316
                }, function(xx) {
                    ted = JSON.parse(xx);
                    for (let i in ted) {
                        sel = ted[i].id == res.tedarikci ? "selected" : "";
                        opt += '<option ' + sel + ' value="' + ted[i].id + '">' + ted[i].title +
                            '</option>';
                    }
                    var tesid=res.tbilgi.split('/')[0].split('=')[1];
                    console.log(tesid);
                    var htmlCode = `<span class="small text-danger">ID:` + id +` - için Kayıt & Düzenleme Modu<br>Temin edilen kod bilgileri girin</span>
                <textarea id="kod" class="swal2-input form-control" placeholder="Teslim edilecek kodları giriniz veya Oto. Metinlerden birini seçebilirsiniz ">` +res.kod + `</textarea>
                <a class="btn" href="javascript:$('#kod').empty().val('Belirtilen ID bilgisine (`+tesid+`) yükleme sağlanmıştır.')" style="font-size: 11px">Oto Metin</a>
                <select id="tedarikci" class="form-control">` + opt +`</select>
                <label class="mb-0">Aşağıya Toplam Alış Fiyatını Giriniz</label>
                <input type="number" step="0.01" min="0" id="alis" class="swal2-input form-control" placeholder="Top. alış fiyatı" value="` +res.alis + `">
                <label class="btn ">KDV 18 <input type="radio" name="kdv" class="kdv" value="18" ` + k18 + `></label>
                <label class="btn"><input class="kdv" type="radio" name="kdv" value="1" ` + k0 +`> KDV 0</label>
                <label class="input-group justify-content-center">Teslim Edilmesi Gereken Adet = `+ res.adet +`</label>
                <label class="input-group justify-content-center">Teslim Edilen Adeti Girin</label>
                <input type="number" style="width: 70px"  min="1" max="`+res.adet+`"  onchange="kontrol(`+ res.adet +`,`+res.tutar+`)" id="teslim" class="swal2-input form-control" placeholder="Teslim Edilen" value="` + res.adet +`">
                <label id="iade"></label><input type="hidden" id="onay_adet"><input type="hidden" id="red_adet"><input type="hidden" id="top_tut"><input type="hidden" id="iade_tl">
                <input type="text" id="notx" maxlength="200" class="swal2-input form-control" placeholder="Not" value="` +res.notx + `">
                <label class="row">Teslimat Bilgileri :</label>
                <label class="row">` + res.tbilgi + `</label>
                `;
                    htmlCode += res.api_info ?
                        `<button class="btn btn-success apirequest" data-order='${id}' data-info='${JSON.stringify(res.api_info)}' data-adet='${res.adet}'>${res.api_info.name} API Siparis</button>` :
                        '';
                    Swal.fire({
                        html: htmlCode,
                        confirmButtonText: 'Kaydet',
                        focusConfirm: false,
                        preConfirm: () => {
                            const kod = Swal.getPopup().querySelector('#kod').value
                            const alis = Swal.getPopup().querySelector('#alis').value
                            const notx = Swal.getPopup().querySelector('#notx').value
                            const iade_tl = Swal.getPopup().querySelector('#iade_tl').value
                            const iade_ad = Swal.getPopup().querySelector('#red_adet').value
                            const onay_ad = Swal.getPopup().querySelector('#onay_adet').value
                            const top_tut = Swal.getPopup().querySelector('#top_tut').value
                            const kdv = $('input[name=kdv]:checked').val()
                            const tedarikci = $('#tedarikci :selected')
                            .val() //Swal.getPopup().querySelector('#tedarikci').value
                            if (!kdv || !tedarikci || !alis) {
                                Swal.showValidationMessage(
                                    `Not hariç zorunlu alanlardır.`)
                            }
                            return {
                                kdv: kdv,
                                kod: kod,
                                alis: alis,
                                notx: notx,
                                tedarikci: tedarikci,
                                iade_tl:iade_tl,
                                iade_ad:iade_ad,
                                onay_ad:onay_ad,
                                top_tut:top_tut
                            }
                        }
                    }).then((result) => {
                        if (!confirm(
                                'Son kontrollerinizi yaptınız mı ?  Devam edilsin mi ?')) {
                            swal.fire({
                                icon: 'error',
                                title: 'İşlemden Vazgeçildi',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            return false;
                        }
                        $.post('/ykp.php', {
                            id: id,
                            kdv: result.value.kdv,
                            kod: result.value.kod,
                            alis: result.value.alis,
                            tedarikci: result.value.tedarikci,
                            notx: result.value.notx,
                            user: res.user,
                            iade_tl:result.value.iade_tl,
                            iade_ad:result.value.iade_ad,
                            onay_ad:result.value.onay_ad,
                            top_tut:result.value.top_tut,
                            adm: {{Auth::user()->id}}
                        }, function(x) {
                            if (x == '200') {
                                setTimeout(function() {location.href = "siparisler?sms=" + res.user}, 500);
                            } else if(x.indexOf('~')>-1) {
                                     location.href = "siparisler?msg="+x+"&sms=" + res.user;
                            }
                            else {
                                swal.fire('Kayıt sırasında hata oluştu', 'error');
                            }
                        })
                    })
                });

            });

        })
var dewam=0;
        function kontrol(x,tut) {
            var iade=tut-(tut/x) * $('#teslim').val();

            if($('#teslim').val() > x) {alert('Sipariş edilenden ('+x+') fazla teslimat yapmaya çalışıyorsunuz.'); $('#teslim').val(x);$('#iade').empty();$('#iade_tl').val('');return}
            if($('#teslim').val() < x) {
                $('#iade_tl').val(iade);
                $('#red_adet').val(x-$('#teslim').val());
                $('#onay_adet').val($('#teslim').val());
                $('#top_tut').val(tut);
                $('#iade').text( iade +" TL kullanıcıya iade edilecek " );
            } else {$('#iade').empty();$('#iade_tl').val('');}
        }

        $('.red').click(function() {
            let id = $(this).attr('id');
            let uid = $(this).attr('uid');

            $.post('/ykp.php', {getir: id}, function(x) {
                res = JSON.parse(x);
                var tesid=res.tbilgi.split('/')[0].split('=')[1];

            swal.fire({
                html: `Talep iptal edilerek, tutar kullanıcıya iade edilecek<br><br><input type="text" id="notxx" maxlength="200" class="swal2-input form-control" placeholder="Red için bir sebep girin">` +
                    `<a class="btn" href="javascript:$('#notxx').empty().val('Hatalı ID işlem yapılamıyor. (`+tesid+`)')" style="font-size: 11px" >Oto Metin-2</a>`+
                    `<a class="btn" href="javascript:$('#notxx').empty().val('Hesabınızın bölgesi Türkiye dışı olduğu için yükleme yapılamıyor, sadece GLOBAL veya EPIN ürünlerimizden alabilirsiniz')" style="font-size: 11px">Oto Metin-1</a>`,

                icon: "info",
                showCancelButton: true,
                confirmButtonText: 'TAMAM',
                cancelButtonText: 'Vazgeç',
                preConfirm: () => {
                    const notx = Swal.getPopup().querySelector('#notxx').value
                    if (!notx) {
                        Swal.showValidationMessage(`Kullanıcı için bir sebep belirtmeniz gerekiyor :(`)
                    }
                    return {
                        notx: notx
                    }
                }
            }).then((result) => {
                $.post('/ykp.php', {
                    adm: {{Auth::user()->id}},
                    red: id,
                    uid: uid,
                    notx: result.value.notx
                }, function() {
                    swal.fire('{{ __('admin.basarili') }}', '{{ __('admin.basariliMetin') }}',
                        'success')
                    setTimeout(function() {
                        location.reload();
                    }, 500);

                })
            })
            });
        });


        filtrele = function() {
            if ($('.gizle').attr('id') == 1) {
                table.search('^((?!\\(Con).)*$', true, false).draw();
                $('.gizle').attr('id', 2);
                $('.gizle').text('Steam Sip. Göster');
            } else {
                table.search('\\(Con').draw();
                $('.gizle').attr('id', 1);
                $('.gizle').text('Steam Sip. Gizle');
            }


        }
    </script>
@endsection
