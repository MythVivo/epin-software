<table lang="{{getLang()}}" id="datatable3" class="table table-bordered nowrap"

       style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

    <tr>

        <th>Kripto Para Adı</th>
        <th>Açıklama</th>

        <th>Logo</th>

        <th>Eklenme Tarihi</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </thead>

    <tbody>

    @foreach(DB::table('payment_channels_crypto')->whereNull('deleted_at')->get() as $u)

        <tr>

            <td>{{$u->title}}</td>

            <td>{{$u->text}}</td>

            <td class="text-center bg-white">

                <img style="max-width: 50%;" src="{{asset('/public/front/crypto_logo/'.$u->image)}}">

            </td>

            <td>{{$u->created_at}}</td>

            <td>

                <button data-toggle="modal" data-target=".duzenleCrypto{{$u->id}}" type="button"

                        class="btn btn-outline-primary waves-effect waves-light">

                    <i class="far fa-edit"></i>

                </button>
                @if(userRoleIsAdmin(Auth::user()->id))
                <button onclick="deleteContent('payment_channels_crypto', {{$u->id}})" type="button"

                        class="btn btn-outline-danger waves-effect waves-light">

                    <i class="far fa-trash-alt"></i>

                </button>
                @endif
            </td>

        </tr>

        <div class="modal fade duzenleCrypto{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">

            <div class="modal-dialog modal-xl">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title mt-0" id="myModalLabel">{{$u->title}} Düzenle</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X

                        </button>

                    </div>

                    <form id="duzenle" method="post" action="{{route('crypto_edit')}}" enctype="multipart/form-data">

                        @csrf

                        {!! getLangInput() !!}

                        <div class="modal-body">

                            <div class="row">

                                <div class="col-sm-6">

                                    <div class="form-group">

                                        <label for="1">Kripto Para Adı</label>

                                        <input name="title" type="text" class="form-control" id="1"

                                               placeholder="Kripto Para Adı" value="{{$u->title}}">

                                    </div>

                                </div>

                                <div class="col-sm-6">

                                    <div class="form-group">

                                        <label for="1">Kripto Para Açıklaması</label>

                                        <input name="text" type="text" class="form-control" id="1"

                                               placeholder="Kripto Para Açıklaması" value="{{$u->text}}">

                                    </div>

                                </div>

                                <div class="col-md-12">

                                    <div class="form-group">

                                        <label for="9">Kripto Para Resmi</label>

                                        <input data-default-file="{{asset('/public/front/crypto_logo/'.$u->image)}}" name="image" type="file" id="9" class="form-control"

                                               accept="image/*">

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

        <th>Kripto Para Adı</th>
        <th>Açıklama</th>

        <th>Logo</th>

        <th>Eklenme Tarihi</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </tfoot>

</table>



