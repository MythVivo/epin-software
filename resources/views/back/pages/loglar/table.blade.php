<div class="col-md-12 text-left">
<form class="row" method="get">
	<div class="col-sm-12 col-md-2"><div class="mb-3"><label class="form-label" for="d1">İlk Tarih</label> <input type="date" id="d1" class="form-control style-input" name="date1" value="{{$date1}}" <?=$ch == 1 ? 'disabled' : ''?> required> </div></div>
	<div class="col-sm-12 col-md-2"><div class="mb-3"><label class="form-label" for="d2">Son Tarih</label> <input type="date" id="d2" class="form-control style-input" name="date2" <?=$ch == 1 ? 'disabled' : ''?>  value="{{$date2}}" required></div></div>
	<div class="col-sm-12 col-md-1"><div class="mb-3"><label class="form-label" for="c1">Tüm Tarihler</label> <input type="checkbox" id="c1" class="form-control style-input" name="ch" @if($ch==1) checked  @endif style="height: 20px;"> </div></div>
	<div class="col-sm-12 col-md-2"><div class="mb-3"><label class="form-label" for="userinput3">İsim'de Geçen</label> <input type="text" id="userinput3" class="form-control style-input" name="isim" value="{{$isim}}" placeholder="İsimde geçen "></div></div>
	<div class="col-sm-12 col-md-2"><div class="mb-3"><label class="form-label" for="userinput4">Açıklama'da Geçen</label> <input type="text" id="userinput4" class="form-control style-input" name="acik" value="{{$acik}}" placeholder="Açıklamada geçen "></div></div>
	<div class="col-sm-12 col-md-2 d-flex justify-content-sm-start justify-content-md-end align-items-center"><button type="submit" class="btn-sm btn-outline-success btn-block mt-2 color-blue">Sorgula</div>
</form>
</div>
<table lang="{{getLang()}}" id="datatable" class="font-12 table-hover table-sm table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
<thead>
<tr>
<th>Id</th>
<th>Kullanıcı</th>
<th>Açıklama</th>
<th>@lang('admin.eklenmeTarihi')</th>
</tr>
</thead>
<tbody>    
<?php
if ($isim != '') { $ek1 = " and u.name like '%$isim%'"; } else {$ek1 = '';}        
if ($acik != '') {$ek2 = " and l.text like '%$acik%'"; } else {$ek2 = '';}
$loglar = DB::select("select l.*, u.name 
from logs l 
join users u on l.user=u.id 
where u.role=0 and l.category=4  and date(l.created_at) between '$date1' and '$date2'  $ek1  $ek2 ");
?>    
@foreach($loglar as $u)
<tr>
<td>{{$u->id}}</td>
<td>{{$u->name}}</td>
<td>{{$u->text}}</td>
<td>{{$u->created_at}}</td>
</tr>    
@endforeach    
</tbody>
</table>