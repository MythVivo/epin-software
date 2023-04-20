<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"

       style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

    <tr>

        <th>Oyun İsmi</th>

        <th>Kategori</th>

        <th>Link</th>

        <th>Başlıklar</th>

        <th>@lang('admin.eklenmeTarihi')</th>

        <th>@lang('admin.durum')</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </thead>

    <tbody>

    @foreach(\App\Models\Games::where('lang', getLang())->get() as $u)

        <?php

            if($u->image != "") {

                $image = explode(".", $u->image);

                $image = $image[0] . "@2x." . $image[1];

            } else {

                $image = $u->image;

            }



        ?>

        <tr>

            <td>{{$u->title}}</td>

            <td>{{categoryFind($u->category)}}</td>

            <td>

                <a href="{{route('oyun_baslik', [$u->link])}}" target="_blank">{{$u->link}}</a>

            </td>

            <td>

                @foreach(\App\Models\GamesTitles::where('game', $u->id)->get() as $uu)

                    {{$uu->title}} <br>

                @endforeach

            </td>

            <td>

                {{$u->created_at}}

            </td>

            <td id="statusText"><?=getDataStatus($u->status)?></td>

            <td>

                <button id="status" onclick="status({{$u->id}}, 'games', event)" type="button"

                        class="btn btn-lg @if($u->status == 0) btn-outline-warning @else btn-outline-success @endif waves-effect waves-light">

                    <i id="status-icon" class="mdi mdi-eye"></i>

                </button>

                <button onclick="editorStart({{$u->id}})" data-target=".duzenle{{$u->id}}" data-toggle="modal" type="button"

                        class="btn btn-lg btn-outline-primary waves-effect waves-light">

                    <i class="far fa-edit"></i>

                </button>

                <div class="modal fade duzenle{{$u->id}}" data-id="duzenle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">

                    <div class="modal-dialog modal-xl">

                        <div class="modal-content">

                            <div class="modal-header">

                                <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.oyunEkle')</h5>

                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X

                                </button>

                            </div>

                            <form id="duzenle{{$u->id}}" method="post" action="{{route('oyun_edit')}}"

                                  enctype="multipart/form-data" autocomplete="off">

                                @csrf

                                {!! getLangInput() !!}

                                <div class="modal-body">

                                    <div class="row">

                                        <div class="col-sm-6 col-md-4">

                                            <div class="form-group">

                                                <label for="1">@lang('admin.oyunBasligi')</label>

                                                <br>

                                                <input name="title" type="text" class="form-control" id="1"

                                                       value="{{$u->title}}"

                                                       placeholder="@lang('admin.oyunBasligi')">

                                            </div>

                                        </div>

                                        <div class="col-sm-4">

                                            <div class="form-group">

                                                <label for="2">@lang('admin.oyunKategori')</label><br>

                                                <select name="category" class="select2 form-control form-group">

                                                    @foreach(\App\Models\Category::whereNull('deleted_at')->get() as $uu)

                                                        <option value="{{$uu->id}}"

                                                                @if($u->category == $uu->id) selected @endif>{{$uu->title}}</option>

                                                    @endforeach

                                                </select>

                                            </div>

                                        </div>

                                        <div class="col-sm-6 col-md-4">

                                            <div class="form-group">

                                                <label for="sira">Oyun Sırası</label>

                                                <input name="sira" type="number" step="1" class="form-control" id="sira"

                                                       placeholder="Sıra" value="{{$u->sira}}">

                                            </div>

                                        </div>

                                        <div class="col-sm-12">

                                            <div class="form-group">

                                                <label for="3">@lang('admin.oyunMetni')</label>

                                                <textarea class="editorText{{$u->id}}" placeholder="@lang('admin.oyunMetni')"

                                                          id="3"

                                                          name="text">{!! $u->text !!}</textarea>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label for="4">@lang('admin.oyunResmi')</label>

                                                <input data-default-file="{{asset(env('ROOT').env('FRONT').env('GAMES').'/'.$u->image)}}"

                                                       name="image" type="file" id="4" class="dropify"

                                                       accept="image/*">

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>Oyun İkonu</label>

                                                <input data-default-file="{{asset(env('ROOT').env('FRONT').env('GAMES_ICON').'/'.$u->icon)}}"

                                                       name="icon" type="file" class="dropify"

                                                       accept="image/*">

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>Oyun İkonu 2</label>

                                                <input data-default-file="{{asset(env('ROOT').env('FRONT').env('GAMES_ICON').'/'.$u->icon_2)}}"

                                                       name="icon_2" type="file" class="dropify"

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

                <button onclick="location.href='{{route('oyun_detay', $u->link)}}'" type="button"

                        class="btn btn-lg btn-outline-info waves-effect waves-light"><i class="fas fa-info-circle"></i>

                </button>
                @if(userRoleIsAdmin(Auth::user()->id))
                <button onclick="deleteContent('games', {{$u->id}})" type="button"

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

        <th>Oyun İsmi</th>

        <th>Kategori</th>

        <th>Link</th>

        <th>Başlıklar</th>

        <th>@lang('admin.eklenmeTarihi')</th>

        <th>@lang('admin.durum')</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </tfoot>

</table>

