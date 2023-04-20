<table lang="{{getLang()}}" id="datatable4" class="table table-bordered nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Diğer Ödeme Yöntemi Adı</th>
        <th>Eklenme Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    @foreach(DB::table('payment_channels_diger')->whereNull('deleted_at')->get() as $u)
        <tr>
            <td>{{$u->title}}</td>
            <td>{{$u->created_at}}</td>
            <td>
                <button data-toggle="modal" data-target=".duzenleDiger{{$u->id}}" type="button"
                        class="btn btn-outline-primary waves-effect waves-light">
                    <i class="far fa-edit"></i>
                </button>
                @if(userRoleIsAdmin(Auth::user()->id))
                    <button onclick="deleteContent('payment_channels_diger', {{$u->id}})" type="button"
                            class="btn btn-outline-danger waves-effect waves-light">
                        <i class="far fa-trash-alt"></i>
                    </button>
                @endif
            </td>
        </tr>
        <div class="modal fade duzenleDiger{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myModalLabel">{{$u->title}} Düzenle</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                        </button>
                    </div>

                    <form id="duzenle" method="post" action="{{route('digerOdeme_edit')}}" enctype="multipart/form-data">

                        @csrf

                        {!! getLangInput() !!}

                        <div class="modal-body">

                            <div class="row">

                                <div class="col-sm-12">

                                    <div class="form-group">

                                        <label for="1">Diğer Ödeme Yöntemi Adı</label>

                                        <input name="title" type="text" class="form-control" id="1"
                                               placeholder="Diğer Ödeme Yöntemi Adı" value="{{$u->title}}">

                                    </div>

                                </div>

                            </div>


                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-outline-secondary waves-effect"

                                    data-dismiss="modal">@lang('admin.kapat')</button>

                            <input type="hidden" name="id" value="{{$u->id}}">

                            <button type="submit"

                                    class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>

                        </div>

                    </form>

                </div><!-- /.modal-content -->

            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->

    @endforeach

    </tbody>

    <tfoot>

    <tr>

        <th>Diğer Ödeme Yöntemi Adı</th>
        <th>Eklenme Tarihi</th>
        <th>@lang('admin.aksiyon')</th>

    </tr>

    </tfoot>

</table>



