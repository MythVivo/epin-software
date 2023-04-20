<?
#---------------------------------------------SMS
if(@$_GET['t'] && @$_GET['m']){sendSms($_GET['t'],urldecode(base64_decode($_GET['m'])));}
#---------------------------------------------İlan detay
if(@$_GET['detay']){
    $p = (object) $_GET;
    $sor=DB::select("select pyi.*, gt.title pz, u.email, u.name, u.id uid, gt.link
            from pazar_yeri_ilanlar pyi
            left join users u on u.id=pyi.user
            left join games_titles gt on gt.id=pyi.pazar
            where pyi.id='$p->detay'
            ");
    die(json_encode($sor));

}
#--------------------------------------------- Set ilan
if(@$_GET['set_mi']){
    $p = (object) $_GET;
    $sets = str_replace('#', ',', substr($p->set_mi, 0, -1));
    $res = DB::select("SELECT gt.id, gt.title, gt.description, gp.image FROM games_titles_items_info gt LEFT JOIN games_titles_items_photos gp on gp.item=gt.id where gt.id in($sets)");

$yaz="<div class='col-12 mt-3' style='display: flex;flex-direction: column;flex-wrap: wrap;align-items: center;background-color: black;border-style: solid;'> <h5>SET İÇERİĞİ</h5><ol type='1'>";
        foreach($res as $r){$yaz.="<div style='display: flex'><input style='margin: 0 20px' type='checkbox'> <li>$r->title</li></div>";}
$yaz.="</ol></div>";

        die($yaz);

}
#---------------------------------------------Foto
if(@$_GET['foto']){
    $p = (object) $_GET;
    $ilanIcerik = DB::table('pazar_yeri_ilan_icerik')->where('ilan', $p->foto)->first();
    if ($ilanIcerik)
    {
        $photo = DB::table('games_titles_items_photos')->where('item', $ilanIcerik->item)->first();
        if ($photo){$photo = '/front/games_items/' . $photo->image;}
    }
    $photo = @$p->img ?  "/public_html/front/ilanlar/".$p->img :$photo;

$res="<img src='$photo' width='300px' style='border-style: solid;border-color: bisque;'>";
    die($res);

}

#---------------------------------------------satis  check

if(@$_GET['kont']) {$p = (object) $_GET;
    echo DB::table('pazar_yeri_ilan_satis')->where('ilan', $p->kont)->count();
die();
}

?>

@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}"
          rel="stylesheet">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}"
          rel="stylesheet" type="text/css">
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
    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">
                        <div class="row">

							<div class="col-md-12 text-left">
										@if(isset($_GET['date1']) and isset($_GET['date2']))
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
                                        <? $uid=isset($_GET['uid'])?$_GET['uid']:''; ?>

										<form class="row" method="get">
											<div class="col-sm-12 col-md-2">
												<div class="mb-3">
													<label class="form-label" for="userinput1">İlk Tarih</label>
													<input type="date" id="userinput1" class="form-control style-input" name="date1" value="{{$date1}}" required>
												</div>
											</div>
											<div class="col-sm-12 col-md-2">
												<div class="mb-3">
													<label class="form-label" for="userinput2">Son Tarih</label>
													<input type="date" id="userinput2" class="form-control style-input" name="date2" value="{{$date2}}" required>
												</div>
											</div>
											<div class="col col-md-1 justify-content-sm-start justify-content-md-end align-items-center">
                                                <button type="submit" class="btn btn-sm btn-outline-success btn-block mt-2" title="Güncelleme tarihine göre (Updated_at)">Sorgula_U</button>
                                                <input type="submit" name="c" value="Sorgula_C" class="btn btn-sm btn-outline-success btn-block mt-2" title="Oluşturulma tarihine göre (Created_at)">
											</div>
										</form>

                                        <form class="row" method="get">
											<div class="col-sm-12 col-md-2">
												<div class="mb-3">
													<input type="text" placeholder="İlan ID " class="form-control style-input" name="uid" value="{{$uid}}" required>
												</div>
											</div>
											<div class="col-sm-12 col-md-1  justify-content-sm-start justify-content-md-end align-items-center">
                                                <button type="submit" class="btn-sm btn-outline-success btn-block color-blue">Ara	</button>
                                            </div>
										</form>


						@if(@$_GET['new']!=316)
                            <div class="col-md-12 text-center">
                                    <button class="btn btn-sm btn-outline-info" onclick="filtrele('')">Tümü</button>
                            @foreach(getIlanStatus() as $abc)
                                    <button class="btn btn-sm btn-outline-info" onclick="filtrele('{{$abc}}')">{{$abc}}</button>
                                @endforeach
                            </div>
                        @endif
                        </div>
                    </h4>
                    <div class="table-responsive erh">
                        {{view('back.pages.ilanlar.table',['date1'=>$date1,'date2'=>$date2,'uid'=>$uid])}}
                    </div>
                </div>
            </div>

        </div>
    </div>




    <div class="modal modal-xl-centered fade" id="SMS_S" tabindex="-1"  aria-hidden="true" >
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">SMS Gönderim Penceresi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                <div class="modal-body">
                    <div class="row">
                        Telefon no : <input type="text" class="form-control" id="telno" >
                        <br>
                        <label>Gönderilecek Mesaj: <i id="kac"></i></label>
                        <textarea class="form-control" id="mess" style="height: 130px"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="zend" class="btn btn-sm btn-success">Gönder</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')

    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/js/dropify.min.js')}}"></script>
    <script>

        var tel,uid,ak;
        // -----------------------SMS Göderim-------------------------------
        $('.sms').click(function (){tel= $(this).attr('uid'); if(tel.length<10) {swal.fire({icon:'error',html:'Telefon Numarası Kayıtlı değil yada hatalı',showConfirmButton: false,timer:2500});} else {$('#telno').val(tel);$('#SMS_S').modal('show');}});
        $('#mess').keyup(function (){$('#kac').empty().text($('#mess').val().length);})
        $('#kac').empty().text($('#mess').val().length);
        $('#zend').click(function (){location.href='?t='+tel+'&m='+btoa(encodeURIComponent($('#mess').val()));})
        @if(@$_GET['t'] && @$_GET['m'])
        swal.fire({icon:'success',html:'SMS Gönderildi',showConfirmButton: false,timer:1500});
        @endif
        // -----------------------SMS Göderim-------------------------------



        @if(@$_GET['new']!=316)

   // $('.nav-link').trigger('click');


        $('.goster').click(function (){
    $('.detay').modal('show'); var d;
            let id=$(this).attr('id');
            $.get('', {detay: id}, function (x) {
                var g=JSON.parse(x);

                $('#baslik').text(g[0].title);
                $('#pzryeri').text(g[0].pz);
                $('#ulnk').attr('href','https://oyuneks.com/panel/uye-detay/'+g[0].email);
                $('#ulnk').text(g[0].name);
                $('#uhar').attr('onclick',"window.open('https://oyuneks.com/panel/uye-aktivite?uid="+g[0].uid+"')");
                $('#fiyat').text(g[0].price.toFixed(2)+' TL');
                $('#komis').text(g[0].moment_komisyon.toFixed(2)+' TL');
                $('#ubas').text(g[0].title);
                $('#sunuc').text(g[0].sunucu);
                $('#stats').text(stat(g[0].status));
                $('#created').text(g[0].created_at);
                $('#acik').text(g[0].text);

                if(g[0].status==2){$('#redn').text("Red Nedeni :"+g[0].red_neden);}

                if(g[0].grup !==null && g[0].grup!='' && g[0].grup.length>2) {$.get('', {set_mi: g[0].grup}, function (x) {$('#seticerik').html(x)});} else {$('#seticerik').html('')}
                $('#foto').html('Yükleniyor..');
                $.get('', {foto: g[0].id,img:g[0].image}, function (x) {$('#foto').html(x)});

                $('#butonlar').empty();

                if(g[0].status == 0 || g[0].status == 1 || g[0].status == 3) {
                    d='<div class="col-md-4">';
                    if(g[0].status == 0 || g[0].status == 3){d+='<button onclick="onay_al('+g[0].id+',1)" class="btn btn-outline-success w-100">İlanı Yayınla</button>';}
                    if(g[0].status == 1){d+='<button onclick="onay_al('+g[0].id+',3)" class="btn btn-outline-danger w-100">İlanı Yayından Kaldır </button>';}
                    $('#butonlar').append(d+'</div>');d='';
                }

                if( g[0].status == 0){
                    $('#butonlar').append('<div class="col-md-2"></div>');
                    d='<div class="col-md-6 ">';
                    d+='<form method="post" action="{{route('ilanlar_yonetim_onay_red')}}">';
                    d+='@csrf';
                    d+='<input type="hidden" name="id" value="'+g[0].id+'">';
                    d+='<input type="hidden" name="durum" value="2">';
                    d+='<div class="row">';
                    d+='<div class="col"> <input type="text" class="form-control" name="red_neden" placeholder="Red Nedeni" required></div>';
                    d+='<div class="col">';
                    d+='<button class="btn btn-outline-danger w-100">İlan Yayınını Reddet</button>';
                    d+='</div></div></form></div>';
                    $('#butonlar').append(d);d='';
                }

                if(g[0].status == 4 || g[0].status == 5){
                    d='<div class="col">';
                        if(g[0].status == 4){
                            d+='<button onclick="onay_al('+g[0].id+',5)" class="btn btn-outline-primary w-100">Site itemi aldı olarak işaretle</button>';
                            d+='<button onclick="onay_al('+g[0].id+',3,\'-ozel\')"  class="btn btn-outline-danger w-100 mt-2">Satış Başarısız Olarak İşaretle ve Alıcıya Parasını İade Et</button>';
                        }
                        if(g[0].status == 5){
                            d+='<button onclick="onay_al('+g[0].id+',6)"  class="btn btn-outline-success w-100">Satış Başarılı Olarak İşaretle Ve Parayı Satıcıya Gönder </button>';
                        }
                    $('#butonlar').append(d+'</div>');d='';
                }

                $.get('', {kont:g[0].id }, function (x) {
                    if(x>0) {
                        $('#butonlar').append('<div class="col-12"><hr></div>');
                        d='<div class="col-12"><h4 class="card-title">';
                            if(g[0].money_status == 0){ d+='Satıcı Henüz Ödemesini Almamış';} else {d+='Satıcı Ödemesini Almış';}
                        $('#butonlar').append(d+'</h4></div>');
                            d='';

                            if(g[0].money_status == 0){
                                $('#butonlar').append('<div class="col"><button onclick="onay_al('+g[0].id+',1,\'-ozel\')" class="btn btn-outline-success">Satıcının Parasını Gönder ('+g[0].moment_komisyon+' TL) </button></div>');
                            }else{
                                $('#butonlar').append('<div class="col"><button onclick="onay_al('+g[0].id+',0,\'-ozel\')" class="btn btn-outline-danger">Satıcıdan Parayı Geri Al ('+g[0].moment_komisyon+' TL) </button></div>');
                            }
                        $('#butonlar').append('<div class="col"><button onclick="onay_al('+g[0].id+',3,\'-ozel\')" class="btn btn-outline-warning m-3">Alıcıya Parasını Geri Gönder ('+g[0].price+' TL) </button></div>');

                    }
                });


            });
        });

function stat(status)
{
           if (status == 0) {return "Onay Bekliyor";
    } else if (status == 1) {return "Yayında";
    } else if (status == 2) {return "Red Edildi";
    } else if (status == 3) {return "Pasif";
    } else if (status == 4) {return "Site İtemi Bekliyor";
    } else if (status == 5) {return "İtem Sitede";
    } else if (status == 6) {return "İtem Satışı Başarılı";
    } else {return "Silindi / Yayınlanmıyor";}
}

@endif

        $(function () {
            $('.dropify').dropify({
                height: "300",
                allowedFileExtensions: ["png", "jpg", "jpeg", "gif"],
                messages: {
                    default: '{{__('admin.dropifyResimSec')}}',
                    replace: '{{__('admin.dropifyDegistir')}}',
                    remove: '{{__('admin.dropifySil')}}',
                    error: '{{__('admin.dropifyHata')}}',
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
        setTimeout(function (){$('.erh').addClass('erhx');},1000)


            $('#datatable thead tr').clone(true).appendTo('#datatable thead');
            $('#datatable thead tr:eq(1) th').each(function (i) {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="' + title + '" />');
                $('input', this).on('keyup change', function () {
                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });
            });
            $('#datatable input[type="text"]').css(
                {'width': '100%', 'display': 'inline-block'}
            );
            var table = $('#datatable').DataTable({
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();
                    nb_cols = api.columns().nodes().length;
                    var j = 4;
                    var e = 3;
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\Adet,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                    var intVal2 = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\TL,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                    while (j < 4) {
                        total = api.column(j).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                        pageTotal = api.column( j, { page: 'current'} ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
                        sumCol4Filtered = display.map(el => data[el][j]).reduce((a, b) => intVal(a) + intVal(b), 0 );
                        $( api.column(j).footer() ).html(sumCol4Filtered + " Adet");
                        j++;
                    }
                    while (e < 5) {
                        total = api.column(e).data().reduce( function (a, b) {return intVal2(a) + intVal2(b);}, 0 );
                        pageTotal = api.column( e, { page: 'current'} ).data().reduce( function (a, b) {return intVal2(a) + intVal2(b);}, 0 );
                        sumCol4Filtered = display.map(el => data[el][e]).reduce((a, b) => intVal2(a) + intVal2(b), 0 );
                        $( api.column(e).footer() ).html(sumCol4Filtered.toLocaleString());
                        e++;
                    }
                },

                columnDefs: [{orderable: false, targets: [10]}],
                pageLength: 100,
                orderCellsTop: true,
                fixedHeader: true,
                lengthChange: false,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: '{{getPageTitle(getPage(), getLang())}} - Excel',
                        text: '<i class="far fa-file-excel"></i>',
                        className: 'btn btn-outline-success',
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        title: '{{getPageTitle(getPage(), getLang())}} - Csv',
                        text: '<i class="fas fa-file-csv"></i>',
                        className: 'btn btn-outline-info',
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: '{{getPageTitle(getPage(), getLang())}} - Pdf',
                        className: 'btn btn-outline-danger',
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        text: '<i class="far fa-file-pdf"></i>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },

                ],
                "order": [[9, "desc"]],
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

            filtrele = function (gelen)
            {
                table.search(gelen).draw();
            }
            $('#datatable').dataTable().fnFilter($("#table-filter").val());

            table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(1)');
            $('#datatable_filter').css(
                {'display': 'none'}
            );
        });
    </script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
    <script>

    function onay_al(uid, durum, ozel) {
        if (typeof ozel == 'undefined') {ozel='';}
            swal.fire({
                title: "Operatör Farkındalık Onayı",
                text: "İşlem onaylanacak emin misiniz ?",
                icon: "info",
                type: 'error',
                showCancelButton: true,
                confirmButtonText: '{{__("admin.onayliyorum")}}',
                cancelButtonText: '{{__("admin.vazgec")}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href= "https://oyuneks.com/panel/ilanlar-yonetim-onay"+ozel+"/"+ uid +"/"+ durum;
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire(
                        '{{__("admin.iptal-edildi")}}',
                        '',
                        'error'
                    )
                }
            })
        }


        function deleteContent(table, id) {
            swal.fire({
                title: "{{__("admin.silme")}}",
                text: "{{__("admin.silmeText")}}",
                icon: "info",
                type: 'error',
                showCancelButton: true,
                confirmButtonText: '{{__("admin.onayliyorum")}}',
                cancelButtonText: '{{__("admin.vazgec")}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get("{{route('deleteContent')}}", {table: table, id: id});
                    swal.fire(
                        '{{__("admin.basarili")}}',
                        '{{__("admin.basariliMetin")}}',
                        'success'
                    )
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire(
                        '{{__("admin.iptal-edildi")}}',
                        '',
                        'error'
                    )
                }
            })
        }

    </script>
    <script>
        setInterval(function () {
            $.get("{{route('getIlanCont')}}", function (data) {
                if (data == "1") {
                    swal.fire({
                        title: "Yeni Sipariş",
                        text: "Yeni bir sipariş var, görmek ister misiniz",
                        icon: "info",
                        type: 'success',
                        showCancelButton: true,
                        confirmButtonText: '{{__("admin.onayliyorum")}}',
                        cancelButtonText: '{{__("admin.vazgec")}}',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            swal.fire(
                                '{{__("admin.basarili")}}',
                                '{{__("admin.basariliMetin")}}',
                                'success'
                            )
                            setTimeout(function () {
                                location.reload();
                            }, 500);
                        } else if (
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            swal.fire(
                                '{{__("admin.iptal-edildi")}}',
                                '',
                                'error'
                            )
                        }
                    })
                }
            });
        }, 10000);

    </script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/tinymce/tinymce.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            if ($("#3").length > 0) {
                tinymce.init({
                    selector: ".editorText",
                    theme: "modern",
                    language: '{{getLang()}}',
                    height: 300,
                    plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "save table contextmenu directionality emoticons template paste textcolor"
                    ],
                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
                    style_formats: [
                        {title: 'Bold text', inline: 'b'},
                        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                        {title: 'Example 1', inline: 'span', classes: 'example1'},
                        {title: 'Example 2', inline: 'span', classes: 'example2'},
                        {title: 'Table styles'},
                        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                    ]
                });
            }
        });
    </script>
@endsection
