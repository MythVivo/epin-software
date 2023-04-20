<?
if(@$_GET['sms']!=''){$p = (object) $_GET;
    $tel=DB::select("select telefon from users where id='$p->sms'");
    sendSms($tel[0]->telefon,'Epin siparisiniz başarıyla tamamlanmıştır.');
}
?>

@extends('front.layouts.app')
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
                            <div class="col-md-6 col-sm-6 text-left">
                                Kullanıcı Siparişleri
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
                                            <option value="0" @if ($status == 0) selected @endif>Tümü
                                            </option>
                                            <option value="1" @if ($status == 1) selected @endif>Onay
                                                Bekleyen</option>
                                            <option value="2" @if ($status == 2) selected @endif>
                                                Tamamlanan</option>
                                            <option value="3" @if ($status == 3) selected @endif>İptal
                                            </option>

                                        </select>

                                    </div>
                                </div>
                                <div class=" d-flex justify-content-sm-start justify-content-md-end align-items-center">
                                    <button type="submit"
                                        class="btn btn-outline-success btn-block mt-2 color-blue">Sorgula</button>
                                    <a onclick="filtrele()" id="1" class="btn btn-gradient-purple gizle ml-2 mt-2"
                                        style="white-space: nowrap">Steam Sip. Göster</a>
                                </div>
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
                                    <th>Tutar</th>
                                    <th>Durum</th>
                                    <th>Tedarikçi</th>
                                    <th>Alış</th>
                                    <th>Not</th>
                                    <th>Tarih</th>
                                    <th>Eylem</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?
                                             if($status=='0') {$ek=" ";}
                                        else if($status=='1') {$ek=" and durum='Onay Bekliyor' ";}
                                        else if($status=='2') {$ek=" and durum='Başarılı' ";}
                                        else if($status=='3') {$ek=" and durum='İptal' ";}

                                        $sor=DB::select("
                                    select es.*, gp.title,u.name,t.title ted,u.email,gp.api_info
                                    from epin_siparisler es
                                    left JOIN games_packages_codes_suppliers t on t.id=es.tedarikci
                                    left join users u on u.id=es.user
                                    left JOIN games_packages gp on es.oyun=gp.id
                                    where date(es.created_at) between '$date1' and '$date2' ".$ek." order by es.created_at");
                                    $totalAlis = 0;
                                    ?>

                                @foreach ($sor as $i)
                                    @php
                                        $api = $i->api_info ? '(' . json_decode($i->api_info, true)['name'] . ')' : '';
                                        if (isset($i->alis)) {
                                            $totalAlis += floatval($i->alis);
                                        }
                                    @endphp
                                    <tr @if ($i->durum == 'Onay Bekliyor') style="background-color: darkgreen;" @endif>
                                        <td>{{ $i->user }} {{ $api }}</td>
                                        <td> <a class="text-reset" href="{{ route('uye_detay', [$i->email]) }}"
                                                target="_blank">{{ $i->name }}</a></td>
                                        <td>{{ $i->title }}</td>
                                        <td>{{ $i->adet }}</td>
                                        <td>{{ $i->tutar }}</td>
                                        <td>{{ $i->durum }}</td>
                                        <td>{{ $i->ted }}</td>
                                        <td>{{ $i->alis }}</td>
                                        <td>{{ $i->notx }}</td>
                                        <td>{{ $i->created_at }}</td>
                                        <td><i id="{{ $i->id }}" title="Bilgi Gir/Düzenle"
                                                class="btn fas fa-check onay"></i> <i title="Red"
                                                id="{{ $i->id }}" uid="{{ $i->user }}"
                                                class="btn fas fa-times <?= $i->durum == 'Onay Bekliyor' ? 'red' : 'uyari' ?>"></i>
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
                                    <th>Tutar</th>
                                    <th>Durum</th>
                                    <th>Tedarikçi</th>
                                    <th>{{number_format($totalAlis, 2);}}</th>
                                    <th>Not</th>
                                    <th>Tarih</th>
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
                    while (e < 5) {
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


            filtrele();
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
                    var htmlCode = `<span class="small text-danger">ID:` + id +
                        ` - için Kayıt & Düzenleme Modu<br>Temin edilen kod bilgileri girin</span>
                <textarea id="kod" class="swal2-input form-control" placeholder="İşlem ID yükleme ise buraya '' Belirtilen ID üzerine yüklendi '' yazınız. Normal işlem ise teslim edilecek kodları giriniz. ">` +
                        res.kod + `</textarea>
                <select id="tedarikci" class="form-control">` + opt +
                        `</select>
                <label class="mb-0">Aşağıya Toplam Alış Fiyatını Giriniz</label>
                <input type="number" step="0.01" min="0" id="alis" class="swal2-input form-control" placeholder="Top. alış fiyatı" value="` +
                        res
                        .alis + `">
                <label class="btn ">KDV 18 <input type="radio" name="kdv" class="kdv" value="18" ` + k18 + `></label>
                <label class="btn"><input class="kdv" type="radio" name="kdv" value="1" ` + k0 +
                        `> KDV 0</label>
                <input type="text" id="notx" maxlength="200" class="swal2-input form-control" placeholder="Not" value="` +
                        res.notx + `">
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
                                tedarikci: tedarikci
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
                            user: res.user
                        }, function(x) {
                            if (x == '200') {
                                setTimeout(function() {
                                    location.href = "siparisler?sms=" + res
                                        .user;
                                }, 500);
                            } else {
                                swal.fire('Kayıt sırasında hata oluştu', 'error');
                            }
                        })
                    })
                });

            });

        })

        $('.red').click(function() {
            let id = $(this).attr('id');
            let uid = $(this).attr('uid');

            swal.fire({
                html: 'Talep iptal edilerek, tutar kullanıcıya iade edilecek<br><br><input type="text" id="notxx" maxlength="200" class="swal2-input form-control" placeholder="Red için bir sebep girin">',
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





<?php
/* $date1 = @$_GET['date1'];
$date2 = @$_GET['date2'];

$date1 = date('Y-m-d', strtotime('-0 days'));
$date2 = date('Y-m-d');

if (isset($_GET['status'])) {
    $status = $_GET['status'];
} else {
    $status = '0';
}
if ($status == '0') {
    $ek = ' ';
} elseif ($status == '1') {
    $ek = " and durum='Onay Bekliyor' ";
} elseif ($status == '2') {
    $ek = " and durum='Başarılı' ";
} elseif ($status == '3') {
    $ek = " and durum='İptal' ";
}

$sor = DB::select(
    "
                                select es.*, gp.title,u.name,t.title ted,u.email,gp.api_info
                                from epin_siparisler es
                                left JOIN games_packages_codes_suppliers t on t.id=es.tedarikci
                                left join users u on u.id=es.user
                                left JOIN games_packages gp on es.oyun=gp.id
                                where date(es.created_at) between '$date1' and '$date2' " .
        $ek .
        ' order by es.created_at',
);
$totalAlis = 0;
$anomaly = [];
foreach ($sor as $i) {
    var_dump($i->alis);
    if(isset($i->alis) && isset($i->tutar)) {
        if($i->tutar < $i->alis) {
            array_push($anomaly,  $i->user);
        }
        $totalAlis += floatval($i->alis);
    }
    
    echo '<br>';
}
var_dump(number_format($totalAlis, 2));
echo '<br>';
var_dump($anomaly); */
?>
