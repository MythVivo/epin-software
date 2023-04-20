<?php
if (
    \App\Models\Pages::whereNull('deleted_at')
        ->where('status', '1')
        ->where('link', $sayfa)
        ->count() < 1
) {
    header('Location: ' . URL::to(route('errors_404')), true, 302);
    exit();
}

$u = \App\Models\Pages::whereNull('deleted_at')
    ->where('status', '1')
    ->where('link', $sayfa)
    ->first();
?>
@extends('front.layouts.app')
@section('head')
    <meta name="description" content="Oyuneks {{ $u->title }}">
    <meta name="keywords" content="Oyuneks {{ $u->title }}">
@endsection
@section('body')
    <section class="about bg-gray pb-40">
        <div class="container">
            <div class="row">

                <h1 class="heading-primary"> {{ $u->title }}</h1>
                {!! $u->text !!}
            </div>
        </div>
    </section>
@endsection
