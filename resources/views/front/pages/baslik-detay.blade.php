@extends('front.layouts.app')
@section('body')
    <?php
    if (is_numeric($oyun)) { //epin ise
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'id=' . $oyun);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $result_package = json_decode($response);
        curl_close($ch);


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
        $baslik = new stdClass();
        foreach ($result_game->GameDto->GameViewModel as $item) {
            if ($item->Id == $oyun) {
                $baslik->id = $item->Id;
                $baslik->type = 2;
                $baslik->image = $item->ImageUrl;
                $baslik->title = $item->Name;
                $baslik->text = $item->Description;
            }
        }
        $image = $baslik->image;
    } else {
        $oyun = \App\Models\Games::where('link', $oyun)->first();
        $baslik = \App\Models\GamesTitles::where('link', $baslik)->first();
        $image = asset(env('ROOT') . env('FRONT') . env('GAMES_TITLES') . $baslik->image);
    }
    ?>


    @if($baslik->type == 2)
        <section class="game header-margin pt-100 pb-100">
            <div class="container">

                <div class="row">

                    <div class="col-4">
                        <div class="game-info-col">
                            <figure>
                                <img src="{{$image}}">
                            </figure>
                            <h5 class="heading-secondary-title">{{$baslik->title}}</h5>

                            <p>{!! $baslik->text !!}</p>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="items-collection-wrapper">
                            @if (!is_numeric($oyun))
                                @foreach(\App\Models\GamesPackages::where('games_titles', $baslik->id)->get() as $u)
                                    <article class="item-col-wrapper">
                                        <figure><img
                                                    src="{{asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES').$u->image)}}">
                                        </figure>
                                        <div class="item-col-center">
                                            <h5 class="heading-secondary">{{$u->title}}</h5>
                                            <h6>{!! $u->text !!}</h6>

                                        </div>
                                        <div class="item-col-buy">
                                            <p><span>₺</span>{{findGamesPackagesPrice($u->id)}}</p>
                                            <button type="button" class="btn-inline color-yellow small"
                                                    data-bs-toggle="modal"
                                                    data-bs-target=".satinAl{{$u->id}}">@lang('general.satin-al')</button>
                                            <!-- Modal -->
                                            <div class="modal fade satinAl{{$u->id}}" tabindex="-1" role="dialog"
                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="exampleModalLabel">@lang('general.satin-al')</h5>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <?php
                                                        $maxStok = DB::table('games_packages_codes')->where('package_id', $u->id)->where('is_used', '0')->count();
                                                        ?>
                                                        <form method="post" action="{{route('urun_onizle_post')}}">
                                                            <div class="modal-body">
                                                                @csrf
                                                                <input type="hidden" name="package" value="{{$u->id}}">
                                                                <label>@lang('general.adet')</label>
                                                                <input max="{{$maxStok}}" min="1" class="form-control"
                                                                       type="number" name="adet" required>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">@lang('admin.kapat')</button>
                                                                <button type="submit"
                                                                        class="btn btn-primary">@lang('admin.kaydet')</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal END -->
                                        </div>


                                    </article>

                                @endforeach
                            @else
                                @foreach($result_package->GameDto as $u)
                                    <article class="item-col-wrapper">
                                        <figure><img src="{{$image}}">
                                        </figure>
                                        <div class="item-col-center">
                                            <h5 class="heading-secondary">{{$u->Name}}</h5>
                                        </div>
                                        <div class="item-col-buy">
                                            <p><span>₺</span>{{$u->Price}}</p>
                                            <button type="button" class="btn-inline color-yellow small"
                                                    data-bs-toggle="modal"
                                                    data-bs-target=".satinAl{{$u->Id}}">@lang('general.satin-al')</button>
                                            <!-- Modal -->
                                            <div class="modal fade satinAl{{$u->Id}}" tabindex="-1" role="dialog"
                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="exampleModalLabel">@lang('general.satin-al')</h5>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <?php
                                                        $maxStok = $u->Stock;
                                                        ?>
                                                        <form method="post" action="{{route('urun_onizle_post')}}">
                                                            <div class="modal-body">
                                                                @csrf
                                                                <input type="hidden" name="package"
                                                                       value="{{$u->StockCode}}">
                                                                <input type="hidden" name="api" value="{{$baslik->id}}">
                                                                <input type="hidden" name="apiUrun" value="{{$u->Id}}">
                                                                <label>@lang('general.adet')</label>
                                                                <input max="{{$maxStok}}" min="1" class="form-control"
                                                                       type="number" name="adet" required>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">@lang('admin.kapat')</button>
                                                                <button type="submit"
                                                                        class="btn btn-primary">@lang('admin.kaydet')</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal END -->
                                        </div>


                                    </article>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </section>
    @elseif($baslik->type == 3)

        <section class="game header-margin pt-100 pb-100">
            <div class="container">

                <div class="row">

                    <div class="col-4">
                        <div class="game-info-col">
                            <figure>
                                <img src="{{$image}}">
                            </figure>
                            <h5 class="heading-secondary-title">{{$baslik->title}}</h5>

                            <p>{!! $baslik->text !!}</p>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="items-collection-wrapper">
                            @if (!is_numeric($oyun))
                                @foreach(\App\Models\GamesPackagesTrade::where('games_titles', $baslik->id)->get() as $u)
                                    <article class="item-col-wrapper">
                                        <figure><img
                                                    src="{{asset(env('ROOT').env('FRONT').env('GAMES_PACKAGES_TRADE').$u->image)}}">
                                        </figure>
                                        <div class="item-col-center">
                                            <h5 class="heading-secondary">{{$u->title}}</h5>
                                            <h6>{!! $u->description !!}</h6>

                                        </div>
                                        <div class="item-col-buy type-2">
                                            <div class="satinal">
                                                <h5>Alış : <span>₺</span>{{findGamesPackagesTradeBuyPrice($u->id)}}</h5>
                                                <button type="button" class="btn-inline color-yellow small"
                                                        data-bs-toggle="modal"
                                                        data-bs-target=".bizeSat{{$u->id}}">Bize Sat</button>
                                            </div>
                                            <div class="satis">
                                                <h5>Satış : <span>₺</span>{{findGamesPackagesTradeSellPrice($u->id)}}
                                                </h5>
                                                <button type="button" class="btn-inline color-blue small"
                                                        data-bs-toggle="modal"
                                                        data-bs-target=".satinAl{{$u->id}}">Bizden Al</button>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade bizeSat{{$u->id}}" tabindex="-1" role="dialog"
                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered style-custom" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="exampleModalLabel">Bize Sat</h5>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <?php
                                                        $maxStokAlis = $u->alis_stok;
                                                        ?>
                                                        <form method="post" action="{{route('urun_onizle_post')}}">
                                                            <div class="modal-body">
                                                                @csrf
                                                                <input type="hidden" name="package" value="{{$u->id}}">
                                                                <input type="hidden" name="trade" value="1">
                                                                <label>@lang('general.adet')</label>
                                                                <input max="{{$maxStokAlis}}" min="1"
                                                                       class="form-control"
                                                                       type="number" name="adet" required>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit"
                                                                        class="btn btn-primary">@lang('admin.kaydet')</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal END -->

                                            <!-- Modal -->
                                            <div class="modal fade satinAl{{$u->id}}" tabindex="-1" role="dialog"
                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered style-custom" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="exampleModalLabel">Bizden Al</h5>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <?php
                                                        $maxStokSatis = $u->satis_stok;
                                                        ?>
                                                        <form method="post" action="{{route('urun_onizle_post')}}">
                                                            <div class="modal-body">
                                                                @csrf
                                                                <input type="hidden" name="package" value="{{$u->id}}">
                                                                <input type="hidden" name="trade" value="2">
                                                                <label>@lang('general.adet')</label>
                                                                <input max="{{$maxStokSatis}}" min="1"
                                                                       class="form-control"
                                                                       type="number" name="adet" required>
                                                            </div>
                                                            <div class="modal-footer">

                                                                <button type="submit"
                                                                        class="btn btn-primary">@lang('admin.kaydet')</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal END -->
                                        </div>


                                    </article>

                                @endforeach

                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </section>
    @endif


@endsection
