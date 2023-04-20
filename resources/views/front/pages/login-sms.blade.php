<?php
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
}
?>
        <!doctype html>
<html lang="tr">
<head>
    {{getStatistic()}}
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Sms İle Giriş Yap | {{getSiteName()}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{asset(env('ROOT').env('FRONT').env('CSS').'bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset(env('ROOT').env('FRONT').env('CSS').'style.css')}}" rel="stylesheet">
    <!-- font awesome -->
    <link rel="stylesheet" href="{{asset(env('ROOT').env('FRONT').env('VENDORS').'fontawesome/css/all.css')}}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
@if(!isset($_COOKIE['theme']) or (isset($_COOKIE['theme']) and $_COOKIE['theme'] == 'dark'))
    <body class="dark"> <!-- dark theme -->
    @else
        <body>
        @endif
        <section class="login-page">
            <div class="container">
                <div class="form-wrapper">
                    <div class="row">

                        <div class="form-container">
                            <div class="form-brand-logo">
                                <div class="center-logo">
                                    <a href="/"><img src="{{asset(env('ROOT').env('BRAND').'oyuneks-form-logo.svg')}}"></a>
                                </div>
                            </div>
                            <div class="alert alert-success fade show d-flex align-items-center"
                                 role="alert">
                                <h5>{{$success}}</h5>
                            </div>
                            @if(session('error'))
                                <div class="alert alert-danger fade show d-flex align-items-center"
                                     role="alert">
                                    <h5>{{session('error')}}</h5>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-success fade show d-flex align-items-center"
                                     role="alert">
                                    <h5>@lang('general.hata-2')</h5>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if(isset($_COOKIE['redirect']))
                                <div class="alert alert-info" role="alert">
                                    <h4 class="alert-heading">@lang('general.odemeyeDevamEt')</h4>
                                    <p class="mb-0">@lang('general.odemeyeDevamEtAciklama')</p>
                                </div>
                            @endif
                            <form method="post" action="{{route('fa_auth_sms', $user->login_token)}}">
                                <div class="row">
                                    @csrf
                                    @if(isset($_COOKIE['redirect']))
                                        <input type="hidden" name="adet" value="{{ $_COOKIE['adet'] }}">
                                        <input type="hidden" name="package" value="{{ $_COOKIE['package'] }}">
                                        <input type="hidden" name="redirect" value="{{ $_COOKIE['redirect'] }}">
                                    @endif
                                    <div class="col-12">
                                        <div id="code">
                                            <input class="field code-input" type="number" name="a" placeholder="•"
                                                   autocomplete="off" required autofocus inputmode="numeric" pattern="[0-9]*"/>

                                            <input class="field code-input" type="number" name="b" placeholder="•"
                                                   autocomplete="off" required inputmode="numeric" pattern="[0-9]*"/>

                                            <input class="field code-input" type="number" name="c" placeholder="•"
                                                   autocomplete="off" required inputmode="numeric" pattern="[0-9]*"/>

                                            <input class="field code-input" type="number" name="d" placeholder="•"
                                                   autocomplete="off" required inputmode="numeric" pattern="[0-9]*"/>

                                            <input class="field code-input" type="number" name="e" placeholder="•"
                                                   autocomplete="off" required inputmode="numeric" pattern="[0-9]*"/>

                                            <input class="field code-input" type="number" name="f" placeholder="•"
                                                   autocomplete="off" required inputmode="numeric" pattern="[0-9]*"/>
                                        </div>
                                    </div>
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
                                $degiskenCode = "2fa_code_expired";
                                $kaydedilen = $user->$degiskenCode;
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
                                <div class="col-12 mt-5 text-center">
                                    <button type="submit">Oturum Aç</button>
                                </div>
                                <div class="col-12 text-center mt-3">
                                    <span>Bu telefona erişiminiz yok mu?</span>
                                    <div class="button-register">
                                        <?php $email = "2fa_email"; $google = "2fa_google"; ?>
                                        @if($user->$google == 1)
                                            <a href="{{route('fa_auth_google', $user->login_token)}}">Google OTP İle
                                                Oturum
                                                Aç</a>
                                        @endif
                                        @if($user->$google == 1 and $user->$email == 1)
                                            <span> - veya - </span>
                                        @endif
                                        @if($user->$email == 1)
                                            <a href="{{route('fa_auth', [$user->login_token, 1])}}">E-mail İle
                                                Oturum
                                                Aç</a>
                                        @endif
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>

            </div>


            </div>


        </section>

        @include('front.layouts.structures.footer')
        <script>
            const root = document.documentElement;

            function getCustomPropertyValue(name) {
                const styles = getComputedStyle(root);
                return styles.getPropertyValue(name);
            }

            const fieldset = document.querySelector(".fieldset");
            const fields = document.querySelectorAll(".field");
            const boxes = document.querySelectorAll(".box");

            function handleInputField({target}) {

                let cIndex=$(target).index()
                let inpLength=$("input[class='"+target.className+"']").length;
                console.log(inpLength)

                if(inpLength-1==cIndex && target.value != ""){
                    $("form").submit()
                }

                const value = target.value.slice(0, 1);
                target.value = value;
                const step = value ? 1 : -1;
                const fieldIndex = [...fields].findIndex((field) => field === target);
                const focusToIndex = fieldIndex + step;



                if (focusToIndex < 0 || focusToIndex >= fields.length) return;

                fields[focusToIndex].focus();




            }

            fields.forEach((field) => {
                field.addEventListener("input", handleInputField);
            });

            /* End SMS Code input logic */

            // Controls
            const successBtn = document.querySelector(".success-btn");
            const failureBtn = document.querySelector(".failure-btn");
            const resetBtn = document.querySelector(".reset-btn");

            successBtn.addEventListener("click", (event) => {
                fieldset.classList.add("animate-success");
            });
            resetBtn.addEventListener("click", (event) => {
                fieldset.classList.remove("animate-failure");
                fieldset.classList.remove("animate-success");
            });
            failureBtn.addEventListener("click", (event) => {
                function getDelay() {
                    const firstStepDuration = getCustomPropertyValue(
                        "--transition-duration-step-1"
                    );
                    const secondStepDuration = getCustomPropertyValue(
                        "--transition-duration-step-2"
                    );

                    return parseInt(firstStepDuration) + parseInt(secondStepDuration);
                }

                function animateFailure() {
                    fieldset.classList.add("animate-failure");
                    const delay = getDelay();

                    setTimeout(() => {
                        fieldset.classList.remove("animate-failure");
                    }, delay);
                }

                if (fieldset.classList.contains("animate-success")) {
                    fieldset.classList.remove("animate-success");

                    const delay = parseInt(getCustomPropertyValue("--transition-duration-step-1"))

                    setTimeout(() => {
                        animateFailure();
                    }, delay)

                    return;
                }

                animateFailure();
            });

            const inputs = document.querySelectorAll(".settings-controls__input");

            function setAnimationDuration({target}) {
                const {
                    value,
                    dataset: {step}
                } = target;
                const safeValue = parseInt(value);
                const propertyValue = Number.isNaN(safeValue) ? null : safeValue + "ms";

                root.style.setProperty(`--transition-duration-step-${step}`, propertyValue);
            }

            inputs.forEach((node) => {
                node.addEventListener("input", setAnimationDuration);
            });
        </script>

