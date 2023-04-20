@extends('front.layouts.app')
@section('body')
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">

                <div class="col-md-12">
                    <div class="sss-diagram">
                        <div class="first-box">
                            <div class="diagram-text-box"><span>Genel</span></div>
                            <article>
                                @foreach(App\Models\FaqCategory::whereNull('deleted_at')->get() as $a)
                                    @if(DB::table('faq')->where('category', $a->id)->where('status', '1')->whereNull('deleted_at')->count() > 0)
                                        <div class="second-box">
                                            <div class="diagram-text-box"><span>{{$a->title}}</span></div>
                                            <article>
                                                @foreach(App\Models\Faq::whereNull('deleted_at')->where('status', '1')->where('category', $a->id)->get() as $u)
                                                    <div class="second-child-box">
                                                        <div class="diagram-text-box">
                                                            <span><h4>{{$u->title}}</h4> <p>{{$u->text}}</p></span></div>
                                                    </div>
                                                @endforeach
                                            </article>
                                        </div>
                                    @endif
                                @endforeach
                            </article>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
