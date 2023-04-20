<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Id</th>
        <th>Kullanıcı</th>
        <th>Kimlik Resmi</th>
        <th>Kimlik Bilgileri</th>
        <th>Onaya Gönderilme</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    @foreach(DB::table('users')->where('tc_verified_at_first', '!=', NULL)->whereNull('deleted_at')->get() as $u)
        <tr>
            <td>{{$u->id}}</td>
            <td>{{$u->name}}</td>
            <td class="text-center">
                @if($u->tc_image != '')
                    <a href="{{asset('public/secret/kimlik/'.$u->tc_image)}}" target="_blank">Resim yüklenmiş<br>Tıklayın</a>
                @else
                    <p class="text-danger">Resim Yok</p>
                @endif
            </td>
            <td>
                @if($u->yabanci == 1)
                    <p class="text-danger">Yabancı</p>
                    {{$u->name}} <br>
                    {{$u->dogum_tarihi}}
                @else
                    {{$u->tcno}} <br>
                    {{$u->name}} <br>
                    {{$u->dogum_tarihi}}
                @endif
            </td>
            <td>
                {{$u->tc_verified_at_first}}
            </td>
            <td>
                @if($u->tc_verified_at == NULL)
                    <button onclick="location.href='{{route('kimlik_yonetim_onayla', [1, $u->id])}}'"
                            type="button" class="btn btn-outline-success waves-effect waves-light">
                        <i class="fas fa-check"></i>
                    </button>
                @endif
                <button onclick="location.href='{{route('kimlik_yonetim_onayla', [2, $u->id])}}'"
                        type="button" class="btn btn-outline-danger waves-effect waves-light">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Id</th>
        <th>Kullanıcı</th>
        <th>Kimlik Resmi</th>
        <th>Kimlik Bilgileri</th>
        <th>Onaya Gönderilme</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

