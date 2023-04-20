<?
if(isset($_GET['cypher']) && $_GET['cypher']==316){
  #----- cypher satis seçilen item kombinasyonu DB üzerinde tanımlı değilse burada patlar
    #----- çözüm panelden ilgili tanımlama yapılarak db e kayıt edilmeli

    $item=DB::table('games_titles_items_info')->where('title',$_GET['item']);

    if($item->count()<=0) {die('Kayıt sırasında hata oluştu');} // satılmak istenen cypher sistemde mevcut mu ?


    $p = (object) $_GET;
    #--------Gelenleri kontrol edelim
    if($p->npc!='M' && $p->npc!='K') {die('Beklenmeyen Parametre');}
    parse_str(base64_decode($p->veri), $form);
    $js = json_encode(array_diff_key($form, ['server'=>1,'fiyat'=>1,'aciklama'=>1]));
    $form=(object) $form;
    if($form->level=='' || $form->yuzde=='' || $form->karakter=='' || $form->irk=='' || $form->server=='' || $form->baslik=='' || $form->fiyat<1) {die('Beklenmeyen Parametre');}


  //  echo $js;

DB::table('pazar_yeri_ilanlar')->insert([
                    'pazar' => 9,
                    'user' => Auth::user()->id,
                    'price'=>$form->fiyat,
                    'moment_komisyon'=>$form->fiyat-$form->fiyat*1/100,
                    'title'=>$form->baslik,
                    'ozellik'=>$js,
                    'text'=>$form->aciklama,
                    'sunucu'=>$form->server,
                    'type'=>0,
                    'tl'=>$form->sure,
                    'status'=>0,
                    'money_status'=>0,
                    'toplu'=>0,
                    'userStatus'=>1,
                    'updated_at' => date('YmdHis'),
                    'created_at' => date('YmdHis')
                ]);

    $lastId = DB::getPdo()->lastInsertId();

    DB::table('pazar_yeri_ilan_icerik')->insert([
        'ilan' => $lastId,
        'item' => $item->first()->id
    ]);

echo "200"; //  her sey yolunda mesaji
die();
}
#----------------------------------------------------------------------------------Set için ITEM ekleme
if(@$_GET['img']){
    $pic = DB::table('games_titles_items_photos')->where('item', $_GET['img'])->get();
     $ad = DB::table('games_titles_items_info')->where('id', $_GET['img'])->get();
    if($pic->count()>0){die($pic[0]->image.'^'.$pic[0]->item.'^('.$ad[0]->title.')');} else {die('hata');}
}
?>


@if(isset($_GET['pazar']) and isset($_GET['itemler']))
    <?php $itemler = array(); ?>
    @foreach(DB::table('games_titles_items_info')->where('game_title', $_GET['pazar'])->whereNull('deleted_at')->where('title', 'like', '%'.$_GET['searchTerm'].'%')->get() as $i)
        <?php
        $itemler[] = ['id' => $i->id, 'text' => $i->title];
        ?>
    @endforeach
    <?php echo json_encode($itemler); ?>
    @php die(); @endphp
@endif
@if (isset($_GET['pazar']))
    @if (isset($_GET['item']))
        <?php
        $item = $_GET['item'];
        $item_info = DB::table('games_titles_items_info')->where('id', $item)->first();
        $item_photo = DB::table('games_titles_items_photos')->where('item', $item)->get();
        ?>
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-3">
                        @foreach ($item_photo as $ip)
                            <img src="{{ asset('public/front/games_items/' . $ip->image) }}" class="card-img-center bg-black" alt="{{ $item_info->title }} görseli">
                        @endforeach
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card-body">
                                    <h5 class="card-title item-baslik" id="itemBaslik">{{ $item_info->title }}</h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    @foreach (DB::table('games_titles_features')->where('game_title', $item_info->game_title)->whereNull('deleted_at')->get() as $i)
                                        @if (DB::table('games_titles_items')->where('item', $item)->where('feature', $i->id)->count() > 0)
                                            <?php
                                            $value = DB::table('games_titles_items')
                                                ->where('item', $item)
                                                ->where('feature', $i->id)
                                                ->first()->value;
                                            ?>
                                            @if ($value != '0')
                                                <li class="list-group-item">{{ $i->title }}
                                                    :
                                                    @if ($value == '0')
                                                        Hepsinde Geçerli
                                                    @else
                                                        {{ ucfirst($value) }}
                                                    @endif
                                                </li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="2" class="form-label">Fiyat</label><br>
                                            <input onkeyup="komisyonHesapla(this)" id="2"
                                                class="form-control style-input" name="price{{ $item }}"
                                                placeholder="Fiyat" type="number" step="0.01" required>
                                        </div>
                                        <div class="col-md-8">
                                            <label for="5" class="form-label">Hesaba Geçecek Tutar</label><br>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text style-input" id="basic-addon1">
                                                    @if ($_GET['market'] == 413)
                                                        5
                                                    @else
                                                        {{ findUserKomisyon(Auth::user()->id) }}
                                                    @endif % komisyon
                                                </span>
                                                <input id="5" type="number" name="kazanc" step="0.01"
                                                    class="form-control style-input-pd" readonly>
                                                <span class="input-group-text style-input">₺</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Teslimat Saati</label><br>

                                            <div class="d-flex flex-row mb-2"  style="display: flex;flex-wrap: wrap; justify-content: center">
                                                <div class="d-flex flex-row dates_cont">
                                                    <select class="date_f s2dates" style="width: 100px">
                                                        <option value="">Seçiniz</option>
                                                        @for ($i = 0; $i < 24; $i++)
                                                            <option
                                                                value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) . ':00' }}">
                                                                {{ str_pad($i, 2, '0', STR_PAD_LEFT) . ':00' }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <span class="form-label" style= "font-weight: bold;font-size:18px;padding:0px 10px;margin-top:6px"> - </span>
                                                    <select class="date_t s2dates" style="width: 100px">
                                                        <option value="">Seçiniz</option>
                                                        @for ($i = 0; $i < 24; $i++)
                                                            <option
                                                                value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) . ':00' }}">
                                                                {{ str_pad($i, 2, '0', STR_PAD_LEFT) . ':00' }}
                                                            </option>
                                                        @endfor
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-12" style="display: flex; justify-content: center">
                                                <label for="7_24" class="form-check form-switch" style="margin-left: 25px">
                                                    <input name="7_24" id="7_24" type="checkbox" role="switch">
                                                    <div class="switcher"><span><i></i></span></div>
                                                    <span class="form-label">24 Saat
                                                        <col>
                                                    </span>
                                                </label>
                                            </div>
                                            <input class="form-control style-input d-none" id="teslimatSaati" name="teslimat{{ $item }}" placeholder="Teslimat Saati" type="text" required>

                                            <script>
                                                    <? # Satilmak istenen item ALIS pazarinda varsa burada uyari ve yonlendirme yapiliyor
                                                if(@$item_info->id) {
                                                $link=DB::table("pazar_yeri_ilanlar_buy")->where("title" ,'like', '%'.$item_info->title.'%')->where('status',1)->where('sunucu', $_GET['server'])->whereNull('deleted_at')->orderBy('price','desc');
                                                $adet=$link->count();
                                                $link=$link->first();
                                                if(@$link->id){ ?>
                                                    $('.pp_center').empty().html('<h6>ALIŞ PAZARI nda satmak istediğiniz item için en yükseği <br> ₺{{$link->price}} olmak üzere {{$adet}} adet alış ilanı mevcut. <br> En yüksek teklife doğrudan satış yapmak <br> ister misiniz ?</h6>');
                                                    setTimeout(function () {$('.control_popup').removeClass('hide');$('.control_popup').addClass('show')},500);
                                                    $('.kal').click(function(){$('.control_popup').removeClass('show');$('.control_popup').addClass('hide');});
                                                    $('.git').click(function(){location.href="{{ route('item_buy_ic_detay', ['item-satis', $_GET['server'], Str::slug($link->title) . '-' . $link->id]) }}";});
                                                    {{--swal.fire({--}}
                                                    {{--    html:'<b>ALIŞ PAZARI</b>nda satmak istediğiniz item için en yükseği ₺{{$link->price}} olmak üzere {{$adet}} adet alış ilanı mevcut. <br> En yüksek teklife <br> doğrudan satış yapmak ister misiniz ?',--}}
                                                    {{--    showCancelButton: true,confirmButtonText: 'Alım ilanına git',cancelButtonText: 'İşleme devam et',reverseButtons: true--}}
                                                    {{--}).then((result) => {if (result.value) {swal.fire({icon:'success',html:'Yönlendiriliyorsunuz..',showConfirmButton: false,timer:1800});--}}
                                                    {{--    setTimeout(function (){location.href="{{ route('item_buy_ic_detay', ['item-satis', $_GET['server'], Str::slug($link->title) . '-' . $link->id]) }}";},2000);}});--}}
                                                console.log("{{$item_info->title}} / {{$item_info->id}} / {{$item}} -->  {{$_GET['server']}} -  {{Str::slug($link->title)}} - {{$link->id}} - adet={{$adet}}");
                                                console.log("{{ route('item_buy_ic_detay', ['item-satis', $_GET['server'], Str::slug($link->title) . '-' . $link->id]) }}");
                                                    <? }} #-----------------------------Yonlendirme sonu ?>


                                                $(".s2dates").select2({minimumResultsForSearch: Infinity});
                                                $('#7_24').on('change', function(event) {
                                                    if ($('#7_24').is(":checked")) {
                                                        $(".date_f").val('').trigger('change');
                                                        $(".date_t").val('').trigger('change');
                                                        $("#teslimatSaati").val("24 Saat Teslimat");
                                                        $(".dates_cont").addClass('d-none').removeClass('d-flex');
                                                    } else {
                                                        $(".dates_cont").addClass('d-flex').removeClass('d-none');
                                                        $("#teslimatSaati").val("");
                                                    }
                                                    $(document).trigger('click');
                                                });
                                                $(".s2dates").on('change', function(event) {
                                                    if (!!$(".date_f").val() && !!$(".date_t").val()) {
                                                        $("#teslimatSaati").val($(".date_f").val() + " - " + $(".date_t").val() + ' saatleri arasında');
                                                    } else {
                                                        $("#teslimatSaati").val("");
                                                    }
                                                });

                                                    $('.set_but').click(function(x){
                                                        var itemx=$("select[name='item_set']").val();
                                                        if(itemx==null) {swal.fire({html:'Eklemek için item seçin',showConfirmButton: false,timer:1500});return false;}
                                                        let arr=$('#grup').val().slice(0,-1).split('#');
                                                       // if($.inArray(itemx,arr)>-1){swal.fire({html:'Bu itemi zaten eklediniz..',showConfirmButton: false,timer:1500});return false;}
                                                        $('.set_but').attr('disabled','disabled');
                                                        $.get('?', {img: itemx}, function (x) { var siid=x.split('^')[1];
                                                            let data =$("<div class='hre col-auto mb-4' id='sitem_"+siid+"'><h4 class='isil'> Kaldır </h4><img class='sit' alt=\""+x.split('^')[2]+"\" src='https://oyuneks.com/public/front/games_items/" +x.split('^')[0]+"'></div>").hide();
                                                            $('.setit_ykp').append(data);data.slideDown(1000);
                                                            if($('#iitt').val()==''){
                                                                $('#itad').text(x.split('^')[2]);
                                                                $('#iitt').val(siid);
                                                                $('#teslimatSaati').attr('name','teslimat'+siid);
                                                                $("#2").attr('name','price'+siid);
                                                                $("#4").attr('name','text'+siid);
                                                            }
                                                        });
                                                        ekle();
                                                        return false;
                                                    });
                                                    $(document).on('mouseover','.hre', function () { $(this).find('.isil').css('display','block'); $(this).find('.sit').css('filter','blur(5px) drop-shadow(2px 4px 6px black)'); });
                                                    $(document).on('mouseout','.hre', function ()  { $(this).find('.isil').css('display','none'); $(this).find('.sit').css('filter','drop-shadow(2px 4px 6px black)');});
                                                    $(document).on('click','.hre', function () {var ii=$(this); ii.hide('slow');
                                                        setTimeout(function (){ii.remove();if($('#iitt').val()==ii.attr('id').split('_')[1]) {$('#iitt').val('');$('#itad').text('')} ekle();$(document).trigger('click');},1000)
                                                    });

                                                    function ekle(){var x='';
                                                        setTimeout(function (){
                                                            $('.hre').each(function(){
                                                                x+=$(this).attr('id').split('_')[1]+"#" });
                                                                $('#grup').val(x);
                                                                if($('#grup').val()==''){$('#iitt').val('');$('#itad').text('')}
                                                                if($('#iitt').val()==''){$('#iitt').val(x.split('#')[0]);$('#itad').text($('.sit').attr('alt'))}
                                                                $('.set_but').removeAttr('disabled');
                                                                },1000);
                                                    }

                                            </script>

                                            <input type="hidden" id="iitt" name="item[]" value="{{ $item }}">
                                            <input type="hidden" id="grup" name="grup" value="0" >
                                        </div>
                                        <div class="col-md-12" style="display: flex;flex-wrap: wrap;flex-direction: row-reverse;align-items: center;justify-content: flex-end;">


{{--                                                <label for="grupsw" class="form-check form-switch" style="margin-left: 25px ">--}}
{{--                                                    <span class="form-label me-3">SET -> Hayır </span>--}}
{{--                                                    <input class="grupsw" name="grupsw" id="grupsw" type="checkbox" role="switch">--}}
{{--                                                    <div class="switcher"><span><i></i></span></div>--}}
{{--                                                    <span class="form-label">Evet</span>--}}
{{--                                                </label>--}}




                                            <select name="sure" id="sure" class="form-control" style="width: 80px">
                                                @for($f=168;$f>0;$f--)
                                                        <? if($f % 24==0) {$g=$f/24; $g.=" Gün";} else {$g=$f. " Saat";} ?>
                                                    <option value="{{$f}}">{{$g}} </option>
                                                @endfor
                                            </select>
                                            <label class="form-label me-2">Yayın Süresi</label><br>
                                        </div>
                                        <div class="col-12">
                                            <label for="4" class="form-label acik">Açıklama</label>
                                            <textarea id="4" class="form-control" style="height: 120px" name="text{{ $item }}" placeholder="Açıklama"></textarea>
                                            <i id="inffo" class="text-muted"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">

                            <div class="card-footer btn-float-right">
                                <button type="button" onclick="silItem(this)" class="btn-inline color-red">
                                    Sil
                                </button>
                            </div>
{{--                            <input type="hidden" name="item[]" value="{{ $item }}">--}}
{{--                            <input type="hidden" id="grup" name="grup" value="0" >--}}
                        </div>
                    </div>
                </div>


{{--                    <div class="row text-center set_div_{{$item}}" style="display: none">--}}
{{--                       <h5 class="modal-title"> İlan Set İçeriği</h5>--}}
{{--                        <div class="select-element">--}}
{{--                            <select class="js-data-example-ajax w-100" name="item_set" id="it_ek" style="width: 20em" required><option selected disabled>Set için item Ekle</option></select>--}}
{{--                            <button class="btn alert-success m-3 set_but">Ekle</button>--}}
{{--                        </div>--}}
{{--                        <div class="sets row"></div>--}}
{{--                    </div>--}}

            </div>
        </div>


        <div class="control_popup hide">
            <article>
                <div class="pp_header"><h4>Dikkat!</h4></div>
                <div class="pp_center"></div>
                <div class="pp_buttons">
                    <a class="btn-inline color-blue small mx-sm-3 kal">İşleme Devam Et</a>
                    <a class="btn-inline alert-success small git">Alım İlanına Git</a>
                </div>
            </article>
        </div>

        <? die();?>
    @endif

@if($_GET['pazar']==9) {{--#cypher satışı mı --}}
@include('front.modules.yeni_satis_cypher', ['pazar' => $_GET['pazar']])
@else
@include('front.modules.yeni_satis', ['pazar' => $_GET['pazar']])
@endif
        <? die(); ?>
@endif


@extends('front.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="/back/assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
    <style>
    .sit {
        cursor: pointer;
        filter: drop-shadow(2px 4px 6px black);
        width: 80%;
        border-radius: 15px;
        max-width: 300px;



    }
    .sit:hover {
        /*filter: blur(5px) drop-shadow(2px 4px 6px black);*/
        /*display: flex;*/
    }
    .isil{
        display: none;
        position: absolute;
        color: white;
        z-index: 2;
        background-color: darkred;
        width: 9em;
        border-radius: 5px;
    }
    .hre {
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-align: center;
        -webkit-transition : -webkit-filter 500ms linear;
    }
    </style>
@endsection
@section('body')
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    @if (session('error'))
                        <!--Mesaj bildirimi--->
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">{{ session('error') }}</h4>
                        </div>
                        <!--Mesaj bildirim END--->
                    @endif

                    <form action="{{ route('satici_panelim_post') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                        <div class="row g-3">
                            @csrf
                            <div class="col-md-12 select-element area">
                                <label for="pazar" class="form-label">Pazar Seçin</label><br>
                                <select id="pazar" class="select2" name="pazar" style="width: 100%" required>
                                    <option value="0" disabled selected>Pazar Seçin</option>
                                    @foreach (DB::table('games_titles')->where('type', '1')->whereNull('deleted_at')->get() as $u)
                                        <option value="{{ $u->id }}"
                                            @if (isset($_GET['market']) and $_GET['market'] == $u->id) selected @endif>
                                            {{ DB::table('games')->where('id', $u->game)->first()->title }} - {{ $u->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if($_GET['market']!=9)  {{-- cypher satışı mı--}}
                            <div class="col-12 btn-float-right">
                                <button class="btn-inline color-darkgreen btn-yayinla" disabled type="submit">Yayınla
                                </button>
                            </div>
                            @endif
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script src="/back/assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>


    $(document).bind('keyup click',function () {
        if($('.form_ykp').length==1 && $('.form_ykp').html().length>3) { $('#inffo').text('En az 2 item, fiyat ve saat alanları zorunludur.');
            if ($('#sunucu_ykp').val()!='' && $('.hre').length > 1 && $('#grup').val() != '' && $('#iitt').val() != '' && $('#2').val() != '' && $('#teslimatSaati').val().length>3) {
                $('.btn-yayinla').removeAttr('disabled').show();
            } else {
                $('.btn-yayinla').attr('disabled', 'disabled').hide();
            }
        }
    });


        $(document).ready(function() {
            $('.select2').select2();

            $(document).on('select2:open', () => {document.querySelector('.select2-search__field').focus();});

            if (window.location.href.indexOf("market") > -1) { itemGetir();}
            $("#pazar").change(function() {itemGetir();});

            function itemGetir() {
                pazar = $("#pazar").val();
                $.ajax({
                    url: "?pazar=" + pazar,
                    success: function(result) {
                        $(".area").html(result);
                        $(".area").append("<input id='pazar' type='hidden' name='pazar' value='" +
                            pazar + "'>");
                        $(".select2").select2();
                        $('.js-data-example-ajax').select2({
                            language: {
                                inputTooShort: function() {
                                    return "Lütfen en az 3 karakter girin";
                                },
                                searching: function() {
                                    return "Aranıyor...";
                                },
                                noResults: function() {
                                    return "Sonuç yok.";
                                }
                            },
                            ajax: {
                                url: '?pazar=' + pazar + '&itemler=1',
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        searchTerm: params.term // search term
                                    };
                                },
                                processResults: function(response) {
                                    return {
                                        results: response
                                    };
                                },
                                cache: true
                            },
                            placeholder: 'Arama yapın...',
                            minimumInputLength: 3,
                        });
                    }
                });
            }
        });
    </script>
@endsection
