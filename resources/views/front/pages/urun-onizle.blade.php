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
            <table class="table table-striped table-hover table-bordered text-center">
                <thead>
                <tr class="table-secondary">
                    <th>@lang('general.resim')</th>
                    <th>@lang('general.adi')</th>
                    <th>@lang('general.adet')</th>
                    <th>@lang('general.fiyat')</th>
                    <th>@lang('general.toplamFiyat')</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="w-25">
                        <img class="img-fluid img-thumbnail"
                             src="{{asset(env('root').env('front').env('games_packages').$package->image)}}">
                    </td>
                    <td>{{$package->title}}</td>
                    <td>{{$adet}}</td>
                    <td>₺{{findGamesPackagesPrice($package->id)}}</td>
                    <td>₺{{findGamesPackagesPrice($package->id) * $adet}}</td>
                </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-4 offset-md-4 align-self-center">
                    <div class="card text-center mt-100 mb-100">
                        <div class="card-body">
                            <h5 class="card-title">@lang('general.siparisOnay')</h5>
                            <form method="post" action="{{route('siparis_ver')}}">
                                @csrf
                                <input type="hidden" name="adet" value="{{$adet}}">
                                <input type="hidden" name="package" value="{{$package->id}}">
                                <button type="submit" class="btn btn-primary mt-3">@lang('general.onayla')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
