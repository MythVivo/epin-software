@extends('front.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('body')
    <?php
    $yayinciDB = DB::table('twitch_support_streamer')->where('yayin_link', $yayinci)->whereNull('deleted_at')->first();
    if($yayinciDB == NULL) {
        header("Location: " . URL::to(route('errors_404')), true, 302);
        exit();
    }
    $yayinciInfo = getStreamUserInfoById($yayinciDB->id);
    ?>
    <script src="https://player.twitch.tv/js/embed/v1.js"></script>
    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">
                @if(session('error'))
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            <p>{{session('error')}}</p>
                        </div>
                    </div>
                @endif
                @if(session('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success" role="alert">
                            <p>{{session('success')}}</p>
                        </div>
                    </div>
                @endif
                @if(isset($yayinciInfo->twitch))
                    <div class="col-md-8">
                        <div id="{{$yayinciInfo->twitch->name}}"></div>
                        <script type="text/javascript">
                            var options = {
                                width: "100%",
                                height: "650px",
                                channel: "{{$yayinciInfo->twitch->name}}",
                                parent: ["{{env('APP_URL_CLEAR')}}"]
                            };
                            var player = new Twitch.Player("{{$yayinciInfo->twitch->name}}", options);
                            player.setVolume(0.5);
                        </script>
                    </div>
                    <div class="col-md-4 tw-chat-panel">
                        @if(isset($_COOKIE['theme']) and $_COOKIE['theme'] == "dark")
                            @php $theme = "darkpopout" @endphp
                        @else
                            @php $theme = "" @endphp
                        @endif
                        <iframe src="https://www.twitch.tv/embed/{{$yayinciInfo->twitch->name}}/chat?{{$theme}}&parent={{env('APP_URL_CLEAR')}}"
                                height="400px"
                                width="100%">
                        </iframe>

                    </div>
                    <div class="col-md-12">
                        @if(isset(Auth::user()->id))
                            @if($yayinciDB->user != Auth::user()->id)
                                <div class="card tw-support-card mt-3">
                                    <div class="card-body">
                                        <form id="supportForm" class="needs-validation"
                                              action="{{route('twitch_support_yayinci_support')}}"
                                              method="POST"
                                              autocomplete="off" novalidate onsubmit="return false;">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5 class="card-title text-start">Yayıncı Destekle</h5>
                                                </div>
                                                <div class="col-md-6 ">
                                                    <h5 class="card-title text-end"><i
                                                                class="fas fa-wallet"></i> <span
                                                                class="bakiye2">{{Auth::user()->bakiye}}</span> TL
                                                    </h5>
                                                </div>
                                                <div class="col-12">
                                                    <hr>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="form-group">
                                                        <label for="1">Görünecek İsim</label>
                                                        <input id="1" type="text" class="form-control style-input"
                                                               name="name">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="form-group">
                                                        <label for="2">Mesaj</label>
                                                        <input id="2" type="text" class="form-control style-input"
                                                               name="message">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="form-group">
                                                        <label for="destek">Destek Tutarı</label>
                                                        <input id="destek" type="number" step="0.01"
                                                               min="{{$yayinciDB->min_bagis}}"
                                                               class="form-control style-input"
                                                               name="amount" max="{{Auth::user()->bakiye}}" required>
                                                        <div class="invalid-feedback">
                                                            Lütfen geçerli bir tutar girin
                                                            (Minimum {{$yayinciDB->min_bagis}} TL,
                                                            Maksimum {{Auth::user()->bakiye}} TL)
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-end">
                                                    <input type="hidden" name="id" value="{{Auth::user()->id}}">
                                                    <input type="hidden" name="yayinci" value="{{$yayinciDB->id}}">
                                                    <button class="btn-inline color-darkgreen">Destekle</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">
                                            Kendinizi destekleyemezsiniz!
                                        </h4>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">
                                        Yayıncıyı desteklemek için giriş yapmanız gerekiyor.
                                    </h4>
                                </div>
                            </div>
                        @endif


                        @endif
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
        $("#supportForm").submit(function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function (data) {
                    if (data) {
                        toastr.success("Gönderdiğiniz donate başarıyla ulaşmıştır.", "Tebrikler");
                        var bakiye = $(".bakiye2").text();
                        var tutar = $("#destek").val();
                        $(".bakiye").text(Number(bakiye - tutar).toFixed(2));
                        $(".bakiye2").text(Number(bakiye - tutar).toFixed(2));
                    } else {
                        toastr.error("Donate gönderilemedi.", "Dikkat")
                    }
                }
            });

            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "showDuration": "0",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }


        });
    </script>
@endsection
