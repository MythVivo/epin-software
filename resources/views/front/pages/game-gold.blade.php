@extends('front.layouts.app')

@section('body')pb-40

    <section class="bg-gray pt-40 pb-40">

        <div class="container">

            <div class="row">

                @php

                $sorgu = DB::table('games_titles')

                        ->select('games_titles.*')

                        ->join('games', 'games_titles.game', '=', 'games.id')

                        ->where('games_titles.type', '3')

                        ->whereNull('games_titles.deleted_at')

                        ->whereNull('games.deleted_at')

                        ->get()

                @endphp

                @foreach($sorgu as $u)

                    <div class="colflex">

                        <div class="col_cell">

                            <a href="{{route('game_gold_detay', [$u->link])}}">

                                <figure>

                                    <img class="api" src="{{asset('public/front/games_titles/'.$u->image)}}">

                                </figure>



                            </a>

                        </div>

                    </div>

                @endforeach



            </div>

        </div>

    </section>

@endsection