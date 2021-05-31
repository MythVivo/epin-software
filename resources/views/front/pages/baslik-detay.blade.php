@extends('front.layouts.app')
@section('body')
    <?php
    $oyun = \App\Models\Games::where('link', $oyun)->first();
    $baslik = \App\Models\GamesTitles::where('link', $baslik)->first();
    ?>


    @if($baslik->type = 2)
    <section class="game header-margin pt-100 pb-100">
        <div class="container">

            <div class="row">

                <div class="col-4">
                    <div class="game-info-col">
                        <figure>
                    <img src="{{asset(env('root').env('front').env('games_titles').$baslik->image)}}">
                    </figure>
                    <h5 class="heading-secondary-title">{{$baslik->title}}</h5>

                    <p>{!! $baslik->text !!}</p>
                </div>
                </div>
                <div class="col-8">
                    <div class="items-collection-wrapper">
                    @foreach(\App\Models\GamesPackages::where('games_titles', $baslik->id)->get() as $u)
                    <article class="item-col-wrapper">
                        <figure><img src="{{asset(env('root').env('front').env('games_packages').$u->image)}}"></figure>
                        <div class="item-col-center">
                        <h5 class="heading-secondary">{{$u->title}}</h5>
                        <h6>{!! $u->text !!}</h6>

                        </div>
                        <div class="item-col-buy">
                        <p><span>₺</span>{{findGamesPackagesPrice($u->id)}}</p>
                        <button class="btn-inline color-yellow small" data-toggle="modal" data-target="#satin-al{{$u->id}}">@lang('general.satin-al')</button>

                        </div>


                    </article>
                        <!-- Modal -->
                        <div class="modal fade" id="satin-al{{$u->id}}" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">@lang('general.satin-al')</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="post" action="{{route('urun_onizle')}}">
                                        <div class="modal-body">
                                            @csrf
                                            <input type="hidden" name="package" value="{{$u->id}}">
                                            <label>@lang('general.adet')</label>
                                            <input type="number" name="adet">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">@lang('admin.kapat')</button>
                                            <button type="submit" class="btn btn-primary">@lang('admin.kaydet')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal END -->
                    @endforeach
                    </div>
                </div>

            </div>

        </div>
</section>
    @endif


@endsection
