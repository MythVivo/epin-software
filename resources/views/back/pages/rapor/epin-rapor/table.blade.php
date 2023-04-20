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
    <form class="row mb-3" method="get">

        <div class="col-sm-12 col-md-5">
            <div class="mb-3">
                <label class="form-label" for="userinput1">İlk Tarih</label>
                <input type="date" id="userinput1" class="form-control style-input" name="date1"
                       value="{{$date1}}" required>
            </div>
        </div>


        <div class="col-sm-12 col-md-5">
            <div class="mb-3">
                <label class="form-label" for="userinput2">Son Tarih</label>
                <input type="date" id="userinput2" class="form-control style-input" name="date2"
                       value="{{$date2}}" required>
            </div>
        </div>

        <div class="col-sm-12 col-md-2 d-flex justify-content-sm-start justify-content-md-end align-items-center">

            <button type="submit" class="btn btn-outline-success btn-block mt-2 color-blue">Sorgula


        </div>


    </form>

</div>
 
 KDV li
<table lang="{{getLang()}}" id="datatable" class="font-12 table-bordered table-hover nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Paket</th>
		<th>Adet</th>
        <th>Alış %18</th>
        <th>Satış %18</th>
        <th>Kar %18</th>        
    </tr>
    </thead>
    <tbody>
    <?php
    $sor0  = DB::select('SELECT DISTINCT esk.*, gp.title, format(sum(es.price/es.adet),2) as SATIS,  format(sum(gpc.alis_fiyati),2) ALIS, format(sum(es.price/es.adet)-sum(gpc.alis_fiyati),2) as KAR, gpc.kdv KDV, count(gp.title) as ADET
		FROM epin_satis_kodlar esk 
		inner join epin_satis es 		    				on es.id=esk.epin_satis
		inner join games_packages gp    	 				on es.paketId=gp.id
		inner join games_packages_codes gpc  				on esk.code_id=gpc.id
		where  date(es.created_at)  BETWEEN ?  and ? and kdv=0  order by satis',[$date1,$date2]
	)->groupBy('title');
	
	/*$sor18 = DB::select('SELECT DISTINCT esk.*, gp.title, format(sum(es.price/es.adet),2) as SATIS,  format(sum(gpc.alis_fiyati),2) ALIS, format(sum(es.price/es.adet)-sum(gpc.alis_fiyati),2) as KAR, gpc.kdv KDV, count(gp.title) as ADET
		FROM epin_satis_kodlar esk 
		inner join epin_satis es 		    				on es.id=esk.epin_satis
		inner join games_packages gp    	 				on es.paketId=gp.id
		inner join games_packages_codes gpc  				on esk.code_id=gpc.id
		where  date(es.created_at)  BETWEEN ?  and ? and kdv=18 group by title order by satis',[$date1,$date2]
	);
*/
    ?>
	
    @foreach($sor0 as $al0)	
        <tr>
            <td>{{$al0->title}}</td>
			<td>{{$al0->ADET}}</td>
            <td>{{$al0->ALIS}}</td>
			<td>{{$al0->SATIS}}</td>
			<td>{{$al0->KAR}}</td>      
        </tr>
    @endforeach
    </tbody>
    
</table>

