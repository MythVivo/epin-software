<?php
use Carbon\Carbon;
?>
@extends('front.layouts.app')
@section('css')
    <style>
        .modal {
            z-index: 9999;
        }
    </style>
@endsection
@section('body')
    @if(session('smsKod'))
        <div class="modal fade" id="telefonKod" data-bs-backdrop="static"
             data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Telefon Doğrulama V2</h5>
                    </div>
                    <form method="post" action="{{route('telefon_onayla_kod')}}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                @if(session('error'))
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show"
                                             role="alert">
                                            <h5>
                                                {{session('error')}}
                                            </h5>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-12">
                                    <label class="form-label">Gönderilen Kodu Girin</label>
                                    <input type="number" minlength="6" maxlength="6"
                                           class="form-control" name="kod"
                                           placeholder="Gönderilen kodu giriniz..." required>
                                </div>
                                <div class="col-12">
                                    <div class="code-timelife">
                                        <strong>
                                            Kod geçerlilik süresi : <span id="timer"></span>
                                        </strong>
                                    </div>
                                </div>
                                <?php
                                $simdiki = date('Y-m-d H:i:s');
                                $dateTimeS = new DateTime($simdiki);
                                $timestampS = $dateTimeS->format('U');
                                $kaydedilen = Carbon::parse(Auth::user()->telefon_code_expired_at)->addSeconds("300")->format('Y-m-d H:i:s'); //5 dakika sonrası
                                $dateTimeK = new DateTime($kaydedilen);
                                $timestampK = $dateTimeK->format('U');
                                $kalanSaniyeToplam = $timestampK - $timestampS;
                                $kalanDakika = floor($kalanSaniyeToplam / 60);
                                $kalanSaniye = $kalanSaniyeToplam - ($kalanDakika * 60);
                                ?>
                                <script>

                                    document.getElementById('timer').innerHTML = {{$kalanDakika}} + ":" + {{$kalanSaniye}};
                                    startTimer();

                                    function startTimer() {
                                        var presentTime = document.getElementById('timer').innerHTML;
                                        var timeArray = presentTime.split(/[:]+/);
                                        var m = timeArray[0];
                                        var s = checkSecond((timeArray[1] - 1));
                                        if (s == 59) {
                                            m = m - 1
                                        }
                                        //if(m<0){alert('timer completed')}
                                        document.getElementById('timer').innerHTML =
                                            m + ":" + s;
                                        console.log(m)
                                        setTimeout(startTimer, 1000);
                                    }

                                    function checkSecond(sec) {
                                        if (sec < 10 && sec >= 0) {
                                            sec = "0" + sec
                                        }
                                        ; // add zero in front of numbers < 10
                                        if (sec < 0) {
                                            sec = "59"
                                        }
                                        ;
                                        return sec;
                                    }
                                </script>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-success">Doğrula
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif
    <section class="bg-gray pb-40">
        <div class="container">

            <div class="row">

                @include('front.modules.user-menu')
                <div class="col-md-9 c-card-panel">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show"
                             role="alert">
                            <h5>{{session('success')}}</h5>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show"
                             role="alert">
                            <h5>{{session('error')}}</h5>
                        </div>
                    @endif
                    <div class="row">

                        <div class="col-md-4 mb-4">
                            <div class="account-admin {{getUserVerifyStatus(1)}}">
                                <h4>V1 Email Doğrulama</h4>
                                <h6 class="text-white">E-postanı doğrulayarak V1 doğrulama elde et.</h6>
                                <div class="btn-footer"><a class="btn-inline small color-white" href="#"
                                                           @if(Auth::user()->email_verified_at == NULL) data-bs-toggle="modal"
                                                           data-bs-target="#emailDogrula"
                                                           @else data-btn="passive" @endif>
                                        {{getUserVerifyText(1)}}
                                    </a></div>
                                <div class="modal fade" id="emailDogrula" data-bs-backdrop="static"
                                     data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">E-postanı Doğrula</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <form method="post" action="{{route('email_onayla')}}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <label class="form-label">E-posta Adresiniz</label>
                                                            <input value="{{Auth::user()->email}}" type="text"
                                                                   class="form-control" name="email"
                                                                   placeholder="E-postanız...">
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal">
                                                        Kapat
                                                    </button>
                                                    <button type="submit" class="btn btn-outline-success">Doğrula
                                                    </button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="account-admin {{getUserVerifyStatus(2)}}">
                                <h4>V2 Telefon Doğrulama</h4>
                                <h6 class="text-white">Telefonunu doğrulayarak V2 doğrulama elde et.</h6>
                                <p>Telefon onayı yaparak ilan ekleme gibi işlemleri yapabilirsiniz. Bu alanlarda telefon numaranız size gönderilecek bilgilendirme sms'leri için kullanılacak olup güvenlik için zorunludur.</p>
                                <div class="btn-footer"><a class="btn-inline small color-white" href="#"
                                                           @if(Auth::user()->telefon_verified_at == NULL) data-bs-toggle="modal"
                                                           data-bs-target="#telefonDogrula"
                                                           @else data-btn="passive" @endif>
                                        {{getUserVerifyText(2)}}
                                    </a></div>
                                <div class="modal fade" id="telefonDogrula" data-bs-backdrop="static"
                                     data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Telefon Doğrula</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <form method="post" action="{{route('telefon_onayla')}}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <label class="form-label">Telefon Numaranız (Başında 0 olmadan)</label>
                                                            <div class="input-group has-validation">
                                                                <select id="countryCode"
                                                                        data-selected-id="{{Auth::user()->telefon_country}}"
                                                                        class="form-control style-input"
                                                                        name="telefon_country">
                                                                    <option data-countryCode="TR" value="90">Türkiye
                                                                        (+90)
                                                                    </option>
                                                                    <optgroup label="Other countries">
                                                                        <option data-countryCode="DZ" value="213">
                                                                            Algeria
                                                                            (+213)
                                                                        </option>
                                                                        <option data-countryCode="AD" value="376">
                                                                            Andorra
                                                                            (+376)
                                                                        </option>
                                                                        <option data-countryCode="AO" value="244">Angola
                                                                            (+244)
                                                                        </option>
                                                                        <option data-countryCode="AI" value="1264">
                                                                            Anguilla
                                                                            (+1264)
                                                                        </option>
                                                                        <option data-countryCode="AG" value="1268">
                                                                            Antigua &amp;
                                                                            Barbuda (+1268)
                                                                        </option>
                                                                        <option data-countryCode="AR" value="54">
                                                                            Argentina
                                                                            (+54)
                                                                        </option>
                                                                        <option data-countryCode="AM" value="374">
                                                                            Armenia
                                                                            (+374)
                                                                        </option>
                                                                        <option data-countryCode="AW" value="297">Aruba
                                                                            (+297)
                                                                        </option>
                                                                        <option data-countryCode="AU" value="61">
                                                                            Australia
                                                                            (+61)
                                                                        </option>
                                                                        <option data-countryCode="AT" value="43">Austria
                                                                            (+43)
                                                                        </option>
                                                                        <option data-countryCode="AZ" value="994">
                                                                            Azerbaijan
                                                                            (+994)
                                                                        </option>
                                                                        <option data-countryCode="BS" value="1242">
                                                                            Bahamas
                                                                            (+1242)
                                                                        </option>
                                                                        <option data-countryCode="BH" value="973">
                                                                            Bahrain
                                                                            (+973)
                                                                        </option>
                                                                        <option data-countryCode="BD" value="880">
                                                                            Bangladesh
                                                                            (+880)
                                                                        </option>
                                                                        <option data-countryCode="BB" value="1246">
                                                                            Barbados
                                                                            (+1246)
                                                                        </option>
                                                                        <option data-countryCode="BY" value="375">
                                                                            Belarus
                                                                            (+375)
                                                                        </option>
                                                                        <option data-countryCode="BE" value="32">Belgium
                                                                            (+32)
                                                                        </option>
                                                                        <option data-countryCode="BZ" value="501">Belize
                                                                            (+501)
                                                                        </option>
                                                                        <option data-countryCode="BJ" value="229">Benin
                                                                            (+229)
                                                                        </option>
                                                                        <option data-countryCode="BM" value="1441">
                                                                            Bermuda
                                                                            (+1441)
                                                                        </option>
                                                                        <option data-countryCode="BT" value="975">Bhutan
                                                                            (+975)
                                                                        </option>
                                                                        <option data-countryCode="BO" value="591">
                                                                            Bolivia
                                                                            (+591)
                                                                        </option>
                                                                        <option data-countryCode="BA" value="387">Bosnia
                                                                            Herzegovina (+387)
                                                                        </option>
                                                                        <option data-countryCode="BW" value="267">
                                                                            Botswana
                                                                            (+267)
                                                                        </option>
                                                                        <option data-countryCode="BR" value="55">Brazil
                                                                            (+55)
                                                                        </option>
                                                                        <option data-countryCode="BN" value="673">Brunei
                                                                            (+673)
                                                                        </option>
                                                                        <option data-countryCode="BG" value="359">
                                                                            Bulgaria
                                                                            (+359)
                                                                        </option>
                                                                        <option data-countryCode="BF" value="226">
                                                                            Burkina Faso
                                                                            (+226)
                                                                        </option>
                                                                        <option data-countryCode="BI" value="257">
                                                                            Burundi
                                                                            (+257)
                                                                        </option>
                                                                        <option data-countryCode="KH" value="855">
                                                                            Cambodia
                                                                            (+855)
                                                                        </option>
                                                                        <option data-countryCode="CM" value="237">
                                                                            Cameroon
                                                                            (+237)
                                                                        </option>
                                                                        <option data-countryCode="CA" value="1">Canada
                                                                            (+1)
                                                                        </option>
                                                                        <option data-countryCode="CV" value="238">Cape
                                                                            Verde
                                                                            Islands (+238)
                                                                        </option>
                                                                        <option data-countryCode="KY" value="1345">
                                                                            Cayman
                                                                            Islands (+1345)
                                                                        </option>
                                                                        <option data-countryCode="CF" value="236">
                                                                            Central
                                                                            African Republic (+236)
                                                                        </option>
                                                                        <option data-countryCode="CL" value="56">Chile
                                                                            (+56)
                                                                        </option>
                                                                        <option data-countryCode="CN" value="86">China
                                                                            (+86)
                                                                        </option>
                                                                        <option data-countryCode="CO" value="57">
                                                                            Colombia
                                                                            (+57)
                                                                        </option>
                                                                        <option data-countryCode="KM" value="269">
                                                                            Comoros
                                                                            (+269)
                                                                        </option>
                                                                        <option data-countryCode="CG" value="242">Congo
                                                                            (+242)
                                                                        </option>
                                                                        <option data-countryCode="CK" value="682">Cook
                                                                            Islands
                                                                            (+682)
                                                                        </option>
                                                                        <option data-countryCode="CR" value="506">Costa
                                                                            Rica
                                                                            (+506)
                                                                        </option>
                                                                        <option data-countryCode="HR" value="385">
                                                                            Croatia
                                                                            (+385)
                                                                        </option>
                                                                        <option data-countryCode="CU" value="53">Cuba
                                                                            (+53)
                                                                        </option>
                                                                        <option data-countryCode="CY" value="90392">
                                                                            Cyprus North
                                                                            (+90392)
                                                                        </option>
                                                                        <option data-countryCode="CY" value="357">Cyprus
                                                                            South
                                                                            (+357)
                                                                        </option>
                                                                        <option data-countryCode="CZ" value="42">Czech
                                                                            Republic
                                                                            (+42)
                                                                        </option>
                                                                        <option data-countryCode="DK" value="45">Denmark
                                                                            (+45)
                                                                        </option>
                                                                        <option data-countryCode="DJ" value="253">
                                                                            Djibouti
                                                                            (+253)
                                                                        </option>
                                                                        <option data-countryCode="DM" value="1809">
                                                                            Dominica
                                                                            (+1809)
                                                                        </option>
                                                                        <option data-countryCode="DO" value="1809">
                                                                            Dominican
                                                                            Republic (+1809)
                                                                        </option>
                                                                        <option data-countryCode="EC" value="593">
                                                                            Ecuador
                                                                            (+593)
                                                                        </option>
                                                                        <option data-countryCode="EG" value="20">Egypt
                                                                            (+20)
                                                                        </option>
                                                                        <option data-countryCode="SV" value="503">El
                                                                            Salvador
                                                                            (+503)
                                                                        </option>
                                                                        <option data-countryCode="GQ" value="240">
                                                                            Equatorial
                                                                            Guinea (+240)
                                                                        </option>
                                                                        <option data-countryCode="ER" value="291">
                                                                            Eritrea
                                                                            (+291)
                                                                        </option>
                                                                        <option data-countryCode="EE" value="372">
                                                                            Estonia
                                                                            (+372)
                                                                        </option>
                                                                        <option data-countryCode="ET" value="251">
                                                                            Ethiopia
                                                                            (+251)
                                                                        </option>
                                                                        <option data-countryCode="FK" value="500">
                                                                            Falkland
                                                                            Islands (+500)
                                                                        </option>
                                                                        <option data-countryCode="FO" value="298">Faroe
                                                                            Islands
                                                                            (+298)
                                                                        </option>
                                                                        <option data-countryCode="FJ" value="679">Fiji
                                                                            (+679)
                                                                        </option>
                                                                        <option data-countryCode="FI" value="358">
                                                                            Finland
                                                                            (+358)
                                                                        </option>
                                                                        <option data-countryCode="FR" value="33">France
                                                                            (+33)
                                                                        </option>
                                                                        <option data-countryCode="GF" value="594">French
                                                                            Guiana
                                                                            (+594)
                                                                        </option>
                                                                        <option data-countryCode="PF" value="689">French
                                                                            Polynesia (+689)
                                                                        </option>
                                                                        <option data-countryCode="GA" value="241">Gabon
                                                                            (+241)
                                                                        </option>
                                                                        <option data-countryCode="GM" value="220">Gambia
                                                                            (+220)
                                                                        </option>
                                                                        <option data-countryCode="GE" value="7880">
                                                                            Georgia
                                                                            (+7880)
                                                                        </option>
                                                                        <option data-countryCode="DE" value="49">Germany
                                                                            (+49)
                                                                        </option>
                                                                        <option data-countryCode="GH" value="233">Ghana
                                                                            (+233)
                                                                        </option>
                                                                        <option data-countryCode="GI" value="350">
                                                                            Gibraltar
                                                                            (+350)
                                                                        </option>
                                                                        <option data-countryCode="GR" value="30">Greece
                                                                            (+30)
                                                                        </option>
                                                                        <option data-countryCode="GL" value="299">
                                                                            Greenland
                                                                            (+299)
                                                                        </option>
                                                                        <option data-countryCode="GD" value="1473">
                                                                            Grenada
                                                                            (+1473)
                                                                        </option>
                                                                        <option data-countryCode="GP" value="590">
                                                                            Guadeloupe
                                                                            (+590)
                                                                        </option>
                                                                        <option data-countryCode="GU" value="671">Guam
                                                                            (+671)
                                                                        </option>
                                                                        <option data-countryCode="GT" value="502">
                                                                            Guatemala
                                                                            (+502)
                                                                        </option>
                                                                        <option data-countryCode="GN" value="224">Guinea
                                                                            (+224)
                                                                        </option>
                                                                        <option data-countryCode="GW" value="245">Guinea
                                                                            -
                                                                            Bissau (+245)
                                                                        </option>
                                                                        <option data-countryCode="GY" value="592">Guyana
                                                                            (+592)
                                                                        </option>
                                                                        <option data-countryCode="HT" value="509">Haiti
                                                                            (+509)
                                                                        </option>
                                                                        <option data-countryCode="HN" value="504">
                                                                            Honduras
                                                                            (+504)
                                                                        </option>
                                                                        <option data-countryCode="HK" value="852">Hong
                                                                            Kong
                                                                            (+852)
                                                                        </option>
                                                                        <option data-countryCode="HU" value="36">Hungary
                                                                            (+36)
                                                                        </option>
                                                                        <option data-countryCode="IS" value="354">
                                                                            Iceland
                                                                            (+354)
                                                                        </option>
                                                                        <option data-countryCode="IN" value="91">India
                                                                            (+91)
                                                                        </option>
                                                                        <option data-countryCode="ID" value="62">
                                                                            Indonesia
                                                                            (+62)
                                                                        </option>
                                                                        <option data-countryCode="IR" value="98">Iran
                                                                            (+98)
                                                                        </option>
                                                                        <option data-countryCode="IQ" value="964">Iraq
                                                                            (+964)
                                                                        </option>
                                                                        <option data-countryCode="IE" value="353">
                                                                            Ireland
                                                                            (+353)
                                                                        </option>
                                                                        <option data-countryCode="IL" value="972">Israel
                                                                            (+972)
                                                                        </option>
                                                                        <option data-countryCode="IT" value="39">Italy
                                                                            (+39)
                                                                        </option>
                                                                        <option data-countryCode="JM" value="1876">
                                                                            Jamaica
                                                                            (+1876)
                                                                        </option>
                                                                        <option data-countryCode="JP" value="81">Japan
                                                                            (+81)
                                                                        </option>
                                                                        <option data-countryCode="JO" value="962">Jordan
                                                                            (+962)
                                                                        </option>
                                                                        <option data-countryCode="KZ" value="7">
                                                                            Kazakhstan
                                                                            (+7)
                                                                        </option>
                                                                        <option data-countryCode="KE" value="254">Kenya
                                                                            (+254)
                                                                        </option>
                                                                        <option data-countryCode="KI" value="686">
                                                                            Kiribati
                                                                            (+686)
                                                                        </option>
                                                                        <option data-countryCode="KP" value="850">Korea
                                                                            North
                                                                            (+850)
                                                                        </option>
                                                                        <option data-countryCode="KR" value="82">Korea
                                                                            South
                                                                            (+82)
                                                                        </option>
                                                                        <option data-countryCode="KW" value="965">Kuwait
                                                                            (+965)
                                                                        </option>
                                                                        <option data-countryCode="KG" value="996">
                                                                            Kyrgyzstan
                                                                            (+996)
                                                                        </option>
                                                                        <option data-countryCode="LA" value="856">Laos
                                                                            (+856)
                                                                        </option>
                                                                        <option data-countryCode="LV" value="371">Latvia
                                                                            (+371)
                                                                        </option>
                                                                        <option data-countryCode="LB" value="961">
                                                                            Lebanon
                                                                            (+961)
                                                                        </option>
                                                                        <option data-countryCode="LS" value="266">
                                                                            Lesotho
                                                                            (+266)
                                                                        </option>
                                                                        <option data-countryCode="LR" value="231">
                                                                            Liberia
                                                                            (+231)
                                                                        </option>
                                                                        <option data-countryCode="LY" value="218">Libya
                                                                            (+218)
                                                                        </option>
                                                                        <option data-countryCode="LI" value="417">
                                                                            Liechtenstein
                                                                            (+417)
                                                                        </option>
                                                                        <option data-countryCode="LT" value="370">
                                                                            Lithuania
                                                                            (+370)
                                                                        </option>
                                                                        <option data-countryCode="LU" value="352">
                                                                            Luxembourg
                                                                            (+352)
                                                                        </option>
                                                                        <option data-countryCode="MO" value="853">Macao
                                                                            (+853)
                                                                        </option>
                                                                        <option data-countryCode="MK" value="389">
                                                                            Macedonia
                                                                            (+389)
                                                                        </option>
                                                                        <option data-countryCode="MG" value="261">
                                                                            Madagascar
                                                                            (+261)
                                                                        </option>
                                                                        <option data-countryCode="MW" value="265">Malawi
                                                                            (+265)
                                                                        </option>
                                                                        <option data-countryCode="MY" value="60">
                                                                            Malaysia
                                                                            (+60)
                                                                        </option>
                                                                        <option data-countryCode="MV" value="960">
                                                                            Maldives
                                                                            (+960)
                                                                        </option>
                                                                        <option data-countryCode="ML" value="223">Mali
                                                                            (+223)
                                                                        </option>
                                                                        <option data-countryCode="MT" value="356">Malta
                                                                            (+356)
                                                                        </option>
                                                                        <option data-countryCode="MH" value="692">
                                                                            Marshall
                                                                            Islands (+692)
                                                                        </option>
                                                                        <option data-countryCode="MQ" value="596">
                                                                            Martinique
                                                                            (+596)
                                                                        </option>
                                                                        <option data-countryCode="MR" value="222">
                                                                            Mauritania
                                                                            (+222)
                                                                        </option>
                                                                        <option data-countryCode="YT" value="269">
                                                                            Mayotte
                                                                            (+269)
                                                                        </option>
                                                                        <option data-countryCode="MX" value="52">Mexico
                                                                            (+52)
                                                                        </option>
                                                                        <option data-countryCode="FM" value="691">
                                                                            Micronesia
                                                                            (+691)
                                                                        </option>
                                                                        <option data-countryCode="MD" value="373">
                                                                            Moldova
                                                                            (+373)
                                                                        </option>
                                                                        <option data-countryCode="MC" value="377">Monaco
                                                                            (+377)
                                                                        </option>
                                                                        <option data-countryCode="MN" value="976">
                                                                            Mongolia
                                                                            (+976)
                                                                        </option>
                                                                        <option data-countryCode="MS" value="1664">
                                                                            Montserrat
                                                                            (+1664)
                                                                        </option>
                                                                        <option data-countryCode="MA" value="212">
                                                                            Morocco
                                                                            (+212)
                                                                        </option>
                                                                        <option data-countryCode="MZ" value="258">
                                                                            Mozambique
                                                                            (+258)
                                                                        </option>
                                                                        <option data-countryCode="MN" value="95">Myanmar
                                                                            (+95)
                                                                        </option>
                                                                        <option data-countryCode="NA" value="264">
                                                                            Namibia
                                                                            (+264)
                                                                        </option>
                                                                        <option data-countryCode="NR" value="674">Nauru
                                                                            (+674)
                                                                        </option>
                                                                        <option data-countryCode="NP" value="977">Nepal
                                                                            (+977)
                                                                        </option>
                                                                        <option data-countryCode="NL" value="31">
                                                                            Netherlands
                                                                            (+31)
                                                                        </option>
                                                                        <option data-countryCode="NC" value="687">New
                                                                            Caledonia
                                                                            (+687)
                                                                        </option>
                                                                        <option data-countryCode="NZ" value="64">New
                                                                            Zealand
                                                                            (+64)
                                                                        </option>
                                                                        <option data-countryCode="NI" value="505">
                                                                            Nicaragua
                                                                            (+505)
                                                                        </option>
                                                                        <option data-countryCode="NE" value="227">Niger
                                                                            (+227)
                                                                        </option>
                                                                        <option data-countryCode="NG" value="234">
                                                                            Nigeria
                                                                            (+234)
                                                                        </option>
                                                                        <option data-countryCode="NU" value="683">Niue
                                                                            (+683)
                                                                        </option>
                                                                        <option data-countryCode="NF" value="672">
                                                                            Norfolk
                                                                            Islands (+672)
                                                                        </option>
                                                                        <option data-countryCode="NP" value="670">
                                                                            Northern
                                                                            Marianas (+670)
                                                                        </option>
                                                                        <option data-countryCode="NO" value="47">Norway
                                                                            (+47)
                                                                        </option>
                                                                        <option data-countryCode="OM" value="968">Oman
                                                                            (+968)
                                                                        </option>
                                                                        <option data-countryCode="PW" value="680">Palau
                                                                            (+680)
                                                                        </option>
                                                                        <option data-countryCode="PA" value="507">Panama
                                                                            (+507)
                                                                        </option>
                                                                        <option data-countryCode="PG" value="675">Papua
                                                                            New
                                                                            Guinea (+675)
                                                                        </option>
                                                                        <option data-countryCode="PY" value="595">
                                                                            Paraguay
                                                                            (+595)
                                                                        </option>
                                                                        <option data-countryCode="PE" value="51">Peru
                                                                            (+51)
                                                                        </option>
                                                                        <option data-countryCode="PH" value="63">
                                                                            Philippines
                                                                            (+63)
                                                                        </option>
                                                                        <option data-countryCode="PL" value="48">Poland
                                                                            (+48)
                                                                        </option>
                                                                        <option data-countryCode="PT" value="351">
                                                                            Portugal
                                                                            (+351)
                                                                        </option>
                                                                        <option data-countryCode="PR" value="1787">
                                                                            Puerto Rico
                                                                            (+1787)
                                                                        </option>
                                                                        <option data-countryCode="QA" value="974">Qatar
                                                                            (+974)
                                                                        </option>
                                                                        <option data-countryCode="RE" value="262">
                                                                            Reunion
                                                                            (+262)
                                                                        </option>
                                                                        <option data-countryCode="RO" value="40">Romania
                                                                            (+40)
                                                                        </option>
                                                                        <option data-countryCode="RU" value="7">Russia
                                                                            (+7)
                                                                        </option>
                                                                        <option data-countryCode="RW" value="250">Rwanda
                                                                            (+250)
                                                                        </option>
                                                                        <option data-countryCode="SM" value="378">San
                                                                            Marino
                                                                            (+378)
                                                                        </option>
                                                                        <option data-countryCode="ST" value="239">Sao
                                                                            Tome &amp;
                                                                            Principe (+239)
                                                                        </option>
                                                                        <option data-countryCode="SA" value="966">Saudi
                                                                            Arabia
                                                                            (+966)
                                                                        </option>
                                                                        <option data-countryCode="SN" value="221">
                                                                            Senegal
                                                                            (+221)
                                                                        </option>
                                                                        <option data-countryCode="CS" value="381">Serbia
                                                                            (+381)
                                                                        </option>
                                                                        <option data-countryCode="SC" value="248">
                                                                            Seychelles
                                                                            (+248)
                                                                        </option>
                                                                        <option data-countryCode="SL" value="232">Sierra
                                                                            Leone
                                                                            (+232)
                                                                        </option>
                                                                        <option data-countryCode="SG" value="65">
                                                                            Singapore
                                                                            (+65)
                                                                        </option>
                                                                        <option data-countryCode="SK" value="421">Slovak
                                                                            Republic (+421)
                                                                        </option>
                                                                        <option data-countryCode="SI" value="386">
                                                                            Slovenia
                                                                            (+386)
                                                                        </option>
                                                                        <option data-countryCode="SB" value="677">
                                                                            Solomon
                                                                            Islands (+677)
                                                                        </option>
                                                                        <option data-countryCode="SO" value="252">
                                                                            Somalia
                                                                            (+252)
                                                                        </option>
                                                                        <option data-countryCode="ZA" value="27">South
                                                                            Africa
                                                                            (+27)
                                                                        </option>
                                                                        <option data-countryCode="ES" value="34">Spain
                                                                            (+34)
                                                                        </option>
                                                                        <option data-countryCode="LK" value="94">Sri
                                                                            Lanka
                                                                            (+94)
                                                                        </option>
                                                                        <option data-countryCode="SH" value="290">St.
                                                                            Helena
                                                                            (+290)
                                                                        </option>
                                                                        <option data-countryCode="KN" value="1869">St.
                                                                            Kitts
                                                                            (+1869)
                                                                        </option>
                                                                        <option data-countryCode="SC" value="1758">St.
                                                                            Lucia
                                                                            (+1758)
                                                                        </option>
                                                                        <option data-countryCode="SD" value="249">Sudan
                                                                            (+249)
                                                                        </option>
                                                                        <option data-countryCode="SR" value="597">
                                                                            Suriname
                                                                            (+597)
                                                                        </option>
                                                                        <option data-countryCode="SZ" value="268">
                                                                            Swaziland
                                                                            (+268)
                                                                        </option>
                                                                        <option data-countryCode="SE" value="46">Sweden
                                                                            (+46)
                                                                        </option>
                                                                        <option data-countryCode="CH" value="41">
                                                                            Switzerland
                                                                            (+41)
                                                                        </option>
                                                                        <option data-countryCode="SI" value="963">Syria
                                                                            (+963)
                                                                        </option>
                                                                        <option data-countryCode="TW" value="886">Taiwan
                                                                            (+886)
                                                                        </option>
                                                                        <option data-countryCode="TJ" value="7">
                                                                            Tajikstan (+7)
                                                                        </option>
                                                                        <option data-countryCode="TH" value="66">
                                                                            Thailand
                                                                            (+66)
                                                                        </option>
                                                                        <option data-countryCode="TG" value="228">Togo
                                                                            (+228)
                                                                        </option>
                                                                        <option data-countryCode="TO" value="676">Tonga
                                                                            (+676)
                                                                        </option>
                                                                        <option data-countryCode="TT" value="1868">
                                                                            Trinidad
                                                                            &amp; Tobago (+1868)
                                                                        </option>
                                                                        <option data-countryCode="TN" value="216">
                                                                            Tunisia
                                                                            (+216)
                                                                        </option>
                                                                        <option data-countryCode="TM" value="7">
                                                                            Turkmenistan
                                                                            (+7)
                                                                        </option>
                                                                        <option data-countryCode="TM" value="993">
                                                                            Turkmenistan
                                                                            (+993)
                                                                        </option>
                                                                        <option data-countryCode="TC" value="1649">Turks
                                                                            &amp;
                                                                            Caicos Islands (+1649)
                                                                        </option>
                                                                        <option data-countryCode="TV" value="688">Tuvalu
                                                                            (+688)
                                                                        </option>
                                                                        <option data-countryCode="UG" value="256">Uganda
                                                                            (+256)
                                                                        </option>
                                                                        <option data-countryCode="GB" value="44">UK
                                                                            (+44)
                                                                        </option>
                                                                        <option data-countryCode="UA" value="380">
                                                                            Ukraine
                                                                            (+380)
                                                                        </option>
                                                                        <option data-countryCode="AE" value="971">United
                                                                            Arab
                                                                            Emirates (+971)
                                                                        </option>
                                                                        <option data-countryCode="UY" value="598">
                                                                            Uruguay
                                                                            (+598)
                                                                        </option>
                                                                        <option data-countryCode="US" value="1">USA (+1)
                                                                        </option>
                                                                        <option data-countryCode="UZ" value="7">
                                                                            Uzbekistan
                                                                            (+7)
                                                                        </option>
                                                                        <option data-countryCode="VU" value="678">
                                                                            Vanuatu
                                                                            (+678)
                                                                        </option>
                                                                        <option data-countryCode="VA" value="379">
                                                                            Vatican City
                                                                            (+379)
                                                                        </option>
                                                                        <option data-countryCode="VE" value="58">
                                                                            Venezuela
                                                                            (+58)
                                                                        </option>
                                                                        <option data-countryCode="VN" value="84">Vietnam
                                                                            (+84)
                                                                        </option>
                                                                        <option data-countryCode="VG" value="84">Virgin
                                                                            Islands
                                                                            - British (+1284)
                                                                        </option>
                                                                        <option data-countryCode="VI" value="84">Virgin
                                                                            Islands
                                                                            - US (+1340)
                                                                        </option>
                                                                        <option data-countryCode="WF" value="681">Wallis
                                                                            &amp;
                                                                            Futuna (+681)
                                                                        </option>
                                                                        <option data-countryCode="YE" value="969">Yemen
                                                                            (North)(+969)
                                                                        </option>
                                                                        <option data-countryCode="YE" value="967">Yemen
                                                                            (South)(+967)
                                                                        </option>
                                                                        <option data-countryCode="ZM" value="260">Zambia
                                                                            (+260)
                                                                        </option>
                                                                        <option data-countryCode="ZW" value="263">
                                                                            Zimbabwe
                                                                            (+263)
                                                                        </option>
                                                                    </optgroup>
                                                                </select>
                                                                <input value="{{Auth::user()->telefon}}" type="text"
                                                                       class="form-control style-input" name="telefon"
                                                                       placeholder="Telefon Numaranız...">
                                                                <div class="invalid-feedback">
                                                                    Lütfen bir telefon numarası girin
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal">
                                                        Kapat
                                                    </button>
                                                    <button type="submit" class="btn btn-outline-success">Doğrula
                                                    </button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(!session('errorType') and !session('errorType') == 2)
                        <div class="col-md-4 mb-4">
                            <div class="account-admin {{getUserVerifyStatus(3)}}">
                                <h4>V3 Kimlik Doğrulama</h4>
                                <h6 class="text-white">Kimliğini doğrulayarak V3 doğrulama elde et.</h6>
                                <p>Kimlik onayı yaparak sistem üzerinden elde ettiğiniz kazançları banka hesaplarınıza çekebilirsiniz. Para gönderim işlemleri için kimlik bilgilerinizin doğrulanması gerekmektedir.</p>
                                <div class="btn-footer"><a class="btn-inline small color-white" href="#"
                                                           @if(Auth::user()->tc_verified_at == NULL and Auth::user()->tc_verified_at_first == NULL) data-bs-toggle="modal"
                                                           data-bs-target="#tcDogrula" @else data-btn="passive" @endif>
                                        {{getUserVerifyText(3)}}
                                    </a></div>
                                <div class="modal fade" id="tcDogrula" data-bs-backdrop="static"
                                     data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Kimlik Doğrula</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <form method="post" action="{{route('kimlik_onayla')}}"
                                                  enctype="multipart/form-data" autocomplete="off">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        @if(session('error'))
                                                            <div class="col-12">
                                                                <div class="alert alert-danger alert-dismissible fade show"
                                                                     role="alert">
                                                                    <h5>{{session('error')}}</h5>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="col-12 mb-3">
                                                            <label for="notTr" class="form-label">T.C.
                                                                Vatandaşı Değilim</label>
                                                            <input type="checkbox" name="notTr"
                                                                   class="form-check-input style-input ms-1"
                                                                   id="notTr">
                                                        </div>

                                                        <div class="col-12" id="tcno">
                                                            <label class="form-label">T.C. No</label>
                                                            <input value="{{Auth::user()->tcno}}" type="text"
                                                                   class="form-control" name="tcno"
                                                                   placeholder="Kimlik Numaranız" required>
                                                        </div>
                                                        <div class="col-6 mt-3">
                                                            <label class="form-label">Adınız</label>
                                                            <input type="text" class="form-control" name="ad"
                                                                   placeholder="Adınız" required>
                                                        </div>
                                                        <div class="col-6 mt-3">
                                                            <label class="form-label">Soyadınız</label>
                                                            <input type="text" class="form-control" name="soyad"
                                                                   placeholder="Soyadınız" required>
                                                        </div>
                                                        <div class="col-4 mt-3">
                                                            <label class="form-label">Doğum Günü</label>
                                                            <select name="dgun" class="form-control" required>
                                                                @for($i=1;$i<32;$i++)
                                                                    @if($i < 10)
                                                                        <option value="0{{$i}}">{{$i}}</option>
                                                                    @else
                                                                        <option value="{{$i}}">{{$i}}</option>
                                                                    @endif
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-4 mt-3">
                                                            <label class="form-label">Doğum Ayı</label>
                                                            <select name="day" class="form-control" required>
                                                                <option value="01">Ocak</option>
                                                                <option value="02">Şubat</option>
                                                                <option value="03">Mart</option>
                                                                <option value="04">Nisan</option>
                                                                <option value="05">Mayıs</option>
                                                                <option value="06">Haziran</option>
                                                                <option value="07">Temmuz</option>
                                                                <option value="08">Ağustos</option>
                                                                <option value="09">Eylül</option>
                                                                <option value="10">Ekim</option>
                                                                <option value="11">Kasım</option>
                                                                <option value="12">Aralık</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-4 mt-3">
                                                            <label class="form-label">Doğum Yılı</label>
                                                            <select name="dyil" class="form-control" required>
                                                                @for($i=2020;$i>1940;$i--)
                                                                    <option value="{{$i}}">{{$i}}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-12 mt-3" id="kimlikTipi">
                                                            <label class="form-label">Kimlik Tipi</label>
                                                            <select id="tip" name="tip" class="form-control" required>
                                                                <option value="0" selected>Yeni Kimlik</option>
                                                                <option value="1">Eski Kimlik</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 mt-3" id="eskiKimlik"
                                                             style="display: none;">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <label class="form-label">Kimlik Serisi</label>
                                                                    <input type="text" class="form-control"
                                                                           name="CuzdanSeri"
                                                                           placeholder="Kimlik Serisi (Örn. A06)">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="form-label">Kimlik Seri No</label>
                                                                    <input type="text" class="form-control"
                                                                           name="CuzdanNo"
                                                                           placeholder="Kimlik Seri No">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mt-3" id="yeniKimlik">
                                                            <label class="form-label">Kimlik Seri No</label>
                                                            <input type="text" class="form-control" name="serino"
                                                                   placeholder="Kimlik seri numarası" required>
                                                        </div>
                                                        <div class="col-12 mt-3" id="fotograf" style="display: none;">
                                                            <label class="form-label">Fotoğraf</label>
                                                            <input type="file" class="form-control" name="image"
                                                                   accept="image/*">
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal">
                                                        Kapat
                                                    </button>
                                                    <button type="submit" class="btn btn-outline-success">Doğrula
                                                    </button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    @if(session('smsKod'))
        <script type="text/javascript">
            $(window).on('load', function () {
                $('#telefonKod').modal('show');
            });
        </script>
    @endif
    @if(session('tc'))
        <script type="text/javascript">
            $(window).on('load', function () {
                $('#tcDogrula').modal('show');
            });
        </script>
    @endif
    <script>
        $(document).ready(function () {
            $('.select2').select2();
            selected = $("#countryCode").data("selectedId");
            $("#countryCode").val(selected);

            $("#tip").on('change', function () {
                var secilen = this.value;
                if (secilen == 0) {
                    $("input[name='serino']").css("display", "block");
                    $("input[name='serino']").prop("required", true);

                    $("input[name='CuzdanSeri']").css("display", "none");
                    $("input[name='CuzdanSeri']").prop("required", false);

                    $("input[name='CuzdanNo']").css("display", "none");
                    $("input[name='CuzdanNo']").prop("required", false);

                    $("#eskiKimlik").css("display", "none");
                    $("#yeniKimlik").css("display", "block");
                } else {
                    $("input[name='serino']").css("display", "none");
                    $("input[name='serino']").prop("required", false);

                    $("input[name='CuzdanSeri']").css("display", "block");
                    $("input[name='CuzdanSeri']").prop("required", true);

                    $("input[name='CuzdanNo']").css("display", "block");
                    $("input[name='CuzdanNo']").prop("required", true);

                    $("#eskiKimlik").css("display", "block");
                    $("#yeniKimlik").css("display", "none");
                }
            });

            $("#notTr").on('change', function () {
                var secilen = this.checked;
                if (secilen) {
                    $("input[name='serino']").css("display", "none");
                    $("input[name='serino']").prop("required", false);

                    $("input[name='CuzdanSeri']").css("display", "none");
                    $("input[name='CuzdanSeri']").prop("required", false);

                    $("input[name='CuzdanNo']").css("display", "none");
                    $("input[name='CuzdanNo']").prop("required", false);

                    $("input[name='tip']").css("display", "none");
                    $("input[name='tip']").prop("required", false);

                    $("input[name='tcno']").css("display", "none");
                    $("input[name='tcno']").prop("required", false);

                    $("input[name='image']").css("display", "block");
                    $("input[name='image']").prop("required", true);

                    $("#fotograf").css("display", "block");

                    $("#eskiKimlik").css("display", "none");
                    $("#yeniKimlik").css("display", "none");
                    $("#kimlikTipi").css("display", "none");
                    $("#tcno").css("display", "none");
                } else {
                    $("input[name='serino']").css("display", "block");
                    $("input[name='serino']").prop("required", true);

                    $("input[name='CuzdanSeri']").css("display", "none");
                    $("input[name='CuzdanSeri']").prop("required", false);

                    $("input[name='CuzdanNo']").css("display", "none");
                    $("input[name='CuzdanNo']").prop("required", false);

                    $("input[name='tip']").css("display", "block");
                    $("input[name='tip']").prop("required", true);

                    $("input[name='tcno']").css("display", "block");
                    $("input[name='tcno']").prop("required", true);

                    $("input[name='image']").css("display", "none");
                    $("input[name='image']").prop("required", false);

                    $("#fotograf").css("display", "none");

                    $("#eskiKimlik").css("display", "none");
                    $("#yeniKimlik").css("display", "block");
                    $("#kimlikTipi").css("display", "block");
                    $("#tcno").css("display", "block");
                }
            });


        });

    </script>
@endsection
