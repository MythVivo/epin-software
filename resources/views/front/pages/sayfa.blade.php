@extends('front.layouts.app')
@section('body')
<?php
    $u = \App\Models\Pages::whereNull('deleted_at')->where('status', '1')->where('link', $sayfa)->first();
    ?>
    {{$u->title}}
    {!!  $u->text !!}

@endsection
