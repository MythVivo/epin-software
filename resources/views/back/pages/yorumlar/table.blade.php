<div class="col-md-12">
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
            <button type="submit" class="btn btn-outline-success btn-block mt-2 color-blue">Getir
        </div>
        </form>
</div>


<table lang="{{getLang()}}" id="datatable" class="font-12 table table-bordered table-hover table-sm text-reset" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>User</th>
        <th>İlan</th>
        <th>Yorum</th>
        <th>Durumu</th>
        <th>Tarih</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    <?
    $sor=DB::select("
    select iy.*, u.email,u.name,gt.link,pyi.sunucu,pyi.title
    from ilan_yorumlar iy
    join users u on u.id=iy.user
    join pazar_yeri_ilanlar pyi on pyi.id=iy.ilan
    join games_titles gt on gt.id=pyi.pazar
    where isnull(iy.deleted_at) and date(iy.created_at) between '$date1' and '$date2' order by iy.created_at desc
    ");
    ?>
    @foreach($sor as $u)
        <tr>
            <td><a target="_blank" href="{{route('uye_detay', [$u->email])}}">{{$u->name}}</a></td>
            @if(DB::table($u->buy == 1 ? 'pazar_yeri_ilanlar_buy' : 'pazar_yeri_ilanlar')->where('id', $u->ilan)->count() > 0)
            @php
                $l = $u->buy == 1 ? "item_buy_ic_detay" : 'item_ic_detay';
            @endphp
                <td><a href="{{route( $l, [$u->link, $u->sunucu, Str::slug($u->title).'-'.$u->ilan])}}"
                       target="_blank">{{substr($u->title, 0, 20)}}...</a></td>
            @else
                <td>Eski İlan</td>
            @endif
            <td>{{$u->text}}</td>
            <td>
                @if($u->status == 0)
                    Onay Aş.
                @elseif($u->status == 1)
                    Onay
                @elseif($u->status == 2)
                    Red
                @else
                    Bir Sorun Oluştu
                @endif
            </td>
            <td>
                {{$u->created_at}}
            </td>
            <td style="white-space: nowrap;">
                @if($u->status != 1)
                        <i onclick="location.href='{{route('yorumlar_ilan_onayla', [1, $u->id])}}'" class="btn fas fa-check"></i>
                @endif
                @if($u->status != 2)
                        <i onclick="location.href='{{route('yorumlar_ilan_onayla', [2, $u->id])}}'" class="btn fas fa-times"></i>
                @endif
                @if(userRoleIsAdmin(Auth::user()->id))
                    <i onclick="deleteContent('ilan_yorumlar', {{$u->id}})" class="btn far fa-trash-alt"></i>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>



