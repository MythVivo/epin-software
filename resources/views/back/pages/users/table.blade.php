<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>@lang('admin.uyeIsmi')</th>
        <th>@lang('admin.uyeEposta')</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>@lang('admin.durum')</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\App\Models\User::whereNull('deleted_at')->get() as $u)
        <tr id="row-{{$u->id}}">
            <td>{{$u->name}}</td>
            <td>{{$u->email}}</td>
            <td>{{$u->created_at}}</td>
            <td id="statusText"><?=getDataStatus($u->status)?></td>
            <td>
                <button id="status" onclick="status({{$u->id}}, 'users', event)" type="button"
                        class="btn btn-lg @if($u->status == 0) btn-outline-warning @else btn-outline-success @endif waves-effect waves-light">
                    <i id="status-icon" class="mdi mdi-eye"></i>
                </button>
                <button onclick="location.href='{{route('uye_detay', [$u->email])}}'" type="button"
                        class="btn btn-lg btn-outline-primary waves-effect waves-light">
                    <i class="far fa-edit"></i>
                </button>
                <button onclick="deleteContent('users', {{$u->id}})" type="button"
                        class="btn btn-lg btn-outline-danger waves-effect waves-light">
                    <i class="far fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>@lang('admin.uyeIsmi')</th>
        <th>@lang('admin.uyeEposta')</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>@lang('admin.durum')</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

