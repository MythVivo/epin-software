<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"

       style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

    <tr>

        <th>Başlık</th>

        <th>Açıklama</th>

        <th>İzin Verilen Sayfalar</th>

        <th>@lang('admin.eklenmeTarihi')</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </thead>

    <tbody>

    @foreach(DB::table('user_group')->whereNull('deleted_at')->get() as $u)

        <tr>

            <td>{{$u->title}}</td>

            <td>{{$u->text}}</td>

            <td>

                @foreach(DB::table('user_group_pages')->where('user_group', $u->id)->get() as $a)

                    @if($a->page == 0)

                        Kod Görüntüleme İzni

                    @else

                        {{DB::table('pages')->where('id', $a->page)->first()->title}}@if(!$loop->last) <br> @endif

                    @endif

                @endforeach

            </td>

            <td>

                {{$u->created_at}}

            </td>

            <td>

                <button data-toggle="modal" data-target=".duzenle{{$u->id}}" type="button"

                        class="btn btn-lg btn-outline-primary waves-effect waves-light">

                    <i class="far fa-edit"></i>

                </button>
                @if(userRoleIsAdmin(Auth::user()->id))
                <button onclick="deleteContent('user_group', {{$u->id}})" type="button"

                        class="btn btn-lg btn-outline-danger waves-effect waves-light">

                    <i class="far fa-trash-alt"></i>

                </button>
                @endif
            </td>



            <div class="modal fade duzenle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">

                <div class="modal-dialog modal-xl">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.sssDuzenle')</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X

                            </button>

                        </div>

                        <form id="yeniEkle" method="post" action="{{route('kullanici_gruplari_edit')}}"

                              enctype="multipart/form-data">

                            @csrf

                            {!! getLangInput() !!}

                            <div class="modal-body">

                                <div class="row">

                                    <div class="col-sm-6">

                                        <div class="form-group">

                                            <label for="1">Kullanıcı Grubu İsmi</label>

                                            <input name="title" type="text" class="form-control" id="1"

                                                   placeholder="Kullanıcı Grubu İsmi" required value="{{$u->title}}">

                                        </div>

                                    </div>

                                    <div class="col-sm-6">

                                        <div class="form-group">

                                            <label for="2">Kullanıcı Grubu Açıklaması</label>

                                            <input name="text" type="text" class="form-control" id="2"

                                                   placeholder="Kullanıcı Grubu Açıklaması" value="{{$u->text}}">

                                        </div>

                                    </div>



                                    <div class="col-md-12">

                                        <div class="form-group">

                                            <label for="3">Erişilebilecek Sayfalar</label>

                                            <select class="select2 form-control" required multiple name="pages[]">

                                                <option value="0"

                                                        @if(DB::table('user_group_pages')->where('page', '0')->where('user_group', $u->id)->count() > 0) selected @endif>

                                                    Kod Görüntüleme İzni

                                                </option>

                                                @foreach(DB::table('pages')->where('lang', 'tr')->where('url', 'like', 'panel%')->get() as $uu)

                                                    <option value="{{$uu->id}}"

                                                            @if(DB::table('user_group_pages')->where('page', $uu->id)->where('user_group', $u->id)->count() > 0) selected @endif>{{$uu->title}}</option>

                                                @endforeach

                                            </select>

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





        </tr>

    @endforeach

    </tbody>

    <tfoot>

    <tr>

        <th>Başlık</th>

        <th>Açıklama</th>

        <th>İzin Verilen Sayfalar</th>

        <th>@lang('admin.eklenmeTarihi')</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </tfoot>

</table>



