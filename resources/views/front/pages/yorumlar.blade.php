@extends('front.layouts.app')
@section('body')
    @foreach(\App\Models\Comments::whereNull('deleted_at')->where('lang', getLang())->where('status', '1')->get() as $u)
        {{$u->text}}
        {{getUserName($u->user)}}
    @endforeach

@endsection
