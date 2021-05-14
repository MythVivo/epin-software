<table lang="{{getLang()}}" id="datatable" class="table table-bordered dt-responsive nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>@lang('admin.yorumAciklama')</th>
        <th>@lang('admin.yorumYapan')</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>@lang('admin.durum')</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\App\Models\Comments::where('lang', getLang())->whereNull('deleted_at')->get() as $u)
        <tr id="row-{{$u->id}}">
            <td>{{$u->text}}</td>
            <td>
                {{getUserName($u->user)}}
            </td>
            <td>
                {{$u->created_at}}
            </td>
            <td id="statusText"><?=getDataStatus($u->status)?></td>
            <td>
                <button id="status" onclick="status({{$u->id}}, 'comments', event)" type="button"
                        class="btn btn-lg @if($u->status == 0) btn-outline-warning @else btn-outline-success @endif waves-effect waves-light">
                    <i id="status-icon" class="mdi mdi-eye"></i>
                </button>
                <button onclick="deleteContent('comments', {{$u->id}})" type="button"
                        class="btn btn-lg btn-outline-danger waves-effect waves-light">
                    <i class="far fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>@lang('admin.yorumAciklama')</th>
        <th>@lang('admin.yorumYapan')</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>@lang('admin.durum')</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

