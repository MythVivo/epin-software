<div class="tab-pane fade" id="iletisim">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Telefon 1</label>
                                <input name="tel_1" type="text" class="form-control" value="{{$u->tel_1}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Telefon 2</label>
                                <input name="tel_2" type="text" class="form-control" value="{{$u->tel_2}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Email 1</label>
                                <input name="email_1" type="text" class="form-control" value="{{$u->email_1}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Email 2</label>
                                <input name="email_2" type="text" class="form-control" value="{{$u->email_2}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Adres</label>
                                <textarea class="form-control" name="address">{{$u->address}}</textarea>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Adres Iframe</label>
                                <textarea class="form-control" name="address_iframe">{{$u->address_iframe}}</textarea>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

