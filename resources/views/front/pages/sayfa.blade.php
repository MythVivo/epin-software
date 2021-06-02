@extends('front.layouts.app')
@section('body')
<?php
    $u = \App\Models\Pages::whereNull('deleted_at')->where('status', '1')->where('link', $sayfa)->first();
    ?>

    <section class="game header-margin pt-100 pb-100">
    <div class="container">
        <div class="row">

        <h1 class="heading-primary"> {{$u->title}}</h1>
        {!!  $u->text !!}
        </div>
    </div>
</section>
@endsection
