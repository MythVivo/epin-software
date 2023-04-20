@if (DB::table('games_titles_special')->where('games_titles', $pazar)->count() > 0)
    <div class="row" data-buton="1">
        <div class="col-12">
            <div class="row mt-3">
                <div class="col-md-4 mb-4">
                    <label for="title" class="form-label">Başlık</label>
                    <input type="text" class="form-control zorunlu" name="title" required placeholder="Başlık"
                        id="title">
                </div>
                <div class="col-md-4 mb-4">
                    <label for="price" class="form-label">Fiyat</label>
                    <input onkeyup="komisyonHesapla(this)" type="number" step="0.01" class="form-control zorunlu"
                        name="price" required placeholder="Fiyat" id="price">
                </div>
                <div class="col-md-4 mb-4">
                    <?php
                    if ($pazar == 397 || $pazar == 413) {
                        $komisyon = 5;
                    } else {
                        $komisyon = findUserKomisyon(Auth::user()->id);
                    }
                    ?>
                    <label for="net" class="form-label">Hesaba Geçecek Tutar.</label><br>
                    <div class="input-group">
                        <span class="input-group-text style-input" id="basic-addon1">{{ $komisyon }}%
                            komisyon</span>
                        <input id="net" type="number" name="kazanc" step="0.01"
                            class="form-control style-input-pd" readonly><span
                            class="input-group-text style-input">₺</span>
                    </div>
                </div>
                @foreach (DB::table('games_titles_features')->where('game_title', $pazar)->whereNull('deleted_at')->get() as $p)
                    <div class="col-md-4 mb-4">
                        <label for="{{ $p->id }}" class="form-label">{{ $p->title }}.</label><br>
                        @if ($p->type == 1)
                            <select class="select2 w-100 zorunlu" name="{{ Str::slug($p->title) }}">
                                <option value="" selected>Belirtilmemiş</option>
                                @foreach (json_decode($p->value) as $deger)
                                    <option value="{{ Str::slug($deger) }}">{{ $deger }}</option>
                                @endforeach
                            </select>
                        @endif
                        @if ($p->type == 2)
                            <select class="select2 w-100 zorunlu" name="{{ Str::slug($p->title) }}" multiple>
                                <option value="">Belirtilmemiş</option>
                                @foreach (json_decode($p->value) as $deger)
                                    <option value="{{ Str::slug($deger) }}">{{ $deger }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                @endforeach
                <div class="col-md-4 mb-4">
                    <label for="image" class="form-label">Resim</label>
                    <input type="file" class="form-control zorunlu" name="image" required id="image"
                        accept="image/*">
                </div>

                <div class="col-md-4">
                    <label for="teslimat" class="form-label">Teslimat Saati</label>

                    <div class="d-flex flex-row mb-2">
                        <div class="d-flex flex-row dates_cont">
                            <select class="date_f s2dates" style="width: 100px">
                                <option value="">Seçiniz</option>
                                @for ($i = 0; $i < 24; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) . ':00' }}">
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
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) . ':00' }}">
                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) . ':00' }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <label for="7_24" class="form-check form-switch" style="margin-left: 25px">
                            <input name="7_24" id="7_24" type="checkbox" role="switch">
                            <div class="switcher"><span><i></i></span></div>
                            <span class="form-label">24 Saat
                                <col>
                            </span>
                        </label>
                    </div>
                    <input class="form-control style-input d-none zorunlu" id="teslimatSaati" name="teslimat"
                        placeholder="Teslimat Saati" type="text" >

                    <script>
                        $(document).ready(function() {
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
                                $("#teslimatSaati").trigger('change');
                            });
                            $(".s2dates").on('change', function(event) {
                                if (!!$(".date_f").val() && !!$(".date_t").val()) {
                                    $("#teslimatSaati").val($(".date_f").val() + " - " + $(".date_t").val() +
                                        ' saatleri arasında');
                                } else {
                                    $("#teslimatSaati").val("");
                                }
                                $("#teslimatSaati").trigger('change');
                            });
                        });
                    </script>

                </div>

                <div class="col-md-4" style="display: flex; flex-direction: row-reverse; justify-content: flex-start; align-items: center">
                    <select name="sure" id="sure" class="form-control" style="width: 80px">
                        @for($f=168;$f>0;$f--)
                            <? if($f % 24==0) {$g=$f/24; $g.=" Gün";} else {$g=$f. " Saat";} ?>
                            <option value="{{$f}}">{{$g}} </option>
                        @endfor
                    </select>
                    <label class="form-label me-2">Yayın Süresi</label><br>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="text" class="form-label">Açıklama</label>
                    <textarea class="form-control" name="text" id="text" style="height: 120px"></textarea>
                </div>
            </div>
        </div>
    </div>
    <script>
        let req_check = false
        $(".zorunlu").on('change', function(e) {
            let z_data = $(".zorunlu");
            z_data.each(function(index, value) {
                if (value.value == "") {
                    req_check = false
                    return false;
                } else {
                    req_check = true
                }
            });

            $(".btn-yayinla").prop("disabled", !req_check)

        });


        function komisyonHesapla(a) {
            gelen = a.value;
            komisyon = {{ $komisyon }};
            a.parentElement.parentElement.children[2].children[2].children[1].value = gelen - (gelen * komisyon / 100);
        }

        function komisyonHesaplaAlis(a) {
            gelen = a.value;
            komisyon = 10;
            a.parentElement.parentElement.children[2].children[2].children[1].value = gelen * komisyon / 100;
        }

        $("#item").change(function() {
            pazar = $("#pazar").val();
            item = $("#item").val();
            alert(pazar);
            $.ajax({
                url: "?pazar=" + pazar + "&item=" + item +"&server="+$("select[name='sunucu']").val(),
                success: function(result) {
                    $(".area").html(result);
                    $('.select2').select2();
                }
            });
        });
    </script>
@else
    <div class="col-12">
        <div class="row" style="display: flex; flex-direction: row; justify-content: left ;">
            <div class="col-md-5 select-element ykp_del">
                <label for="sunucu" class="form-label">Sunucu Seçin</label>
                <select class="select2" name="sunucu" style="width: 100%;" required>
                    <option disabled selected>Sunucu Seçin</option>
                    @foreach (json_decode(
        DB::table('games_titles_features')->where('game_title', $pazar)->where('title', 'Sunucu')->whereNull('deleted_at')->first()->value,
    ) as $values)
                        <option value="{{ Str::slug($values) }}">{{ $values }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-5 select-element itemsec_ykp">
                <label for="item" class="form-label">İtem</label>
                <select class="js-data-example-ajax w-100" name="itemSec" required>
                    <option selected disabled>İtem Türünü Seçin</option>
                </select>
            </div>
            <div class='select-element ykp1' style="display: none; margin-top: 9px"><select class='js-data-example-ajax w-100' name='item_set' id='it_ek' style='width: 20em' required><option selected disabled>Set için item Ekle</option></select><button class='btn btn-success m-3 set_but'>Ekle</button></div>

            <div class="col-md-2 d-flex justify-content-sm-start justify-content-md-end align-items-end ykp_del">
                <button disabled type="button" class="btn-inline color-blue me-2 setbut">Set İlan</button>
                <button disabled type="button" class="itemGet btn-inline color-blue">Tek İlan</button>
            </div>

        </div>
        <div class="col-md-12 mt-2 form-label ykp_del">
            **Birden çok ilan eklemek için seçim yaptıktan sonra Ekle butonuna basınız. Bu işlemi tekrarlayarak çoklu
            ilan oluşturabilirsiniz.
        </div>
    </div>
    <div class="row items mt-5"></div>

    <div class="row items_set mt-2" style="display: none">
        <div class="col-md-12">
            <div class="card">
                <h5 style=" color: aliceblue; text-align: center; ">SET İlan Ekleme</h5><h6 id="itad" style="color: aliceblue; text-align: center; "></h6>
                <div class="row g-0">
                    <div class="col-md-8 setit_ykp" style="display: flex;flex-wrap: wrap;border: solid;border-radius: 20px;padding-top: 15px;"></div>
                    <div class="col-md-4 form_ykp" style="border: solid;border-radius: 20px;"></div>
                </div>
            </div>
        </div>
        <input type="hidden" id="sunucu_ykp" name="sunucu">
    </div>

    <script>
        $(".zorunlu").on('change', function(e) {
            let z_data = $(".zorunlu");
            console.log()
            z_data

        });
        if($('#sozlesme').length==1){$('.setbut').remove();}

        $('.setbut').click(function(){
            $('#sunucu_ykp').val($("select[name='sunucu']").val());
            pazar = $("#pazar").val();
            item = $("select[name='itemSec']").val();
            $('.itemsec_ykp').remove();
            $('.ykp_del').remove();
            $(".items").remove();
            $('.ykp1').addClass('col-auto').show();

            $.get("?",{pazar: pazar, item: item, server : $("select[name='sunucu']").val()},function ($result){
                var $t=$($result).find('.col-md-6:eq(1)').clone();
                $('.form_ykp').html($t);
                $('.form_ykp').find('.col-md-6').removeClass('col-md-6');
                $('.items_set').addClass('items');
                $('.items_set').show();
            });
        })




        $("select[name='itemSec']").on('change', function(e) {
            let sunucuval = $("select[name='sunucu']").val()
            if (!isNaN(Number(e.target.value)) && sunucuval != null) {
                $(".itemGet ").prop('disabled', false);
            } else {
                $(".itemGet ").prop('disabled', true);
            }

        });
        $("select[name='sunucu']").on('change', function(e) {
            let itemval = $("select[name='itemSec']").val()
            let sunucuval = $(this).val()

            if (sunucuval != null) {$('.setbut').prop('disabled', false);} else {$('.setbut').prop('disabled', true);}

            if (!isNaN(Number(itemval)) && itemval != null && sunucuval != null) {
                $(".itemGet ").prop('disabled', false)
            } else {
                $(".itemGet ").prop('disabled', true);
            }

        });

        $(".itemGet").on('click', function(e) {
            $('.setbut').remove();
            $('.items_set').remove();
            pazar = $("#pazar").val();
            item = $("select[name='itemSec']").val();
            $.ajax({
                url: "?pazar=" + pazar + "&item=" + item +"&server="+$("select[name='sunucu']").val(),
                success: function(result) {

                    $("select[name='itemSec']")[0].selectedIndex = 0
                    $("select[name='itemSec']").select2()
                    $(".itemGet").prop("disabled", true)
                    $(".btn-yayinla").prop("disabled", false)
                    $(".items").prepend(result);
                    //baslikEkle();
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
                            url: '?pazar=' + pazar + '&itemler=1&server='+$("select[name='sunucu']").val(),
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

        });

        function komisyonHesapla(a) {
            gelen = a.value;
            komisyon = {{ findUserKomisyon(Auth::user()->id) }};
            a.parentElement.parentElement.children[1].children[2].children[1].value = gelen - (gelen * komisyon / 100);
        }

        function komisyonHesaplaAlis(a) {
            gelen = a.value;
            komisyon = 10;
            a.parentElement.parentElement.children[1].children[2].children[1].value = gelen * komisyon / 100;
        }

        function itemGet(a) {
            pazar = $("#pazar").val();
            item = a.value;
            $.ajax({
                url: "?pazar=" + pazar + "&item=" + item,
                success: function(result) {
                    $(".items").append(result);
                    //baslikEkle();
                    $('.js-data-example-ajax').select2({
                        language: {
                            inputTooShort: function() {
                                return "Lütfen en az 3 karakter girin";
                            },
                            searching: function() {
                                return "Aranıyor..";
                            },
                            noResults: function() {
                                return "Sonuç yok.";
                            }
                        },
                        ajax: {
                            url: '?pazar=' + pazar + '&itemler=1&server='+$("select[name='sunucu']").val(),
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
        };

        /*function baslikEkle() {
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
        }*/

        function silItem(a) {

            a.parentElement.parentElement.parentElement.parentElement.parentElement.remove();

            if ($(".row.items>div").length < 1) {

                $(".btn-yayinla").prop("disabled", true)


            }
            /*
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
            $('#3').val($('#3').val().replace(", " + baslik, "")) */
        }
    </script>

@endif
