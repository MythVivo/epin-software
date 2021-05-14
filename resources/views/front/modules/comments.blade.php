<div class="row title-area" data-lang="{{getLang()}}">
    <div class="col-sm-12 title text-center">
        <h1>@lang('general.yorumlar')</h1>
    </div>
</div>

    <div class="accordion" id="accordionExample">
        <div class="row g-4">
        @foreach(\App\Models\Comments::whereNull('deleted_at')->where('lang', getLang())->where('status', '1')->get() as $u)
            <div class="col-sm-12 col-md-6">

                <div class="accordion-item">
                    <div class="card">
                        <div class="collapseble accordion-header" id="heading{{$u->id}}">
                            <button type="button"
                                    class="customNextBtn btn btn-group text-center @if(!$loop->first) collapsed @endif"
                                    data-bs-toggle="collapse" data-bs-target="#collapse{{$u->id}}"
                                    @if($loop->first) aria-expanded="true" @else aria-expanded="false"
                                    @endif  aria-controls="collapse{{$u->id}}">
                                <i class="fas fa-angle-down align-self-center"></i>
                            </button>
                        </div>
                        <div id="collapse{{$u->id}}"
                             class="card-body accordion-collapse collapse @if($loop->first) show @endif"
                             aria-labelledby="heading{{$u->id}}" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                {{$u->text}}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-4 offset-8">
                                    {{getUserName($u->user)}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
</div>
