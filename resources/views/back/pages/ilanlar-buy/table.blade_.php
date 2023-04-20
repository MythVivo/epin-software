<table lang="{{getLang()}}" id="datatable" class="font-12 nowrap table-hover table-sm table-bordered"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Id</th>
        <th>Pazar</th>
        <th>Alıcı</th>
        <th>Satıcı</th>
        <th>Fiyat</th>
        <th>Kazanç</th>
        <th>Başlık</th>
        <th>Nickname</th>
        <th>Durumu</th>
        <th>İşlem Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    @foreach(DB::table('pazar_yeri_ilanlar_buy')->whereNull('deleted_at')->get() as $u)
        <tr>
            <td>{{$u->id}}</td>
            <td>{{DB::table('games_titles')->where('id', $u->pazar)->first()->title}} - Satış</td>
            <td>
                <?php $uye = DB::table('users')->where('id', $u->user)->first(); ?>
                <a href="{{route('uye_detay', [$uye->email])}}" target="_blank">
                    {{$uye->name}}
                </a>
            </td>
            <td>
                @if(DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $u->id)->count() > 0)
                    <?php
                    $satis = DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $u->id)->first(); ?>
                    <?php $uye2 = DB::table('users')->where('id', $satis->satin_alan)->first(); ?>
                    <a href="{{route('uye_detay', [$uye2->email])}}" target="_blank">
                        {{$uye2->name}}
                    </a>
                @else
                    Henüz Satıcı Yok
                @endif
            </td>
            <td align="center"><?=number_format($u->price,2,'.','')?></td>
            <td align="center"><? $cost=$u->price - $u->moment_komisyon; echo number_format($cost,2,'.','')?></td>
            <?php $item = DB::table('games_titles')->where('id', $u->pazar)->first(); ?>
            <td><a href="{{route('item_buy_ic_detay', [$item->link, $u->sunucu, Str::slug($u->title).'-'.$u->id])}}"
                   target="_blank">{{substr($u->title, 0, 20)}}</a></td>
            <td>
                @if(DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $u->id)->count() > 0)
                    {{DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $u->id)->first()->note}}
                @else
                    Henüz Satılmamış
                @endif
            </td>
            <td>
                {{findIlanStatus($u->status)}}
            </td>
            <td>
                @if(DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $u->id)->count() > 0)
                    {{DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $u->id)->first()->created_at}}
                @else
                    {{$u->created_at}}
                @endif

            </td>
            <td align="center">
                
                        
                    <i data-toggle="modal" data-target=".goruntule{{$u->id}}" class="btn fas fa-eye"></i>
                
                <div class="modal fade goruntule{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0" id="myModalLabel">{{$u->title}} İlanı Görüntüleniyor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Pazar Yeri
                                                    : {{DB::table('games_titles')->where('id', $u->pazar)->first()->title}}</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Alıcı
                                                    :
                                                    <?php $uye = DB::table('users')->where('id', $u->user)->first(); ?>
                                                    <a href="{{route('uye_detay', [$uye->email])}}" target="_blank">
                                                        {{$uye->name}}
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Satıcı
                                                    :
                                                    @if(DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $u->id)->count() > 0)
                                                        <?php
                                                        $satis = DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $u->id)->first(); ?>
                                                        <?php $uye2 = DB::table('users')->where('id', $satis->satin_alan)->first(); ?>
                                                        <a href="{{route('uye_detay', [$uye2->email])}}"
                                                           target="_blank">
                                                            {{$uye2->name}}
                                                        </a>
                                                        {{DB::table('users')->where('id', $satis->satin_alan)->first()->name}}
                                                    @else
                                                        Henüz Satıcı Yok
                                                    @endif
                                                </label>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Fiyat : {{$u->price}} TL</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Satıcının Kazanacağı
                                                    : {{$u->price - $u->moment_komisyon}}
                                                    TL</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Başlık : {{$u->title}}</label>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">İlan Durumu
                                                    : {{findIlanStatus($u->status)}}</label>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">İlan Oluşturulma Tarihi
                                                    : {{$u->created_at}}</label>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Açıklama : {{$u->text}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">İlan İşlemleri</h4>
                                                <div class="row">
                                                    @if($u->status == 0 or $u->status == 1 or $u->status == 3)
                                                        <div class="col">
                                                            @if($u->status == 0)
                                                                <button onclick="location.href='{{route('ilanlar_yonetim_buy_onay', [$u->id, '1'])}}'"
                                                                        class="btn btn-outline-primary w-100">İlanı
                                                                    Yayınla
                                                                </button>
                                                            @endif
                                                            @if($u->status == 1)
                                                                <button onclick="location.href='{{route('ilanlar_yonetim_buy_onay', [$u->id, '0'])}}'"
                                                                        class="btn btn-outline-primary w-100">İlanı
                                                                    Yayından
                                                                    Kaldır
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    @if($u->status == 1 or $u->status == 0)
                                                        <div class="col-md-4">
                                                            <form method="post"
                                                                  action="{{route('ilanlar_yonetim_buy_onay_red')}}">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input type="text" class="form-control"
                                                                               name="red_neden" placeholder="Red Nedeni"
                                                                               required>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input type="hidden" name="id"
                                                                               value="{{$u->id}}">
                                                                        <input type="hidden" name="durum" value="2">
                                                                        <button class="btn btn-outline-primary w-100">
                                                                            İlan
                                                                            Yayınını Reddet
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    @endif
                                                    @if($u->status == 4 or $u->status == 5)
                                                        <div class="col">
                                                            @if($u->status == 4)
                                                                <button onclick="location.href='{{route('ilanlar_yonetim_buy_onay', [$u->id, '5'])}}'"
                                                                        class="btn btn-outline-success w-100">Site itemi
                                                                    aldı
                                                                    olarak işaretle
                                                                </button>
                                                            @endif
                                                            @if($u->status == 5)
                                                                @if($u->aliciPara == 0)
                                                                    <h4>Üye Bakiyesi : ₺{{$uye->bakiye}}</h4>
                                                                    @if($uye->bakiye < ($u->price - $u->moment_komisyon))
                                                                        <h4 class="text-danger">Yüklemesi Gereken Bakiye
                                                                            :
                                                                            ₺{{$uye->bakiye - ($u->price - $u->moment_komisyon)}}</h4>
                                                                    @endif
                                                                    <h4 class="text-danger">Henüz Alıcıdan para tahsil
                                                                        edilmedi, öncelikle alıcıdan parayı tahsil
                                                                        ediniz.</h4>
                                                                    <button onclick="location.href='{{route('ilanlar_yonetim_buy_onay', [$u->id, '66'])}}'"
                                                                            class="btn btn-outline-success w-100"
                                                                            @if($uye->bakiye < ($u->price - $u->moment_komisyon)) disabled
                                                                            style="cursor:not-allowed;" @endif>Alıcıdan
                                                                        Parayı Çek
                                                                    </button>
                                                                @else
                                                                        <h4 class="text-blue">Alıcıdan para tahsil edildi, henüz satıcıya parası gönderilmedi.</h4>
                                                                    <button onclick="location.href='{{route('ilanlar_yonetim_buy_onay', [$u->id, '6'])}}'"
                                                                            class="btn btn-outline-success w-100 mb-3">Satış
                                                                        Başarılı
                                                                        Olarak İşaretle Ve Parayı Satıcıya Gönder
                                                                    </button>
                                                                @endif
                                                                <button onclick="location.href='{{route('ilanlar_yonetim_buy_onay', [$u->id, '99'])}}'"
                                                                        class="btn btn-outline-danger w-100">
                                                                    Alıcı Para Yüklemedi Olarak İşaretle (Olumsuz)
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    @if($u->status < 3)
                                                        <div class="col">
                                                            <button onclick="location.href='{{route('ilanlar_yonetim_buy_onay', [$u->id, '3'])}}'"
                                                                    class="btn btn-outline-primary w-100">İlanı Pasif
                                                                Hale
                                                                Getir
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>


                                                @if(DB::table('pazar_yeri_ilan_satis_buy')->where('ilan', $u->id)->count() > 0)
                                                    <div class="col-12">
                                                        <hr>
                                                        <br>
                                                        <h4 class="text-warning">Uyarı! Aşağıdaki işlemler sadece özel
                                                            durumlarda kullanılmalıdır. Yukarıdaki butonlar ile sistemin
                                                            normal para akış transferi gerçekleşmektedir!</h4>
                                                        <h1 class="card-title">
                                                            @if($u->money_status == 0)
                                                                <span class="text-danger">Satıcı Henüz Ödemesini Almamış</span>
                                                            @else
                                                                <span class="text-success">Satıcı Ödemesini Almış</span>
                                                            @endif
                                                        </h1>
                                                    </div>

                                                    @if($u->money_status == 0)
                                                        <button onclick="location.href='{{route('ilanlar_yonetim_buy_onay_ozel', [$u->id, '1'])}}'"
                                                                class="btn btn-outline-success">Satıcının Parasını
                                                            Gönder
                                                            ({{$u->price - $u->moment_komisyon}}TL)
                                                        </button>
                                                    @else
                                                        <button onclick="location.href='{{route('ilanlar_yonetim_buy_onay_ozel', [$u->id, '0'])}}'"
                                                                class="btn btn-outline-danger">Satıcıdan Parayı Geri Al
                                                            ({{$u->price}}TL)
                                                        </button>
                                                    @endif
                                                    <?php /*
                                                    <button onclick="location.href='{{route('ilanlar_yonetim_buy_onay_ozel', [$u->id, '3'])}}'"
                                                            class="btn btn-outline-warning">Alıcıya Parasını Geri Gönder
                                                        ({{$u->price}}TL)
                                                    </button> */ ?>
                                                @endif
                                            </div>
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
                @if(userRoleIsAdmin(Auth::user()->id))                                       
                        <i onclick="deleteContent('pazar_yeri_ilanlar_buy', {{$u->id}})" class="btn far fa-trash-alt"></i>
                    
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Id</th>
        <th>Pazar</th>
        <th>Alıcı</th>
        <th>Satıcı</th>
        <th>Fiyat</th>
        <th>Kazanç</th>
        <th>Başlık</th>
        <th>Nickname</th>
        <th>Durumu</th>
        <th>İşlem Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

