<div class="tab-pane fade" id="guvenlik">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="custom-control custom-switch">
                                <?php
                                $name = "2fa_sms";
                                ?>
                                <input name="2fa_sms" id="a" class="custom-control-input" type="checkbox"
                                       @if($user->$name == 1) checked @endif>
                                <label for="a" class="custom-control-label">SMS İle Giriş</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="custom-control custom-switch">
                                <?php
                                $name = "2fa_email";
                                ?>
                                <input name="2fa_email" id="10" class="custom-control-input" type="checkbox"
                                       @if($user->$name == 1) checked @endif>
                                <label for="10" class="custom-control-label">Email İle Giriş</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="custom-control custom-switch">
                                <?php
                                $name = "2fa_google";
                                ?>
                                <input name="2fa_google" id="100" class="custom-control-input" type="checkbox"
                                       @if($user->$name == 1) checked @endif>
                                <label for="100" class="custom-control-label">Google İle Giriş</label>
                            </div>
                        </div>

                        <div class="col-md-6 mt-5">
                            <div class="form-group">
                                <label for="uye-grup">Kullanıcı Grubu</label>
                                <select class="select2 form-control" name="kullanici_grubu">
                                    <option value="0">Üye</option>
                                    @foreach(DB::table('user_group')->whereNull('deleted_at')->get() as $ug)
                                        <option value="{{$ug->id}}"
                                                @if(DB::table('user_group_users')->where('user_group', $ug->id)->where('user', $user->id)->count() > 0) selected @endif>
                                            {{$ug->title}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mt-5">
                            <div class="form-group">
                                <label for="uye-grup">Kullanıcı Rolü</label>
                                <select class="select2 form-control" name="role">
                                    <option value="0" @if($user->role == 0) selected @endif>Yönetici</option>
                                    <option value="1" @if($user->role == 1) selected @endif>Üye</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!--end card-->
        </div><!--end col-->
    </div><!--end row-->
</div><!--end general detail-->

