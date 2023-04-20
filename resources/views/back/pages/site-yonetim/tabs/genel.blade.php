<div class="tab-pane fade show active" id="genel">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Site Başlığı</label>
                                <input name="site_name" type="text" class="form-control" value="{{$u->site_name}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Site Açıklama</label>
                                <input name="description" type="text" class="form-control" value="{{$u->description}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Site Footer Text</label>
                                <input name="footer_text" type="text" class="form-control" value="{{$u->footer_text}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Google Analytics Kodu</label>
                                <input placeholder="UA-XXXXX-Y" name="analytics" type="text" class="form-control"
                                       value="{{$u->analytics}}">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Meta TAG's</label>
                                <textarea class="form-control" name="meta">{!! $u->meta !!}</textarea>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>Robot Taraması</label>
                                <select class="form-control" name="robots">
                                    <option value="1" @if($u->robots == 1) selected @endif>Evet, tarasın</option>
                                    <option value="0" @if($u->robots == 0) selected @endif>Hayır, taramasın</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Açık Zemin İçin Logo</label>
                                <input name="logo"
                                       data-default-file="{{asset('public/front/site/'.$u->logo)}}"
                                       type="file" class="dropify"
                                       accept="image/*">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Koyu Zemin İçin Logo</label>
                                <input name="logo_white"
                                       data-default-file="{{asset('public/front/site/'.$u->logo_white)}}"
                                       type="file" class="dropify"
                                       accept="image/*">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Favicon</label>
                                <input name="favicon"
                                       data-default-file="{{asset('public/front/site/'.$u->favicon)}}"
                                       type="file" class="dropify"
                                       accept="image/*">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

