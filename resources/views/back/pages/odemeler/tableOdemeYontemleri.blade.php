<table lang="{{getLang()}}" id="datatable2" class="table table-bordered nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Banka</th>
        <th>Açıklama</th>
        <th>Alıcı</th>
        <th>Iban</th>
        <th>Şube - Hesap No</th>
        <th>Havale - Atm Kesinti</th>
        <th>Aracı</th>
        <th>Logo</th>
        <th>Durum</th>
        <th>Eklenme Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    @foreach(DB::table('payment_channels_eft')->whereNull('deleted_at')->get() as $u)
        <tr>
            <td>{{$u->title}} - {{$u->bankSlug}}</td>
            <td>{{$u->text}}</td>
            <td>{{$u->alici}}</td>
            <td>{{$u->iban}}</td>
            <td>{{$u->sube}} - {{$u->hesap}}</td>
            <td>{{$u->havale_kesinti}} - {{$u->atm_kesinti}}</td>
            <td>
                @if($u->channel_type == 2)
                    Paytr
                @elseif($u->channel_type == 15)
                    Gpay
                @endif
            </td>
            <td class="text-center bg-white">
                <img style="max-width: 50%;" src="{{asset('/public/front/bank_logo/'.$u->image)}}">
            </td>
            <td>
                @if($u->status == 1)
                    <span class="text-success">Aktif</span>
                @else
                    <span class="text-danger">Pasif</span>
                @endif
            </td>
            <td>{{$u->created_at}}</td>
            <td>
                <?php
                if($u->status == 1) {
                    $class = "success";
                } else {
                    $class = "warning";
                }
                ?>
                <button onclick="location.href='?banka=1&bankaId={{$u->id}}'" type="button"
                        class="btn btn-outline-{{$class}} waves-effect waves-light">
                    <i class="far fa-eye"></i>
                </button>
                <button data-toggle="modal" data-target=".duzenle{{$u->id}}" type="button"
                        class="btn btn-outline-primary waves-effect waves-light">
                    <i class="far fa-edit"></i>
                </button>
                @if(userRoleIsAdmin(Auth::user()->id))
                    <button onclick="deleteContent('payment_channels_eft', {{$u->id}})" type="button"
                            class="btn btn-outline-danger waves-effect waves-light">
                        <i class="far fa-trash-alt"></i>
                    </button>
                @endif
            </td>
        </tr>
        <div class="modal fade duzenle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myModalLabel">{{$u->title}} Düzenle</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                        </button>
                    </div>
                    <form id="duzenle" method="post" action="{{route('odemeler_edit')}}" enctype="multipart/form-data">
                        @csrf
                        {!! getLangInput() !!}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label for="1">Banka Adı</label>
                                        <input name="title" type="text" class="form-control" id="1"
                                               placeholder="Banka Adı" value="{{$u->title}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label for="2">Alıcı</label>
                                        <input name="alici" type="text" class="form-control" id="2"
                                               placeholder="Alıcı" value="{{$u->alici}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label for="3">Iban</label>
                                        <input name="iban" type="text" class="form-control" id="3"
                                               placeholder="Başında TR ile yazınız" value={{$u->iban}}>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label for="4">Şube</label>
                                        <input name="sube" type="text" class="form-control" id="4"
                                               placeholder="Şube" value="{{$u->sube}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label for="5">Hesap No</label>
                                        <input name="hesap" type="text" class="form-control" id="5"
                                               placeholder="Hesap No" value="{{$u->hesap}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label for="6">Havale Kesinti</label>
                                        <input name="havale_kesinti" type="text" class="form-control" id="6"
                                               placeholder="Havale Kesinti" value="{{$u->havale_kesinti}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label for="7">Atm Kesinti</label>
                                        <input name="atm_kesinti" type="text" class="form-control" id="7"
                                               placeholder="Atm Kesinti" value="{{$u->atm_kesinti}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label for="8">Açıklama</label>
                                        <input name="text" type="text" class="form-control" id="8"
                                               placeholder="Açıklama" value="{{$u->text}}">
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label>Banka Slug Değeri</label>
                                        <input name="bankSlug" type="text" class="form-control"
                                               placeholder="Banka Slug" value="{{$u->bankSlug}}">
                                        <small>
                                            [Paytr İçin : isbank, akbank, denizbank, finansbank,
                                            halkbank, ptt, teb, vakifbank, yapikredi,
                                            ziraat | Gpay için api sayfasından bank id'sini alınız!]
                                        </small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Havale Aracısı</label>
                                        <select name="channel_type" class="form-control">
                                            <option value="2" @if($u->channel_type == 2) selected @endif >PayTR</option>
                                            <option value="15" @if($u->channel_type == 15) selected @endif >Gpay
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="9">Banka Resmi Açık Tema</label>
                                        <input data-default-file="{{asset('/public/front/bank_logo/'.$u->image)}}"
                                               name="image" type="file" id="9" class="form-control"
                                               accept="image/*">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="123">Banka Resmi Koyu Tema</label>
                                        <input data-default-file="{{asset('/public/front/bank_logo/'.$u->image_dark)}}"
                                               name="image_dark" type="file" id="123" class="form-control"
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
        <th>Banka</th>
        <th>Açıklama</th>
        <th>Alıcı</th>
        <th>Iban</th>
        <th>Şube - Hesap No</th>
        <th>Havale - Atm Kesinti</th>
        <th>Aracı</th>
        <th>Logo</th>
        <th>Durum</th>
        <th>Eklenme Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

