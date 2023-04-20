<div class="tab-pane fade" id="bildirim">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="custom-control custom-switch">
                                <input name="notify_sms" id="11" class="custom-control-input"
                                       type="checkbox"
                                       @if($user->notify_sms == 1) checked @endif>
                                <label for="11" class="custom-control-label">SMS Bildirimleri</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="custom-control custom-switch">
                                <input name="notify_email" id="12" class="custom-control-input"
                                       type="checkbox"
                                       @if($user->notify_email == 1) checked @endif>
                                <label for="12" class="custom-control-label">Email Bildirimleri</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!--end card-->
        </div><!--end col-->
    </div><!--end row-->
</div><!--end general detail-->

