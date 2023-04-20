@if (isset($_GET['pazar']) and isset($_GET['itemler']))
    <?php $itemler = []; ?>
    @foreach (DB::table('games_titles_items_info')->where('game_title', $_GET['pazar'])->whereNull('deleted_at')->where('title', 'like', '%' . $_GET['searchTerm'] . '%')->get() as $i)
        <?php
        $itemler[] = ['id' => $i->id, 'text' => $i->title];
        ?>
    @endforeach
    <?php echo json_encode($itemler); ?>
    @php die(); @endphp
@endif
@if (isset($_GET['pazar']))
    @if (isset($_GET['item']))
        <?php
        $item = $_GET['item'];
        $item_info = DB::table('games_titles_items_info')
            ->where('id', $item)
            ->first();
        $item_photo = DB::table('games_titles_items_photos')
            ->where('item', $item)
            ->get();
        ?>
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-3">
                        @foreach ($item_photo as $ip)
                            <img src="{{ asset('public/front/games_items/' . $ip->image) }}"
                                class="card-img-center bg-black" alt="{{ $item_info->title }} görseli">
                        @endforeach
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card-body">
                                    <h5 class="card-title item-baslik" id="itemBaslik">{{ $item_info->title }}</h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    @foreach (DB::table('games_titles_features')->where('game_title', $item_info->game_title)->whereNull('deleted_at')->get() as $i)
                                        @if (DB::table('games_titles_items')->where('item', $item)->where('feature', $i->id)->count() > 0)
                                            <?php
                                            $value = DB::table('games_titles_items')
                                                ->where('item', $item)
                                                ->where('feature', $i->id)
                                                ->first()->value;
                                            ?>
                                            @if ($value != '0')
                                                <li class="list-group-item">{{ $i->title }}
                                                    :
                                                    @if ($value == '0')
                                                        Hepsinde Geçerli
                                                    @else
                                                        {{ ucfirst($value) }}
                                                    @endif
                                                </li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="2" class="form-label">Fiyat</label><br>
                                            <input onkeyup="komisyonHesapla(this)" id="2"
                                                class="form-control style-input" name="price{{ $item }}"
                                                placeholder="Fiyat" type="number" step="0.01" required>
                                        </div>
                                        <div class="col-md-8">
                                            <label for="5" class="form-label">Hesaba Geçecek Tutar</label><br>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text style-input" id="basic-addon1">
                                                    @if ($_GET['market'] == 413)
                                                        5
                                                    @else
                                                        {{ findUserKomisyon(Auth::user()->id) }}
                                                    @endif % komisyon
                                                </span>
                                                <input id="5" type="number" name="kazanc" step="0.01"
                                                    class="form-control style-input-pd" readonly>
                                                <span class="input-group-text style-input">₺</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Teslimat Saati</label><br>

                                            <div class="d-flex flex-row mb-2">
                                                <div class="d-flex flex-row dates_cont">
                                                    <select class="date_f s2dates" style="width: 100px">
                                                        <option value="">Seçiniz</option>
                                                        @for ($i = 0; $i < 24; $i++)
                                                            <option
                                                                value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) . ':00' }}">
                                                                {{ str_pad($i, 2, '0', STR_PAD_LEFT) . ':00' }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <span class="form-label"
                                                        style="font-weight: bold;font-size:18px;padding:0px 10px;margin-top:6px">
                                                        - </span>
                                                    <select class="date_t s2dates" style="width: 100px">
                                                        <option value="">Seçiniz</option>
                                                        @for ($i = 0; $i < 24; $i++)
                                                            <option
                                                                value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) . ':00' }}">
                                                                {{ str_pad($i, 2, '0', STR_PAD_LEFT) . ':00' }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <label for="7_24" class="form-check form-switch"
                                                    style="margin-left: 25px">
                                                    <input name="7_24" id="7_24" type="checkbox" role="switch">
                                                    <div class="switcher"><span><i></i></span></div>
                                                    <span class="form-label">24 Saat
                                                        <col>
                                                    </span>
                                                </label>
                                            </div>
                                            <input class="form-control style-input d-none" id="teslimatSaati"
                                                name="teslimat{{ $item }}" placeholder="Teslimat Saati"
                                                type="text" required>

                                            <script>
                                                $(".s2dates").select2({
                                                    minimumResultsForSearch: Infinity
                                                });
                                                $('#7_24').on('change', function(event) {
                                                    if ($('#7_24').is(":checked")) {
                                                        $(".date_f").val('').trigger('change');;
                                                        $(".date_t").val('').trigger('change');;
                                                        $("#teslimatSaati").val("24 Saat Teslimat");
                                                        $(".dates_cont").addClass('d-none').removeClass('d-flex');
                                                    } else {
                                                        $(".dates_cont").addClass('d-flex').removeClass('d-none');
                                                        $("#teslimatSaati").val("");
                                                    }
                                                });
                                                $(".s2dates").on('change', function(event) {
                                                    if (!!$(".date_f").val() && !!$(".date_t").val()) {
                                                        $("#teslimatSaati").val($(".date_f").val() + " - " + $(".date_t").val() + ' saatleri arasında');
                                                    } else {
                                                        $("#teslimatSaati").val("");
                                                    }
                                                });
                                            </script>



                                        </div>
                                        @if(@$_GET['swx2']=='swx2')
                                        <div class="col-12">
                                            asdadasd
                                        </div>
                                        @endif
                                        <div class="col-12">
                                            <label for="4" class="form-label">Açıklama</label>
                                            <textarea id="4" class="form-control" style="height: 120px" name="text{{ $item }}"
                                                placeholder="Açıklama"></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">

                            <div class="card-footer btn-float-right">
                                <button type="button" onclick="silItem(this)" class="btn-inline color-red">
                                    Sil
                                </button>
                            </div>
                            <input type="hidden" name="item[]" value="{{ $item }}">
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php
        die();
        ?>
    @endif
    @include('front.modules.yeni_satis', ['pazar' => $_GET['pazar']])
    @php die(); @endphp
@endif
@extends('front.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('body')
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    @if (session('error'))
                        <!--Mesaj bildirimi--->
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">{{ session('error') }}</h4>
                        </div>
                        <!--Mesaj bildirim END--->
                    @endif

                    <form action="{{ route('satici_panelim_post') }}" method="post" autocomplete="off"
                        enctype="multipart/form-data">
                        <div class="row g-3">
                            @csrf
                            <div class="col-md-12 select-element area">
                                <label for="pazar" class="form-label">Pazar Seçin</label><br>
                                <select id="pazar" class="select2" name="pazar" style="width: 100%" required>
                                    <option value="0" disabled selected>Pazar Seçin</option>
                                    @foreach (DB::table('games_titles')->where('type', '1')->whereNull('deleted_at')->get() as $u)
                                        <option value="{{ $u->id }}"
                                            @if (isset($_GET['market']) and $_GET['market'] == $u->id) selected @endif>
                                            {{ DB::table('games')->where('id', $u->game)->first()->title }}
                                            - {{ $u->title }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-12 btn-float-right">
                                <button class="btn-inline color-darkgreen btn-yayinla" disabled type="submit">Yayınla
                                </button>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            if (window.location.href.indexOf("market") > -1) {
                itemGetir();
            }
            $("#pazar").change(function() {
                itemGetir();
            });

            function itemGetir() {
                pazar = $("#pazar").val();
                $.ajax({
                    url: "?pazar=" + pazar,
                    success: function(result) {
                        $(".area").html(result);
                        $(".area").append("<input id='pazar' type='hidden' name='pazar' value='" +
                            pazar + "'>");
                        $(".select2").select2();
                        $('.js-data-example-ajax').select2({
                            language: {
                                inputTooShort: function() {
                                    return "Lütfen en az 3 karakter girin";
                                },
                                searching: function() {
                                    return "Aranıyor...";
                                },
                                noResults: function() {
                                    return "Sonuç yok";
                                }
                            },
                            ajax: {
                                url: '?pazar=' + pazar + '&itemler=1',
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        searchTerm: params.term // search term
                                    };
                                },
                                processResults: function(response) {
                                    return {
                                        results: response
                                    };
                                },
                                cache: true
                            },
                            placeholder: 'Arama yapın...',
                            minimumInputLength: 3,
                        });
                    }
                });
            }
        });
    </script>
@endsection
