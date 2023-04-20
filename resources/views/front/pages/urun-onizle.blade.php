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
    if (isset($redirect)) {
        $package = $package;
        $adet = $adet;
        if (isset($api)) {
            $api = $api;
            $apiUrun = $apiUrun;
        }
        if (isset($trade)) {
            $trade = $trade;
        }
    }

    if (isset($_COOKIE['redirect'])) {
        $package = $_COOKIE['package'];
        $adet = $_COOKIE['adet'];
        if (isset($_COOKIE['api'])) {
            $api = $_COOKIE['api'];
            $apiUrun = $_COOKIE['apiUrun'];
        }
        if (isset($_COOKIE['trade'])) {
            $trade = $_COOKIE['trade'];
        }
    } else {
        if (!isset($redirect)) {
            echo "<meta http-equiv='refresh' content='2;url=" . route('homepage') . "' />";
            die(__('general.yonlendiriliyorsunuz'));
        }
    }

    if (isset($redirect)) {
        $package = $package;
        $adet = $adet;
        if (isset($api)) {
            $api = $api;
            $apiUrun = $apiUrun;
        }
        if (isset($trade)) {
            $trade = $trade;
        }
    }

    if (!isset($trade)) {

        if ($api != 0) {
            $ch = curl_init();
            $headers = array(
                'Authorization: ' . getAuthName(),
                'ApiName: ' . getApiName(),
                'ApiKey: ' . getApiKey(),
                'Content-Type: Content-Type: application/json',
            );
            curl_setopt($ch, CURLOPT_URL, 'https://epntest.epin.com.tr/fra/apv2/CheckOrderProduct');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'StockCode=' . $package);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            $result_product = json_decode($response);
            curl_close($ch);

            /*
             * Oyun Bilgisi
             */
            $ch = curl_init();
            $headers = array(
                'Authorization: ' . getAuthName(),
                'ApiName: ' . getApiName(),
                'ApiKey: ' . getApiKey(),
            );
            curl_setopt($ch, CURLOPT_URL, 'https://epntest.epin.com.tr/fra/apv2/GetCategoryList');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_POSTFIELDS, '');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            $result_game = json_decode($response);
            curl_close($ch);
            $package = new stdClass();
            foreach ($result_game->GameDto->GameViewModel as $item) {
                if ($item->Id == $api) {
                    $image = $item->ImageUrl;
                }
            }

            /*
             * Seçilen Ürün Bilgisi
             */
            $ch = curl_init();
            $headers = array(
                'Authorization: ' . getAuthName(),
                'ApiName: ' . getApiName(),
                'ApiKey: ' . getApiKey(),
                'Content-Type: application/x-www-form-urlencoded',
            );
            curl_setopt($ch, CURLOPT_URL, 'https://epntest.epin.com.tr/fra/apv2/GameItemListById');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'id=' . $api);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            $result_package = json_decode($response);
            curl_close($ch);

            $package = new stdClass();
            foreach ($result_package->GameDto as $item) {
                if ($item->Id == $apiUrun) {
                    $package->id = $item->Id;
                    $package->type = 2;
                    $package->title = $item->Name;
                    $package->text = $item->Description;
                    $package->price = $item->Price;
                }
            }

            $tekil = $package->price;
        } else {
            $package = \App\Models\GamesPackages::where('id', $package)->first();
            $image = asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $package->image);
            $tekil = findGamesPackagesPrice($package->id);
        }

    }

    ?>
    @if(!isset($trade))
        <section class="bg-gray pt-140">
            <div class="container">
                <table class="table table-hover table-bordered text-center item-checkout-table">
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
                                 src="{{$image}}">
                        </td>
                        <td>{{$package->title}}</td>
                        <td>{{$adet}}</td>
                        <td>₺{{$tekil}}</td>
                        <td>₺{{$tekil * $adet}}</td>
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
                                    <input type="hidden" name="tutar" value="{{$tekil * $adet}}">
                                    <button type="submit" class="btn btn-primary mt-3">@lang('general.onayla')</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    @elseif(isset($trade))
        <?php
        $package = \App\Models\GamesPackagesTrade::where('id', $package)->first();
        $image = asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES_TRADE') . $package->image);
        if ($trade == 1) {
            $tekil = findGamesPackagesTradeBuyPrice($package->id);
        } else {
            $tekil = findGamesPackagesTradeSellPrice($package->id);
        }

        ?>
        <section class="game pt-140">
            <div class="container">
                <table class="table table-hover table-bordered text-center item-checkout-table">
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
                                 src="{{$image}}">
                        </td>
                        <td>{{$package->title}}</td>
                        <td>{{$adet}}</td>
                        <td>₺{{$tekil}}</td>
                        <td>₺{{$tekil * $adet}}</td>
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
                                    <input type="hidden" name="tutar" value="{{$tekil * $adet}}">
                                    <input type="hidden" name="trade" value="{{$trade}}">
                                    <button type="submit" class="btn btn-primary mt-3">@lang('general.onayla')</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    @endif
@endsection
