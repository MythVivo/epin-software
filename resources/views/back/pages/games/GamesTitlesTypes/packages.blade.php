@foreach(\App\Models\GamesPackages::where('games_titles', $u->id)->get() as $uu)
    <div class="row">
        <div class="col-9 altBaslik float-left">
            {{$uu->title}} - ₺{{findGamesPackagesPrice($uu->id)}}
        </div>
        <div class="col-3 btn-group" role="group">
            <button type="button" data-toggle="modal" data-target=".kodGoruntule{{$uu->id}}"
                    class="btn btn-outline-orange btn-sm float-right"><i class="fa fa-eye"></i></button>
            <div class="modal fade kodGoruntule{{$uu->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.kodGoruntule')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                            </button>
                        </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="1">{{$uu->title}} - ₺{{findGamesPackagesPrice($uu->id)}}</label>
                                            <hr>
                                            @foreach(DB::table('games_packages_codes')->where('package_id', $uu->id)->get() as $uuu)
                                                {{$uuu->code}} - @if($uuu->is_used == 1) @lang('admin.kullanildi') @else @lang('admin.kullanilmadi') @endif
                                                <br>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary waves-effect"
                                        data-dismiss="modal">@lang('admin.kapat')</button>
                            </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->


            <button type="button" data-toggle="modal" data-target=".kodEkle{{$uu->id}}"
                    class="btn btn-outline-beanred btn-sm float-right"><i class="fa fa-plus"></i></button>
            <div class="modal fade kodEkle{{$uu->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.kodEkle')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                            </button>
                        </div>
                        <form method="post" action="{{route('oyun_paket_kod_add')}}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="1">{{$uu->title}} - ₺{{findGamesPackagesPrice($uu->id)}}</label>
                                            <textarea rows="7" name="code" type="text" class="form-control" id="1"></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary waves-effect"
                                        data-dismiss="modal">@lang('admin.kapat')</button>
                                <input type="hidden" name="games_titles_package" value="{{$uu->id}}">
                                <button type="submit"
                                        class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
        <div class="col-12">
            <hr>
        </div>

    </div>
@endforeach
<div class="card-bottom">
    <button type="button" data-toggle="modal" data-target=".paketEkle"
            class="btn btn-block btn-outline-secondary">@lang('admin.paketEkle')</button>
    <button onclick="deleteContent('games_titles', {{$u->id}})" type="button"
            class="btn btn-block btn-outline-danger">@lang('admin.baslikSil')
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
