<div class="row title-area" data-lang="{{getLang()}}">
    <div class="col-sm-12 title text-center">
        <h1>@lang('general.yorumlar')</h1>
    </div>
</div>


        <div class="row g-4">
        @foreach(\App\Models\Comments::whereNull('deleted_at')->where('lang', getLang())->where('status', '1')->get() as $u)
            <div class="col-sm-12 col-md-6">


                    <div class="card">
                        <div class="card-body">
                                <p>{{$u->text}}</p>
                        </div>
                        <div class="card-footer">
                            <p>{{getUserName($u->user)}}</p>
                        </div>
                    </div>


            </div>
        @endforeach
    </div>
</div>
