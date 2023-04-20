<table id="datatable" class="font-12 table-sm table-bordered table-hover table" style="border-collapse: collapse; border-spacing: 0; width: 100%">
    <thead>
    <tr class="twitch-tablo-baslik">
        <th>Id</th>
        <th>Yayıncı</th>
        <th>Twitch Url</th>
        <th>video Klip</th>
        <th>Açıklama</th>
        <th>Durumu</th>
        <th>İşlem Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody class="twitc-kesintisiz-talepleri">
    <?
    $sor=(object)DB::select("select tw.*, u.email, u.name, u.id uid
    from twitch_kesintisiz_yayinci tw
    left join users u on u.id=tw.streamer
    where tw.deleted_at is null
    ");
    ?>
    @foreach($sor as $u)
        <tr>
            <td>{{$u->id}}</td>
            <td>
                <? //php $uye = DB::table('users')->where('id', $u->streamer)->first(); ?>
                <a href="{{route('uye_detay', [$u->email])}}" target="_blank">{{$u->name}}</a>
            </td>
            <td><a href="https://twitch.tv/{{$u->twitch_url}}" target="_blank"> TIKLA </a></td>
            <td><a href="{{$u->twitch_clip_link}}" target="_blank">TIKLA</a></td>
            <td>{{$u->text}}</td>
            <td>
                @if($u->status == '0')
                    Onay Bekliyor
                @elseif($u->status == '1')
                    Onaylı
                @else
                    Reddedildi : {{$u->red_neden}}
                @endif
            </td>
            <td>{{$u->created_at}}</td>
            <td>
                @if($u->status != 1)
                    <button onclick="location.href='{{route('twitch_kesintisi_yonetim_onayla', [1, $u->id])}}'" type="button" class="btn btn-sm"><i class="fas fa-check"></i></button>
                @endif
                @if($u->status != 2)
                    <button data-toggle="modal" data-target=".goruntule{{$u->id}}" type="button" class="btn btn-sm"><i class="fas fa-times"></i></button>
                    <div class="modal fade goruntule{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Reddet</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="text" class="form-control" id="red{{$u->id}}" placeholder="Red Nedeni">
                                            <button type="button" class="btn btn-outline-warning w-100 mt-3" onclick="location.href='{{route('twitch_kesintisi_yonetim_onayla', [2, $u->id])}}'+'?red='+$('#red{{$u->id}}').val()">Reddet</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @endif
                @if(userRoleIsAdmin(Auth::user()->id))
                <button onclick="deleteContent('twitch_kesintisiz_yayinci', {{$u->id}})" type="button" class="btn btn-sm"><i class="far fa-trash-alt"></i></button>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>



