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
    <title>Google OTP | {{getSiteName()}}</title>
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
                                <div class="alert alert-danger fade show d-flex align-items-center"
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
                            <form method="post" action="{{route('fa_auth_google', $user->login_token)}}">
                                <div class="row">
                                    @csrf
                                    @if(isset($_COOKIE['redirect']))
                                        <input type="hidden" name="adet" value="{{ $_COOKIE['adet'] }}">
                                        <input type="hidden" name="package" value="{{ $_COOKIE['package'] }}">
                                        <input type="hidden" name="redirect" value="{{ $_COOKIE['redirect'] }}">
                                    @endif
                                    <div class="col-12">
                                        <div id="code">
                                            PIN:
                                            <input class="field code-input" pattern="[0-9]*" inputmode="numeric" type="text" name="pass[]" placeholder="•" maxlength="1"  autocomplete="off" required autofocus>
                                            <input class="field code-input" pattern="[0-9]*" inputmode="numeric" type="text" name="pass[]" placeholder="•" maxlength="1"  autocomplete="off" required>
                                            <input class="field code-input" pattern="[0-9]*" inputmode="numeric" type="text" name="pass[]" placeholder="•" maxlength="1"  autocomplete="off" required>
                                            <input class="field code-input" pattern="[0-9]*" inputmode="numeric" type="text" name="pass[]" placeholder="•" maxlength="1"  autocomplete="off" required>
                                            <input class="field code-input" pattern="[0-9]*" inputmode="numeric" type="text" name="pass[]" placeholder="•" maxlength="1"  autocomplete="off" required>
                                            <input class="field code-input" pattern="[0-9]*" inputmode="numeric" type="text" name="pass[]" placeholder="•" maxlength="1"  autocomplete="off" required>
                                        </div>
                                    </div>
                                    {{-- <div class="col-12 mt-5 text-center">
                                        <button type="submit">Oturum Aç</button>
                                    </div> --}}
                                    <div class="col-12 text-center mt-3">
                                        <span class="header-btn-inline">Bu uygulamaya erişiminiz yok mu?</span>
                                        <div class="button-register">
                                            <?php $sms = "2fa_sms"; $email = "2fa_email"; ?>
                                            @if($user->$email == 1)
                                                <a href="{{route('fa_auth', [$user->login_token, 1])}}">Email İle Oturum Aç</a>
                                            @endif
                                            @if($user->$email == 1 and $user->$sms == 1)
                                                <span> - veya - </span>
                                            @endif
                                            @if($user->$sms == 1)
                                                <a href="{{route('fa_auth_sms', [$user->login_token, 1])}}">Sms İle Oturum Aç</a>
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

            const els = (sel, par) => (par || document).querySelectorAll(sel);
            // Task: multiple inputs "field"

            els("#code").forEach((elGroup) => {

                const elsInput = [...elGroup.children];
                const len = elsInput.length;

                const handlePaste = (ev) => {
                    const clip = ev.clipboardData.getData('text');     // Get clipboard data
                    const pin = clip.replace(/\s/g, "");               // Sanitize string
                    const ch = [...pin];                               // Create array of chars
                    elsInput.forEach((el, i) => el.value = ch[i]??""); // Populate inputs
                    elsInput[pin.length - 1].focus();                  // Focus input

                    if(pin.length==6){$('form').submit();}
                };

                const handleInput = (ev) => {
                    const elInp = ev.currentTarget;
                    const i = elsInput.indexOf(elInp);
                    if (elInp.value && (i+1) % len) elsInput[i + 1].focus();  // focus next
                    if(i==5){$('form').submit();}
                };

                const handleKeyDn = (ev) => {
                    const elInp = ev.currentTarget
                    const i = elsInput.indexOf(elInp);
                    if (!elInp.value && ev.key === "Backspace" && i) elsInput[i - 1].focus(); // Focus previous
                    if(i==5){$('form').submit();}
                };


                // Add the same events to every input in group:
                elsInput.forEach(elInp => {
                    elInp.addEventListener("paste", handlePaste);   // Handle pasting
                    elInp.addEventListener("input", handleInput);   // Handle typing
                    elInp.addEventListener("keydown", handleKeyDn); // Handle deleting
                });

            });

        </script>


