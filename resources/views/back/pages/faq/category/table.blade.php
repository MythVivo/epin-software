<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"

       style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

    <tr>

        <th>@lang('admin.sssKategoriBasligi')</th>

        <th>@lang('admin.eklenmeTarihi')</th>

        <th>@lang('admin.durum')</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </thead>

    <tbody>

    @foreach(\App\Models\FaqCategory::where('lang', getLang())->whereNull('deleted_at')->get() as $u)

        <tr id="row-{{$u->id}}">

            <td>{{$u->title}}</td>

            <td>{{$u->created_at}}</td>

            <td id="statusText"><?=getDataStatus($u->status)?></td>

            <td>

                <button id="status" onclick="status({{$u->id}}, 'faq_categories', event)" type="button"

                        class="btn btn-lg @if($u->status == 0) btn-outline-warning @else btn-outline-success @endif waves-effect waves-light">

                    <i id="status-icon" class="mdi mdi-eye"></i>

                </button>

                <button data-toggle="modal" data-target=".duzenle{{$u->id}}" type="button"

                        class="btn btn-lg btn-outline-primary waves-effect waves-light">

                    <i class="far fa-edit"></i>

                </button>

                @if(userRoleIsAdmin(Auth::user()->id))
                <button onclick="deleteContent('faq_categories', {{$u->id}})" type="button"

                        class="btn btn-lg btn-outline-danger waves-effect waves-light">

                    <i class="far fa-trash-alt"></i>

                </button>
                @endif

            </td>



            <div class="modal fade duzenle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">

                <div class="modal-dialog modal-xl">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.sssKategoriDuzenle')</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X

                            </button>

                        </div>

                        <form autocomplete="off" id="duzenle" method="post" action="{{route('sss_kategori_edit')}}" enctype="multipart/form-data">

                            @csrf

                            {!! getLangInput() !!}

                            <div class="modal-body">

                                <div class="row">

                                    <div class="col-sm-12">

                                        <div class="form-group">

                                            <label for="1">@lang('admin.sssKategoriBasligi')</label>

                                            <input name="title" type="text" class="form-control" id="1"

                                                   placeholder="@lang('admin.sssKategoriBasligi')" value="{{$u->title}}">

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

        <th>@lang('admin.sssKategoriBasligi')</th>

        <th>@lang('admin.eklenmeTarihi')</th>

        <th>@lang('admin.durum')</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </tfoot>

</table>



