<?
    if(@$_GET['new']!=316){
        $p = (object) $_GET;
?>
<style>
    .erh {max-height: 0;}
    .erhx {max-height: 52000px;transition: max-height 0.75s ease-in;}
</style>

<table id="datatable" class="font-12 table-sm table-hover table-bordered nowrap " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead><tr><th>Id</th><th>Pazar</th><th>Kullanıcı</th><th>Fiyat</th><th>Kazanç</th><th>Başlık</th><th>Nickname</th><th>Durumu</th><th>İşlem Tar</th><th>Update Tar</th><th>@lang('admin.aksiyon')</th></tr></thead>
    <tbody>

        <?
        if($uid!=''){
            $sor=DB::select("select pyi.*, gt.title pz, u.email, u.name, gt.link, u.telefon
            from pazar_yeri_ilanlar pyi
            left join users u on u.id=pyi.user
            left join games_titles gt on gt.id=pyi.pazar
            where pyi.id='$uid'
            and isnull(pyi.deleted_at)
            order by pyi.id desc, pyi.updated_at desc");


        }else{

            if(@$p->c=='Sorgula_C') {$ek=" date(pyi.created_at) between '$date1' and '$date2' ";} else {$ek=" date(pyi.updated_at) between '$date1' and '$date2' ";}

            //$sor=DB::select('select * from pazar_yeri_ilanlar where status in(0,4,5,6,3) and  date(updated_at) between ? and ?   and isnull(deleted_at)' , [$date1,$date2]);
            $sor=DB::select("select pyi.*, gt.title pz, u.email, u.name, gt.link, u.telefon
            from pazar_yeri_ilanlar pyi
            left join users u on u.id=pyi.user
            left join games_titles gt on gt.id=pyi.pazar
            where ".$ek."
            and isnull(pyi.deleted_at)
            and pyi.status in(0,3,4,5,6)
            order by pyi.id desc, pyi.updated_at desc");
        }
        //# pyi.status in(0,3,4,5,6)
        //DB::table('pazar_yeri_ilanlar')->whereDate('created_at', '>=', $date1)->whereDate('created_at', '<=', $date2)->whereDate('updated_at', '>=', $date1)->whereDate('updated_at', '<=', $date2)->whereNull('deleted_at')->get()
        ?>
    @foreach($sor as $u)
        <tr @if($u->status == 0) class="text-white" style="background-color: crimson"
            @elseif($u->status == 4) class="text-white" style="background-color: forestgreen"
            @elseif($u->status == 5) class="text-white" style="background-color: darkred" @endif
        >
            <td>{{$u->id}}</td>
            <td>{{$u->pz}}</td>
            <td><a href="{{route('uye_detay', [$u->email])}}" target="_blank">{{$u->name}}</a></td>
            <td><?=number_format($u->price,2,".","")?></td>
            <td><?=number_format($u->moment_komisyon,2,".","")?></td>
                <? if($u->sunucu == '') {$u->sunucu = 'genel';} ?>
            <td><a href="{{route('item_ic_detay', [$u->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}" target="_blank">{{mb_substr($u->title, 0, 40)}}</a></td>
            <td>

{{--        İlan satılmış ise alıcı bilgilerini al--}}
@if(DB::table('pazar_yeri_ilan_satis')->where('ilan', $u->id)->whereNull('deleted_at')->count() > 0)
<? $alan =(object) DB::select("select u.name, u.email, pyis.note from pazar_yeri_ilan_satis pyis left join users u on u.id=pyis.satin_alan where pyis.ilan='$u->id'")[0]; ?>
{{$alan->note}} (<a href="{{route('uye_detay', [$alan->email])}}" target="_blank">{{$alan->name}}</a>)
@else
Henüz Satılmamış
@endif

            </td>
            <td>{{findIlanStatus($u->status)}} - {{findUserIlanStatus($u->userStatus)}}</td>
            <td class="text-nowrap">{{$u->created_at}}</td>
            <td class="text-nowrap">{{$u->updated_at}} </td>
            <td style="text-align: center" >
                <i data-toggle="modal" id="{{$u->id}}" class="btn btn-sm fas fa-eye goster" title="Detay Gör"></i>
                <i data-toggle="modal" uid="{{$u->telefon}}" class="btn btn-sm sms fa fa-phone " title="SMS Gönder"></i>
                @if(userRoleIsAdmin(Auth::user()->id))
                <i onclick="deleteContent('pazar_yeri_ilanlar', {{$u->id}})" class="btn btn-sm far fa-trash-alt" title="Sil"></i>
                 @endif
            </td>
        </tr>

    @endforeach
    </tbody>
    <tfoot><tr><th>Id</th><th>Pazar</th><th>Kullanıcı</th><th>Fiyat</th><th>Kazanç</th><th>Başlık</th><th>Nickname</th><th>Durumu</th><th>İşlem Tarihi</th><th>Eylem</th></tr></tfoot>
</table>

{{--------------------------------------------------------------------Düzenleme Onay penceresi --}}
<div class="modal fade detay" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="baslik"> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-12 d-flex mt-md-n1">
                                    <label class="col-6 form-control text-left">Pazar Yeri:</label> <label class="col-8 form-control text-left" id="pzryeri"> </label>
                                </div>
                                <div class="col-12 d-flex mt-md-n1">
                                    <label class="col-6 form-control text-left">Kullanıcı:</label> <label class="col-8 form-control text-left">
                                        <a id="ulnk" title="Üye Yönetim" href="" target="_blank"></a>
                                        <i id="uhar" title="Üye Hareket İzleme" onclick="" class="btn fa fa-search" aria-hidden="true"></i>
                                    </label>
                                </div>
                                <div class="col-12 d-flex mt-md-n1">
                                    <label class="col-6 form-control text-left">Fiyat : </label> <label class="col-8 form-control text-left" id="fiyat"></label>
                                </div>
                                <div class="col-12 d-flex mt-md-n1">
                                    <label class="col-6 form-control text-left">Satıcının Kazanacağı :</label> <label class="col-8 form-control text-left" id="komis"> </label>
                                </div>
                                <div class="col-12 d-flex mt-md-n1">
                                    <label class="col-6 form-control text-left">Başlık : </label> <label class="col-8 form-control text-left;" style="height: auto" id="ubas"></label>
                                </div>
                                <div class="col-12 d-flex mt-md-n1">
                                    <label class="col-6 form-control text-left">Sunucu : </label> <label class="col-8 form-control text-left" style="text-transform: capitalize" id="sunuc"></label>
                                </div>
                                <div class="col-12 d-flex mt-md-n1">
                                    <label class="col-6 form-control text-left">İlan Durumu : </label> <label class="col-8 form-control text-left" id="stats"></label>
                                </div>
                                <div class="col-12 d-flex mt-md-n1">
                                    <label class="col-6 form-control text-left">Tarih :</label> <label class="col-8 form-control text-left" id="created"> </label>
                                </div>
                                <div class="col-md-12" style="display: flex; flex-direction: column">
                                    <label class="text-dark text-left" style="display: flex; flex-direction: column"> Açıklama : </label> <label class="font-12  text-left" id="acik"></label>
                                </div>

                                <div class="col-12" id="seticerik"></div>

                                <div class="col-md-12 text-danger"><label class="form-label text-warning" id="redn"></label></div>

                            </div>
                            <div class="col-md-6">
                                <div class="col-md-12" style="text-align: center" id="foto">
                                </div>
                            </div>
                        </div>
                    </div>
{{--                    <div class="col-12"><hr></div>--}}
                    <div class="col-12" style="text-align: center">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">İlan İşlemleri</h4>
                                <div class="row">
                                <div id="butonlar" style="display: flex; flex-wrap: wrap; align-items: center; width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary waves-effect" data-dismiss="modal">@lang('admin.kapat')</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<?    }
        else{ #/// Eski yapıdan dewam
?>

<table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-hover table-bordered nowrap ilan-siparisleri"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Id</th>
        <th>Pazar</th>
        <th>Kullanıcı</th>
        <th>Fiyat</th>
        <th>Kazanç</th>
        <th>Başlık</th>
        <th>Nickname</th>
        <th>Durumu</th>
        <th>İşlem Tar</th>
        <th>Update Tar</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
	<?
	if($uid!=''){
    $sor=DB::select("select * from pazar_yeri_ilanlar where id='$uid'  and isnull(deleted_at)");
    }else{

    //$sor=DB::select('select * from pazar_yeri_ilanlar where status in(0,4,5,6,3) and  date(updated_at) between ? and ?   and isnull(deleted_at)' , [$date1,$date2]);
    $sor=DB::select('select * from pazar_yeri_ilanlar where status in(0,3,4,5,6) and  date(updated_at) between ? and ?   and isnull(deleted_at) order by id desc, updated_at desc limit 200', [$date1,$date2]);
	}
    //DB::table('pazar_yeri_ilanlar')->whereDate('created_at', '>=', $date1)->whereDate('created_at', '<=', $date2)->whereDate('updated_at', '>=', $date1)->whereDate('updated_at', '<=', $date2)->whereNull('deleted_at')->get()
	?>
    @foreach($sor as $u)
        <tr @if($u->status == 0) class="text-white" style="background-color: crimson"
            @elseif($u->status == 4) class="text-white" style="background-color: forestgreen"
            @elseif($u->status == 5) class="text-white" style="background-color: darkred" @endif
        >
            <td>{{$u->id}}</td>
            <td>{{DB::table('games_titles')->where('id', $u->pazar)->first()->title}}</td>
            <td><?php $uye = DB::table('users')->where('id', $u->user)->first(); ?><a href="{{route('uye_detay', [$uye->email])}}" target="_blank">{{$uye->name}}</a></td>
            <td><?=number_format($u->price,2,".","")?></td>
            <td><?=number_format($u->moment_komisyon,2,".","")?></td>
            <?php $item = DB::table('games_titles')->where('id', $u->pazar)->first(); ?>
            <? if($u->sunucu == '') {$u->sunucu = 'genel';} ?>
            <td><a href="{{route('item_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}" target="_blank">{{mb_substr($u->title, 0, 40)}}</a></td>
            <td>
                @if(DB::table('pazar_yeri_ilan_satis')->where('ilan', $u->id)->whereNull('deleted_at')->count() > 0)
                    <?php $satis = DB::table('pazar_yeri_ilan_satis')->where('ilan', $u->id)->first(); ?>
                    {{$satis->note}}
                        <?php $uye2 = DB::table('users')->where('id', $satis->satin_alan)->first(); ?>
                        (<a href="{{route('uye_detay', [$uye2->email])}}" target="_blank">
                            {{$uye2->name}}
                        </a>)
                @else
                    Henüz Satılmamış
                @endif
            </td>
            <td>{{findIlanStatus($u->status)}} - {{findUserIlanStatus($u->userStatus)}}</td>
            <td class="text-nowrap">
{{--                @if(DB::table('pazar_yeri_ilan_satis')->where('ilan', $u->id)->count() > 0)--}}
{{--                    {{DB::table('pazar_yeri_ilan_satis')->where('ilan', $u->id)->first()->created_at}}--}}
{{--                @else--}}
{{--                    {{$u->updated_at}}--}}
{{--                @endif--}}
                {{$u->created_at}}
            </td>
            <td class="text-nowrap">{{$u->updated_at}} </td>
            <td style="text-align: center" >
                    <i data-toggle="modal" data-target=".goruntule{{$u->id}}" class="btn btn-sm fas fa-eye"></i>
					<i data-toggle="modal" data-target=".smsGonder{{$u->id}}" class="btn btn-sm fas fa-sms"></i>
                @if(userRoleIsAdmin(Auth::user()->id))
                        <i onclick="deleteContent('pazar_yeri_ilanlar', {{$u->id}})" class="btn btn-sm far fa-trash-alt"></i>
                @endif

                <div class="modal fade goruntule{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0" id="myModalLabel">{{$u->title}} </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">

										<div class="col-md-6">

                                            <div class="col-12 d-flex mt-md-n1">
                                                <label class="col-6 form-control text-left">Pazar Yeri:</label> <label class="col-8 form-control text-left"> {{DB::table('games_titles')->where('id', $u->pazar)->first()->title}}</label>
                                            </div>
                                            <div class="col-12 d-flex mt-md-n1">
                                                <label class="col-6 form-control text-left">Kullanıcı:</label> <label class="col-8 form-control text-left"><?php $uye = DB::table('users')->where('id', $u->user)->first(); ?><a title="Üye Yönetim" href="{{route('uye_detay', [$uye->email])}}" target="_blank">{{$uye->name}}</a>
                                                <i title="Üye Hareket İzleme" onclick="window.open('https://oyuneks.com/panel/uye-aktivite?uid={{$uye->id}}')" class="btn fa fa-search" aria-hidden="true"></i>
                                                </label>
                                            </div>
                                            <div class="col-12 d-flex mt-md-n1">
                                                <label class="col-6 form-control text-left">Fiyat : </label> <label class="col-8 form-control text-left">{{number_format($u->price,2,',','.')}} TL</label>
                                            </div>
                                            <div class="col-12 d-flex mt-md-n1">
                                                <label class="col-6 form-control text-left">Satıcının Kazanacağı :</label> <label class="col-8 form-control text-left"> {{number_format($u->moment_komisyon,2,',','.')}} TL</label>
                                            </div>
                                            <div class="col-12 d-flex mt-md-n1">
                                                <label class="col-6 form-control text-left">Başlık : </label> <label class="col-8 form-control text-left">{{$u->title}}</label>
                                            </div>
                                            <div class="col-12 d-flex mt-md-n1">
                                                <label class="col-6 form-control text-left">Sunucu : </label> <label class="col-8 form-control text-left">{{ucfirst($u->sunucu)}}</label>
                                            </div>
                                            <div class="col-12 d-flex mt-md-n1">
                                                <label class="col-6 form-control text-left">İlan Durumu : </label> <label class="col-8 form-control text-left">{{findIlanStatus($u->status)}}</label>
                                            </div>
                                            <div class="col-12 d-flex mt-md-n1">
                                                <label class="col-6 form-control text-left">Tarih :</label> <label class="col-8 form-control text-left"> {{$u->created_at}}</label>
                                            </div>
                                            <div class="col-md-12" style="display: flex; flex-direction: column">
                                                <label class="col-6 form-control text-left" style="display: flex; flex-direction: column"> Açıklama : </label> <label class="font-12  text-left">{{$u->text}}</label>
                                            </div>


                                                <?if($u->grup!=0) {$sets = str_replace('#', ',', mb_substr($u->grup, 0, -1));
                                                $res = DB::select("SELECT gt.id, gt.title, gt.description, gp.image FROM games_titles_items_info gt LEFT JOIN games_titles_items_photos gp on gp.item=gt.id where gt.id in($sets)");
                                                ?>
                                            <div class="col-12 mt-3" style="display: flex;flex-direction: column;flex-wrap: wrap;align-items: center;background-color: black;border-style: solid;"> <h5>SET İÇERİĞİ</h5>
                                                <ol type="1">
                                                @foreach($res as $r)
                                                        <div style="display: flex"><input style="margin: 0 20px" type="checkbox"> <li>{{$r->title}}</li></div>
                                                @endforeach
                                                </ol>
                                            </div>
                                            <?}?>



                                        @if($u->status == 2)
                                                <div class="col-md-12 text-danger">
                                                    <label class="form-label">Red Nedeni : {{$u->red_neden}}</label>
                                                </div>
                                            @endif
                                        </div>
											<div class="col-md-6">
                                                <?php
                                                $ilanIcerik = DB::table('pazar_yeri_ilan_icerik')->where('ilan', $u->id)->first();
                                               if ($ilanIcerik)
                                                {
                                                    $photo = DB::table('games_titles_items_photos')->where('item', $ilanIcerik->item)->first();
                                                    if ($photo){$photo = '/front/games_items/' . $photo->image;}
                                                }
                                                 $photo = $u->image ?  "/public_html/front/ilanlar/".$u->image :$photo;
                                                        ?>
												<div class="col-md-12">	<img src="{{$photo}}" width="300px" style="border-style: solid;border-color: bisque;"> </div>
											</div>
										</div>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">İlan İşlemleri</h4>
                                                <div class="row">
                                                    @if($u->status == 0 or $u->status == 1 or $u->status == 3)
                                                        <div class="col-md-4">
                                                            @if($u->status == 0 or $u->status == 3)
                                                                <button onclick="onay_al({{$u->id}},1)"  class="btn btn-outline-success w-100">İlanı Yayınla </button>
                                                            @endif
                                                            @if($u->status == 1)
                                                                <button onclick="onay_al({{$u->id}},'3')" class="btn btn-outline-danger w-100">İlanı Yayından Kaldır </button>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if( $u->status == 0)
                                                    <div class="col-md-2"></div>
                                                        <div class="col-md-6 ">
                                                            <form method="post"
                                                                  action="{{route('ilanlar_yonetim_onay_red')}}">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input type="text" class="form-control"
                                                                               name="red_neden" placeholder="Red Nedeni"
                                                                               required>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input type="hidden" name="id"
                                                                               value="{{$u->id}}">
                                                                        <input type="hidden" name="durum" value="2">
                                                                        <button class="btn btn-outline-danger w-100">
                                                                            İlan
                                                                            Yayınını Reddet
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    @endif
                                                    {{-- @if($u->status != 3 and $u->status != 6)
                                                        <div class="col">
                                                            <button onclick="onay_al({{$u->id}},3)" class="btn btn-outline-primary w-100">İlanı Pasif Hale Getir </button>
                                                        </div>
                                                    @endif --}}
                                                    @if($u->status == 4 or $u->status == 5)
                                                        <div class="col">
                                                            @if($u->status == 4)
                                                                <button onclick="onay_al({{$u->id}},5)" class="btn btn-outline-primary w-100">Site itemi aldı olarak işaretle </button>
                                                                <button onclick="onay_al({{$u->id}},3,'-ozel')"  class="btn btn-outline-danger w-100 mt-2">Satış Başarısız Olarak İşaretle ve Alıcıya Parasını İade Et </button>
                                                            @endif
                                                            @if($u->status == 5)
                                                                <button onclick="onay_al({{$u->id}},6)"  class="btn btn-outline-success w-100">Satış Başarılı Olarak İşaretle Ve Parayı Satıcıya Gönder </button>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>


                                                @if(DB::table('pazar_yeri_ilan_satis')->where('ilan', $u->id)->count() > 0)
                                                    <div class="col-12">
                                                        <hr>
                                                    </div>
                                                    <h4 class="card-title">
                                                        @if($u->money_status == 0)
                                                            Satıcı Henüz Ödemesini Almamış
                                                        @else
                                                            Satıcı Ödemesini Almış
                                                        @endif
                                                    </h4>
                                                   {{--  @if(userRoleIsAdmin(Auth::user()->id)) --}}
                                                        @if($u->money_status == 0)
                                                            <button onclick="onay_al({{$u->id}},1,'-ozel')" class="btn btn-outline-success">Satıcının Parasını Gönder ({{$u->moment_komisyon}}TL) </button>
                                                        @else
                                                            <button onclick="onay_al({{$u->id}},'0','-ozel')" class="btn btn-outline-danger">Satıcıdan Parayı Geri Al ({{$u->moment_komisyon}}TL) </button>
                                                        @endif
                                                    <button onclick="onay_al({{$u->id}},3,'-ozel')" class="btn btn-outline-warning">Alıcıya Parasını Geri Gönder ({{$u->price}}TL) </button>
                                                    {{-- @endif --}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary waves-effect"
                                        data-dismiss="modal">@lang('admin.kapat')</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->



               <div class="modal fade smsGonder{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0" id="myModalLabel">{{$u->title}} İlanı Sms Gönder</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X </button>
                            </div>
                            <div class="modal-body">
                                <div class="card">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Satıcıya Sms Gönder</h4>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <form method="post" action="{{route('ilanlar_yonetim_sms_gonder')}}">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col">
                                                                    <input type="text" class="form-control" name="sms_metni"  placeholder="Gönderilecek tam sms metnini yazın." required>
                                                                </div>
                                                                <div class="col-3">
                                                                    <input type="hidden" name="id" id="_id" value="{{$u->id}}"> <button class="btn btn-outline-primary w-100"> SMS'i gönder </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary waves-effect"
                                        data-dismiss="modal">@lang('admin.kapat')</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Id</th>
        <th>Pazar</th>
        <th>Kullanıcı</th>
        <th>Fiyat</th>
        <th>Kazanç</th>
        <th>Başlık</th>
        <th>Nickname</th>
        <th>Durumu</th>
        <th>İşlem Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

<? } ?>
