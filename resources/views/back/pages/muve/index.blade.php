@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}"
          rel="stylesheet">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}"
          rel="stylesheet" type="text/css">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/select2/select2.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link type="text/css"
          href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/x-editable/css/bootstrap-editable.css')}}"
          rel="stylesheet">

    <style>
        table.dataTable.nowrap td:nth-child(2) {
            white-space: unset !important;
        }
    </style>
@endsection
@section('body')

    @if(session('success'))
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-success alert-success-shadow" role="alert">
                    <i class="mdi mdi-check-all alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{__('admin.basarili')}}</strong> {{__('admin.basariliMetin')}}
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-danger alert-danger-shadow" role="alert">
                    <i class="mdi mdi-crosshairs alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{__('admin.hata-2')}}</strong>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row" data-lang="{{getLang()}}">

        <?
        $ch = curl_init();
        $headers = array(
            'Authorization: Bearer ' . muveAuth(),
        );
        curl_setopt($ch, CURLOPT_URL, env('MUVE_BASE_URL') . '/api/account/balance');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $result_game = json_decode($response);
        curl_close($ch);
        if (isset($result_game->code)) {
            if ($result_game->code == 200) {
                $balance=$result_game->data;
            }
        } else {
            $balance= "Alınamadı";
        }
        ?>

        <div class="col-lg-2 font-weight-bolder text-danger">Status: {{ $balance->enabled }} <br> Balance: <?=$balance->balance ?> </div>
        <div class="col-lg-2">
            <?php
            $muveGames = muveGetProducts();

            /*echo "<pre>";
            print_r($muveGames);
            echo "</pre>";
            */

            ?>

            <button onclick="editorStart(0)" data-toggle="modal" data-target=".ekle" type="button"
                    class="btn btn-block btn-outline-success" @if(!$muveGames) disabled
                    title="Oyun Bilgileri Getirilemedi" @endif>
                @if($muveGames)
                    @lang('admin.oyunEkle')
                @else
                    Oyun bilgileri bulunamadı, lütfen çerezleri temizleyin!
                @endif
            </button>
            @if($muveGames)
                <div class="modal fade ekle" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.oyunEkle')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                </button>
                            </div>
                            <form id="yeniEkle" method="post" autocomplete="off" action="{{route('muve_oyun_add')}}"
                                  enctype="multipart/form-data">
                                @csrf
                                {!! getLangInput() !!}
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="2">Muve İsmi</label>
                                                <select name="muveId" id="muveId" class="select2 form-group" required>
                                                    <option selected disabled>Bir Oyun Seçin (Alış TL)</option>
                                                    @foreach($muveGames as $u)
                                                        <? $tt = $u->prices; $alis= $tt['0']->royalty_min_value; ?>
                                                        @if(DB::table('muve_games')->where('muveId', $u->id)->whereNull('deleted_at')->count() < 1)
                                                            <option value="{{$u->id}}" alis="{{$alis}}" data-code="{{$u->code}}" data-title="{{$u->title}}" data-price="{{json_encode($u->prices)}}">{{$u->title}} ({{number_format($alis,2)}} TL)</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <input name="muveCode" type="hidden" class="form-control" id="1">
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label for="1">@lang('admin.oyunBasligi')</label>
                                                <input name="title" type="text" class="form-control" id="title"
                                                       placeholder="@lang('admin.oyunBasligi')" required>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label for="steamId">Steam ID</label>
                                                <input name="steamId" type="number" class="form-control" id="steamId"
                                                       placeholder="Steam Id" value="0">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label for="desteklenenDiller">Desteklenen Diller</label>
                                                <textarea rows="3" name="desteklenenDiller" id="desteklenenDiller"
                                                          class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label for="windowsGer">Windows Gereksinimler</label>
                                                <textarea rows="3" name="windowsGer" id="windowsGer"
                                                          class="form-control"></textarea>

                                            </div>
                                            <div class="form-group">
                                                <label for="windowsSup">Windows da var mı?</label>
                                                <input type="checkbox" id="windowsSup" name="windowsSup" value="1">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label for="macGer">Mac Gereksinimler</label>
                                                <textarea rows="3" name="macGer" id="macGer"
                                                          class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="macSup">Mac de var mı?</label>
                                                <input type="checkbox" id="macSup" name="macSup" value="1">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label for="linuxGer">Linux Gereksinimler</label>
                                                <textarea rows="3" name="linuxGer" id="linuxGer"
                                                          class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="linuxSup">Linux da var mı?</label>
                                                <input type="checkbox" id="linuxSup" name="linuxSup" value="1">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label for="gelistiriciler">Geliştiriciler (Her bir satıra bir
                                                    tane)</label>
                                                <textarea rows="3" name="gelistiriciler" id="gelistiriciler"
                                                          class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label for="kategoriler">Kategoriler (Her bir satıra bir tane)</label>
                                                <textarea rows="3" name="kategoriler" id="kategoriler"
                                                          class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label for="metaScor">Meta Critic Skoru</label>
                                                <input name="metaScor" type="text" class="form-control" id="metaScor"
                                                       placeholder="Meta Critic Skoru">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label for="metaLink">Meta Critic Linki</label>
                                                <input name="metaLink" type="text" class="form-control" id="metaLink"
                                                       placeholder="Meta Critic Linki">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label for="yayinTarihi">Yayın Tarihi</label>
                                                <input name="yayinTarihi" type="text" class="form-control"
                                                       id="yayinTarihi"
                                                       placeholder="Yayın Tarihi">
                                            </div>
                                        </div>

                                        <?php
                                        $sonOyun = DB::table('muve_games')->whereNull('deleted_at')->orderBy('sira', 'desc')->first();
                                        if (!$sonOyun) {
                                            $sonOyun = 0;
                                        } else {
                                            $sonOyun = $sonOyun->sira;
                                        }
                                        ?>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label for="sira">Oyun Sırası</label>
                                                <input name="sira" type="number" step="1" class="form-control" id="sira"
                                                       placeholder="Sıra" value="{{$sonOyun + 1}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="shortDesc">Kısa Açıklama</label>
                                                <textarea class="form-control" rows="5" placeholder="Kısa Açıklama"
                                                          id="shortDesc"
                                                          name="shortDesc"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="3">@lang('admin.oyunMetni')</label>
                                                <textarea class="editorText0" placeholder="@lang('admin.oyunMetni')"
                                                          id="3"
                                                          name="text"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="4">@lang('admin.oyunResmi')</label>
                                                <div id="dropArea">
                                                    <input name="image" type="file" id="4" class="dropify"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="arkaplan">Arkaplan Resmi</label>
                                                <div id="dropArea2">
                                                    <input name="arkaplan" type="file" id="arkaplan" class="dropify"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="imageText">
                                        <input type="hidden" name="arkaplanText">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="galeri">Resim Galeri</label>
                                                <textarea rows="3" name="galeri" id="galeri"
                                                          class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="galeriVideo">Video Galeri (.webm / .mp4)</label>
                                                <textarea rows="3" name="galeriVideo" id="galeriVideo"
                                                          class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <table class="table mb-5" id="fiyatlarTable" style="display: none;">
                                                <thead>
                                                <tr>
                                                    <th>Ülke</th>
                                                    <th>Para Birimi</th>
                                                    <th>Fiyat</th>
                                                    <th>Seçim</th>
                                                </tr>
                                                </thead>
                                                <tbody id="fiyatlarTbody" class="text-left">
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php /*
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Oyun İkonu</label>
                                            <input name="icon" type="file" class="dropify"
                                                   accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Oyun İkonu 2</label>
                                            <input name="icon_2" type="file" class="dropify"
                                                   accept="image/*">
                                        </div>
                                    </div> */ ?>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="alis" id="alis">
                                    <input type="hidden" name="country">
                                    <input type="hidden" name="currency">
                                    <button type="button" class="btn btn-outline-secondary waves-effect"
                                            data-dismiss="modal">@lang('admin.kapat')</button>
                                    <button type="submit"
                                            class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                </div>
                            </form>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            @endif
        </div>
    </div>
    <div class="row mt-3">
        <div class="table-responsive">
            {{view('back.pages.muve.table')}}
        </div>
    </div>
@endsection
@section('js')
    <script>
        function fiyatHesapla(a) {
            let gelen = a.value;
            let fiyatBilgiler = $(".fiyatBilgiler");
            let closest = $(a.closest(".row")).find(".fiyatBilgiler");
            let bilgiler = [];
            closest.each(function () {
                bilgiler.push(this.value);
            });
            $.get("{{route('currencyConverter')}}?price=" + bilgiler[0] + "&currency=" + bilgiler[1], function (data) {
                $(a.closest(".row")).find("input[name='tryKarsiligi']").val(data);
            });
        }

        var baslikturu = $(".baslikturu").val();
        if (baslikturu == 1) {
            $(".bt").removeClass("col-md-4");
            $(".bt").addClass("col-md-3");
            $("#pazaryeri").css("display", "block");
            $("#epin").css("display", "none");
        } else if (baslikturu == 2) {
            $(".bt").removeClass("col-md-4");
            $(".bt").addClass("col-md-3");
            $("#epin").css("display", "block");
            $("#pazaryeri").css("display", "none");
        } else {
            $(".bt").addClass("col-md-4");
            $(".bt").removeClass("col-md-3");
            $("#epin").css("display", "none");
            $("#pazaryeri").css("display", "none");
        }
        $(".baslikturu").change(function () {
                baslikturu = $(".baslikturu").val();
                if (baslikturu == 1) {
                    $(".bt").removeClass("col-md-4");
                    $(".bt").addClass("col-md-3");
                    $("#pazaryeri").css("display", "block");
                    $("#epin").css("display", "none");
                } else if (baslikturu == 2) {
                    $(".bt").removeClass("col-md-4");
                    $(".bt").addClass("col-md-3");
                    $("#epin").css("display", "block");
                    $("#pazaryeri").css("display", "none");
                } else {
                    $(".bt").addClass("col-md-4");
                    $(".bt").removeClass("col-md-3");
                    $("#epin").css("display", "none");
                    $("#pazaryeri").css("display", "none");
                }
            }
        );
    </script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/select2/select2.min.js')}}"></script>
    <script>
        $(".select2").select2({
            width: '100%'
        });
    </script>
    <script>
        $(function () {

            var imageArea = $('#4').dropify({
                height: "300",
                allowedFileExtensions: ["png", "jpg", "jpeg", "gif"],
                messages: {
                    default: '{{__('admin.dropifyResimSec')}}',
                    replace: '{{__('admin.dropifyDegistir')}}',
                    remove: '{{__('admin.dropifySil')}}',
                    error: '{{__('admin.dropifyHata')}}',
                }
            });
            var imageArea2 = $('#arkaplan').dropify({
                height: "300",
                allowedFileExtensions: ["png", "jpg", "jpeg", "gif"],
                messages: {
                    default: '{{__('admin.dropifyResimSec')}}',
                    replace: '{{__('admin.dropifyDegistir')}}',
                    remove: '{{__('admin.dropifySil')}}',
                    error: '{{__('admin.dropifyHata')}}',
                }
            });
            $('#steamId').on('input', function (e) {
                $.get("{{route('muve_oyun_detaylari_ajax', [], false)}}?id=" + $("#steamId").val(), function (data) {
                    if (data) {
                        let sira = $("#sira").val();
                        let muveId = $("#muveId").val();
                        $(':input', '#yeniEkle')
                            .not(':button, :submit, :reset, :hidden, :radio, :selected')
                            .val('')
                            .prop('checked', false);
                        //$('#yeniEkle').trigger("reset");
                        $("input[id='title']").val(data.name);
                        $("input[id='steamId']").val(data.steam_appid);
                        $("input[id='sira']").val(sira);
                        $("select[id='muveId']").val(muveId);
                        $("textarea[id='desteklenenDiller']").val(data.supported_languages);
                        $("textarea[id='windowsGer']").val(data.pc_requirements.minimum);
                        $("textarea[id='macGer']").val(data.mac_requirements.minimum);
                        $("textarea[id='linuxGer']").val(data.linux_requirements.minimum);
                        for (let i in data.developers) {
                            let gelistiriciler = $("textarea[id='gelistiriciler']").val() + data.developers[i];
                            $("textarea[id='gelistiriciler']").val(gelistiriciler);
                            if (i < data.developers.length) {
                                $("textarea[id='gelistiriciler']").val(gelistiriciler + '\n');
                            }
                        }
                        for (let i in data.genres) {
                            let kategoriler = $("textarea[id='kategoriler']").val() + data.genres[i].description;
                            $("textarea[id='kategoriler']").val(kategoriler);
                            if (i < data.genres.length) {
                                $("textarea[id='kategoriler']").val(kategoriler + '\n');
                            }
                        }
                        if (data.platforms.windows) {
                            $("input[id='windowsSup']").prop('checked', true);
                            $("input[id='windowsSup']").prop('value', 1);
                        }
                        if (data.platforms.mac) {
                            $("input[id='macSup']").prop('checked', true);
                            $("input[id='macSup']").prop('value', 1);
                        }
                        if (data.platforms.linux) {
                            $("input[id='linuxSup']").prop('checked', true);
                            $("input[id='linuxSup']").prop('value', 1);
                        }
                        if (data.metacritic) {
                            $("input[id='metaScor']").val(data.metacritic.score);
                            $("input[id='metaLink']").val(data.metacritic.url);
                        }

                        $("input[id='yayinTarihi']").val(data.release_date.date);
                        $("textarea[id='shortDesc']").val(data.short_description);


                        /*
                        Açıklama
                         */
                        var editor = tinymce.get('3');
                        editor.setContent(data.detailed_description);

                        /*
                        Resim
                         */
                        let imageNew = data.header_image;
                        imageNew = imageNew.split('?');
                        imageNew = imageNew[0];
                        drDestroy = imageArea.data('dropify')
                        if (drDestroy) {
                            drDestroy.destroy();
                        }
                        $("#4").remove();
                        let imageAreaNew = document.createElement("INPUT")
                        imageAreaNew.setAttribute('class', 'dropify');
                        imageAreaNew.setAttribute("data-height", 300);
                        imageAreaNew.setAttribute("data-default-file", imageNew);
                        imageAreaNew.setAttribute("name", "image");
                        imageAreaNew.setAttribute("type", "file");
                        imageAreaNew.setAttribute("id", "4");
                        imageAreaNew.setAttribute("accept", "image/*");
                        $("#dropArea").html(imageAreaNew.outerHTML);
                        $('#4').dropify();
                        $("input[name='imageText']").val(imageNew);

                        /*
                        Arkaplan
                         */
                        let imageNew2 = data.background_raw;
                        imageNew2 = imageNew2.split('?');
                        imageNew2 = imageNew2[0];
                        drDestroy2 = imageArea2.data('dropify')
                        if (drDestroy2) {
                            drDestroy2.destroy();
                        }
                        $("#arkaplan").remove();
                        let imageAreaNew2 = document.createElement("INPUT")
                        imageAreaNew2.setAttribute('class', 'dropify');
                        imageAreaNew2.setAttribute("data-height", 300);
                        imageAreaNew2.setAttribute("data-default-file", imageNew2);
                        imageAreaNew2.setAttribute("name", "arkaplan");
                        imageAreaNew2.setAttribute("type", "file");
                        imageAreaNew2.setAttribute("id", "arkaplan");
                        imageAreaNew2.setAttribute("accept", "image/*");
                        $("#dropArea2").html(imageAreaNew2.outerHTML);
                        $('#arkaplan').dropify();
                        $("input[id='arkaplanText']").val(imageNew2);

                        /*
                        Galeri
                         */
                        for (let i in data.screenshots) {
                            let galeri = $("textarea[id='galeri']").val() + data.screenshots[i].path_thumbnail.split('?')[0];
                            $("textarea[id='galeri']").val(galeri);
                            if (i < data.screenshots.length) {
                                $("textarea[id='galeri']").val(galeri + '\n');
                            }
                        }

                        /*
                        Galeri Video
                         */
                        for (let i in data.movies) {
                            let galeriVideo = $("textarea[id='galeriVideo']").val() + data.movies[i].webm.max.split('?')[0];
                            $("textarea[id='galeriVideo']").val(galeriVideo);
                            if (i < data.movies.length) {
                                $("textarea[id='galeriVideo']").val(galeriVideo + '\n');
                            }
                        }
                    }
                });
            });

            $(document).on("change", "select[name='muveId']", function (e) {
                $("#alis").val($('#muveId option:selected').attr('alis'));
                let code = e.target[e.target.selectedIndex].attributes["data-code"].value;
                let title = e.target[e.target.selectedIndex].attributes["data-title"].value;
                let prices = JSON.parse(e.target[e.target.selectedIndex].attributes["data-price"].value);

                $("input[id='muveCode']").val(code);
                $("input[id='title']").val(title);
                $("#fiyatlarTable").show();
                var table = document.getElementById("fiyatlarTbody");
                table.innerHTML = '';
                for (let i in prices) {
                    var x = document.createElement("INPUT");
                    x.setAttribute("type", "radio");
                    x.setAttribute("name", "fiyat");
                    x.setAttribute("class", "form-control");
                    x.setAttribute("id", "fiyat" + i);
                    x.setAttribute("autocomplete", "off");
                    x.setAttribute("data-country", prices[i].country);
                    x.setAttribute("data-currency", prices[i].currency);
                    //x.setAttribute("value", prices[i].price);
                    x.value = prices[i].price;
                    var row = table.insertRow(0);
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);
                    var cell4 = row.insertCell(3);
                    cell1.innerHTML = prices[i].country;
                    cell2.innerHTML = prices[i].currency;
                    cell3.innerHTML = "("+ prices[i].royalty_min_value.toFixed(2)+ ") / " + prices[i].price + " " + prices[i].currency;
                    cell4.innerHTML = x.outerHTML;
                }
            });

            $(document).on("change", "input[id='fiyat']", function (e) {
                let country = e.target.dataset["country"];
                let currency = e.target.dataset["currency"];
                $("input[id='country']").val(country);
                $("input[id='currency']").val(currency);

            });


        });
    </script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/tinymce/tinymce.min.js')}}"></script>

    <script type="text/javascript">
        function editorStart(gelen) {
            if (gelen == 0) {
                var modalName = ".ekle";
            } else if (gelen == 999999) {
                var modalName = ".baslik";
            } else {
                var modalName = ".duzenle" + gelen;
            }


            var selectorName = ".editorText" + gelen;
            $(modalName).on('shown.bs.modal', function () {
                tinymce.init({
                    selector: selectorName,
                    theme: "modern",
                    language: '{{getLang()}}',
                    height: 300,
                    plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "save table contextmenu directionality emoticons template paste textcolor"
                    ],
                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
                    style_formats: [
                        {title: 'Bold text', inline: 'b'},
                        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                        {title: 'Example 1', inline: 'span', classes: 'example1'},
                        {title: 'Example 2', inline: 'span', classes: 'example2'},
                        {title: 'Table styles'},
                        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                    ]
                });
                $('.dropify').dropify({
                    height: "300",
                    allowedFileExtensions: ["png", "jpg", "jpeg", "gif"],
                    messages: {
                        default: '{{__('admin.dropifyResimSec')}}',
                        replace: '{{__('admin.dropifyDegistir')}}',
                        remove: '{{__('admin.dropifySil')}}',
                        error: '{{__('admin.dropifyHata')}}',
                    }
                });
            })
        }

        $('.modal').on('hide.bs.modal', function () {
            var modalId = "#" + this.dataset.id;
            // scope the selector to the modal so you remove any editor on the page underneath.
            tinymce.remove();
            //tinymce.destroy(modalId + ' textarea');
        });


        $(document).ready(function () {

            setTimeout(function () {
                if ($("#3").length > 0) {

                }
            }, 100);


            $('#datatable thead tr').clone(true).appendTo('#datatable thead');
            $('#datatable thead tr:eq(1) th').each(function (i) {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="' + title + '" />');
                $('input', this).on('keyup change', function () {
                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });
            });
            $('#datatable input[type="text"]').css(
                {'width': '100%', 'display': 'inline-block'}
            );
            var table = $('#datatable').DataTable({
                columnDefs: [{orderable: false, targets: [5]}],
                pageLength: 50,
                orderCellsTop: true,
                fixedHeader: true,
                lengthChange: false,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: '{{getPageTitle(getPage(), getLang())}} - Excel',
                        text: '<i class="far fa-file-excel"></i>',
                        className: 'btn btn-outline-success',
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        title: '{{getPageTitle(getPage(), getLang())}} - Csv',
                        text: '<i class="fas fa-file-csv"></i>',
                        className: 'btn btn-outline-info',
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: '{{getPageTitle(getPage(), getLang())}} - Pdf',
                        className: 'btn btn-outline-danger',
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary')
                        },
                        text: '<i class="far fa-file-pdf"></i>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },

                ],
                "order": [[3, "desc"]],
                "language": {
                    "decimal": "",
                    "emptyTable": "{{__('admin.hic-veri-yok')}}",
                    "info": "{{__('admin.adet-veri-gosterim', ['TOTAL' => '_TOTAL_', 'START' => '_START_', 'END' => '_END_'])}}",
                    "infoEmpty": "{{__('admin.sifir-veri-var')}}",
                    "infoFiltered": "{{__('admin.adet-veri-araniyor', ['MAX' => '_MAX_'])}}",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "{{__('admin.veri-gosteriliyor', ['MENU' => '_MENU_'])}}",
                    "loadingRecords": "{{__('admin.yukleniyor')}}",
                    "processing": "{{__('admin.isleniyor')}}",
                    "search": "{{__('admin.ara')}}",
                    "zeroRecords": "{{__('admin.eslesen-veri-bulunamadi')}}",
                    "paginate": {
                        "first": "{{__('admin.ilk')}}",
                        "last": "{{__('admin.son')}}",
                        "next": "{{__('admin.sonraki')}}",
                        "previous": "{{__('admin.onceki')}}"
                    },
                }
            });
            table.buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(1)');
            $('#datatable_filter').css(
                {'display': 'none'}
            );


            /*$('#datatable').DataTable({
                columnDefs: [{orderable: false, targets: [6]}],
                pageLength: 10,
                "processing": true,
                "deferLoading": 57,
                "order": [[4, "desc"]],
                "language": {
                    "decimal": "",
                    "emptyTable": "{{__('admin.hic-veri-yok')}}",
                    "info": "{{__('admin.adet-veri-gosterim', ['TOTAL' => '_TOTAL_', 'START' => '_START_', 'END' => '_END_'])}}",
                    "infoEmpty": "{{__('admin.sifir-veri-var')}}",
                    "infoFiltered": "{{__('admin.adet-veri-araniyor', ['MAX' => '_MAX_'])}}",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "{{__('admin.veri-gosteriliyor', ['MENU' => '_MENU_'])}}",
                    "loadingRecords": "{{__('admin.yukleniyor')}}",
                    "processing": "{{__('admin.isleniyor')}}",
                    "search": "{{__('admin.ara')}}",
                    "zeroRecords": "{{__('admin.eselsen-veri-bulunamadi')}}",
                    "paginate": {
                        "first": "{{__('admin.ilk')}}",
                        "last": "{{__('admin.son')}}",
                        "next": "{{__('admin.sonraki')}}",
                        "previous": "{{__('admin.onceki')}}"
                    },
                }
            });*/
        });
    </script>
    <script>
        function status(id, table, event) {
            var icon;
            var buton;
            if (event.target.children[0] == undefined) { //ikonu verir
                icon = event.target;
                buton = event.target.offsetParent;
            } else { //butonu verir
                icon = event.target.children[0];
                buton = event.target;
            }
            var statusText = buton.parentElement.parentElement;
            statusText = $(statusText).find('td#statusText');
            $.get("{{route('setStatus')}}", {table: table, id: id});
            $(buton).prop('disabled', true);
            $(buton).css('cursor', 'not-allowed');
            $(icon).removeClass("mdi-eye");
            $(icon).addClass("mdi-spin mdi-loading");
            if ($(buton).hasClass("btn-outline-warning")) {
                $(buton).removeClass("btn-outline-warning");
                $(buton).addClass("btn-outline-primary");
                var cevir = "success";
            } else {
                $(buton).removeClass("btn-outline-success");
                $(buton).addClass("btn-outline-primary");
                var cevir = "warning";
            }
            setTimeout(function () {
                $(buton).css('cursor', 'pointer');
                $(buton).prop('disabled', false);
                $(icon).removeClass("mdi-spin mdi-loading");
                $(icon).addClass("mdi-eye");
                $(buton).removeClass("btn-outline-primary");
                if (cevir == "success") {
                    $(buton).addClass("btn-outline-success");
                } else {
                    $(buton).addClass("btn-outline-warning");
                }

                if ($(statusText)[0].innerText == "{{__('admin.aktif')}}") {
                    $(statusText)[0].innerHTML = "{!! getDataStatus(0) !!}";
                } else {
                    $(statusText)[0].innerHTML = "{!! getDataStatus(1) !!}";
                }
            }, 2000);
        }
    </script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
    <script>
        function deleteContent(table, id) {
            swal.fire({
                title: "{{__("admin.silme")}}",
                text: "{{__("admin.silmeText")}}",
                icon: "info",
                type: 'error',
                showCancelButton: true,
                confirmButtonText: '{{__("admin.onayliyorum")}}',
                cancelButtonText: '{{__("admin.vazgec")}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get("{{route('deleteContent')}}", {table: table, id: id});
                    swal.fire(
                        '{{__("admin.basarili")}}',
                        '{{__("admin.basariliMetin")}}',
                        'success'
                    )
                    setTimeout(function () {
                        location.reload();
                    }, 2000);

                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire(
                        '{{__("admin.iptal-edildi")}}',
                        '',
                        'error'
                    )
                }
            })
        }

    </script>

@endsection

