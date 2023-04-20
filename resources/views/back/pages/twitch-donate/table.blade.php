<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr class="twitch-tablo-baslik">
        <th>Id</th>
        <th>Yayıncı</th>
        <th>Gönderen</th>
        <th>Mesaj Başlığı</th>
        <th>Mesaj İçeriği</th>
        <th>Tutar</th>
        <th>İşlem Tarihi</th>
    </tr>
    </thead>
    <tbody>
    @foreach(DB::table('twitch_support_donates')->whereNull('deleted_at')->get() as $u)
        <tr>
            <td>{{$u->id}}</td>
            <td>
                <?php $streamer = DB::table('twitch_support_streamer')->where('id', $u->streamer)->first(); ?>
                @if($streamer)
                    <?php $uye = DB::table('users')->where('id', $streamer->user)->first(); ?>
                    @if($uye)
                        <a href="{{route('uye_detay', [$uye->email])}}" target="_blank">
                            {{$uye->name}}
                        </a>
                    @else
                        Eski Yayıncı
                    @endif
                @else
                    Eski Yayıncı
                @endif
            </td>
            <td>
                <?php $uye = DB::table('users')->where('id', $u->user)->first(); ?>
                <a href="{{route('uye_detay', [$uye->email])}}" target="_blank">
                    {{$uye->name}}
                </a>
            </td>
            <td>{{$u->title}}</td>
            <td>{{$u->text}}</td>
            <td>{{$u->amount}}</td>
            <td>{{$u->created_at}}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Id</th>
        <th>Yayıncı</th>
        <th>Gönderen</th>
        <th>Mesaj Başlığı</th>
        <th>Mesaj İçeriği</th>
        <th>Tutar</th>
        <th>İşlem Tarihi</th>
    </tr>
    </tfoot>
</table>

