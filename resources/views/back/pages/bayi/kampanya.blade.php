<?

//$dis = \App\Models\Campaigns::GetCampaign(49381, 1255);
//dd($dis);

#-------------------Buna bakacaz kategori altındaki ürünler
if(@$_GET['ctt']) {$ctt='<ol>';
    $p = (object) $_GET;
    foreach (DB::select("select id,title from games_packages where games_titles='$p->ctt' and deleted_at is null order by title ") as $urr) {
        $ctt .= "<small><li>".$urr->title."</li></small>";
    }
    echo $ctt."</ol>";
    die();
}
#-------------------------------------------Sil
if(@$_GET['kamp_sil']) {$p = (object) $_GET;DB::select("delete from campaigns where id='$p->kamp_sil'");die();}

#-------------------------------------------düzenle ve yeni ekle olayları
if(@$_GET['ad'] && @$_GET['oran']) {
    $p = (object) $_GET;
    if(@$p->dzn){
        DB::table('campaigns')->where('id',$p->dzn)->update(['name' => $p->ad,'campaign' => json_encode(array('percentage'=>$p->oran)),'state' => !$p->aktif?0:1,'created_at' => date('YmdHis')]);
    }else{
        DB::table('campaigns')->insert(['name' => $p->ad, 'campaign' => json_encode(array('percentage'=>$p->oran)), 'state' => !$p->aktif?0:1, 'created_at' => date('YmdHis')]);
    }
    die();
}
#-------------------------------------------kişi  ekle olayları
if(@$_GET['kisi_ekle'] && @$_GET['id']){
    $p = (object) $_GET;
      DB::table('campaigns')->where('id',$p->id)->update(['target_audience' => $p->kisi_ekle, 'updated_at' => date('YmdHis') ]);
    die();
}
#-------------------------------------------ürün ekle olayları
if(@$_GET['urun_ekle'] && @$_GET['id']){
    $p = (object) $_GET;
    DB::table('campaigns')->where('id',$p->id)->update(['target_products' => $p->urun_ekle, 'updated_at' => date('YmdHis') ]);
    die();
}
#-------------------------------------------kategori  ekle olayları
if(@$_GET['kat_ekle'] && @$_GET['id']){
    $p = (object) $_GET;
    DB::table('campaigns')->where('id',$p->id)->update(['target_products' => $p->kat_ekle, 'updated_at' => date('YmdHis') ]);

    die();
}


#-------------------------------------------Detay resp.
if(@$_GET['detay']){
    $p = (object) $_GET;
    $al=DB::table('campaigns')->where('id',$p->detay)->get();
    $ad=array();
    $t = new stdClass();
    $t->urun= array();$t->kat= array();
    $t->kisi=array();$bos='';
    foreach ($al as $a){

        if(strlen($a->target_audience)>10) {
            $ref = json_decode($a->target_audience)->include->referrers;
            foreach (json_decode($a->target_audience)->include->users as $u) { // katılımcı ad email vs
                $ax = DB::select("select id,name, email, (select name from users where refId='$ref[0]' limit 1) ref from users where id= '$u'");
                array_push($t->kisi, $ax);
            }
        } else{
                array_push($t->kisi,'{"kisi":[[{"id":,"name":"","email":"","ref":""}]]}');
        }

        if(strlen($a->target_products)>10) {
            foreach (json_decode($a->target_products)->include->categories as $u) {// Kategori  isimleri
                $ct = DB::select("select id,game,title from games_titles where id='$u'");
                array_push($t->kat, $ct);
            }
        } else {
            array_push($t->kat, '{"kat":[[{"id":"","game":"","title":""}]]}');
        }

        if(strlen($a->target_products)>10) {
            foreach (json_decode($a->target_products)->include->products as $u) {// ürün isimleri
                $pr = DB::select("select id gpid,title from games_packages where id='$u'");
                array_push($t->urun, $pr);
            }
        } else {
            array_push($t->urun, '{"urun":[[{"gpid":"","title":""}]]}');
        }

        $user=json_encode($t);

    }
    echo $user;

die();
}
?>

@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
    <style>
        table.dataTable.nowrap td:nth-child(2) {white-space: unset !important;}
        .swal2-container {z-index: 10501;}
    </style>
@endsection
@section('body')
    <div class="row" data-lang="{{getLang()}}">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 " >
                                Kampanya İşlemleri
                            </div>
                            <div class="col-md-6 col-sm-6 text-right" >
                                <a href="#" class="ekle btn btn-small">Yeni Kampanya</a>

                                <div id="yekle" style="display: none" >
                                    <label class="mr-2 mt-2" for="aktif">Aktif/Pasif </label> <input class="mt-2" id="aktif" type="checkbox" style="height: 20px;width: 20px">
                                    <input class="mr-2" type="text" id="ad" placeholder="Kampanya adı">
                                    <input class="mr-2" type="text" id="oran" placeholder="Oran" size="2">
                                    <button class="btn-sm btn btn-gradient-success ke">Ekle</button>
                                </div>

                            </div>
                        </div>
                    </h4>
                    <div class="table-responsive">

                        <table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead><tr style="text-align: center"><th>No</th><th>Kampanya Adı</th><th>Referans</th><th>Durum</th><th>Katılan Üye</th><th>Kategori / Ürün</th><th>Oran</th><th>Oluşturma</th><th>Dzn</th><th>Dty</th><th>Sil</th></tr></thead>
                            <tbody>

                            <?
                                $kamp=DB::select("SELECT * FROM campaigns");
                                $no=0;
                            ?>

                            @foreach($kamp as $u)
                                <? $no++;
                                    if(strlen($u->target_audience)>10){
                                        $reff=DB::table('users')->where('id',json_decode($u->target_audience)->include->referrers[0])->first();
                                    } else {$reff='';}


                                    ?>
                                <tr>
                                    <td style="text-align: center" >{{$no}}</td>
                                    <td style="text-align: center; cursor: pointer " id="kis_{{$u->id}}">{{$u->name}}</td>
                                    <td style="text-align: center;">{{ @$reff->name }} </td>
                                    <td style="text-align: center;" class="{{$u->state==1?'btn-success':'btn-gradient-danger'}}" id="ak_{{$u->id}}">{{$u->state==1?'Aktif':'Pasif'}}</td>
                                    <td style="text-align: center">@if(strlen($u->target_audience)>10) {{count(json_decode($u->target_audience)->include->users)}}@endif</td>
                                    <td style="text-align: center">@if(strlen($u->target_products)>10) {{count(json_decode($u->target_products)->include->categories)}} / {{@count(json_decode($u->target_products)->include->products)}}@endif</td>
                                    <td style="text-align: center" id="or_{{$u->id}}">% {{ json_decode($u->campaign)->percentage }}</td>
                                    <td style="text-align: center">{{$u->created_at}}</td>
                                    <td style="text-align: center; cursor: pointer" class="dzn btn-sm"  id="D_{{$u->id}}">Düzen</td>
                                    <td style="text-align: center; cursor: pointer" class="tik" reff="{{@$reff->name}} - {{@$reff->email}} - {<?=@$reff->id?>" id="kad_{{$u->id}}" orr="{{json_decode($u->campaign)->percentage}}">Detay</td>
                                    <td style="text-align: center; cursor: pointer" class="sil btn-sm" id="S_{{$u->id}}">Sil</td>
                                </tr>
                                <tr class="dty" style="display: none" id="dty_{{$u->id}}"></tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{---------------------------------------------------------------------------üye-----------------------------------------------------------    --}}
    <div class="modal" id="exa" tabindex="-1" role="dialog" aria-labelledby="exa" aria-hidden="true" data-backdrop="static" style="z-index: 10500">
        <div class="modal-dialog-centered" role="document" style="width: 800px; margin: 0 auto;">
            <div class="modal-body" style="background-color: black; border-radius: 20px;">

                <div>
                    <label id="secilen">Üye Seçin <br><i style='background-color: brown'></i> <ol></ol> </label>
                    <div class="col__search">
                        <div class="input-group search-element">
                            <button id="ref_but" class="btn btn-gradient-warning" title="Referans kişi ekle"><i class="fa fa-ankh"></i></button>
                            <button id="ekle_but" class="btn btn-gradient-primary" title="Listeye Ekle"><i class="fa fa-plus"></i></button>
                            <input placeholder="Kullanıcı Email/İsim araması için en az 5 karakter" id="q3" autocomplete="off" class="form-control style-input">
                            <button id="tmm_but" class="btn btn-gradient-success" title="Kayıt"><i class="fa fa-check"></i></button>
                            <button id="iptal" class="btn btn-gradient-danger" title="Kapat"><i class="fa fa-times"></i></button>
                        </div>
                        <div class="result__body mt-3" style="display: none"><div class="result__body__inner3"><ol></ol></div></div></div>
                </div>
            </div>
        </div>
    </div>

{{------------------------------------------------------------------------------ürün--------------------------------------------------------    --}}
    <div class="modal" id="urun_ara" tabindex="-1" role="dialog"  aria-labelledby="exa" aria-hidden="true" data-backdrop="static" style="z-index: 10500">
        <div class="modal-dialog-centered" role="document" style="width: 800px; margin: 0 auto;">
            <div class="modal-body" style="background-color: black; border-radius: 20px;">

                <div>
                    <label id="secilen1">Ürün Seçip (+) Basın <br><ol></ol> </label>
                    <div class="col__search">
                        <div class="input-group search-element">
                            <button id="ekle_but1" class="btn btn-gradient-primary" title="Listeye Ekle"><i class="fa fa-plus"></i></button>
                            <input placeholder="Ürün araması için en az 3 karakter" id="q1" autocomplete="off" class="form-control style-input">
                            <button id="tmm_but1" class="btn btn-gradient-success" title="Kayıt"><i class="fa fa-check"></i></button>
                            <button id="iptal1" class="btn btn-gradient-danger" title="Kapat"><i class="fa fa-times"></i></button>
                        </div>
                        <div class="result__body1 mt-3"><div class="result__body__inner1"><ol></ol></div></div></div>
                </div>
            </div>
        </div>
    </div>

{{------------------------------------------------------------------------------Kategori--------------------------------------------------------    --}}
    <div class="modal" id="cat_ara" tabindex="-1" role="dialog" aria-labelledby="exa" aria-hidden="true" data-backdrop="static" style="z-index: 10500">
        <div class="modal-dialog-centered" role="document" style="width: 800px; margin: 0 auto">
            <div class="modal-body" style="background-color: black; border-radius: 20px;">

                <div>
                    <label id="secilen2">Kategori Seçip (+) Basın <br><ol></ol> </label>
                    <div class="col__search">
                        <div class="input-group search-element">
                            <button id="ekle_but2" class="btn btn-gradient-primary" title="Listeye Ekle"><i class="fa fa-plus"></i></button>
                            <input placeholder="Kategori araması için en az 3 karakter" id="q2" autocomplete="off" class="form-control style-input">
                            <button id="tmm_but2" class="btn btn-gradient-success" title="Kayıt"><i class="fa fa-check"></i></button>
                            <button id="iptal2" class="btn btn-gradient-danger" title="Kapat"><i class="fa fa-times"></i></button>
                        </div>
                        <div class="result__body2 mt-3"><div class="result__body__inner2"><ol></ol></div></div></div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('js')
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
    <script type="text/javascript">
var ref=0,refid=[],urunid=[];

//---------------------------------------------------------------------------
$(document).on('click','#ekle_but',function(){
    console.log(refid);
    if($('#q3').val()=='') { swal.fire({icon:'error',html:'Eklemek için önce bir kullanıcı seçin.',showConfirmButton: false,timer:1500}); return}
    if(refid.includes($('#q3').val().split('{')[1])) { swal.fire({icon:'error',html:'Bu kullanıcı zaten eklenmiş.',showConfirmButton: false,timer:1500}); return;}
    refid.push($('#q3').val().split('{')[1]);
    $('#secilen ol').append("<li><iw class='exi text-danger' title='Kaldır' style='cursor: pointer'>[X]</iw> "+$('#q3').val()+"</li>" );
    $('#q3').focus().val('');
});

$(document).on('click','#ekle_but1',function(){
    console.log(urunid);
    if($('#q1').val()=='') { swal.fire({icon:'error',html:'Eklemek için önce bir ürün seçin.',showConfirmButton: false,timer:1500}); return}
    if(urunid.includes($('#q1').val().split('{')[1])) { swal.fire({icon:'error',html:'Bu ürün zaten eklenmiş.',showConfirmButton: false,timer:1500}); return;}
    urunid.push($('#q1').val().split('{')[1]);
    $('#secilen1 ol').append("<li><iw class='exi1 text-danger' title='Kaldır' style='cursor: pointer'>[X]</iw> "+$('#q1').val()+"</li>" );
    $('#q1').focus().val('');
});

$(document).on('click','#ekle_but2',function(){
    console.log(urunid);
    if($('#q2').val()=='') { swal.fire({icon:'error',html:'Eklemek için önce bir kategori seçin.',showConfirmButton: false,timer:1500}); return}
    if(urunid.includes($('#q2').val().split('-')[0])) { swal.fire({icon:'error',html:'Bu kategori zaten eklenmiş.',showConfirmButton: false,timer:1500}); return;}
    urunid.push($('#q2').val().split('-')[0]);
    $('#secilen2 ol').append("<li><iw class='exi1 text-danger' title='Kaldır' style='cursor: pointer'>[X]</iw> "+$('#q2').val()+"</li>" );
    $('#q2').focus().val('');
});

//---------------------------------------------------------------------------
$(document).on('click','#ref_but',function(){
    if($('#q3').val()=='') { swal.fire({icon:'error',html:'Eklemek için önce bir kullanıcı seçin.',showConfirmButton: false,timer:1500}); return}
    $('#secilen i').empty().text("Reff -> " + $('#q3').val() );
    $('#q3').focus().val('');
    ref++;
});
//---------------------------------------------------------------------------
$(document).on('click','.exi',function(){
    refid.pop($(this).parent('li').text().split('{')[1]);
    $(this).parent('li').remove();
    console.log(refid);
});

$(document).on('click','.exi1',function(){
    urunid.pop($(this).parent('li').text().split('{')[1]);
    $(this).parent('li').remove();
    console.log(urunid);
});

$(document).on('click','.exi2',function(){
    urunid.pop($(this).parent('li').text().split('{')[1]);
    $(this).parent('li').remove();
    console.log(urunid);
});

//---------------------------------------------------------------------------
$(document).on('click','#tmm_but',function(){
    var uid='',reff='';
    if($('#secilen li').length<1) {swal.fire({icon:'error',html:'Üyeyi seçip (+) butonuna basarak listeye ekleyin. Liste boş..',showConfirmButton: false,timer:2500});return }
    if(ref<1) {swal.fire({icon:'error',html:'Referans kişi tanımlanmamış. Referans kişiyi seçerek <i class="fa fa-ankh"></i> butonuna basıp listeye ekleyin.',showConfirmButton: false,timer:2500});return }
    reff=$('#secilen i').text().split('{')[1];
    $('#secilen li').each(function() {uid+=$(this).text().split('{')[1]+',';});

    var json='{"include": {"users": ['+uid.slice(0,-1)+'],"referrers": ['+reff+']}}';

    $.get('', {kisi_ekle:json, id:$(this).val()}, function (x) {
        location.reload();
    });
    refid='';
    console.log(json);
});

$(document).on('click','#tmm_but1',function(){
    var uid='';
    if($('#secilen1 li').length<1) {swal.fire({icon:'error',html:'Ürünü seçip (+) butonuna basarak listeye ekleyin. Liste boş..',showConfirmButton: false,timer:2500});return }
    $('#secilen1 li').each(function() {uid+=$(this).text().split('-')[0].split(']')[1]+',';});

    var json='{"include": {"products": ['+uid.slice(0,-1)+'],"categories": []}}';

    $.get('', {urun_ekle:json, id:$(this).val()}, function (x) {
        location.reload();
    });
    console.log(json);
});

$(document).on('click','#tmm_but2',function(){
    var uid='';
    if($('#secilen2 li').length<1) {swal.fire({icon:'error',html:'Kategori seçip (+) butonuna basarak listeye ekleyin. Liste boş..',showConfirmButton: false,timer:2500});return }
    $('#secilen2 li').each(function() {uid+=$(this).text().split('-')[0].split(']')[1]+',';});

    var json='{"include": {"products": [],"categories": ['+uid.slice(0,-1)+']}}';

    $.get('', {kat_ekle:json, id:$(this).val()}, function (x) {
        location.reload();
    });
    console.log(json);
});

//---------------------------------------------------------------------------
        var txt,id,urun='',kat='',kisi,rs,ekle,ok=0,dznn='',dzn_ur='',dzn_ct='',dznn_ref='';

        $('.tik').click(function(x){ $('.dty').hide('slow');
            id=$(this).attr('id').split('_')[1];
            var orr=$(this).attr('orr');
            var eks ="<td colspan='11' style='border-radius: 20px; background-color:black; text-align: -webkit-center'>";

            console.log($('#dty_'+id).css('display'));

            if($('#dty_'+id).css('display') !== 'none'){ $('#dty_'+id).hide('slow');return false}

            $('#dty_'+id).html(eks+"Yükleniyor..").show('slow');

            $.get('', {detay:id}, function (x) {
                dzn_ur='',dzn_ct='';dznn=''; ref=0; refid=[];ok=0;

              //  if(x=='bos1bos2'){$('#dty_'+id).empty().html(eks+"<button value='detek_"+id+"' class='btn btn-sm btn-gradient-success detek' >KATILIMCI ve ÜRÜN EKLE</button></td>"); return;}

                txt=eks+"<table><tr><th>ID</th><th>Referans</th><th>Üye</th><th>E-Mail</th><th>Ürün</th><th>Kategori</th><th>Oran</th><th>Düzen</th></tr>";

                console.log(x);

                kisi=JSON.parse(x);
                rs=kisi.kisi.length;
                refid=[],tekrar=0;
                kisi.kisi.forEach(function (z){

if(ok<1) {
    JSON.parse(x).urun.forEach(function (s) {
        urun += s[0].gpid + "-" + s[0].title + "<br>";
        dzn_ur += "<li><iw class='exi1 text-danger' title='Kaldır' style='cursor: pointer'>[X]</iw> " + s[0].gpid + "-" + s[0].title + "</li>";
    });
    JSON.parse(x).kat.forEach(function (s) {
        kat += "<i style='cursor: pointer' class='ic text-danger' id='" + s[0].id + "'>" + s[0].id + "</i>-" + s[0].title + "<br>";
        dzn_ct += "<li><iw class='exi2 text-danger' title='Kaldır' style='cursor: pointer'>[X]</iw> " + s[0].id + "-" + s[0].title + "</li>";
    });
}
                        urun=urun.indexOf('undefined')>-1 ?'YOK':urun;
                        kat=kat.indexOf('undefined')>-1 ?'YOK':kat;


                        ekle = ok < 1 ? "<td rowspan='" + rs + "'>" + urun + "</td><td rowspan='" + rs + "'>" + kat + "</td>" : ""

                    var oran=ok<1?"<td rowspan='"+rs+"'>% "+orr+"</td>":"";
                    var duzen=ok<1?"<td rowspan='"+rs+"'>" +
                        "<button style='width: 100%' value='detek_"+id+"' class='detek btn-dark'>Üye</button><br>" +
                        "<button style='width: 100%' value='urun_"+id+"' class='urun_ek btn-dark mt-1'>Ürün</button><br>" +
                        "<button style='width: 100%' value='cat_"+id+"' class='cat_ek btn-dark mt-1'>Kat.</button>" +
                        "</td>":"";

                    z[0].ref=z[0].ref===null?'YOK':z[0].ref;

                        txt+="<tr><td>"+z[0].id+"</td><td>"+z[0].ref+"</td><td>"+z[0].name+"</td><td>"+z[0].email+"</td>"+ekle+oran+"</td>"+duzen+"</tr>";
                        dznn+="<li><iw class='exi text-danger' title='Kaldır' style='cursor: pointer'>[X]</iw> "+z[0].name+" - "+z[0].email+" - {"+z[0].id+"</li>";
                        dznn_ref = z[0].ref;
                        refid.push(""+z[0].id);

                        urun='';ok++;kat=''

                });
                tekrar=0;
                txt+="</table></td>";

                console.log(txt);
                txt=txt.replace(/undefined/g,'YOK');

                $('#dty_'+id).empty().html(txt);
                ok=0;

            });


        });

//--------------------------------------------------------------------------- KAtegori içeriği
$(document).on('click','.ic',function(x) {
    let id=$(this).attr('id');
    $.get('', {ctt:id}, function (xx) {
        swal.fire({html:"<b>Kategori Kapsamındaki Ürünler</b><br>"+xx,showConfirmButton: false});
    });
});

//--------------------------------------------------------------------------- Ürün ekle
$(document).on('click','.urun_ek',function(x) {
    var id = $(this).val().split('_')[1];

    if(dzn_ct.indexOf('undefined')==-1 && dzn_ct.length>15) {swal.fire({icon:'info',title:'Devam ederseniz kategori iptal edilerek ürün bazında indirim uygulanacak. Kategori ve ürün aynı anda kullanılamaz.',showConfirmButton: true});}
    if(dzn_ur.indexOf('undefined')==-1 && dzn_ur.length>15) { // duzenleme liste doluysa al gel
        $('#secilen1 ol').empty().html(dzn_ur);
    } else {
        $('#secilen1 ol').empty();
    }

    $('#urun_ara').modal('show');
    $('#tmm_but1').val(id);
})

//--------------------------------------------------------------------------- Kategori ekle
$(document).on('click','.cat_ek',function(x) {
    var id = $(this).val().split('_')[1];

    if(dzn_ct.indexOf('undefined')==-1 && dzn_ur.length>15) {swal.fire({icon:'info',title:'Devam ederseniz ürünler iptal edilerek kategori bazında indirim uygulanacak. Kategori ve ürün aynı anda kullanılamaz.',showConfirmButton: true});}
    if(dzn_ct.indexOf('undefined')==-1 && dzn_ct.length>15) { // duzenleme liste doluysa al gel
        $('#secilen2 ol').empty().html(dzn_ct);
    } else {
        $('#secilen2 ol').empty();
    }

    $('#cat_ara').modal('show');
    $('#tmm_but2').val(id);
})


//--------------------------------------------------------------------------- Katılımcı ekle
$(document).on('click','.detek',function(x) {

    var id = $(this).val().split('_')[1];
    console.log(id, dznn);

    if(dznn.indexOf('undefined')==-1 && dznn.length>15) { // duzenleme liste doluysa al gel
        $('#secilen ol').empty().html(dznn);
        $('#secilen i').empty().text("Reff -> " + $('#kad_' + id).attr('reff'));
        ref++;
    } else {
        $('#secilen ol').empty();
        $('#secilen i').empty();
    }

    $('#exa').modal('show');
    $('#tmm_but').val(id);


});

//--------------------------------------------------------------------------- modal ort
$('#iptal').click(function() {$('#exa').modal('hide');});
$('#iptal1').click(function() {$('#urun_ara').modal('hide');});
$('#iptal2').click(function() {$('#cat_ara').modal('hide');});

//--------------------------------------------------------------------------- Sil
$('.sil').click(function(x){
            let id=$(this).attr('id').split('_')[1];
            swal.fire({
                icon: 'warning',
                title:'!!..Dikkat..!!',
                html:'<p>Kampanya ve altındaki tüm tanımlı hesaplar veritabanından KALICI olarak silinerek indirim tanımları iptal edilecektir.<br>Bu işlem <b>GERİ</b> alınamaz.<br>Ne yaptığınızı biliyorsanız işleme devam ediniz. </p>',
                showCancelButton: true,
                confirmButtonText: 'SİL',
                cancelButtonText: 'VAZGEÇ',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.get('', {kamp_sil: id}, function (x) {location.reload();});
                } else {swal.fire({icon:'error',title:'İşlemden Vazgeçildi',showConfirmButton: false,timer:1500});}
            });
        });
//--------------------------------------------------------------------------- düzenle hazırlık
$('.dzn').click(function(x) {
    let id = $(this).attr('id').split('_')[1];
    $('#ad').val($('#kis_'+id).text());
    $('#oran').val($('#or_'+id).text().split(' ')[1]);
    if($('#ak_'+id).text()=='Aktif') {
        $('#aktif').prop('checked', true);
    } else {
        $('#aktif').prop('checked', false);
    }
    $('#yekle').show('slow');
    $('.ke').text('Kaydet');
    $('.ke').val(id);

});
//--------------------------------------------------------------------------- Yeni ekle / düzenle kayıt
$('.ke').click(function (){
    if ($('#ad').val() == '' || $('#oran').val() == '') {alert("Ad ve oran boş olamaz");return}
    if($('.ke').text()=='Kaydet'){
        $.get('', {dzn:$('.ke').val(),ad: $('#ad').val(),oran: $('#oran').val(),aktif: $('#aktif').is(':checked') ? 1 : 0}, function (x) {location.reload();});
    }else {
        $.get('', {ad: $('#ad').val(),oran: $('#oran').val(),aktif: $('#aktif').is(':checked') ? 1 : 0}, function (x) {location.reload();});
    }
});
//--------------------------------------------------------------------------- Yeni ekle hazırlık
$('.ekle').click(function (){
    $('#yekle').toggle('slow');
    $('.ke').text('Ekle');
    $('#aktif').prop('checked', false);
    $('#ad').val('');
    $('#oran').val('');
});

var que='';

//--------------------------------------------------------------------------- User Arama muhabbeti
$('#q3').keyup(function (x) { if($('#q3').val().length<5 || que==$('#q3').val()) {return false;}
    que=$('#q3').val();
    $('.result__body__inner3 ol')[0].innerHTML = "";
    $('.result__body').show();
    $.post('/live.php', {sec1: $('#q3').val()}, function (x) {let oyun = JSON.parse(x);
        if (oyun.length) {$('.result__body__inner3 ol').empty();
            for (o in oyun) {$('.result__body__inner3 ol').append("<li><a href='#' onclick='$(\".result__body\").hide();$(\"#q3\").val($(this).text())'>"+oyun[o].name + " - " + oyun[o].email + " - {"+oyun[o].id+"</a></li>"); }
        } else {$('.result__body__inner3 ol').html("Aramadan bi sonuç çıkmadı :( ");}
    });

    $(document).on("click", function (x) {if (!x.target.closest(".col__search")) {$('.result__body').hide();}});

});

//--------------------------------------------------------------------------- epin Arama muhabbeti
$('#q1').keyup(function (x) { if($('#q1').val().length<3 || que==$('#q1').val()) {return false;}
    que=$('#q1').val();
    $('.result__body__inner1 ol')[0].innerHTML = "";
    $('.result__body1').show();
    $.post('/live.php', {sec2: $('#q1').val()}, function (x) {let oyun = JSON.parse(x);
        if (oyun.length) {$('.result__body__inner1 ol').empty();
            for (o in oyun) {$('.result__body__inner1 ol').append("<li><a href='#' onclick='$(\".result__body1\").hide();$(\"#q1\").val($(this).text())'>"+oyun[o].id+"-"+oyun[o].title+" - ["+oyun[o].price+" TL] </a></li>"); }
        } else {$('.result__body__inner1 ol').html("Aramadan bi sonuç çıkmadı :( ");}
    });

    $(document).on("click", function (x) {if (!x.target.closest(".col__search")) {$('.result__body1').hide();}});

});



//--------------------------------------------------------------------------- Kategori Arama muhabbeti
$('#q2').keyup(function (x) { if($('#q2').val().length<3 || que==$('#q2').val()) {return false;}
    que=$('#q2').val();
    $('.result__body__inner2 ol')[0].innerHTML = "";
    $('.result__body2').show();
    $.post('/live.php', {sec4: $('#q2').val()}, function (x) {let oyun = JSON.parse(x);
        if (oyun.length) {$('.result__body__inner2 ol').empty();
            for (o in oyun) {$('.result__body__inner2 ol').append("<li><a href='#' onclick='$(\".result__body2\").hide();$(\"#q2\").val($(this).text())'>"+oyun[o].id+"-"+oyun[o].title+" - ("+oyun[o].urun+" Ürün Mevcut) </a></li>"); }
        } else {$('.result__body__inner2 ol').html("Aramadan bi sonuç çıkmadı :( ");}
    });

    $(document).on("click", function (x) {if (!x.target.closest(".col__search")) {$('.result__body2').hide();}});

});


// $("[class^=result__body__inner]").mousemove(function (){
//     if(trig==0) {$('#q'+$(this).attr('class').slice(-1)).trigger('keyup');trig=1;}
// });


    </script>
@endsection
