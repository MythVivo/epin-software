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
								<option value="0" @if($status==0) selected @endif>Fatura Kesilmeyenler</option>								
								<option value="1" @if($status==1) selected @endif>Fatura Kesilenler</option>
								<option value="2" @if($status==2) selected  @endif>Tümü</option>
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
        <th>Tarih</th>
        <th>İsim</th>      
        <th>Adet</th>        
        <th>Tutar</th>        
        <th>Durum</th>
        <th>Eylem</th>
    </tr>
    </thead>
    <tbody>

    <?php
    /*
    
	SELECT es.adet, format(es.price/es.adet,2) Satis,gpc.alis_fiyati,gpc.kdv, gp.title, u.name,u.email,u.tcno, es.created_at,f.sonuc sonuc 
    FROM epin_satis es 
    join games_packages gp on gp.id=es.paketId 
    join users u on u.id=es.user 
    join epin_satis_kodlar esc on esc.epin_satis=es.id 
    join games_packages_codes gpc on gpc.id=esc.code_id 
    left join e_fatura f on f.satis_id=es.id
    WHERE date(es.created_at) BETWEEN '$date1' and '$date2' isnull(deleted_at) order by u.id, kdv");
    */
    
    if (isset($_GET['status'])) {$status = $_GET['status'];} else{ $status='0';}

	$sorgu=DB::select("
                        SELECT DISTINCT es.*,u.id uid,u.name, u.email, sum(adet) tadet,format(sum(price),2) rakam,count(user) adet, date_format(es.created_at,'%Y-%m-%d') tarih
                        FROM epin_satis es
                        left join users u on u.id=es.user
                        where date(es.created_at) BETWEEN '$date1' and '$date2' and isnull(es.deleted_at) and isnull(u.deleted_at) group by user
                    ");
    ?>

    @foreach($sorgu as $u)
        <tr class="tik" id="s_{{$u->uid}}">         
            <td>{{$u->id}}</td>
            <td>{{$u->tarih}}</td>
            <td><a href="{{route('uye_detay', [$u->email])}}" target="_blank">{{$u->name}}</a></td>            
            <td>{{$u->adet}}</td>            
            <td>{{$u->rakam}}</td>                                    
            <td>durum</td>
            <td align="center">
                <i onclick="confirme(1,<?=$u->id?>)" class="btn fas fa-check"></i>
                <i onclick="confirme(2,<?=$u->id?>)" class="btn fas fa-times"></i>             
            </td>        
        </tr>
    @endforeach
    
    </tbody>
    <tfoot>
    <tr>
        <th>Id</th>
        <th>Tarih</th>
        <th>İsim</th>      
        <th>Adet</th>
        <th>Tutar</th>        
        <th>Durum</th>
        <th>Eylem</th>
    </tr>
    </tfoot>
</table>


    <div class="modal fade" id="goster" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">        
            <div class="modal-content">
                
            </div>                            
        </div>
    </div>

@section('js')

<script>
jQuery.noConflict();
$('.tik').click(function(){
    let w='<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
        $('#goster').modal({backdrop: 'static', keyboard: false}); 
        //alert($(this).attr('id'));
        $.get('/ykp.php',{date1:{{$date1}},date2:{{$date2}},islem:'bizden-al',user:$(this).attr('id').split('_')[1]},function(x){
            $('.modal-content').empty().html(x+w);
            
        });
});
</script>
@endsection