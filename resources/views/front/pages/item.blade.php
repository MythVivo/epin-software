@extends('front.layouts.app')

@section('body')

    <section class="bg-gray pt-40 pb-40">

        <div class="container">

            <div class="row">



                @foreach(DB::table('games_titles')->where('type', '1')->whereNull('deleted_at')->get() as $u)

                    <div class="colflex">

                        <div class="col_cell">

                            <a href="{{route('item_detay', [$u->link])}}">

                                <figure>

                                    <img class="api" src="{{asset('public/front/games_titles/'.$u->image)}}">

                                </figure>



                                <div class="text-container">

                                    <h5>{{ DB::table('games')->where('id', $u->game)->first()->title . " - " . $u->title}}</h5>

                                </div>

                            </a>

                        </div>

                    </div>

                @endforeach



            </div>

        </div>

    </section>

@endsection