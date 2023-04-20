@extends('front.layouts.app')
@section('css')
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        .modal {
            z-index: 9999
        }
    </style>
@endsection

@section('body')
    <?php
    $yayinci = DB::table('twitch_support_streamer')
        ->where('user', Auth::user()->id)
        ->first();
    ?>

    <section class="pt-40 pb-40">
        <div class="container">
            <div class="row">
                @include('front.modules.user-menu')
                <div class="col-md-9">
                    <div class="row">
                        @if (session('success'))
                            <!--Mesaj bildirimi--->
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fas fa-check me-2"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                            <!--Mesaj bildirim END--->
                        @endif
                        @if (session('error'))
                            <!--Mesaj bildirimi--->
                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
                                role="alert">
                                <i class="fas fa-exclamation-triangle me-3"></i>
                                <h5>{{ session('error') }}</h5>
                            </div>
                            <!--Mesaj bildirim END--->
                        @endif
                    </div>
                    <?php
                    $kesintisiz = DB::table('twitch_kesintisiz_yayinci')
                        ->where('streamer', Auth::user()->id)
                        ->whereNull('deleted_at')
                        ->orderBy('created_at', 'desc');
                    if ($kesintisiz->count() > 0 && $kesintisiz->first()->status == 2) {
                        $twitch_url = $kesintisiz->first()->twitch_url;
                        $twitch_clip_link = $kesintisiz->first()->twitch_clip_link;
                        $text = $kesintisiz->first()->text;
                    } else {
                        $twitch_url = '';
                        $twitch_clip_link = '';
                        $text = '';
                    }
                    ?>

                    @if ($kesintisiz->count() < 1 or $kesintisiz->count() > 0 and $kesintisiz->first()->status == 2)
                        <div class="row g-3">
                            <div class="col-sm-12">
                                <form class="needs-validation"
                                    action="{{ route('twitch_support_yayinci_kesintisiz_post') }}" method="POST"
                                    autocomplete="off" novalidate>
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="1" class="form-label">Twitch Kanal URL</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text style-input">https://twitch.tv/</span>
                                                <input type="text" id="1" name="twitch_url"
                                                    class="form-control style-input" required value="{{ $twitch_url }}">
                                            </div>
                                            <div class="invalid-feedback">Lütfen geçerli bir url girin</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="2" class="form-label">Logomuz Bulunan Bir Klip Linki</label>
                                            <input type="text" name="twitch_clip_link" class="form-control style-input"
                                                id="2" required value="{{ $twitch_clip_link }}">
                                            <div class="invalid-feedback">Lütfen geçerli bir url girin</div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="3" class="form-label">Açıklama</label>
                                            <textarea class="form-control" name="text" id="3">{{ $text }}</textarea>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <input type="hidden" name="streamer" value="{{ Auth::user()->id }}">
                                            @if ($kesintisiz->count() > 0 and $kesintisiz->first()->status == 2)
                                                <button class="btn-inline color-blue w-100">Başvuruyu Tekrar Gönder</button>
                                            @else
                                                <button class="btn-inline color-blue w-100">Kesintisiz Yayıncı Ol</button>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    <div class="row g-3 mt-3">
                        @if ($kesintisiz->count() > 0)
                            @if ($kesintisiz->first()->status == 0)
                                <div class="alert alert-info alert-dismissible fade show d-flex align-items-center"
                                    role="alert">
                                    <h5>Başvurunuz henüz onay aşamasındadır. Başvurunuz hakkında bilgi almak için bizimle
                                        iletişime geçebilirsiniz.</h5>
                                </div>
                            @elseif($kesintisiz->first()->status == 1)
                                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center"
                                    role="alert">
                                    <i class="fas fa-exclamation-triangle me-3"></i>
                                    <h5>Artık kesintisiz yayıncısınız! Yapacağınız tüm bağış bakiye çekme işlemlerinde
                                        kesinti uygulanmayacaktır.</h5>
                                </div>
                            @else
                                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
                                    role="alert">
                                    <i class="fas fa-exclamation-triangle me-3"></i>
                                    <h5>Kesintisiz yayıncı başvurunuz reddedilmiştir. Red Nedeni :
                                        {{ $kesintisiz->first()->red_neden }}</h5>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
                                role="alert">
                                <i class="fas fa-exclamation-triangle me-3"></i>
                                <h5>Henüz kesintisiz yayıncı başvurunuz bulunmuyor. Başvurunuzun ardından durumunu bu
                                    ekrandan görüntüleyebilirsiniz.</h5>
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
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
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
