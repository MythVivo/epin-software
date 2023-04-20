@if (isset($_GET['kimlikOnayla']))
    <?php
    DB::table('users')
        ->where('id', $_GET['kimlikOnayla'])
        ->update(['tc_verified_at' => date('YmdHis')]);
    header('Location: ?okey');
    die();
    exit();
    ?>
@endif

<?
if(@$_GET['email_onay']){
    DB::table('users')->where('id', $_GET['email_onay'])->update(['email_verified_at' => date('YmdHis')]);
    header('Location: ?okey');
    die();
}

?>

@extends('back.layouts.app')
@section('css')
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/dropify/css/dropify.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/sweet-alert2/sweetalert2.min.css') }}"
        rel="stylesheet" type="text/css">
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/select2/select2.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link type="text/css"
        href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/x-editable/css/bootstrap-editable.css') }}"
        rel="stylesheet">
    <style>
        table.dataTable.nowrap td:nth-child(2) {
            white-space: unset !important;
        }
    </style>
@endsection
@section('body')
    <?php
    $user = \App\Models\User::where('email', $email)->first();
    ?>

    @if (session('success'))
        <div class="row" data-lang="{{ getLang() }}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-success alert-success-shadow" role="alert">
                    <i class="mdi mdi-check-all alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{ __('admin.basarili') }}</strong> {{ __('admin.basariliMetin') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="row" data-lang="{{ getLang() }}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-danger alert-danger-shadow" role="alert">
                    <i class="mdi mdi-crosshairs alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{ __('admin.hata-2') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body  met-pro-bg">
                    <div class="met-profile">
                        <div class="row">
                            <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                                <div class="met-profile-main">
                                    <div class="met-profile-main-pic">
                                        @if (getUserAvatar() == '')
                                            <img src="{{ asset('public/brand/brandicon.png') }}"
                                                alt="{{ $user->name }} avatar" class="rounded-circle thumb-xl "
                                                style="width: 100%;">
                                        @else
                                            <img src="{{ asset(env('ROOT') . env('FRONT') . env('AVATARS') . getUserAvatar()) }}"
                                                alt="{{ $user->name }} avatar" class="rounded-circle thumb-xl "
                                                style="width: 100%;">
                                        @endif
                                    </div>

                                    <div class="met-profile_user-detail">
                                        <h3 class="mb-0 text-success"># {{ $user->id }}
                                            <i title="Üye Hareket İzleme" onclick="if (!window.__cfRLUnblockHandlers) return false; window.open('https://oyuneks.com/panel/uye-aktivite?uid={{ $user->id }}')" class="btn-gradient-danger btn fa fa-search" aria-hidden="true"></i>
                                        </h3>
                                        <h5 class="met-user-name">{{ $user->name }}</h5>
                                        <p class="mb-0 met-user-name-post">{{ $user->username }}</p>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4 ml-auto">
                                <ul class="list-unstyled personal-detail">
                                    <li class=""><i class="dripicons-phone mr-2 text-info font-18"></i>
                                        <b> @lang('admin.uyeTelefon') </b>
                                        :
                                        +{{ $user->telefon_country }} {{ $user->telefon }} {!! getUserPhoneStatus($user->id) !!}
                                    </li>
                                    <li class="mt-2"><i class="dripicons-mail text-info font-18 mt-2 mr-2"></i>
                                        <b> @lang('admin.uyeEposta') </b>
                                        : {{ $user->email }} {!! getUserEmailStatus($user->id) !!}
                                        @if ($user->email_verified_at == null)
                                            <button class="btn btn-sm btn-success" onclick="location.href='?email_onay={{$user->id}}'">Onayla</button>
                                        @endif
                                    </li>
                                    <li class="mt-2"><i class="dripicons-card text-info font-18 mt-2 mr-2"></i>
                                        <b> Kimlik Durumu </b>
                                        :
                                        @if ($user->tc_verified_at == null)
                                            <button class="btn btn-sm btn-success" onclick="location.href='?kimlikOnayla={{ $user->id }}'">Onayla </button>
                                        @endif

                                        {!! getUserTcnoStatus($user->id) !!}
                                    </li>
                                    <li class="mt-2"><i class="dripicons-wallet  text-info font-18 mt-2 mr-2"></i>
                                        <b>@lang('admin.uyeBakiye')</b> : ₺{{ MF($user->bakiye) }} /
                                        ₺{{ MF($user->bakiye_cekilebilir) }}
                                    </li>
                                    <?php
                                    $yayinci = DB::table('twitch_support_streamer')
                                        ->where('user', $user->id)
                                        ->first();
                                    ?>
                                    @if ($yayinci)
                                        <li class="mt-2"><i class="dripicons-link text-info font-18 mt-2 mr-2"></i>
                                            <b>Ref Linki</b> : <a
                                                href="{{ route('kayit') }}?ref={{ $yayinci->yayin_link }}"
                                                target="_blank">{{ route('kayit') }}?ref={{ $yayinci->yayin_link }}</a>
                                        </li>
                                        <li class="mt-2"><i class="dripicons-wallet text-success font-18 mt-2 mr-2"></i>
                                            <b>Yayın Bakiyesi</b> : {{ $user->bagis_bakiye }}
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end f_profile-->
                </div>
                <!--end card-body-->
                <div class="card-body">
                    <ul class="nav nav-pills mb-0" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="genel_tab" data-toggle="pill"
                                href="#genel">@lang('admin.uyeGenel')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sifre_tab" data-toggle="pill" href="#sifre">@lang('admin.uyeSifreAyar')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="guvenlik_tab" data-toggle="pill" href="#guvenlik">@lang('admin.uyeGuvenlik')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="bildirim_tab" data-toggle="pill" href="#bildirim">@lang('admin.uyeBildirim')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="activity_detail_tab" data-toggle="pill"
                                href="#aktivite">@lang('admin.uyeAktivite')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="bakiye_detail_tab" data-toggle="pill" href="#bakiye">Bakiye</a>
                        </li>
                    </ul>
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
    <div class="row">
        <div class="col-12">
            <form method="post" action="{{ route('uye_edit') }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">
                <div class="tab-content detail-list" id="pills-tabContent">
                    @include('back.pages.users.tabs.genel')
                    @include('back.pages.users.tabs.sifre')
                    @include('back.pages.users.tabs.guvenlik')
                    @include('back.pages.users.tabs.bildirim')
                    @include('back.pages.users.tabs.aktivite')
                    @include('back.pages.users.tabs.bakiye')
                </div>
                <!--end tab-content-->
                <div class="card">
                    <div class="card-footer text-right">
                        <input type="hidden" name="bakiye_ekle" value="0">
                        <button type="submit"
                            class="btn btn-lg btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                    </div>
                </div>
            </form>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js" type="text/javascript"></script>
    <script>
        $('input[name="telefon"]').mask('(000) 000 00-00');
        $('input[name="tcno"]').mask('00000000000');
    </script>
    <script src="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/jquery.dataTables.min.js') }}">
    </script>
    <script
        src="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/dataTables.bootstrap4.min.js') }}">
    </script>


    <script src="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/select2/select2.min.js') }}"></script>
    <script>
        $(".select2").select2({
            width: '100%'
        });
    </script>
    <script>
        $(function() {
            $('.dropify').dropify({
                height: "300",
                allowedFileExtensions: ["png", "jpg", "jpeg", "gif"],
                messages: {
                    default: '{{ __('admin.dropifyResimSec') }}',
                    replace: '{{ __('admin.dropifyDegistir') }}',
                    remove: '{{ __('admin.dropifySil') }}',
                    error: '{{ __('admin.dropifyHata') }}',
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [6]
                }],
                pageLength: 10,
                "order": [
                    [4, "desc"]
                ],
                "language": {
                    "decimal": "",
                    "emptyTable": "{{ __('admin.hic-veri-yok') }}",
                    "info": "{{ __('admin.adet-veri-gosterim', ['TOTAL' => '_TOTAL_', 'START' => '_START_', 'END' => '_END_']) }}",
                    "infoEmpty": "{{ __('admin.sifir-veri-var') }}",
                    "infoFiltered": "{{ __('admin.adet-veri-araniyor', ['MAX' => '_MAX_']) }}",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "{{ __('admin.veri-gosteriliyor', ['MENU' => '_MENU_']) }}",
                    "loadingRecords": "{{ __('admin.yukleniyor') }}",
                    "processing": "{{ __('admin.isleniyor') }}",
                    "search": "{{ __('admin.ara') }}",
                    "zeroRecords": "{{ __('admin.eselsen-veri-bulunamadi') }}",
                    "paginate": {
                        "first": "{{ __('admin.ilk') }}",
                        "last": "{{ __('admin.son') }}",
                        "next": "{{ __('admin.sonraki') }}",
                        "previous": "{{ __('admin.onceki') }}"
                    },
                }
            });
        });
    </script>
    <script src="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/sweet-alert2/sweetalert2.min.js') }}">
    </script>
    <script>
        function deleteContent(table, id) {
            swal.fire({
                title: "{{ __('admin.silme') }}",
                text: "{{ __('admin.silmeText') }}",
                icon: "info",
                type: 'error',
                showCancelButton: true,
                confirmButtonText: '{{ __('admin.onayliyorum') }}',
                cancelButtonText: '{{ __('admin.vazgec') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get("{{ route('deleteContent') }}", {
                        table: table,
                        id: id
                    });
                    swal.fire(
                        '{{ __('admin.basarili') }}',
                        '{{ __('admin.basariliMetin') }}',
                        'success'
                    )
                    setTimeout(function() {
                        location.reload();
                    }, 2000);

                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire(
                        '{{ __('admin.iptal-edildi') }}',
                        '',
                        'error'
                    )
                }
            })
        }
    </script>
    <script src="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            if ($("#3").length > 0) {
                tinymce.init({
                    selector: ".editorText",
                    theme: "modern",
                    language: '{{ getLang() }}',
                    height: 300,
                    plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "save table contextmenu directionality emoticons template paste textcolor"
                    ],
                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
                    style_formats: [{
                            title: 'Bold text',
                            inline: 'b'
                        },
                        {
                            title: 'Red text',
                            inline: 'span',
                            styles: {
                                color: '#ff0000'
                            }
                        },
                        {
                            title: 'Red header',
                            block: 'h1',
                            styles: {
                                color: '#ff0000'
                            }
                        },
                        {
                            title: 'Example 1',
                            inline: 'span',
                            classes: 'example1'
                        },
                        {
                            title: 'Example 2',
                            inline: 'span',
                            classes: 'example2'
                        },
                        {
                            title: 'Table styles'
                        },
                        {
                            title: 'Table row 1',
                            selector: 'tr',
                            classes: 'tablerow1'
                        }
                    ]
                });
            }
        });
    </script>
@endsection
