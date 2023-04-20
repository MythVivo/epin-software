<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"

       style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

    <tr>

        <th>Menü İsmi</th>

        <th>Alt Kategori</th>

        <th>Link</th>

        <th>Resim</th>

        <th>@lang('admin.eklenmeTarihi')</th>

        <th>@lang('admin.durum')</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </thead>

    <tbody>

    @foreach(DB::table('header_menu')->whereNull('deleted_at')->get() as $u)

        <tr>

            <td>{{$u->title}}</td>

            <td>

                @if($u->sub_menu != 0)

                    @if(DB::table('header_menu')->where('id', $u->sub_menu)->first()->sub_menu != 0)

                        <?php $findSecondMenu = DB::table('header_menu')->where('id', $u->sub_menu)->first(); ?>

                        Genel Menü

                        > {{DB::table('header_menu')->where('id', $findSecondMenu->sub_menu)->first()->title}}

                        > {{DB::table('header_menu')->where('id', $u->sub_menu)->first()->title}}

                    @else

                        Genel Menü > {{DB::table('header_menu')->where('id', $u->sub_menu)->first()->title}}

                    @endif

                @else

                    Genel Menü

                @endif

            </td>

            <td>

                <a href="{{env("APP_URL").$u->link}}">{{$u->link}}</a>

            </td>

            <td class="text-center">

                @if($u->image != '')

                    <img class="w-25" src="{{asset('public/front/mega_menu/'.$u->image)}}">

                @else

                    <span class="text-danger">Resim Yok</span>

                @endif

            </td>

            <td>

                {{$u->created_at}}

            </td>

            <td id="statusText"><?=getDataStatus($u->status)?></td>

            <td>

                <button id="status" onclick="status({{$u->id}}, 'header_menu', event)" type="button"

                        class="btn btn-lg @if($u->status == 0) btn-outline-warning @else btn-outline-success @endif waves-effect waves-light">

                    <i id="status-icon" class="mdi mdi-eye"></i>

                </button>

                <button data-toggle="modal" data-target=".duzenle{{$u->id}}" type="button"

                        class="btn btn-lg btn-outline-primary waves-effect waves-light">

                    <i class="far fa-edit"></i>

                </button>

                <div class="modal fade duzenle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">

                    <div class="modal-dialog modal-xl">

                        <div class="modal-content">

                            <div class="modal-header">

                                <h5 class="modal-title mt-0" id="myModalLabel">{{$u->title}} Düzenle</h5>

                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X

                                </button>

                            </div>

                            <form id="yeniEkle" method="post" action="{{route('header_menu_edit')}}"

                                  enctype="multipart/form-data">

                                @csrf

                                {!! getLangInput() !!}

                                <div class="modal-body">

                                    <div class="row">

                                        <div class="col-sm-4">

                                            <div class="form-group">

                                                <label for="1">Mega Menü Başlığı</label>

                                                <br>

                                                <input name="title" type="text" class="form-control" id="1"

                                                       placeholder="Mega Menü Başlığı" value="{{$u->title}}">

                                            </div>

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-group">

                                                <label for="2">Alt Başlık</label>

                                                <select id="2" class="form-control" name="sub_menu">

                                                    <option value="0">Genel Menü</option>

                                                    @foreach(DB::table('header_menu')->orderBy('sub_menu', 'asc')->whereNull('deleted_at')->get() as $uu)

                                                        @if($uu->sub_menu == 0)

                                                            <option value="{{$uu->id}}"

                                                                    @if($u->sub_menu == $uu->id) selected @endif>{{$uu->title}}</option>

                                                        @else



                                                            @if(DB::table('header_menu')->where('id', $uu->sub_menu)->first()->sub_menu != 0)

                                                                <option value="{{$uu->id}}"

                                                                        @if($u->sub_menu == $uu->id) selected @endif>

                                                                    >> {{$uu->title}}</option>

                                                            @else

                                                                <option value="{{$uu->id}}"

                                                                        @if($u->sub_menu == $uu->id) selected @endif>

                                                                    > {{$uu->title}}</option>

                                                            @endif

                                                        @endif

                                                    @endforeach

                                                </select>

                                            </div>

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-group">

                                                <label for="4">Mega Menü Linki</label>

                                                <br>

                                                <input name="link" type="text" class="form-control" id="4"

                                                       placeholder="Mega Menü Linki" value="{{$u->link}}">

                                            </div>

                                        </div>



                                        <div class="col-md-12">

                                            <div class="form-group">

                                                <label for="3">Mega Menü Resmi</label>

                                                <input data-default-file="{{asset('public/front/mega_menu/'.$u->image)}}"

                                                       name="image" type="file" id="3" class="dropify"

                                                       accept="image/*">

                                            </div>

                                        </div>



                                    </div>



                                </div>

                                <div class="modal-footer">

                                    <button type="button" class="btn btn-outline-danger waves-effect"

                                            onclick="location.href='?sil=1&id={{$u->id}}'">Resmi Sil

                                    </button>

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


                @if(userRoleIsAdmin(Auth::user()->id))
                <button onclick="deleteContent('header_menu', {{$u->id}})" type="button"

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

        <th>Menü İsmi</th>

        <th>Alt Kategori</th>

        <th>Link</th>

        <th>Resim</th>

        <th>@lang('admin.eklenmeTarihi')</th>

        <th>@lang('admin.durum')</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </tfoot>

</table>



