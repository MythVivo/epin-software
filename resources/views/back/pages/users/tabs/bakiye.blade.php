<div class="tab-pane fade" id="bakiye">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 offset-md-6 mb-3">
                            <button type="button" data-toggle="modal" data-target=".bakiyeEkle"
                                    class="btn btn-outline-success btn-block">
                                 Kul. Bakiye Ekle/Çıkar
                            </button>
                            <div class="modal fade bakiyeEkle" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title mt-0" id="myModalLabel">Bakiye Ekle-Çıkar / Bakiye : {{$user->bakiye}} TL / Ç.Bakiye : {{$user->bakiye_cekilebilir}} TL</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                X
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Tutar</label>
                                                    <input type="number" step="0.01" class="form-control" name="amount">
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label">Açıklama</label>
                                                    <input type="text" class="form-control" name="description">
                                                </div>
                                            Bakiye düşürmek için tutarı - (eksi) olarak giriniz. <br>Bakiye şayet yeterli değilse kalan tutar Çekilebilir bakiyeden düşülür.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-primary"
                                                    data-bs-dismiss="modal">Kapat
                                            </button>
                                            <button type="submit" class="btn btn-outline-success">Kaydet</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button type="button" data-toggle="modal" data-target=".bakiyeCek"
                                    class="btn btn-outline-success btn-block">
                                 Çek. Bakiye Ekle/Çıkar
                            </button>
                            <div class="modal fade bakiyeCek" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title mt-0" id="myModalLabel">Çekilebilir Bakiye Ekle/Çıkar</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                X
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Tutar</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                           name="amount1">
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label">Açıklama</label>
                                                    <input type="text" class="form-control" name="description1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-primary"
                                                    data-bs-dismiss="modal">Kapat
                                            </button>
                                            <button type="submit" class="btn btn-outline-success">Kaydet</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table lang="{{getLang()}}" id="datatable2" class="table table-bordered nowrap"
                                   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Ödeme Tutarı</th>
                                    <th>Kanal</th>
                                    <th>İşlemi Yapan</th>
                                    <th>Açıklama</th>
                                    <th>Durum</th>
                                    <th>İşlem Tarihi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(DB::table('odemeler')->where('user', $user->id)->orderBy('created_at', 'desc')->get() as $u)
                                    <tr>
                                        <td>{{$u->amount}}</td>
                                        <td>{{findPaymentChannel($u->channel)}}</td>
                                        <td>
                                            @if($u->islemYapan == 0)
                                                Kendisi
                                            @else
                                                {{DB::table('users')->where('id', $u->islemYapan)->first()->name}}
                                            @endif
                                        </td>
                                        <td>{{$u->description}}</td>
                                        <td>
                                            @if($u->status == 0)
                                                Onay Aşamasında
                                            @elseif($u->status == 1)
                                                Onaylandı
                                            @elseif($u->status == 2)
                                                Ödeme İşlemi İptal Edildi
                                            @else
                                                Bir Sorun Oluştu
                                            @endif
                                        </td>
                                        <td>{{$u->created_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Ödeme Tutarı</th>
                                    <th>Kanal</th>
                                    <th>İşlemi Yapan</th>
                                    <th>Açıklama</th>
                                    <th>Durum</th>
                                    <th>İşlem Tarihi</th>
                                </tr>
                                </tfoot>
                            </table>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
