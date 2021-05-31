@extends('front.layouts.app')
@section('css')
    <style>
        td {
            vertical-align: middle;
        }
    </style>
@endsection
@section('body')
    <?php
    if (Cookie::get('redirect') !== null) {
        $package = Cookie::get('package');
        $adet = Cookie::get('adet');
    }
    if ($package > 0) {
        $package = \App\Models\GamesPackages::where('id', $package)->first();
    } else {
        $adet = 0;
        $package = array();
    }
    ?>
    <section class="game pt-140">
        <div class="container">


        </div>
    </section>
@endsection
