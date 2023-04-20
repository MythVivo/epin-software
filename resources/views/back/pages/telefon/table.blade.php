<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Id</th>
        <th>Kullanıcı</th>
        <th>Telefon Numarası</th>
        <th>Verilen Kod</th>
        <th>Onaya Gönderilme</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    @foreach(DB::table('users')->where('telefon_verified_at_first', '!=', NULL)->whereNull('deleted_at')->get() as $u)
        <tr>
            <td>{{$u->id}}</td>
            <td>{{$u->name}}</td>
            <td>+{{$u->telefon_country}} {{$u->telefon}}</td>
            <td>{{$u->telefon_code}}</td>
            <td>
                {{$u->telefon_verified_at_first}}
            </td>
            <td>
                @if($u->telefon_verified_at == NULL)
                <button onclick="location.href='{{route('telefon_onaylaAdmin', [1, $u->id])}}'"
                        type="button" class="btn btn-outline-success waves-effect waves-light">
                    <i class="fas fa-check"></i>
                </button>
                @endif
                <button onclick="location.href='{{route('telefon_onaylaAdmin', [2, $u->id])}}'"
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
        <th>Telefon Numarası</th>
        <th>Verilen Kod</th>
        <th>Onaya Gönderilme</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

