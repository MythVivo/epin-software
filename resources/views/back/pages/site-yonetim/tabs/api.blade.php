<div class="tab-pane fade" id="api">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Epin Auth Name</label>
                                <input name="epinAuthName" type="text" class="form-control" value="{{$u->epinAuthName}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Epin Api Name</label>
                                <input name="epinApiName" type="text" class="form-control" value="{{$u->epinApiName}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Epin Api Key</label>
                                <input name="epinApiKey" type="password" class="form-control" value="{{$u->epinApiKey}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Epin Base Url</label>
                                <input name="EPIN_BASE" type="text" class="form-control" value="{{env('EPIN_BASE')}}">
                            </div>
                        </div>


                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Paytr Api Merhanct Id</label>
                                <input name="paytrMerchantId" type="text" class="form-control" value="{{$u->paytrMerchantId}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Paytr Api Merhanct Key</label>
                                <input name="paytrMerchantKey" type="text" class="form-control" value="{{$u->paytrMerchantKey}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Paytr Api Merhanct Salt</label>
                                <input name="paytrMerchantSalt" type="password" class="form-control" value="{{$u->paytrMerchantSalt}}">
                            </div>
                        </div>


                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Twitch Id</label>
                                <input name="TWITCH_ID" type="text" class="form-control" value="{{env('TWITCH_ID')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Twitch Yönlendirme URL'si</label>
                                <input name="TWITCH_REDIRECT_URI" type="text" class="form-control" value="{{env('TWITCH_REDIRECT_URI')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Twitch Secret</label>
                                <input name="TWITCH_SECRET" type="password" class="form-control" value="{{env('TWITCH_SECRET')}}">
                            </div>
                        </div>


                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Streamlabs Id</label>
                                <input name="STREAM_ID" type="text" class="form-control" value="{{env('STREAM_ID')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Streamlabs Yönlendirme URL'si</label>
                                <input name="STREAM_URL" type="text" class="form-control" value="{{env('STREAM_URL')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Streamlabs Secret</label>
                                <input name="STREAM_SECRET" type="password" class="form-control" value="{{env('STREAM_SECRET')}}">
                            </div>
                        </div>


                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Google Client Id</label>
                                <input name="GOOGLE_CLIENT_ID" type="text" class="form-control" value="{{env('GOOGLE_CLIENT_ID')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Google Yönlendirme URL'si</label>
                                <input name="GOOGLE_REDIRECT_URI" type="text" class="form-control" value="{{env('GOOGLE_REDIRECT_URI')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Google Client Secret</label>
                                <input name="GOOGLE_CLIENT_SECRET" type="password" class="form-control" value="{{env('GOOGLE_CLIENT_SECRET')}}">
                            </div>
                        </div>


                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Steam Client Secret</label>
                                <input name="STEAM_CLIENT_SECRET" type="password" class="form-control" value="{{env('STEAM_CLIENT_SECRET')}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Steam Yönlendirme URL'si</label>
                                <input name="STEAM_REDIRECT_URI" type="text" class="form-control" value="{{env('STEAM_REDIRECT_URI')}}">
                            </div>
                        </div>


                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Param Client Code</label>
                                <input name="PARAM_CLIENT_CODE" type="text" class="form-control" value="{{env('PARAM_CLIENT_CODE')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Param Client Username</label>
                                <input name="PARAM_CLIENT_USERNAME" type="text" class="form-control" value="{{env('PARAM_CLIENT_USERNAME')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Param Client Password</label>
                                <input name="PARAM_CLIENT_PASSWORD" type="password" class="form-control" value="{{env('PARAM_CLIENT_PASSWORD')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Param GUID</label>
                                <input name="PARAM_GUID" type="password" class="form-control" value="{{env('PARAM_GUID')}}">
                            </div>
                        </div>


                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>BILNEX Token</label>
                                <input name="BILNEX_TOKEN" type="text" class="form-control" value="{{env('BILNEX_TOKEN')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>BILNEX VKN</label>
                                <input name="BILNEX_TEDARIKCI" type="text" class="form-control" value="{{env('BILNEX_TEDARIKCI')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>BILNEX Branch</label>
                                <input name="BILNEX_BRANCH" type="text" class="form-control" value="{{env('BILNEX_BRANCH')}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Papara Api Key</label>
                                <input name="paparaKey" type="text" class="form-control" value="{{$u->paparaKey}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Papara Api Secret Key</label>
                                <input name="paparaSecretKey" type="password" class="form-control" value="{{$u->paparaSecretKey}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Gpay Username</label>
                                <input name="gpayUsername" type="text" class="form-control" value="{{$u->gpayUsername}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Gpay Api Key</label>
                                <input name="gpayKey" type="password" class="form-control" value="{{$u->gpayKey}}">
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

