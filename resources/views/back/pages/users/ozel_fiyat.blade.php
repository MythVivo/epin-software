<?

if (@$_GET['cpy'] == 316) {
    $p = (object)$_GET;
    $cpy=DB::table('camp_gg_py')->where('id',$p->id)->first();
    $adm=Auth::user()->id;
    $tar=date('YmdHis');
    DB::select("insert into camp_gg_py (user,indirim,admin,aktif,created_at) values('$p->uid','$cpy->indirim','$adm','0','$tar')");

    die();
}

#----------------------------------------------------------Aktif pasif
if (@$_GET['durum'] == 316) {
    $p = (object) $_GET;
    DB::select("UPDATE camp_gg_py SET aktif = IF(aktif=1, 0, 1) where id='$p->id'");
    die();
}
#----------------------------------------------------------ORAN update
//if (@$_GET['kup'] == 316) {
//    $p = (object) $_GET;
//    DB::table('camp_gg_py')->where('id', $p->id)->update(['py_rise_oran' => $p->r,'py_knight_oran' => $p->k]);
//die();
//}
#----------------------------------------------------------user ekle
if (@$_GET['uad'] == 316) {
    $p = (object)$_GET;
    $al = DB::table('camp_gg_py')->where('user', $p->user)->whereNull('deleted_at')->get();

    if ($al->count() > 0) { // kullanıcı varsa tekrar eklenmiyor urun listesi update ediliyor

        $js = json_decode($al[0]->indirim, true);

        // Eklenen urun zaten mevcutsa eskisi siliniyor değilse boş geçiyor yeni eleman ekleniyor
        $arr_index = array();
        foreach ($js as $key => $value) {
            if ($value['id'] == $p->uid) {
                $arr_index[] = $key;
            }
        }
        foreach ($arr_index as $i) {
            unset($js[$i]);
        } //sil
        $js = array_values($js);
        // yeni map edilerek build edilen eski dizi içine push ediliyor
        $yeni = array('id' => $p->uid, 'alis' => $p->saf, 'satis' => $p->ssf, 'aoran' => $p->ao,'soran' => $p->so);
        array_push($js, $yeni);
        $son_js = json_encode($js);

        DB::table('camp_gg_py')->where('user', $p->user)->update(['indirim' => $son_js, 'admin' => Auth::user()->id]);

    } else { //yoxa insert

        $json = json_encode(array(array('id' => $p->uid, 'alis' => $p->saf, 'satis' => $p->ssf, 'aoran' => $p->ao,'soran' => $p->so)));
        DB::table('camp_gg_py')->insert([
            'user' => $p->user,
            'indirim' => $json,
            'aktif' => 1,
            'admin' => Auth::user()->id,
            'created_at' => date('YmdHis')
        ]);
    }

    die();
}

#----------------------------------------------------------urun sil JSON
if (@$_GET['jsil'] == 316) {
    $p = (object)$_GET;
    $al = DB::table('camp_gg_py')->where('user', $p->user)->get();
    $json_arr = json_decode($al[0]->indirim, true);
    $arr_index = array();
    foreach ($json_arr as $key => $value) {
        if ($value['id'] == $p->id) {
            $arr_index[] = $key;
        }
    }
    foreach ($arr_index as $i) {
        unset($json_arr[$i]);
    } //sil
    $json_arr = array_values($json_arr);
    DB::table('camp_gg_py')->where('user', $p->user)->update(['indirim' => json_encode($json_arr)]);

    die();
}

#----------------------------------------------------------uye sil
if (@$_GET['ksil'] == 316) {
    $p = (object)$_GET;
    DB::select("delete from camp_gg_py where id='$p->id'");
    die();
}

?>

@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}"
            rel="stylesheet" type="text/css">
    <style>
        table.dataTable.nowrap td:nth-child(2) {
            white-space: unset !important;
        }

        .genis {
            width: 900px !important;
        }

        hr {
            border-top-color: currentcolor !important;;
        }

        table tr, td {
            border: thin !important;
            border-style: double !important;
            text-align: center !important;
        }
    </style>

@endsection
@section('body')
    <div class="row" id="eklemek" data-lang="{{getLang()}}">
        <div class="col-12">
            <div class="card">
                <div class="row" style="display: flex; justify-content: flex-end; align-items: center">
                    <input type="text" class="form-control-sm" style="text-align: center" id="arama" placeholder="Üye Arama">
                    <button class="btn btn-primary m-1" onclick="$('#notx').toggle('slow')"><li class="fa-question fa "></li></button>
                    <button class="btn btn-success m-1" style="width: fit-content;align-self: self-end;" onclick="$('.ekleme').toggle();$('#q').val('');$('#isim').empty();">
                        GAME GOLD ÜYE EKLE
                    </button>
                    <button class="btn btn-success m-1" style="width: fit-content;align-self: self-end;" onclick="location.href='kampanya'">
                        EPIN GRUBU INDIRIM TABLOSU
                    </button>
                </div>
<div style="display: none; padding: 10px" id="notx">
    <b>Bu Sayfada Ne Nedir ?</b>
    <li>Oran değeri alış ve satış için ayrı girilmelidir</li>
    <li>Sabit Alış veya Satış rakamı belirtilmişse Oran geçersiz kalacaktır.</li>
    <li>Geçerli olan indirim alanı Siyah zemin olarak gösterilir</li>
    <li>Aynı kullanıcı tekrar listeye eklenmez düzenleme olarak işlem görür</li>
    <li>Aynı ürün tekrar aynı kullanıcıya eklenmez düzenleme olarak işlem görür</li>
    <li>Ürün oranlarını düzenlemek/değiştirmek için ürünü yeni oranlarıyla aynı kullanıcıya tekrar ekleyin</li>
    <li>Ürünleri Yazarak değil, listeden seçerek ekleyin her ne kadar debug edilse de hataya sebebiyet verebilirsiniz</li>
    <li>Tüm ürün listesi için % % % girin </li>
    <li> %1 oran girmek için ondalık kullanmadan sadece 1 girin</li>

</div>
                <div class="card-body" style=" display: flex; flex-direction: column; align-items: center; ">

                    <div class="kduz" style="position: absolute; padding: 10px; background-color: #0c0c0c; border-radius: 20px; display: none; padding: 10px">
                        <div class="col mt-2 py" style="border-style: groove;border-radius: 20px; text-align: center; padding: 10px ">
                            <label class="mt-2">Pazar Yeri İlanları </label> -
                            <label style="color:red " id="py_isim"></label>
                            <div class="row">
                                <div class="col-6">
                                    <label>Rise Komisyon Oranı %</label><input type="text" id="rrkom" class="form-control border-gray">
                                </div>
                                <div class="col-6">
                                    <label>Knight Komisyon Oranı %</label><input type="text" id="kkkom" class="form-control border-gray mb-3">
                                </div>
                            </div>
                            <button class="btn btn-warning" onclick="$('.kduz').hide('slow')">Kapat</button>
                            <button class="btn btn-success kk" id="">Kaydet</button>
                        </div>
                    </div>

                    <div id="ekleme" class="ekleme" style="display: none; position: absolute; padding: 10px; background-color: #0c0c0c; border-radius: 20px">
                        <div class="mb-5">
                            <div class="col mt-3 us" style="border-top-style: groove;border-radius: 20px; ">
                                <label>Üye Seçin</label>
                                <div class="col__search">
                                    <div class="input-group search-element">
                                        <button id="search_button" class="btn btn-outline-white">
                                            <i class="fa fa-search"></i></button>
                                        <input placeholder="Kullanıcı Email/İsim araması için en az 5 karakter" id="q" class="form-control style-input border-success">
                                    </div>
                                    <div class="result__body mt-3">
                                        <div class="result__body__inner">
                                            <ol></ol>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col mt-3 gg" style="border-top-style: dashed; border-bottom-style: dashed; border-width: thin ">
                                <label class="mt-2">Game Gold</label> - <label style="color:red " id="isim"></label>
                                <div class="col__search">
                                    <div class="input-group search-element">
                                        <button id="search_button" class="btn btn-outline-white">
                                            <i class="fa fa-search"></i></button>
                                        <input placeholder="İndirim tanımlanacak GG paket araması için en az 3 karakter" id="q2" class="form-control style-input border-success">
                                    </div>
                                    <div class="result__body2 mt-3">
                                        <div class="result__body__inner2">
                                            <ol></ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="row ggf ">
                                    <div class="col-3"><label>Sabit Alış Fiyatı</label>
                                        <input type="text" id="saf" class="form-control border-gray">
                                    </div>
                                    <div class="col-3"><label>Sabit Satış Fiyatı</label>
                                        <input type="text" id="ssf" class="form-control border-gray">
                                    </div>
                                    <div class="col-3"><label>Alış Oran %</label>
                                        <input type="text" id="ao" class="form-control border-gray mb-3">
                                    </div>
                                    <div class="col-3"><label>Satış Oran %</label>
                                        <input type="text" id="so" class="form-control border-gray mb-3">
                                    </div>
                                </div>
                            </div>

                            <div class="col mt-2 py" style="border-bottom-style: groove;border-radius: 20px; display: none ">

                                <label class="mt-2">Pazar Yeri İlanları </label>
                                <div class="row">
                                    <div class="col-6">
                                        <label>Rise için Komisyon Oranı %</label><input type="text" id="rkom" class="form-control border-gray">
                                    </div>
                                    <div class="col-6">
                                        <label>Knight için Komisyon Oranı %</label><input type="text" id="kkom" class="form-control border-gray mb-3">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-success mb-1 mt-2 card-img-bottom ekle">Yukarıdaki Bilgileri Tabloya Ekle</button>
                            <button class="btn btn-warning mb-1 card-img-bottom ipt" onclick="$('.ekleme').hide('slow');$('.py').show();$('.us').show();">Kapat</button>
                        </div>
                    </div>
                    Game Gold için Özel Fiyat Tanımlı Üye Tablosu
                    <table id="tablo" class="table font-12 table-sm text-body mt-1">
                        <tr>
                            <td>No</td>
                            <td>Uid</td>
                            <td>Üye</td>
                            <td colspan="4">Tanımlı ürün ve Oranlar</td>
                            <td>Durum</td>
                            <td>Admin</td>
                            <td>Tarih</td>
                            <td>Düzen</td>
                        </tr>

                        <?
                        $al = DB::select("select cc.*, u.name, uu.name adm  from camp_gg_py cc
                        join users u on u.id=cc.user
                        join users uu on uu.id=cc.admin
                        ");
                        $no = 0;
                        $noo = 0;
                        ?>
                        @foreach($al as $i)
                                <? $no++; ?>
                            <tr>
                                <td>{{$no}}</td>
                                <td>{{$i->user }}</td>
                                <td class="uad">{{$i->name}} </td>
                                <td colspan="4">
                                    <table id="ic" style="width: 100%" class="table-striped">
                                        <tr style="background-color: #0B2C5F">
                                            <th>No</th>
                                            <th>Ürün adı</th>
                                            <th>Alış</th>
                                            <th>Satış</th>
                                            <th>A.Oran</th>
                                            <th>S.Oran</th>
                                            <th>Sil</th>
                                        </tr>
                                        @foreach (json_decode($i->indirim) as $j)
                                            <? $noo++;
                                                if($j->alis>0)  {$abck="background-color: black";} else {$abck='';}
                                                if($j->satis>0) {$sbck="background-color: black";} else {$sbck='';}
                                                if($j->aoran>0 && $j->alis<=0) {$aobck="background-color: black";} else {$aobck='';}
                                                if($j->soran>0 && $j->satis<=0) {$sobck="background-color: black";} else {$sobck='';}
                                            ?>
                                            <tr>
                                                <td>{{$noo}}</td>
                                                <td>{{DB::select("select title from games_packages_trade where id='$j->id'")[0]->title}}</td>
                                                <td style="{{$abck}}">{{$j->alis}}</td>
                                                <td style="{{$sbck}}">{{$j->satis}}</td>
                                                <td style="{{$aobck}}">%{{$j->aoran }}</td>
                                                <td style="{{$sobck}}">%{{$j->soran }}</td>
                                                <td>
                                                    <i title="Sil" id="{{$j->id}}" user="{{$i->user}}" class="btn btn-sm far fa-trash-alt sil text-danger"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <? $noo=0;?>

                                    </table>
                                </td>
                                <td><a style="cursor: pointer" iid="{{$i->id}}"  class="durum btn-sm {{ $i->aktif==1?'btn-success':'btn-warning' }}">{{ $i->aktif==1?'Aktif':'Pasif' }}</a></td>
                                <td>{{ $i->adm }}</td>
                                <td>{{ $i->created_at }}</td>
                                <td>
                                    <a href="#eklemek"><i title='Ürün Ekle' id="{{$i->user}}" ad="{{$i->name}}" class='btn-sm btn fas fa-plus text-success uekle'></i></a>
                                    <i title='Bu satırı çoğalt' id="{{$i->id}}" class='btn-sm btn fas fa-copy cpy'></i>
                                    <i title='Üyeyi Sil' id="{{$i->id}}" class='btn-sm btn fas fa-times text-danger uyesil'></i>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('js')
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
    <script>

// kopyalama
        $('.cpy').click(function () {
            var id = $(this).attr('id');
            $('.ekleme').show().css('width','600px');
            $('.gg').hide();
            $('.ggf').hide();
            $('.ekle').text('- Seçilen İsimle Kopyala -');
            $('.ekle').attr('id',id);
            location.href='#eklemek';
        });


            //--------------------------Arama muhabbeti
            $(document).ready(function(){
            $("#arama").on("keyup", function() {var value = $(this).val().toLowerCase();
                $("#tablo td.uad").filter(function() {$(this).closest('tr').toggle($(this).text().toLowerCase().indexOf(value) > -1)});
            });
        });

// Aktif - Pasif stat. xor

        $('.durum').click(function (){
            var bu=$(this);
                $.get('',{durum:316, id:$(this).attr('iid')},function (){
                    if(bu.text()=='Aktif') {bu.text('Pasif'); bu.removeClass('btn-success'); bu.addClass('btn-warning')} else {bu.text('Aktif');bu.removeClass('btn-warning'); bu.addClass('btn-success')}
                })
        })

        // ------------------------------------------------------ PY oran düzenle
        // $('.dzn').click(function () {$('.kk').attr('id',$(this).attr('id'))  ;$('#py_isim').empty().text($(this).attr('ad')); $('.kduz').show();});
        // $('.kk').click(function (){
        //    if(confirm("Üye komisyon oranları güncellenecek dewam edilsin mi ?")) {
        //        $.get('', {kup:316,r:$('#rrkom').val(),k:$('#kkkom').val(),id:$(this).attr('id')}, function (x) {location.reload();});
        //    } else {
        //        swal.fire({icon:'error',html:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});
        //        $('.kduz').hide('slow');
        //    }
        // });

        // ------------------------------------------------------urun ekle

        $('.uyesil').click(function () {
            if (confirm('Üye ve üyeye ait tüm tanımlar silinecek..!')) {
                $.get('', {ksil:316, id: $(this).attr('id')}, function (x) {
                    location.reload();
                });
            } else {
                swal.fire({icon:'error',html:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});
            }
        });


        // ------------------------------------------------------urun ekle

        $('.uekle').click(function () {

            $('.ekleme').show();
            $('.py').hide();
            $('.us').hide();
            $('#q').val($(this).attr('id') + '-xxxxxxxxxxx');
            $('#isim').empty().text('Ekleme Yapılan Üye -> ' + $(this).attr('ad'));


        })


        // ------------------------------------------------------urun Jsil

        $('.sil').click(function () {
            if (confirm('Ürün kullanıcının indirim listesinden silinecek..!')) {
                $.get('?', {jsil: 316, id: $(this).attr('id'), user: $(this).attr('user')}, function (x) {
                    location.reload();
                });
            } else {
                swal.fire({icon: 'error', html: 'İşlemden Vazgeçildi', showConfirmButton: false, timer: 1500});
            }
        })


        // ------------------------------------------------------uye ekle

        $('.ekle').click(function () {
            if ($('#q').val().length < 10) {
                swal.fire({icon: 'error', title: 'Bir üye seçmeniz gerekiyor', showConfirmButton: false, timer: 1500});
                return
            }
            if($('.ekle').text()[0]=='-') { // copy mod
                $.get('',{cpy:316, id:$('.ekle').attr('id'), uid: $('#q').val().split('-')[0]},function (){
                    location.reload();
                })
                return;
            }

            if ($('#q2').val().length < 10 ) {
                swal.fire({icon: 'error', title: 'Bir ürün seçmeniz gerekiyor', showConfirmButton: false, timer: 1500});
                return;
            }

            if($('#q2').val().indexOf('-')<0){
                swal.fire({icon: 'error', title: 'Listeden bir ürün seçmeniz gerekiyor', showConfirmButton: false, timer: 1500});
            return;}

            if(Number.isInteger(parseInt($('#q2').val().split('-')[0]))){} else {
                swal.fire({icon: 'error', title: 'Listeden bir ürün seçmeniz gerekiyor', showConfirmButton: false, timer: 1500});
            return;}


            $.get('?uad=316',
                {
                    user: $('#q').val().split('-')[0],
                    uid: parseInt($('#q2').val().split('-')[0]),
                    saf: $('#saf').val().replace(',','.'),
                    ssf: $('#ssf').val().replace(',','.'),
                    ao: $('#ao').val(),
                    so: $('#so').val()
                }, function (x) {
                    location.reload();
                });

            return;
        });


        // ------------------------------------------------------uye arama
        $('#q').keyup(function (x) {
            if ($('#q').val().length < 5) {
                return false;
            }

            $('.result__body__inner ol')[0].innerHTML = "";
            $('.result__body').show();
            $.post('/live.php', {sec1: $('#q').val()}, function (x) {let oyun = JSON.parse(x);
                if (oyun.length) {$('.result__body__inner ol').empty();
                    for (o in oyun) {
                        $('.result__body__inner ol').append("<li><a href='#' onclick='$(\".result__body\").hide();$(\"#q\").val($(this).text())'>" + oyun[o].id + "- " + oyun[o].name + " - " + oyun[o].email + "</a></li>");
                    }
                } else {
                    $('.result__body__inner ol').html("Aramadan bi sonuç çıkmadı :( ");
                }
            });

            $(document).on("click", function (x) {
                if (!x.target.closest(".col__search")) {
                    $('.result__body').hide();
                }
            });
        });



        // ------------------------------------------------------GG arama
        $('#q2').keyup(function (x) {
            if ($('#q2').val().length < 3) {
                return false;
            }
            //new Promise(resolve => setTimeout(resolve, 300));
            $('.result__body__inner2 ol')[0].innerHTML = "";
            $('.result__body2').show();
            $.post('/live.php', {sec3: $('#q2').val()}, function (x) {
                let oyun = JSON.parse(x);
                if (oyun.length) {$('.result__body__inner2 ol').empty();
                    for (o in oyun) {
                        $('.result__body__inner2 ol').append("<li><a href='#' onclick='$(\".result__body2\").hide();$(\"#q2\").val($(this).text())'>" + oyun[o].id + "- " + oyun[o].title + " - [A->" + oyun[o].alis_fiyat + " | S->" + oyun[o].satis_fiyat + " TL]</a></li>");
                    }
                } else {
                    $('.result__body__inner2 ol').html("Aramadan bi sonuç çıkmadı :( ");
                }
            });

            $(document).on("click", function (x) {
                if (!x.target.closest(".col__search")) {
                    $('.result__body2').hide();
                }
            });
        });

    </script>
@endsection
