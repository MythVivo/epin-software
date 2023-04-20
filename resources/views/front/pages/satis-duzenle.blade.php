@if(isset($_GET['pazar']))
    @if(isset($_GET['item']))
        <?php
        $item = $_GET['item'];
        $item_info = DB::table('games_titles_items_info')->where('id', $item)->first();
        $item_photo = DB::table('games_titles_items_photos')->where('item', $item)->get();
        ?>
        <div class="col-md-6">
            <div class="card">
                <div class="row g-0">


                    <div class="col-md-4">
                        @foreach($item_photo as $ip)
                            <img src="{{asset('public/front/games_items/' . $ip->image)}}" class="card-img-center"
                                 alt="{{$item_info->title}} görseli">
                        @endforeach
                    </div>
                    <div class="col-md-8">

                        <div class="card-body">
                            <h5 class="card-title item-baslik" id="itemBaslik">{{$item_info->title}}</h5>

                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach(DB::table('games_titles_features')->where('game_title', $item_info->game_title)->whereNull('deleted_at')->get() as $i)
                                <?php
                                $value = DB::table('games_titles_items')->where('item', $item)->where('feature', $i->id)->first()->value;
                                ?>
                                    @if($value != "0")
                                        <li class="list-group-item">{{$i->title}}
                                            :
                                            @if($value == "0")
                                                Hepsinde Geçerli
                                            @else
                                                {{ucfirst($value)}}
                                            @endif
                                        </li>
                                    @endif
                            @endforeach
                        </ul>
                        <div class="card-footer">
                            <button type="button" onclick="silItem(this)" class="btn btn-outline-danger w-100">Sil
                            </button>
                        </div>
                        <input type="hidden" name="item[]" value="{{$item}}">
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('body')
    <?php
    $ilan = DB::table('pazar_yeri_ilanlar')->where('id', $ilan)->where('user', Auth::user()->id)->first();
    ?>
    <section class="game pb-100">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">

                @if(session('error'))
                    <!--Mesaj bildirimi--->
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">{{session('error')}}</h4>
                        </div>
                        <!--Mesaj bildirim END--->
                    @endif

                    <form action="{{route('satis_duzenle_post')}}" method="post" autocomplete="off">
                        <div class="row g-3">
                            @csrf
                            <div class="col-md-3 select-element ">
                                <label for="pazar" class="form-label">Pazar</label><br>
                                <select id="pazar" class="select2" name="pazar" style="width: 100%"
                                        required onchange="baslikSil()" readonly>
                                    @foreach(DB::table('games_titles')->where('id', $ilan->pazar)->where('type', '1')->get() as $u)
                                        <option value="{{$u->id}}"
                                                selected>{{DB::table('games')->where('id', $u->game)->first()->title}}
                                            - {{$u->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="2" class="form-label">Fiyat</label><br>
                                <input id="2" class="form-control style-input" name="price" placeholder="Fiyat"
                                       type="number"
                                       value="{{$ilan->price}}"
                                       step="0.01" required>
                            </div>
                            <div class="col-md-4">
                                <label for="5" class="form-label">Kazancınız</label><br>
                                <div class="input-group mb-3">
                                    <span class="input-group-text style-input" id="basic-addon1">{{DB::table('settings')->first()->pazar_komisyon}}% komisyon</span>
                                    <input id="5" type="number" name="kazanc" step="0.01"
                                           class="form-control style-input-pd" value="{{$ilan->moment_komisyon}}"
                                           readonly>
                                    <span class="input-group-text style-input">₺</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="3" class="form-label">Başlık</label><br>
                                <input id="3" class="form-control style-input" name="title" placeholder="Başlık"
                                       type="text" required value="{{$ilan->title}}"
                                       @if(DB::table('games_titles_special')->where('games_titles', $ilan->pazar)->count() < 1) readonly @endif>
                            </div>
                            <div class="col-12">
                                <label for="4" class="form-label">Açıklama</label>
                                <textarea id="4" class="form-control" name="text" placeholder="Açıklama"
                                          required>{{$ilan->text}}</textarea>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="area">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col select-element ">
                                                <label for="sunucu" class="form-label">Sunucu Seçin</label>
                                                <select class="select2" name="sunucu" style="width: 100%;" required>
                                                    <option disabled selected>Sunucu Seçin</option>
                                                    @foreach(json_decode(DB::table('games_titles_features')->where('game_title', $ilan->pazar)->where('title', 'Sunucu')->whereNull('deleted_at')->first()->value) as $values)
                                                        <option value="{{Str::slug($values)}}"
                                                                @if($ilan->sunucu == Str::slug($values)) selected @endif>{{$values}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col select-element">
                                                <label for="item" class="form-label">İtem</label>
                                                <select onchange="itemGet(this)" class="select2 w-100" name="itemSec"
                                                        required>
                                                    <option selected disabled>İtem Seçin</option>
                                                    @foreach(DB::table('games_titles_items_info')->where('game_title', $ilan->pazar)->whereNull('deleted_at')->get() as $i)
                                                        <option value="{{$i->id}}"
                                                                @if(isset($item) and $item == $i->id) selected @endif>{{$i->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row items mt-5">

                                    @foreach(DB::table('pazar_yeri_ilan_icerik')->where('ilan', $ilan->id)->get() as $item)
                                        <?php
                                        $item = $item->item;
                                        $item_info = DB::table('games_titles_items_info')->where('id', $item)->first();
                                        $item_photo = DB::table('games_titles_items_photos')->where('item', $item)->get();
                                        ?>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="row g-0">
                                                    <div class="col-md-4">
                                                        @foreach($item_photo as $ip)
                                                            <img src="{{asset('public/front/games_items/' . $ip->image)}}"
                                                                 class="card-img-center"
                                                                 alt="{{$item_info->title}} görseli">
                                                        @endforeach
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="card-body">
                                                            <h5 class="card-title item-baslik"
                                                                id="itemBaslik">{{$item_info->title}}</h5>
                                                        </div>
                                                        <ul class="list-group list-group-flush">
                                                            @foreach(DB::table('games_titles_features')->where('game_title', $item_info->game_title)->whereNull('deleted_at')->get() as $i)
                                                                <?php
                                                                $value = DB::table('games_titles_items')->where('item', $item)->where('feature', $i->id)->first()->value;
                                                                ?>
                                                                <li class="list-group-item">{{$i->title}}
                                                                    :
                                                                    @if($value == "0")
                                                                        Hepsinde Geçerli
                                                                    @else
                                                                        {{$value}}
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="card-footer">
                                                            <button type="button" onclick="silItem(this)"
                                                                    class="btn btn-outline-danger w-100">Sil
                                                            </button>
                                                        </div>
                                                        <input type="hidden" name="item[]" value="{{$item}}">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach

                                </div>
                                <script>
                                    function itemGet(a) {
                                        pazar = $("#pazar").val();
                                        item = a.value;
                                        $.ajax({
                                            url: "?pazar=" + pazar + "&item=" + item,
                                            success: function (result) {
                                                $(".items").append(result);
                                                baslikEkle();
                                                $('.select2').select2();
                                            }
                                        });
                                    };

                                    function baslikEkle() {
                                        if ($(".item-baslik").length > 0) {
                                            $("#3").prop("readonly", "true");
                                            if ($(".item-baslik").length > 1) {
                                                str = $('#3').val();
                                                if (str.indexOf("SET") < 0) {
                                                    $('#3').val("SET " + $('#3').val());
                                                }
                                                $('#3').val($('#3').val() + ", " + $(".item-baslik").last().text());
                                            } else {
                                                $("#3").val("");
                                                $('#3').val($('#3').val() + $(".item-baslik").last().text() + " ");
                                            }

                                        } else {
                                            $("#3").prop("readonly", "true");
                                            $("#3").val("");
                                        }
                                    }

                                    function silItem(a) {
                                        a.parentElement.parentElement.parentElement.parentElement.parentElement.remove();
                                        if ($(".item-baslik").length > 0 && $(".item-baslik").length < 2) {
                                            str = $('#3').val();
                                            if (str.indexOf("SET") > -1) {
                                                $('#3').val($('#3').val().replace("SET", ""))
                                            }
                                        }
                                        if ($(".item-baslik").length < 1) {
                                            $('#3').val("");
                                        }
                                        if ($(".item-baslik").length > 1) {
                                            str = $('#3').val();
                                            var baslik = a.parentElement.parentElement.children[0].children[0].innerHTML;
                                            if (str.indexOf(", " + baslik) > -1) {
                                                console.log("sdfsdfsdf");
                                                $('#3').val($('#3').val().replace(", " + baslik, ""))
                                            } else {
                                                $('#3').val($('#3').val().replace(baslik + " , ", ""))
                                            }

                                        }
                                        var baslik = a.parentElement.parentElement.children[0].children[0].innerHTML;
                                        $('#3').val($('#3').val().replace(", " + baslik, ""))
                                    }
                                </script>

                            </div>
                            <div class="col-12 mt-5">
                                <input type="hidden" name="ilan_id" value="{{$ilan->id}}">
                                <button class="btn btn-outline-primary w-100" type="submit">Kaydet</button>
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
        $(document).ready(function () {
            $('.select2').select2();
            $("#2").keyup(function () {
                gelen = $("#2").val();
                komisyon = {{DB::table('settings')->first()->pazar_komisyon}};
                $("#5").val(gelen - (gelen * komisyon / 100));
            });
            if (window.location.href.indexOf("market") > -1) {
                itemGetir();
            }
            $("#pazar").change(function () {
                itemGetir();
            });

            function itemGetir() {
                pazar = $("#pazar").val();
                $.ajax({
                    url: "?pazar=" + pazar,
                    success: function (result) {
                        $(".area").html(result);
                        $('.select2').select2();
                    }
                });
            }
        });

        function baslikSil() {
            $("#3").val("");
        }
    </script>
@endsection