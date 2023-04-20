<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Id</th>
        <th>Firma</th>
        <th>Eklenme Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    @foreach(DB::table('games_packages_codes_suppliers')->whereNull('deleted_at')->get() as $u)
        <tr>
            <td>{{$u->id}}</td>
            <td>{{$u->title}}</td>
            <td>{{$u->created_at}}</td>
            <td>
                <button type="button"  data-toggle="modal" data-target=".duzenle{{$u->id}}" class="btn btn-lg btn-outline-primary waves-effect waves-light">
                    <i class="far fa-edit"></i>
                </button>
                <div class="modal fade duzenle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0" id="myModalLabel">Tedarikçi Düzenle</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                </button>
                            </div>
                            <form id="yeniEkle" method="post" action="{{route('tedarikciler_edit')}}" enctype="multipart/form-data">
                                @csrf
                                {!! getLangInput() !!}
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="1">Firma Adı</label>
                                                <input name="title" type="text" class="form-control" id="1"
                                                       placeholder="Firma Adı" value="{{$u->title}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{$u->id}}">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary waves-effect"
                                            data-dismiss="modal">@lang('admin.kapat')</button>
                                    <button type="submit"
                                            class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                </div>
                            </form>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                @if(userRoleIsAdmin(Auth::user()->id))
                <button onclick="deleteContent('games_packages_codes_suppliers', {{$u->id}})" type="button"
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
        <th>Id</th>
        <th>Firma</th>
        <th>Eklenme Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

