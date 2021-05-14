@foreach(\App\Models\GamesPackages::where('games_titles', $u->id)->get() as $uu)
    <h5 class="altBaslik">
        {{$uu->title}} - ₺{{findGamesPackagesPrice($uu->id)}}
    </h5>
@endforeach
<div class="card-bottom">
    <button type="button" data-toggle="modal" data-target=".paketEkle" class="btn btn-block btn-outline-secondary">@lang('admin.paketEkle')</button>
    <button onclick="deleteContent('games_titles', {{$u->id}})" type="button"
            class="btn btn-block btn-outline-danger">Sil
    </button>
</div>
<div class="modal fade paketEkle" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.paketEkle')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                </button>
            </div>
            <form id="yeniEkle" method="post" action="{{route('oyun_paket_add')}}"
                  enctype="multipart/form-data">
                @csrf
                {!! getLangInput() !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="1">@lang('admin.paketBasligi')</label>
                                <input name="title" type="text" class="form-control" id="1"
                                       placeholder="@lang('admin.paketBasligi')">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="2">@lang('admin.paketFiyati')</label>
                                <input name="price" type="number" step="0.01" class="form-control" id="2"
                                       placeholder="@lang('admin.paketFiyati')">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="3">@lang('admin.paketIndirimTipi')</label>
                                <select id="3" name="discount_type" class="select2 form-group">
                                    <option value="0">@lang('admin.paketIndirimYok')</option>
                                    <option value="1">@lang('admin.paketIndirimYuzde')</option>
                                    <option value="2">@lang('admin.paketIndirimTutar')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="4">@lang('admin.paketIndirimTutari')</label>
                                <input name="discount_amount" type="number" step="0.01" class="form-control" id="4"
                                       placeholder="@lang('admin.paketIndirimTutari')">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="5">@lang('admin.paketMetni')</label>
                                <textarea class="editorText" placeholder="@lang('admin.paketMetni')" id="5"
                                          name="text"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="6">@lang('admin.paketResmi')</label>
                                <input name="image" type="file" id="6" class="dropify"
                                       accept="image/*">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary waves-effect"
                            data-dismiss="modal">@lang('admin.kapat')</button>
                    <input type="hidden" name="games_titles" value="{{$u->id}}">
                    <button type="submit"
                            class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
