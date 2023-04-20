<? #--------- saat 23:59:00 sistem devir olayları sırasında paneli 64 sn. kapatıyoruz?>
@if(date("H:i:s")>date("H:i:s", strtotime('23:58:58')) || date("H:i:s") < date("H:i:s", strtotime('00:00:04')))
    <center> <h2>Panik yok..!! <br>
            Sistem devir işlemleriyle meşgul olduğu için işleminiz askıya alındı ve buradasınız.  <br>
            Saat şu an <?=date('H:i:s')?> ve bu işlemin 00:00:04 e kadar sürmesi öngörülmekte. <br>
            F5 tuşuna basarak süreyi kontrol edebilir, süre sonunda işleminize devam edebilirsiniz.<br>
        </h2>
    </center>
    @php(die)
@endif

<div class="col-md-12 mb-4 mt-4">
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
                <input type="date" id="userinput2" class="form-control style-input" name="date2"
                       value="{{$date2}}" required>
            </div>
        </div>
			<div>
				<div class="col-sm-12 mb-3">
				<label class="form-label">Durum</label>
				<?php if (isset($_GET['status'])) {$status = $_GET['status'];} else {$status = "0";} ?>
						   <select name="status" onchange="this.form.submit()" class="form-control ">
								<option value="0" @if($status==0) selected @endif>İşlem Bekleyenler</option>
								<option value="1" @if($status==1) selected @endif>Tamamlananlar</option>
								<option value="2" @if($status==2) selected @endif>İptal Edilenler</option>
								<option value="3" @if($status>2) selected @endif>Tümü</option>
							</select>
				 
				</div>
			</div>
        <div class="col-sm-12 col-md-1 d-flex justify-content-sm-start justify-content-md-end align-items-center">
            <button type="submit" class="btn btn-outline-success btn-block mt-2 color-blue">Sorgula
        </div>
    </form>
	
	
</div>
        

<table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-bordered table-hover nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Id</th>
        <th>Satın Alan</th>
        <th>Paket</th>
        <th>Nickname</th>
        <th>Adet</th>
        <th>İşlem Türü</th>
        <th>Fiyat</th>
        <th>Durumu</th>
        <th>İşlem Tarihi</th>
        <th>Tamamlanma Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>

    <?php
    if (isset($_GET['status'])) {$status = $_GET['status'];} else{ $status='0';}

if($status==3){
	$sorgu=DB::select("
	SELECT u.name, u.email, gpt.title, ggs.*
	FROM game_gold_satis  ggs 
	inner join users u on u.id=ggs.user
	inner join games_packages_trade gpt on gpt.id=ggs.paket
	WHERE isnull(ggs.deleted_at) and date(ggs.created_at) BETWEEN ? and ? order by ggs.id desc",[$date1,$date2]);
}
else{
	$sorgu=DB::select("
	SELECT u.name, u.email, gpt.title, ggs.*,u.id uid
	FROM game_gold_satis  ggs 
	inner join users u on u.id=ggs.user
	inner join games_packages_trade gpt on gpt.id=ggs.paket
	WHERE ggs.STATUS=? and isnull(ggs.deleted_at) and date(ggs.created_at) BETWEEN ? and ? order by ggs.id desc",[$status,$date1,$date2]);
}		
		/*
		if ($status > 2) {
            $sorgu = DB::table('game_gold_satis')->whereNull('deleted_at')->get();
        } else {
            $sorgu = DB::table('game_gold_satis')->where('status', $status)->whereNull('deleted_at')->get();
        }
    else {
        $sorgu = DB::table('game_gold_satis')->where('status', '0')->whereNull('deleted_at')->get();
    }
	*/
	
    ?>
    @foreach($sorgu as $u)

        <?php

        if ($u->tur == 'bizden-al') {
            $tur = "Müşteriye Satış";
        } else {
            $tur = "Müşteriden Alış";
        }

        ?>

        <tr @if($u->status == 0) 
                @if($u->tur == "bizden-al") class="bg-orange text-white"
                @elseif($u->teslim_nick != NULL and $u->tur == "bize-sat") class="bg-beanred text-white"
                    @else class="bg-info text-white" 
                @endif 
            @endif>
            
            <td>{{$u->uid}}</td>
            <td>
                <?php //$uye = DB::table('users')->where('id', $u->user)->first(); ?>
                <a class="text-reset" href="{{route('uye_detay', [$u->email])}}" target="_blank">
                    {{$u->name}}
                </a>
            </td>
            <td <?if(strpos($u->title,'Rise On')!==false) {echo "class='bg-blue-gradient text-white'";} ?>>{{$u->title}}</td>
            <td>{{$u->note}}</td>
            <td>{{$u->adet}}</td>
            <td>{{$tur}}</td>
            <td><?=number_format($u->price,2,".","")?></td>
            <td>
                @if($u->status == 0)
                    Müşteri teslimat bekliyor
                    @if($u->teslim_nick == NULL and $u->tur == "bize-sat")
                        <span class="text-danger">Nick Verilmemiş</span>
                    @elseif($u->teslim_nick != NULL and $u->tur == "bize-sat")
                        <span class="text-blue" title="{{$u->teslim_nick}}"><? echo substr($u->teslim_nick,0,30);?>..</span>
                    @endif
                @elseif($u->status == 1)
                    Satış Tamamlandı
                @elseif($u->status == 2)
                    Satış İptal Edildi
                @else
                    Bir Sorun Oluştu
                @endif
            </td>
            <td>
                {{$u->created_at}}
            </td>
            <td>
                {{$u->updated_at}}
            </td>
            <td align="center">
                @if($u->status == 0)
                    @if($u->tur == "bize-sat")
                        <i data-toggle="modal" data-target="#nickGir{{$u->id}}" class="btn fas fa-pen"></i> 
                        <div class="modal fade" id="nickGir{{$u->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Nick Girin</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="post" action="{{route('game_gold_yonetim_onayla_post', [0, $u->id])}}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="col-form-label">Teslim Edecek Nick</label><br>
                                                <input placeholder="Teslim edecek nick" type="text" name="teslim_nick" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
											<button type="submit" class="btn btn-primary">Onayla</button>
                                        </div>
									</form>
                                </div>
                            </div>
                        </div>

                        @if($u->teslim_nick != NULL)
                                <i onclick="confirme(1,<?=$u->id?>)" class="btn fas fa-check"></i> 
                        @endif        <? // location.href='{{route('game_gold_yonetim_onayla', [1, $u->id])}}' ?>
                    @else
                            <i onclick="confirme(1,<?=$u->id?>)" class="btn fas fa-check"></i>                        
                    @endif
                @endif
                @if($u->status == 0)
                        <i onclick="confirme(2,<?=$u->id?>)" class="btn fas fa-times"></i> 
                @endif
                @if(userRoleIsAdmin(Auth::user()->id))					
                        <i onclick="deleteContent('game_gold_satis', {{$u->id}})" class=" btn far fa-trash-alt"></i>                    
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Id</th>
        <th>Satın Alan</th>
        <th>Paket</th>
        <th>Nickname</th>
        <th>Adet</th>
        <th>İşlem Türü</th>
        <th>Fiyat</th>
        <th>Durumu</th>
        <th>İşlem Tarihi</th>
        <th>Tamamlanma Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>



