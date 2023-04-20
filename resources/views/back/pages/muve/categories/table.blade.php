<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>@lang('admin.kategoriBasligi')</th>
        <th>Bağlı Olduğu Kategori</th>
        <th>@lang('admin.kategoriResmi')</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    @foreach(DB::table('muve_games_categories')->whereNull('deleted_at')->get() as $u)
        <tr id="row-{{$u->id}}">
            <td>{{$u->title}}</td>
            <td>
                <?php $parent = DB::table('muve_games_categories')->where('id', $u->parent_id)->first(); ?>
                @if($parent)
                    {{$parent->title}}
                @else
                    @if($u->parent_id == 'null')
                        Genel Kategori
                    @else
                        {{$u->parent_id}}
                    @endif
                @endif
            </td>
            <td class="text-center w-25"><img class="w-30"
                                              src="{{asset(env('ROOT').env('FRONT').env('CATEGORIES').$u->image)}}">
            </td>
            <td>{{$u->created_at}}</td>
            <td>
                <button data-toggle="modal" data-target=".duzenle{{$u->id}}"
                        type="button"
                        class="btn btn-lg btn-outline-primary waves-effect waves-light">
                    <i class="far fa-edit"></i>
                </button>
                @if(userRoleIsAdmin(Auth::user()->id))
                    <button onclick="deleteContent('categories', {{$u->id}})" type="button"
                            class="btn btn-lg btn-outline-danger waves-effect waves-light">
                        <i class="far fa-trash-alt"></i>
                    </button>
                @endif
            </td>
        </tr>
        <div class="modal fade duzenle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myModalLabel">{{$u->title}} Kategori Düzenle</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                        </button>
                    </div>
                    <form id="yeniEkle" method="post" action="{{route('muve_kategori_edit')}}"
                          autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$u->id}}">
                        {!! getLangInput() !!}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="2">Muve Kategorisi</label>
                                        <select name="muveId" class="select2 form-group">
                                            <option selected value="{{$u->muve_id}}">{{$u->muve_id}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="2">Bağlı Olduğu Kategori</label>
                                        <select name="parent_id" class="form-control form-group">
                                            <option disabled selected>Bir Bağlı Kategori Seçin</option>
                                            <option value="null" @if($u->parent_id == 'null') selected @endif>Genel Kategori</option>
                                            @foreach(DB::table('muve_games_categories')->where('parent_id', null)->whereNull('deleted_at')->get() as $uu)
                                                <option value="{{$uu->id}}" @if($u->parent_id == $uu->id) selected @endif >{{$uu->id}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="4">Kategori İsmi</label>
                                        <input name="title" type="text" id="4" class="form-control" value="{{$u->title}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="file" name="fileToUpload" id="fileToUpload">

                                    <div class="form-group">
                                        <label for="4">@lang('admin.kategoriResmi')</label>
                                        <input name="image" type="file" class="dropify"
                                               accept="image/*" data-default-file="{{asset(env('ROOT').env('FRONT').env('CATEGORIES').$u->image)}}">
                                    </div>
                                </div>
                            </div>

                        </div>
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
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>@lang('admin.kategoriBasligi')</th>
        <th>Bağlı Olduğu Kategori</th>
        <th>@lang('admin.kategoriResmi')</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>
