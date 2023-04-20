<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>@lang('admin.haberBasligi')</th>
        <th>@lang('admin.haberAciklama')</th>
        <th>@lang('admin.haberResmi')</th>
        <th>@lang('admin.haberMetni')</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>@lang('admin.durum')</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\App\Models\News::where('lang', getLang())->whereNull('deleted_at')->get() as $u)
        <tr id="row-{{$u->id}}">
            <td>{{$u->title}}</td>
            <td>{{$u->text_short}}</td>
            <td class="text-center"><img style="max-width: 50%;"
                                         src="{{asset(env('ROOT').env('FRONT').env('NEWS').$u->image)}}"></td>
            <td>
                <a href="{{route('haber_detay', $u->link)}}"
                   target="_blank">@lang('admin.goruntulemek-icin-tiklayin')</a>
            </td>
            <td>
                {{$u->created_at}}
            </td>
            <td id="statusText"><?=getDataStatus($u->status)?></td>
            <td>
                <button id="status" onclick="status({{$u->id}}, 'news', event)" type="button"
                        class="btn btn-lg @if($u->status == 0) btn-outline-warning @else btn-outline-success @endif waves-effect waves-light">
                    <i id="status-icon" class="mdi mdi-eye"></i>
                </button>
                <button data-toggle="modal" data-target=".duzenle" onclick="edit({{$u->id}}, 'news', event)"
                        type="button"
                        class="btn btn-lg btn-outline-primary waves-effect waves-light">
                    <i class="far fa-edit"></i>
                </button>
                @if(userRoleIsAdmin(Auth::user()->id))
                    <button onclick="deleteContent('news', {{$u->id}})" type="button"
                            class="btn btn-lg btn-outline-danger waves-effect waves-light">
                        <i class="far fa-trash-alt"></i>
                    </button>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>@lang('admin.haberBasligi')</th>
        <th>@lang('admin.haberAciklama')</th>
        <th>@lang('admin.haberResmi')</th>
        <th>@lang('admin.haberMetni')</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>@lang('admin.durum')</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

