<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>@lang('admin.sssBasligi')</th>
        <th>@lang('admin.sssMetni')</th>
        <th>@lang('admin.sssKategorisi')</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>@lang('admin.durum')</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\App\Models\Faq::where('lang', getLang())->whereNull('deleted_at')->get() as $u)
        <tr id="row-{{$u->id}}">
            <td>{{$u->title}}</td>
            <td>{!! $u->text !!}</td>
            <td>{{findFaqCategory($u->category)}}</td>
            <td>
                {{$u->created_at}}
            </td>
            <td id="statusText"><?=getDataStatus($u->status)?></td>
            <td>
                <button id="status" onclick="status({{$u->id}}, 'faq', event)" type="button"
                        class="btn btn-lg @if($u->status == 0) btn-outline-warning @else btn-outline-success @endif waves-effect waves-light">
                    <i id="status-icon" class="mdi mdi-eye"></i>
                </button>
                <button data-toggle="modal" data-target=".duzenle{{$u->id}}" type="button"
                        class="btn btn-lg btn-outline-primary waves-effect waves-light">
                    <i class="far fa-edit"></i>
                </button>
                <button onclick="deleteContent('faq', {{$u->id}})" type="button"
                        class="btn btn-lg btn-outline-danger waves-effect waves-light">
                    <i class="far fa-trash-alt"></i>
                </button>
            </td>

            <div class="modal fade duzenle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.sssDuzenle')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                            </button>
                        </div>
                        <form autocomplete="off" id="duzenle" method="post" action="{{route('sss_edit')}}" enctype="multipart/form-data">
                            @csrf
                            {!! getLangInput() !!}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="1">@lang('admin.sssBasligi')</label>
                                            <input name="title" type="text" class="form-control" id="1"
                                                   placeholder="@lang('admin.sssBasligi')" value="{{$u->title}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="2">@lang('admin.sssKategorisi')</label>
                                            <select id="2" class="form-control" name="category">
                                                @foreach(DB::table('faq_categories')->whereNull('deleted_at')->where('lang', getLang())->get() as $uu)
                                                    <option value="{{$uu->id}}" @if($u->category == $uu->id) selected @endif >{{$uu->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="3">@lang('admin.sssMetni')</label>
                                            <textarea class="form-control" placeholder="@lang('admin.sssMetni')" id="3" name="text">{!! $u->text !!}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary waves-effect"
                                        data-dismiss="modal">@lang('admin.kapat')</button>
                                <input id="5" type="hidden" name="id" value="{{$u->id}}">
                                <button type="submit"
                                        class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->


        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>@lang('admin.sssBasligi')</th>
        <th>@lang('admin.sssMetni')</th>
        <th>@lang('admin.sssKategorisi')</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>@lang('admin.durum')</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

