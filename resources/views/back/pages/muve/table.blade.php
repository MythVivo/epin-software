<table lang="{{getLang()}}" id="datatable" class="font-12 nowrap table-bordered table-hover table-sm"

       style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

    <tr>
        <th style="text-align: center">SteamID</th>
        <th style="text-align: center">MuveID</th>
        <th style="text-align: center">Oyun İsmi</th>
        <th style="text-align: center">Alış</th>
        <th style="text-align: center">Satış</th>
        <th style="text-align: center">Link</th>
        <th>@lang('admin.eklenmeTarihi')</th>
{{--        <th>@lang('admin.durum')</th>--}}
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>

    <tbody>
    <?php
    $sorgu = DB::table('muve_games')->whereNull('deleted_at')->orderBy('created_at', 'desc')->get(); //paginate(50); //get();
    ?>
    @foreach($sorgu as $u)
        <tr>
            <td>{{$u->steamId}}</td>
            <td>{{$u->muveId}}</td>
            <td>{{$u->title}}</td>
            <?php
            $u->categories = explode("\n", $u->categories);
            if ($u->steamId == 0) {
                $u->image = asset('public/front/games/' . $u->image);
                $u->background = asset('public/front/games/' . $u->background);
            }
            ?>
            <td style="text-align: right">
{{--                @foreach($u->categories as $cat)--}}
{{--                    {{$cat}},--}}
{{--                @endforeach--}}
                {{number_format($u->alis,2)}}
            </td>
            <td style="text-align: right">{{$u->muvePrice  }}</td>
            <td>
                <a href="{{route('cd_key_detay', $u->link)}}" target="_blank">Oyunu Gör</a>
            </td>
            <td>{{$u->created_at}}</td>
{{--            <td id="statusText"><?=getDataStatus($u->status)?></td>--}}
            <td>

{{--                    <i id="status-icon"  onclick="status({{$u->id}}, 'muve_games', event)" class="btn btn-sm mdi mdi-eye"></i>--}}

                    <i onclick="editorStart({{$u->id}})" data-target=".duzenle{{$u->id}}" data-toggle="modal" type="button" class="far fa-edit btn btn-sm"></i>

                <div class="modal fade duzenle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0" id="myModalLabel">{{$u->title}} Düzenle</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                </button>
                            </div>
                            <form method="post" autocomplete="off" action="{{route('muve_oyun_edit')}}" enctype="multipart/form-data">
                                @csrf
                                {!! getLangInput() !!}
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Muve Id</label>
                                                <input name="muveId" type="text" class="form-control"
                                                       placeholder="Muve Id" value="{{$u->muveId}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label>@lang('admin.oyunBasligi')</label>
                                                <input name="title" type="text" class="form-control"
                                                       value="{{$u->title}}">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label>Steam ID</label>
                                                <input name="steamId" type="number" class="form-control"
                                                       placeholder="Steam Id" value="{{$u->steamId}}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>Desteklenen Diller</label>
                                                <textarea rows="3" name="desteklenenDiller"
                                                          class="form-control">{{$u->supLang}}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>Windows Gereksinimler</label>
                                                <textarea rows="3" name="windowsGer"
                                                          class="form-control">{{$u->winGer}}</textarea>

                                            </div>
                                            <div class="form-group">
                                                <label>Windows da var mı?</label>
                                                <input type="checkbox" name="windowsSup" value="1" @if($u->winSup == 1) checked @endif>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>Mac Gereksinimler</label>
                                                <textarea rows="3" name="macGer" class="form-control">{{$u->macGer}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Mac de var mı?</label>
                                                <input type="checkbox" name="macSup" value="1" @if($u->macSup == 1) checked @endif>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>Linux Gereksinimler</label>
                                                <textarea rows="3" name="linuxGer"
                                                          class="form-control">{{$u->linuxGer}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Linux da var mı?</label>
                                                <input type="checkbox" name="linuxSup" value="1" @if($u->linuxSup == 1) checked @endif>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label>Geliştiriciler (Her bir satıra bir tane)</label>
                                                <textarea rows="3" name="gelistiriciler"
                                                          class="form-control">{{$u->developers}}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label>Kategoriler (Her bir satıra bir tane)</label>
                                                <textarea rows="3" name="kategoriler"
                                                          class="form-control">@foreach($u->categories as $cat){{$cat}}@endforeach</textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label>Meta Critic Skoru</label>
                                                <input name="metaScor" type="text" class="form-control"
                                                       placeholder="Meta Critic Skoru" value="{{$u->metaScore}}">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label>Meta Critic Linki</label>
                                                <input name="metaLink" type="text" class="form-control"
                                                       placeholder="Meta Critic Linki" value="{{$u->metaLink}}">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label>Yayın Tarihi</label>
                                                <input name="yayinTarihi" type="text" class="form-control"
                                                       placeholder="Yayın Tarihi" value="{{$u->releaseDate}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label>Oyun Sırası</label>
                                                <input name="sira" type="number" step="1" class="form-control"
                                                       placeholder="Sıra" value="{{$u->sira}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Kısa Açıklama</label>
                                                <textarea class="form-control" rows="5" placeholder="Kısa Açıklama"
                                                          name="shortDesc">{{$u->shortDesc}}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>@lang('admin.oyunMetni')</label>
                                                <textarea class="editorText{{$u->id}}" placeholder="@lang('admin.oyunMetni')" name="text">{{$u->description}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('admin.oyunResmi')</label>
                                                <input name="image" type="file" class="dropify" data-default-file="{{$u->image}}"
                                                           accept="image/*">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Arkaplan Resmi</label>
                                                <input name="arkaplan" type="file" class="dropify" data-default-file="{{$u->background}}"
                                                           accept="image/*">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Resim Galeri</label>
                                                <textarea rows="3" name="galeri" class="form-control">{{$u->images}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Video Galeri (.webm / .mp4)</label>
                                                <textarea rows="3" name="galeriVideo"
                                                          class="form-control">{{$u->videos}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Fiyat / (Alış : {{number_format($u->alis,2)}})</label>
                                                <input onchange="fiyatHesapla(this)" name="fiyat" type="number" step="0.01" class="form-control fiyatBilgiler"
                                                       placeholder="Fiyat" value="{{$u->muvePrice}}">
                                            </div>
                                        </div>
                                        <?php
                                        $currencies = getMuveGamesCurrency();
                                        ?>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Para Birimi</label>
                                                <select onchange="fiyatHesapla(this)" class="form-control select2 fiyatBilgiler" name="muveCurrency">
                                                    @foreach($currencies as $currency)
                                                        <option value="{{$currency['code']}}" @if($u->muveCurrency == $currency['code']) selected @endif>{{$currency['ShortName']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>TRY Karşılığı</label>
                                                <input class="form-control" name="tryKarsiligi" value="{{currencyConverter($u->muvePrice, $u->muveCurrency, 'TRY')}}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <label>@lang('admin.paketIndirimTipi')</label>
                                                <select name="discount_type" class="select2 form-group">
                                                    <option value="0" @if($u->discount_type == 0) selected @endif>@lang('admin.paketIndirimYok')</option>
                                                    <option value="1" @if($u->discount_type == 1) selected @endif>@lang('admin.paketIndirimYuzde')</option>
                                                    <option value="2" @if($u->discount_type == 2) selected @endif>@lang('admin.paketIndirimTutar')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <label>@lang('admin.paketIndirimTutari')</label>
                                                <input name="discount_amount" type="number" step="0.01" class="form-control"
                                                       placeholder="@lang('admin.paketIndirimTutari')"
                                                       value="{{$u->discount_amount}}">
                                                <small>* İndirim tutarı seçmiş olduğunuz para birimine göre yapılır.</small>
                                            </div>
                                        </div>
                                        <?php
                                        if ($u->discount_date != NULL) {
                                            $date = \Carbon\Carbon::parse($u->discount_date);
                                            $date1 = $date->format('Y-m-d');
                                            $date2 = $date->format('H:i');
                                        } else {
                                            $date1 = "";
                                            $date2 = "";
                                        }
                                        ?>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <label>İndirim Son Tarihi</label>
                                                <input name="discount_date" type="date" class="form-control" placeholder="İndirim Son Tarihi" value="{{$date1}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <label>İndirim Son Saati</label>
                                                <input name="discount_date_time" type="time" class="form-control" placeholder="İndirim Son Saati" value="{{$date2}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="id" value="{{$u->id}}">
                                    <button type="button" class="btn btn-outline-secondary waves-effect" data-dismiss="modal">@lang('admin.kapat')</button>
                                    <button type="submit" class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                </div>
                            </form>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            @if(userRoleIsAdmin(Auth::user()->id))

                        <i onclick="deleteContent('muve_games', {{$u->id}})" type="button" class="btn btn-sm far fa-trash-alt"></i>

                @endif
            </td>
        </tr>
    @endforeach

    </tbody>
    <tfoot>
    <tr>
        <th>StreamID</th>
        <th>MuveID</th>
        <th>Oyun İsmi</th>
        <th>Alış</th>
        <th>Satış</th>
        <th>Link</th>
        <th>@lang('admin.eklenmeTarihi')</th>
{{--        <th>@lang('admin.durum')</th>--}}
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>
