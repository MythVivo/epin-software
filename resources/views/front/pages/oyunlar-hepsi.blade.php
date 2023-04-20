@extends('front.layouts.app')
@section('body')
    <section class="bg-gray pt-40 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center pb-5">

                    <?php
                    /*
                                                        <div class="bg-white">
                                        
                                                        $ch = curl_init();
                                                        $headers = array(
                                                            'Authorization: ' . getAuthName(),
                                                            'ApiName: ' . getApiName(),
                                                            'ApiKey: ' . getApiKey(),
                                                        );
                                                        curl_setopt($ch, CURLOPT_URL, env('EPIN_BASE') . '/GetGameList');
                                                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                                        curl_setopt($ch, CURLOPT_HEADER, 0);
                                                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                                                        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
                                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                                                        $response = curl_exec($ch);
                                                        $result_game = json_decode($response);
                                                        curl_close($ch);
                                                        foreach ($result_game->GameDto->GameViewModel as $item) {
                                                            echo $item->Name . " - " . $item->Id . "<br>";
                                                        }
                                        
                                                            </div> */
                    ?>

                    <form method="get" autocomplete="off">
                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">
                                    <input onkeyup="ara(this.value)" placeholder="Tüm Oyunlarda Ara"
                                        class="form-control style-input" name="q"
                                        @if (isset($_GET['q'])) value="{{ $_GET['q'] }} @endif">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <button class="btn-inline color-darkgreen w-100">Ara</button>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>


                @if (isset($_GET['q']))
                    @php $title = $_GET['q']; @endphp
                @else
                    @php $title = ""; @endphp
                @endif
                @php
                    $sorgu = DB::table('games')
                        ->where('title', 'like', '%' . $title . '%')
                        ->whereNull('deleted_at')
                        ->where('status', '1')
                        ->orderBy('sira', 'asc')
                        ->get();
                @endphp
                @foreach ($sorgu as $u)
                    <?php
                    $link = route('oyun_baslik', $u->link);
                    $kontrol = \App\Models\GamesTitles::whereNull('deleted_at')
                        ->where('status', '1')
                        ->where('game', $u->id);
                    
                    if ($kontrol->count() == 1) {
                        if ($kontrol->first()->type == 1) {
                            $link = route('item_detay', [$kontrol->first()->link]);
                        } elseif ($kontrol->first()->type == 2) {
                            $link = route('epin_detay', [$kontrol->first()->link]);
                        } elseif ($kontrol->first()->type == 3) {
                            $link = route('game_gold_detay', [$kontrol->first()->link]);
                        }
                    }
                    /*  $datetime = new DateTime($u->created_at);
                     $datetime = $datetime->format('Y-m-d\TH:i:sP'); */
                    ?>
                    <div class="colflex" data-title="{{ $u->title }}">
                        <div class="col_cell">
                            <a href="{{ $link }}">
                                <figure>
                                    <img class="api" src="{{ asset('front/games/' . $u->image) }}"
                                        alt="{{ $u->alt }}">
                                </figure>
                                <div class="text-container">
                                    <h5>{{ $u->title }}</h5>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        function ara(q) {
            var classes = $('.colflex');
            for (var i = 0; i < classes.length; i++) {
                $(classes[i]).css("display", "none").animate({
                    opacity: 0,
                }, 100, function() {});
                var metin = $(classes[i]).data('title').toLowerCase();
                if (metin.indexOf(q) >= 0) {
                    $(classes[i]).css("display", "block").animate({
                        opacity: 1,
                    }, 250, function() {});
                } else {
                    $(classes[i]).css("display", "none").animate({
                        opacity: 0,
                    }, 250, function() {});
                }
            }
        }
    </script>
@endsection
