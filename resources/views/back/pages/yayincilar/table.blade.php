<?php
if (isset($_GET['type'])) {
    $type = $_GET['type'];
} else {
    $type = 0;
}
?>
<form method="get" autocomplete="off">
    <select name="type" onchange="this.form.submit()" class="form-control select2 mt-3 mb-3">
        <option value="0" @if($type==0) selected @endif>Aktif Olanlar</option>
        <option value="1" @if($type==1) selected @endif>Hataya Düşmüş Olanlar</option>
    </select>
</form>
<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"

       style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

    <tr>

        <th>Kullanıcı</th>

        <th>Twitch Kanal İsmi</th>

        <th>Minimum Bağış Tutarı</th>

        <th>Favori</th>

        <th>Kayıt Tarihi</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </thead>

    <tbody>
    <?php
    if ($type == 0) {
        $sorgu = DB::table('twitch_support_streamer')->where('status', '1')->whereNull('deleted_at')->get();
    } else {
        $sorgu = DB::table('twitch_support_streamer')->where('status', '0')->whereNull('deleted_at')->get();
    }
    ?>

    @foreach($sorgu as $u)

        <tr>

            <td>{{DB::table('users')->where('id', $u->user)->first()->name}}</td>

            <td>{{$u->title}}</td>

            <td>{{$u->min_bagis}}</td>

            <td>

                @if($u->favori == 1)

                    <a href="{{route('twitch_yayincilar_yonetim_favori_kaldir', $u->id)}}">

                        <i class="fas fa-star text-warning"></i> Kaldır

                    </a>

                @else

                    <a href="{{route('twitch_yayincilar_yonetim_favori_ekle', $u->id)}}">

                        <i class="far fa-star text-warning"></i> Ekle

                    </a>

                @endif

            </td>

            <td>

                {{$u->created_at}}

            </td>

            <td>
                @if(userRoleIsAdmin(Auth::user()->id))
                    @if($type == 0)
                        <button onclick="deleteContent('twitch_support_streamer', {{$u->id}})" type="button"
                                class="btn btn-lg btn-outline-danger waves-effect waves-light">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    @else
                        <button onclick="location.href='?sil={{$u->id}}'" type="button"
                                class="btn btn-lg btn-outline-danger waves-effect waves-light">
                            <i class="far fa-trash-alt"></i> Kalıcı Sil
                        </button>
                    @endif

                @endif
            </td>

        </tr>

    @endforeach

    </tbody>

    <tfoot>

    <tr>

        <th>Kullanıcı</th>

        <th>Twitch Kanal İsmi</th>

        <th>Minimum Bağış Tutarı</th>

        <th>Favori</th>

        <th>Kayıt Tarihi</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </tfoot>

</table>



