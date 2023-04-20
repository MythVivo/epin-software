<div class="row justify-content-center">
    <div class="col-12 mb-3 mt-5">
        @if(isset($_GET['date1']) and isset($_GET['date2']))
            <?php
            $date1 = $_GET['date1'];
            $date2 = $_GET['date2'];
            ?>
        @else
            <?php
            $date1 = date('Y-m-d');
            $date2 = date('Y-m-d');
            ?>
        @endif
        <form method="get">
            <div class="row mb-3">

                <div class="col-sm-12 col-md-5">
                    <label class="form-label" for="userinput1">İlk Tarih</label>
                    <input type="date" id="userinput1" class="form-control style-input" name="date1"
                           value="{{$date1}}" required>
                </div>
                <div class="col-sm-12 col-md-5">
                    <label class="form-label" for="userinput2">Son Tarih</label>
                    <input type="date" id="userinput2" class="form-control style-input" name="date2"
                           value="{{$date2}}" required>
                </div>

                <div class="col-sm-12 col-md-2 d-flex justify-content-sm-start justify-content-md-end align-items-center">
                    <button type="submit" class="btn btn-outline-success mt-4 btn-block">Sorgula
                </div>

            </div>
        </form>
        @if(isset($_GET['date1']) and isset($_GET['date2']))
            <?php
            $date1 = $_GET['date1'].' 00:00:01';
            $date2 = $_GET['date2'].' 23:59:59';
            ?>
        @else
            <?php
            $date1 = date('Y-m-d').' 00:00:01';
            $date2 = date('Y-m-d').' 23:59:59';
            ?>
        @endif

    </div>


    <div class="col">
        <div class="card report-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-dark font-weight-semibold font-14">@lang('admin.kullanici')</p>
                        <h3 class="my-3">{{\App\Models\User::where('created_at', '>=', $date1)->where('created_at', '<=', $date2)->count()}}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="dripicons-user-group report-main-icon bg-soft-purple text-purple"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card report-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-dark font-weight-semibold font-14">Toplam Üye Bakiyesi</p>
                        <h3 class="my-3">
                            ₺{{MF(round(DB::table('users')->whereNull('deleted_at')->sum('bakiye')))}}
                        </h3>
                    </div>
                    <div class="align-self-center">
                        <i class="dripicons-card report-main-icon bg-soft-secondary text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card report-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-dark font-weight-semibold font-14">Çekilebilir Üye Bakiyesi</p>
                        <h3 class="my-3">
                            ₺{{MF(round(DB::table('users')->whereNull('deleted_at')->sum('bakiye_cekilebilir')))}}
                        </h3>
                    </div>
                    <div class="align-self-center">
                        <i class="dripicons-card report-main-icon bg-soft-secondary text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card report-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-dark font-weight-semibold font-14">Alış Bloke Kazançları</p>
                        <h3 class="my-3">
                            ₺{{MF(DB::table('pazar_yeri_ilanlar_buy')->where('status', '99')->where('created_at', '>=', $date1)->where('created_at', '<=', $date2)->sum('moment_komisyon'))}}
                        </h3>
                    </div>
                    <div class="align-self-center">
                        <i class="dripicons-cart report-main-icon bg-soft-warning text-warning"></i>
                    </div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
    <div class="col">
        <div class="card report-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-dark font-weight-semibold font-14">@lang('admin.urun')</p>
                        <h3 class="my-3">
                            ₺{{MF(DB::table('odemeler')->where('status', '1')->where('channel', '!=', '4')->where('channel', '!=', '5')->where('channel', '!=', '3')->where('created_at', '>=', $date1)->where('created_at', '<=', $date2)->sum('amount'))}}
                        </h3>
                    </div>
                    <div class="align-self-center">
                        <i class="dripicons-cart report-main-icon bg-soft-warning text-warning"></i>
                    </div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
</div>
