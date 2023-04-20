<table lang="{{getLang()}}" id="datatable" class="font-12 table table-bordered table-hover text-reset" style="border-collapse: collapse; border-spacing: 0;">

    <thead>
    <tr>
        <th>Yorum</th>
        <th>User</th>        
        <th>Tarih</th>
        <th>Durum</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    
    <?
    $sor=DB::table('comments as c')
    ->select('c.*','u.name','gt.title')
    ->join('games_titles as gt','c.oyun','=','gt.id')
    ->join('users as u','c.user','=','u.id')
    ->whereNull('c.deleted_at')
    ->orderBy('created_at','desc')
    ->paginate(100);
    
    
//    select("SELECT c.*,u.name,gt.title FROM comments c, games_titles gt, users u WHERE c.user=u.id and c.oyun=gt.id and isnull(c.deleted_at) order by created_at")->paginate(10);    
    ?>
    <tbody>    
    @foreach($sor as $u)    
        <tr>
            <td><b>{{$u->title}} ({{$u->rate}} Puan)</b> <br> {{$u->text}}</td>
            <td>{{$u->name}}</td>            
            <td nowrap>{{$u->created_at}}</td>
            <td id="st_{{$u->id}}"><?=getDataStatus($u->status)?></td>
            <td nowrap>
            <i id="status-icon" onclick="status({{$u->id}},1)" title="Onayla" class="btn btn-sm mdi mdi-check-outline"></i>
            <i id="status-icon" onclick="status({{$u->id}},2)" title="Red" class="btn btn-sm mdi mdi-close-outline"></i>
                @if(userRoleIsAdmin(Auth::user()->id))
                    <i onclick="deleteContent('comments', {{$u->id}})" title="Sil" class="btn far fa-trash-alt"></i>                
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
    
</table>
@if($sor->total()>100)
<a href="{{$sor->previousPageUrl()}}" class="btn">Önceki</a>    
    <span class="about font-monospace">[{{$sor->currentPage()}} > <b><?=ceil($sor->total()/$sor->perPage())?></b>]</span>
<a href="{{$sor->nextPageUrl()}}" class="btn">Sonraki</a>
@endif