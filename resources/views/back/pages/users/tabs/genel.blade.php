<div class="tab-pane fade show active" id="genel_tab">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="1">@lang('admin.uyeIsmi')</label>
                                <input name="name" type="text" class="form-control" id="1"
                                       placeholder="@lang('admin.uyeIsmi')" value="{{$user->name}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="2">@lang('admin.uyeEposta')</label>
                                <input name="email" type="text" class="form-control" id="2"
                                       placeholder="@lang('admin.uyeEposta')" value="{{$user->email}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="3">@lang('admin.uyeTelefon')</label>
                                <input name="telefon" class="form-control" id="3"
                                       placeholder="(000) 000 00-00" value="{{$user->telefon}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="4">@lang('admin.uyeTcno')</label>
                                <input name="tcno" class="form-control" id="4"
                                       placeholder="00000000000" value="{{$user->tcno}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="5">@lang('admin.uyeDogumTarihi')</label>
                                <input type="date" name="dogum_tarihi" class="form-control" id="5" value="{{$user->dogum_tarihi}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="6">@lang('admin.uyeCinsiyet')</label>
                               <select name="cinsiyet" class="form-control">
                                   <option value="0" @if($user->cinsiyet == 0) selected @endif>@lang('admin.uyeCinsiyetBelirsiz')</option>
                                   <option value="1" @if($user->cinsiyet == 1) selected @endif>@lang('admin.uyeCinsiyetErkek')</option>
                                   <option value="2" @if($user->cinsiyet == 2) selected @endif>@lang('admin.uyeCinsiyetKadin')</option>
                               </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="7">@lang('admin.uyeAvatar')</label>
                                <input name="avatar" data-default-file="{{asset(env('root').env('front').env('avatars').getUserAvatar())}}" type="file" id="4" class="dropify"
                                       accept="image/*">
                            </div>
                        </div>

                    </div>
                </div>
            </div><!--end card-->
        </div><!--end col-->
    </div><!--end row-->
</div><!--end general detail-->

