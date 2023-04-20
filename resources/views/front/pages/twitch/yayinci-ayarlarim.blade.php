@if(isset($_GET['sil']))
    <?php
    DB::table('odeme_kanallari')->where('id', $_GET['sil'])->update([
        'deleted_at' => date('YmdHis'),
    ]);
    header('Location: ?okey');
    exit;
    ?>
@endif
@extends('front.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <style>
        .modal {
            z-index: 9999;
        }
    </style>
@endsection
@section('body')
    <?php
    $yayinci = DB::table('twitch_support_streamer')->where('user', Auth::user()->id)->first();
    ?>
    <section class="pb-40 bg-gray">
        <div class="container">
            <div class="row">
                @include('front.modules.user-menu')
                <div class="col-md-9">
                    <div class="alert alert-success text-center" role="alert">
                        <?php
                        $link = 'https://streamlabs.com/api/v1.0/authorize?client_id=' . env("STREAM_ID") . '&redirect_uri=' . env("STREAM_URL") . '&response_type=code&scope=donations.read+donations.create';
                        ?>
                        <span style="font-size: 16px;font-weight: bold;margin-right:50px">Şu anda yayıncı olarak twitch support sistemimizde yer alıyorsunuz ve bağış alabilirsiniz.</span>
                        <button style="padding: 5px 15px" class="btn-inline color-darkgreen" type="submit" onclick="location.href='{{$link}}'">
                            <img src="{{asset('public/front/images/streamlabs.png')}}" width="32" height="32">'e
                            Tekrar Bağlan
                        </button>
                    </div>
                    @php
                    $kesintisiz = DB::table('twitch_kesintisiz_yayinci')
                        ->where('streamer', Auth::user()->id)
                        ->whereNull('deleted_at')
                        ->orderBy('created_at', 'desc');    
                        $kesintisizVar =  $kesintisiz->count() > 0 && $kesintisiz->first()->status == 2 ; 
                    @endphp

                    @if(!$kesintisizVar)
                        <a href="{{route('twitch_support_yayinci_kesintisiz')}}" class="alert alert-danger alert-dismissible fade show d-flex align-items-center text-center" role="alert">
                            <h5>
                                <i class="fas fa-exclamation-triangle"></i>
                                Henüz kesintisiz yayıncı başvurunuz bulunmuyor. Başvuru koşullarını görmek ve başvurmak için tıklayınız.
                            </h5>
                        </a>
                    @endif
                    <div class="row">
                    @if(session('success'))
                        <!--Mesaj bildirimi--->
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fas fa-check me-2"></i>
                                <div>{{session('success')}}</div>
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
                    </div>
                    <form class="needs-validation" action="{{route('twitch_support_yayinci_ayarlarim_post')}}"
                          method="POST"
                          autocomplete="off" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="1" class="form-label">Yayıncı Adı</label>
                                        <input type="text" name="title" class="form-control style-input" id="1"
                                               value="{{$yayinci->title}}" required>
                                        <div class="invalid-feedback">
                                            Lütfen geçerli bir isim girin.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="2" class="form-label">Minimum Bağış Tutarı</label>
                                        <input type="number" name="min_bagis" class="form-control style-input" id="2"
                                               required
                                               min="1"
                                               max="100" value="{{$yayinci->min_bagis}}">
                                        <div class="invalid-feedback">
                                            Lütfen 1 ila 100 arasında bir değer girin.
                                        </div>
                                    </div>

                                    @foreach(DB::table('bildirim_kategorileri')->where('title', 'like', '%twitch%')->get() as $bk)
                                        <div class="col-md-4">
                                            <label for="bildirim{{$bk->id}}" class="form-label">{{$bk->title}}</label>
                                            <div class="form-check form-switch">
                                                <label for="bildirim{{$bk->id}}"
                                                       class="form-check-label">{{$bk->text}}</label>
                                                <input name="{{Str::slug($bk->title)}}" id="bildirim{{$bk->id}}"
                                                       class="form-check-input"
                                                       type="checkbox"
                                                       @if(kullaniciBildirimKategorisi(Auth::user()->id, $bk->id)) checked @endif>
                                                <div class="switcher"><span><i></i></span></div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="col-12 mt-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="text-success">
                                                    @if($yayinci->yayin_link != NULL)
                                                        Yayıncı Linkiniz : <a
                                                                href="{{route('twitch_support_yayinci', $yayinci->yayin_link)}}"
                                                                target="_blank">{{route('twitch_support_yayinci', $yayinci->yayin_link)}}</a>
                                                    @else
                                                        Yayıncı Linkiniz : Yayıncı linkiniz bulunmuyor, tekrar
                                                        bağlanmayı deneyebilirsiniz.
                                                    @endif
                                                </h5>

                                                <h5 class="text-warning">
                                                    Örnek Logo Kullanımları : <a href="{{route('marka_yonergeleri')}}">Marka
                                                        Yönergeleri</a>
                                                </h5>
                                                <hr>
                                                <h5 class="text-info">
                                                    Referans Linkiniz : <a
                                                            href="{{route('kayit')}}?ref={{$yayinci->yayin_link}}"
                                                            target="_blank">
                                                        {{route('kayit')}}?ref={{$yayinci->yayin_link}}
                                                    </a>
                                                    <br>
                                                    <small class="text-danger">Dikkat! Referans linkiniz ile kayıt olan
                                                        kullanıcılara bu bildirilecek ve kayıt olan kullanıcı telefon
                                                        numarasını onaylayana kadar geçerli bir referans
                                                        sayılmayacaktır.</small>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-5">
                            <button class="btn-inline color-darkgreen" type="submit">Kaydet</button>
                        </div>
                    </form>


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
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#datatable').DataTable({
                pageLength: 10,
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
                    "zeroRecords": "{{__('admin.eslesen-veri-bulunamadi')}}",
                    "paginate": {
                        "first": "{{__('admin.ilk')}}",
                        "last": "{{__('admin.son')}}",
                        "next": "{{__('admin.sonraki')}}",
                        "previous": "{{__('admin.onceki')}}"
                    },
                }
            });

            $('#datatable2').DataTable({
                pageLength: 10,
                "order": [[0, "desc"]],
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
        });
    </script>
@endsection
