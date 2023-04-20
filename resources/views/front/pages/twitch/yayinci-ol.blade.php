@extends('front.layouts.app')

@section('css')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
pb-40
@endsection

@section('body')

    <section class="bg-gray pt-40 pb-40">

        <div class="container">

            <div class="row">

                @include('front.modules.user-menu')

                <div class="col-md-9">

                    <div class="row">



                        @if(DB::table('twitch_support_streamer')->where('user', Auth::user()->id)->count() > 0)

                            <?php

                            $yayinci = DB::table('twitch_support_streamer')->where('user', Auth::user()->id)->first();

                            ?>

                            @if($yayinci->status == 2)

                                <div class="alert alert-danger" role="alert">

                                    <h4 class="alert-heading">Dikkat!</h4>

                                    <p>Yayıncı kaydınız sitemiz üzerinde mevcuttur fakat streamlabs onaylamasına izin

                                        vermediğiniz gözükmektedir.</p>

                                    <hr>

                                    <p class="mb-0 text-center">

                                        <?php

                                        $link = 'https://streamlabs.com/api/v1.0/authorize?client_id=' . env("STREAM_ID") . '&redirect_uri=' . env("STREAM_URL") . '&response_type=code&scope=donations.read+donations.create';

                                        ?>

                                        <button class="btn btn-outline-primary w-100" type="submit"

                                                onclick="location.href='{{$link}}'">

                                            <img src="{{asset('public/front/images/streamlabs.png')}}" width="32" height="32">'e

                                            Tekrar Bağlan

                                        </button>

                                    </p>

                                </div>

                            @elseif($yayinci->status == 1)

                                <div class="alert alert-success" role="alert">

                                    <h4 class="alert-heading">Tebrikler!</h4>

                                    <p>Şu anda yayıncı olarak twitch support sistemimizde yer alıyorsunuz ve bağış alabilirsiniz.</p>

                                    <hr>

                                    <p class="mb-0 text-center">

                                        <?php

                                        $link = 'https://streamlabs.com/api/v1.0/authorize?client_id=' . env("STREAM_ID") . '&redirect_uri=' . env("STREAM_URL") . '&response_type=code&scope=donations.read+donations.create';

                                        ?>

                                        <button class="btn-inline color-darkgreen" type="submit"

                                                onclick="location.href='{{$link}}'">

                                            <img src="{{asset('public/front/images/streamlabs.png')}}" width="32" height="32">'e

                                            Tekrar Bağlan

                                        </button>

                                    </p>

                                </div>

                            @endif

                        @else



                            @if(session('error'))

                            <!--Mesaj bildirimi--->

                                <div class="alert alert-error d-flex align-items-center" role="alert">

                                    <i class="fas fa-exclamation-triangle me-2"></i>

                                    <div>{{session('error')}}</div>

                                </div>

                                <!--Mesaj bildirim END--->

                            @endif



                            <div class="row">

                                <form class="needs-validation" action="{{route('twitch_support_yayinci_ol_post')}}"

                                      method="POST"

                                      autocomplete="off" novalidate>

                                    @csrf

                                    <div class="row g-3">



                                        <div class="col-md-6">

                                            <label for="1" class="form-label">Sitemizde Görünmesini istediğiniz isminiz</label>

                                            <input type="text" name="title" class="form-control style-input" id="1" required>

                                            <div class="invalid-feedback">

                                                Lütfen bir isim girin

                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <label for="3" class="form-label">En Az Gönderilebilecek Bağış Tutarı</label>

                                            <input type="number" name="min_bagis" class="form-control style-input" id="3" required min="1"

                                                   max="100">

                                            <div class="invalid-feedback">

                                                Lütfen 1 ila 100 arasında bir değer girin.

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-12 mt-5 d-flex justify-content-center">

                                        <button class="btn-inline color-blue" type="submit">

                                            Kaydet ve

                                            <img src="{{asset('public/front/images/streamlabs.png')}}" width="32" height="32">'e

                                            Bağlan

                                        </button>

                                    </div>

                                </form>





                            </div>



                        @endif



                    </div>

                </div>

            </div>

        </div>

    </section>







@endsection

@section('js')

    <script>

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

    </script>

@endsection

