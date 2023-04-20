<div class="tab-pane fade" id="finans">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Pazar Komisyonu %</label>
                                <input name="pazar_komisyon" type="number" step="0.01" class="form-control" value="{{$u->pazar_komisyon}}">
                            </div>
                        </div>

                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Pazar Alış Komisyonu %</label>
                                <input name="pazar_komisyon_alis" type="number" step="0.01" class="form-control" value="{{$u->pazar_komisyon_alis}}">
                            </div>
                        </div>

                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Para Çekim Komisyonu (Tutar Cinsinden)</label>
                                <input name="yayin_komisyon" type="number" step="0.01" class="form-control" value="{{$u->yayin_komisyon}}">
                            </div>
                        </div>

                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Yayıncı Min Bağış Çevirme (Tutar Cinsinden)</label>
                                <input name="yayin_min" type="number" step="0.01" class="form-control" value="{{$u->yayin_min}}">
                            </div>
                        </div>

                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>PayTR Yurtiçi Online Kom. Or. %</label>
                                <input name="onlineOdemeKomisyon" type="number" step="0.01" class="form-control" value="{{$u->onlineOdemeKomisyon}}">
                            </div>
                        </div>

                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>PayTR Yurtdışı Online Kom. Or. %</label>
                                <input name="onlineOdemeYurtdisiKomisyon" type="number" step="0.01" class="form-control" value="{{$u->onlineOdemeYurtdisiKomisyon}}">
                            </div>
                        </div>

                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Gpay Yurtiçi Online Kom. Or. %</label>
                                <input name="gpayKomisyon" type="number" step="0.01" class="form-control" value="{{$u->gpayKomisyon}}">
                            </div>
                        </div>

                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Gpay Yurtdışı Online Kom. Or. %</label>
                                <input name="gpayYurtdisiKomisyon" type="number" step="0.01" class="form-control" value="{{$u->gpayYurtdisiKomisyon}}">
                            </div>
                        </div>

                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Papara Kom. Or. %</label>
                                <input name="paparaKomisyon" type="number" step="0.01" class="form-control" value="{{$u->paparaKomisyon}}">
                            </div>
                        </div>

                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Gpay İninal Kom. Or. %</label>
                                <input name="ininalKomisyon" type="number" step="0.01" class="form-control" value="{{$u->ininalKomisyon}}">
                            </div>
                        </div>

                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Gpay Bkm Kom. Or. %</label>
                                <input name="bkmKomisyon" type="number" step="0.01" class="form-control" value="{{$u->bkmKomisyon}}">
                            </div>
                        </div>

                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Ozan Kom. Or. %</label>
                                <input name="ozanKomisyon" type="number" step="0.01" class="form-control" value="{{$u->ozanKomisyon}}">
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-3 border border-success">
                            <div class="form-group">
                                <label>Razer TL Kom. Or. %</label>
                                <input name="razertl" type="number" step="0.01" class="form-control" value="{{$u->razertl}}">
                            </div>
                        </div><div class="col-sm-3 col-md-3 border border-success">
                            <div class="form-group">
                                <label>Razer USD Kom. Or. %</label>
                                <input name="razerusd" type="number" step="0.01" class="form-control" value="{{$u->razerusd}}">
                            </div>

                        </div><div class="col-sm-3 col-md-3 border border-success">
                            <div class="form-group">
                                <label>Razer Custom Name</label>
                                <input name="razercstn" type="text" class="form-control" value="{{$u->razercstn}}">
                            </div>
                        </div><div class="col-sm-3 col-md-3 border border-success">
                            <div class="form-group">
                                <label>Razer Custom Kom. Or. %</label>
                                <input name="razercsto" type="text" class="form-control" value="{{$u->razercsto}}">
                            </div>
                        </div><div class="col-sm-3 col-md-3 border border-success">
                            <div class="form-group">
                                <label>Razer USD KUR</label>
                                <input name="razerusdk" type="number" class="form-control" value="{{$u->razerusdk}}">
                            </div>
                        </div><div class="col-sm-3 col-md-3 border border-success">
                            <div class="form-group">
                                <label>Razer Custom KUR</label>
                                <input name="razercstk" type="number" class="form-control" value="{{$u->razercstk}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


