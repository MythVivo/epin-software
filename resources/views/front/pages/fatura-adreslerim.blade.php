<?php
if (isset($_GET['sil']) and isset($_GET['adres'])) {
    DB::table('fatura_adresleri')->where('id', $_GET['sil'])->update([
        'deleted_at' => date('YmdHis'),
    ]);
    header('Location: ?okey');
    die();
    exit();
}
if (isset($_GET['il'])) {
    $ilceler = DB::table('ilceler')->where('il_id', $_GET['il'])->get();
    echo json_encode($ilceler);
    die();
}

?>
@extends('front.layouts.app')
@section('css')
    <style>
        .form-check-input, .form-select:focus {
            box-shadow: unset !important;
        }

        .card-wrapper {
            position: relative;
            filter: blur(3px);
        }
        .modal {
            z-index: 9999;
        }

    </style>
@endsection
@section('body')
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">
                @include('front.modules.user-menu')
                <div class="col-md-9">
                @if(session('success'))
                    <!--Mesaj bildirimi--->
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fas fa-check me-2"></i>
                            <div>{{session('success')}}</div>
                        </div>
                        <!--Mesaj bildirim END--->
                @endif
                @if(isset($_GET['okey']))
                    <!--Mesaj bildirimi--->
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fas fa-check me-2"></i>
                            <div>{{__('general.mesaj-10')}}</div>
                        </div>
                        <!--Mesaj bildirim END--->
                @endif
                @if(session('error'))
                    <!--Mesaj bildirimi--->
                        <div class="alert alert-error d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>{{session('error')}}</div>
                        </div>
                        <!--Mesaj bildirim END--->
                @endif
                @if ($errors->any())
                    <!--Hata bildirimi--->
                        <div class="alert alert-error d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation"></i>
                            <p>@lang('general.hata-2')</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <!--Hata bildirim END--->
                @endif


                <!-- Modal -->
                    <div class="modal fade" id="faturaAdresiEkle" tabindex="-1" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <form method="post" class="needs-validation" novalidate autocomplete="off">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Yeni Fatura Adresi Ekle</h5>
                                        <label for="3" class="form-label ms-3 modal-title fs-5">Kurumsal</label>
                                        <input type="checkbox" name="bireysel_kurumsal" class="form-check-input style-input ms-1"
                                               id="3">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-sm-12 col-md-4">
                                                <label for="1" class="form-label">Adres İsmi (Örn.: Ev, İş)</label>
                                                <input type="text" name="adres_ismi" class="form-control style-input" id="1"
                                                       required>
                                                <div class="invalid-feedback">
                                                    Lütfen adresiniz için bir isim verin.
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-4">
                                                <label for="2" class="form-label">Ad Soyad</label>
                                                <input type="text" name="ad_soyad" class="form-control style-input" id="2" required>
                                                <div class="invalid-feedback">
                                                    Lütfen bu alanı doldurun.
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-4">
                                                <label for="4" class="form-label">T.C. No</label>
                                                <input type="number" name="tc_no" class="form-control style-input" min="10000000000"
                                                       max="99999999999" id="4" required>
                                                <div class="invalid-feedback">
                                                    Lütfen T.C. Kimlik Numaranızı girin.
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-2" style="display: none;">
                                                <label for="5" class="form-label">Vergi Dairesi</label>
                                                <input type="text" name="vergi_dairesi" class="form-control style-input" id="5">
                                                <div class="invalid-feedback">
                                                    Lütfen Vergi Dairesinin ismini girin.
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-2" style="display: none;">
                                                <label for="6" class="form-label">Vergi No</label>
                                                <input type="text" name="vergi_no" class="form-control style-input" id="6">
                                                <div class="invalid-feedback">
                                                    Lütfen Vergi Numaranızı girin.
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-4">
                                                <label for="9" class="form-label">Ülke</label>
                                                <select id="9" class="form-select style-input" size="5" name="ulke" required
                                                        readonly>
                                                    <option value="Türkiye" selected>Türkiye</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Lütfen bir ülke seçin.
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-4">
                                                <label for="7" class="form-label">İl</label>
                                                <select id="7" class="form-select" size="5" name="il" required>
                                                    <option disabled>Bir İl Seçin</option>
                                                    @foreach(DB::table('iller')->orderBy('id')->get() as $u)
                                                        <option value="{{$u->id}}">{{$u->il_adi}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Lütfen bir il seçin
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-4">
                                                <label for="8" class="form-label">İlçe</label>
                                                <select id="8" class="form-select" size="5" name="ilce" required>
                                                    <option disabled>Bir İl Seçin</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Lütfen bir ilçe seçin
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-4">
                                                <label for="10" class="form-label">Adres</label>
                                                <input type="text" name="adres" class="form-control style-input" id="10" required>
                                                <div class="invalid-feedback">
                                                    Lütfen adresinizi yazın.
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-4">
                                                <label for="11" class="form-label">Posta kodu</label>
                                                <input type="number" name="posta_kodu" class="form-control style-input" id="11"
                                                       required>
                                                <div class="invalid-feedback">
                                                    Lütfen geçerli bir posta kodu girin.
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-4">
                                                <label for="12" class="form-label">Cep Telefonu</label>
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text style-input" id="inputGroupPrepend">+90</span>
                                                    <input name="telefon" class="form-control style-input" id="12" pattern=".{15}"
                                                           required>
                                                    <div class="invalid-feedback">
                                                        Lütfen geçerli bir telefon numarası girin
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn-inline color-red" data-bs-dismiss="modal">Kapat
                                        </button>
                                        <button type="submit" class="btn-inline color-darkgreen">Kaydet</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    @php $fatura_adresleri = DB::table('fatura_adresleri')->where('user', Auth::user()->id)->whereNull('deleted_at'); @endphp
                    @if($fatura_adresleri->count() > 0)
                        <div class="row">
                            @foreach($fatura_adresleri->get() as $u)
                                <div class="col-md-4 mb-4">
                                    <div class="card adress-card">
                                        <div class="card-body">
                                            <h5 class="card-title">{{$u->adres_ismi}}</h5>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><span>Ad Soyad</span><i>:</i>{{$u->ad_soyad}}</li>
                                            <li class="list-group-item"><span>Telefon</span><i>:</i>{{$u->telefon}}</li>
                                            @if($u->bireysel_kurumsal == 1)
                                                <li class="list-group-item"><span>T.C.</span><i>:</i>{{$u->tc_no}}</li>
                                            @else
                                                <li class="list-group-item"><span>Firma V.D.</span><i>:</i>{{$u->vergi_dairesi}}
                                                    - {{$u->vergi_no}}</li>
                                            @endif
                                            <li class="list-group-item"><span>Adres</span><i>:</i>{{$u->adres}} - Posta Kodu
                                                : {{$u->posta_kodu}}</li>
                                            <li class="list-group-item">{{findIl($u->il)}} - {{findIlce($u->ilce)}}
                                                - {{$u->ulke}} </li>
                                        </ul>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-12 pt-2 pb-2">
                                                    <button class="btn-inline color-blue small border"
                                                            onclick="update({{$u->id}})"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#faturaAdresiDuzenle{{$u->id}}">Düzenle
                                                    </button>
                                                    <button class="btn-inline color-red border small confirm-btn"
                                                            confirm-data='?sil={{$u->id}}&adres'>Sil
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="faturaAdresiDuzenle{{$u->id}}" tabindex="-1"
                                     aria-labelledby="exampleModalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <form method="post" class="needs-validation" novalidate autocomplete="off">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">{{$u->adres_ismi}}
                                                        Adresini Düzenle</h5>
                                                    <label for="53"
                                                           class="form-label ms-3 modal-title fs-5">Kurumsal</label>
                                                    <input type="checkbox" name="bireysel_kurumsal"
                                                           class="form-check-input ms-1"
                                                           id="53" @if($u->bireysel_kurumsal == 2) checked
                                                           @endif onchange="update({{$u->id}})">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="{{$u->id}}">
                                                    @csrf
                                                    <div class="row g-3">
                                                        <div class="col-sm-12 col-md-4">
                                                            <label for="51" class="form-label">Adres İsmi (Örn.: Ev,
                                                                İş)</label>
                                                            <input type="text" name="adres_ismi" class="form-control style-input "
                                                                   id="51" value="{{$u->adres_ismi}}"
                                                                   required>
                                                            <div class="invalid-feedback">
                                                                Lütfen adresiniz için bir isim verin.
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-4">
                                                            <label for="52" class="form-label">Ad Soyad</label>
                                                            <input type="text" name="ad_soyad" class="form-control style-input"
                                                                   id="52" value="{{$u->ad_soyad}}" required>
                                                            <div class="invalid-feedback">
                                                                Lütfen bu alanı doldurun.
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-4">
                                                            <label for="54" class="form-label">T.C. No</label>
                                                            <input type="number" name="tc_no" class="form-control style-input"
                                                                   min="10000000000"
                                                                   max="99999999999" value="{{$u->tc_no}}" id="54"
                                                                   required>
                                                            <div class="invalid-feedback">
                                                                Lütfen T.C. Kimlik Numaranızı girin.
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-2" style="display: none;">
                                                            <label for="55" class="form-label">Vergi Dairesi</label>
                                                            <input type="text" name="vergi_dairesi" class="form-control style-input"
                                                                   id="55" value="{{$u->vergi_dairesi}}">
                                                            <div class="invalid-feedback">
                                                                Lütfen Vergi Dairesinin ismini girin.
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-2" style="display: none;">
                                                            <label for="56" class="form-label">Vergi No</label>
                                                            <input type="text" name="vergi_no" value="{{$u->vergi_no}}"
                                                                   class="form-control"
                                                                   id="56">
                                                            <div class="invalid-feedback">
                                                                Lütfen Vergi Numaranızı girin.
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-4">
                                                            <label for="59" class="form-label">Ülke</label>
                                                            <select id="59" class="form-select" size="5" name="ulke"
                                                                    required
                                                                    readonly>
                                                                <option value="Türkiye" selected>Türkiye</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Lütfen bir ülke seçin.
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-4">
                                                            <label for="57" class="form-label">İl</label>
                                                            <select id="57" class="form-select" size="5" name="il"
                                                                    required onchange="update({{$u->id}})">
                                                                <option disabled>Bir İl Seçin</option>
                                                                @foreach(DB::table('iller')->orderBy('id')->get() as $uu)
                                                                    <option value="{{$uu->id}}"
                                                                            @if($u->il == $uu->id) selected @endif>{{$uu->il_adi}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Lütfen bir il seçin
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-4">
                                                            <label for="58" class="form-label">İlçe</label>
                                                            <select id="58" data-default-ilce="{{$u->ilce}}"
                                                                    class="form-select" size="5" name="ilce"
                                                                    required>
                                                                @foreach(DB::table('ilceler')->where('il_id', $u->il)->orderBy('id')->get() as $uu)
                                                                    <option value="{{$uu->id}}"
                                                                            @if($u->ilce == $uu->id) selected @endif >{{$uu->ilce_adi}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Lütfen bir ilçe seçin
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-4">
                                                            <label for="60" class="form-label">Adres</label>
                                                            <input type="text" name="adres" value="{{$u->adres}}"
                                                                   class="form-control style-input" id="60"
                                                                   required>
                                                            <div class="invalid-feedback">
                                                                Lütfen adresinizi yazın.
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-4">
                                                            <label for="61" class="form-label">Posta kodu</label>
                                                            <input type="number" name="posta_kodu"
                                                                   value="{{$u->posta_kodu}}" class="form-control style-input"
                                                                   id="61"
                                                                   required>
                                                            <div class="invalid-feedback">
                                                                Lütfen geçerli bir posta kodu girin.
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-4">
                                                            <label for="62" class="form-label">Cep Telefonu</label>
                                                            <div class="input-group has-validation">
                                                                <span class="input-group-text style-input" id="inputGroupPrepend">+90</span>
                                                                <input name="telefon" value="{{$u->telefon}}"
                                                                       class="form-control style-input" id="62"
                                                                       pattern=".{15}"
                                                                       required>
                                                                <div class="invalid-feedback">
                                                                    Lütfen geçerli bir telefon numarası girin
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn-inline color-red"
                                                            data-bs-dismiss="modal">Kapat
                                                    </button>
                                                    <button type="submit" class="btn-inline color-darkgreen">Kaydet
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @endforeach

                            <div class="col-md-4 mb-4">
                                <div class="card adress-card prw">
                                    <div class="card-wrapper">
                                        <div class="card-body">
                                            <h5 class="card-title">Örnek</h5>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Ad Soyad : ------</li>
                                            <li class="list-group-item">Telefon : ------</li>
                                            <li class="list-group-item">T.C. : ------</li>
                                            <li class="list-group-item">Adres : ------ ------ ------ ------ - Posta Kodu
                                                : ------
                                            </li>
                                            <li class="list-group-item">------ - ------ - ------</li>
                                        </ul>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-12">
                                                    <button class="btn-inline color-blue small border"
                                                            type="button">Düzenle
                                                    </button>
                                                    <button class="btn-inline color-red small border" type="button">
                                                        Sil
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-img-overlay">
                                        <button class="btn-inline color-blue" data-bs-toggle="modal"
                                                data-bs-target="#faturaAdresiEkle">
                                            Yeni Adres Ekle
                                        </button>
                                    </div>


                                </div>

                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger  fade show d-flex align-items-center"
                             role="alert">
                            <i class="fas fa-exclamation-triangle me-3"></i>
                            <h5>
                                Henüz hesabınıza bir fatura adresi eklememişsiniz. Faturalarınız kişisel
                                olarak T.C. Kimlik Numaranız üzerinden kesilecektir.
                            </h5>

                            <button type="button" class="btn-inline color-red" data-bs-toggle="modal"
                                    data-bs-target="#faturaAdresiEkle">Yeni Adres
                                Ekle
                            </button>
                        </div>
                    @endif


                </div>


            </div>
        </div>
    </section>
    <div class="control_popup hide">
        <article>
            <div class="pp_header">
                <h4>Dikkat!</h4>
            </div>
            <div class="pp_center">
                <h6>Silmek istediğinize emin misiniz?</h6>
            </div>
            <div class="pp_buttons">
                <a class="btn-inline color-red small del">Sil</a>
                <a class="btn-inline color-blue small cancel">Vazgeç</a>
            </div>

        </article>


    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/imask/3.4.0/imask.min.js"></script>
    <script>
        var phoneMask = IMask(
            document.getElementById('12'), {
                mask: '(000) 000 00-00'
            },
            document.getElementById('62'), {
                mask: '(000) 000 00-00'
            }
            );

        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
        $("#3").change(function () {
            if (this.checked) { //kurumsal
                $("#2").parent().children(':first-child').text("Firma Adı");
                $("#5").parent().css("display", "block");
                $("#6").parent().css("display", "block");
                $("#4").parent().css("display", "none");
                $("#4").attr("required", false);
                $("#5").attr("required", true);
                $("#6").attr("required", true);
            } else {
                $("#2").parent().children(':first-child').text("Ad Soyad");
                $("#5").parent().css("display", "none");
                $("#6").parent().css("display", "none");
                $("#4").parent().css("display", "block");
                $("#4").attr("required", true);
                $("#5").attr("required", false);
                $("#6").attr("required", false);
            }
        });
        $("#7").on('change', function () {
            $.ajax({
                url: "?il=" + this.value,
                method: 'get',
                success: function (data) {
                    var ilceler = JSON.parse(data);
                    var gelenIlceler = '';
                    ilceler.forEach(function (item) {
                        gelenIlceler += '<option value="' + item.id + '">' + item.ilce_adi + '</option>';
                    });
                    $("#8").html(gelenIlceler);
                }
            })
        });


        function update(id) {
            var modal = $("#faturaAdresiDuzenle" + id);
            var modal_body = modal.children(':first-child').children(':first-child').children(':first-child')[0].children[1].children[2];

            var bireyselKurumsal = modal.children(':first-child').children(':first-child').children(':first-child').children(':first-child')[0].children[2];
            var ad_soyad = modal_body.children[1].children[0];
            var tc_no = modal_body.children[2];
            var vergi_dairesi = modal_body.children[3];
            var vergi_no = modal_body.children[4];
            if (bireyselKurumsal.checked) { //kurumsal
                ad_soyad.innerHTML = "Firma Adı";
                vergi_dairesi.style.display = "block";
                vergi_no.style.display = "block";
                tc_no.style.display = "none";
                tc_no.children[1].required = false;
                vergi_dairesi.children[1].required = true;
                vergi_no.children[1].required = true;
            } else {
                ad_soyad.innerHTML = "Ad Soyad";
                vergi_dairesi.style.display = "none";
                vergi_no.style.display = "none";
                tc_no.style.display = "block";
                tc_no.children[1].required = true;
                vergi_dairesi.children[1].required = false;
                vergi_no.children[1].required = false;
            }

            var il = modal_body.children[6].children[1];
            var ilce = modal_body.children[7].children[1];
            var defaultIlce = modal_body.children[7].children[1].dataset.defaultIlce;
            $.ajax({
                url: "?il=" + il.value,
                method: 'get',
                success: function (data) {
                    var ilceler = JSON.parse(data);
                    var gelenIlceler = '';
                    ilceler.forEach(function (item) {
                        if (item.id == defaultIlce) {
                            gelenIlceler += '<option value="' + item.id + '" selected>' + item.ilce_adi + '</option>';
                        } else {
                            gelenIlceler += '<option value="' + item.id + '">' + item.ilce_adi + '</option>';
                        }

                    });
                    ilce.innerHTML = gelenIlceler;
                }
            })
        }

    </script>
@endsection
