<div class="tab-pane fade" id="sms">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Sms Kullanıcı Adı</label>
                                <input name="smsUsername" type="text" class="form-control" value="{{$u->smsUsername}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Sms Kullanıcı Şifresi</label>
                                <input name="smsUserpass" type="password" class="form-control" value="{{$u->smsUserpass}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Sms Gönderim Başlığı</label>
                                <input name="smsSendTitle" type="text" class="form-control" value="{{$u->smsSendTitle}}">
                                <small class="text-danger">Başlığı kopyala yapıştır yaparak yazınız.</small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

