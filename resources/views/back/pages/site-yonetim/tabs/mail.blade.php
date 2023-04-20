<div class="tab-pane fade" id="mail">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Mail Servisi</label>
                                <input name="MAIL_MAILER" type="text" class="form-control" value="{{env('MAIL_MAILER')}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Mail Host</label>
                                <input name="MAIL_HOST" type="text" class="form-control" value="{{env('MAIL_HOST')}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Mail Port</label>
                                <input name="MAIL_PORT" type="text" class="form-control" value="{{env('MAIL_PORT')}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Mail Kullanıcı Adı</label>
                                <input name="MAIL_USERNAME" type="text" class="form-control" value="{{env('MAIL_USERNAME')}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Mail Password</label>
                                <input name="MAIL_PASSWORD" type="password" class="form-control" value="{{env('MAIL_PASSWORD')}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Mail Şifreleme</label>
                                <input name="MAIL_ENCRYPTION" type="text" class="form-control" value="{{env('MAIL_ENCRYPTION')}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Mail Gönderim Adresi</label>
                                <input name="MAIL_FROM_ADDRESS" type="text" class="form-control" value="{{env('MAIL_FROM_ADDRESS')}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Mail Destek</label>
                                <input name="CONTATC_MAIL" type="text" class="form-control" value="{{env('CONTATC_MAIL')}}">
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
