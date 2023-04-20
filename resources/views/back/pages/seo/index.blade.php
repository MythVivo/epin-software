@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}"
          rel="stylesheet">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}"
          rel="stylesheet" type="text/css">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/select2/select2.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link type="text/css"
          href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/x-editable/css/bootstrap-editable.css')}}"
          rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <style>
        table.dataTable.nowrap td:nth-child(2) {
            white-space: unset !important;
        }

        .searchResult {

        }

        .searchResult span {
            font-size: 14px;
            line-height: 1.3;
            display: block;
            color: #2baf86;
        }

        .searchResult h3 {
            display: inline-block;
            line-height: 1.3;
            margin-bottom: 3px;
            font-size: 20px;
            display: block;
            color: #8ab4f8;
            margin-bottom: 3px;
            padding-top: 5px;
            margin-top: 0px;
        }

        .searchResult p {
            line-height: 1.58;
            text-align: left;
            font-size: 14px;
            display: block;

        }

        .spin {
            position: relative;
            min-height: 320px;
            height: 75vh;
            overflow-x: auto;
        }
        .spin .card{
            position: relative;
        }

        .spin .progress {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 11;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spin .progress span {
            display: inline-block;
            width: 103px;
            height: 103px;
            border-radius: 50%;
            box-shadow: 0 0 0 1px #ffffff26, inset 0 0 0 1px #ffffff26;
            margin-top: 100px;
            border: 9px solid #0000002b;
            position: relative;
        }

        .spin .progress i {
            position: absolute;
            font-size: 119px;
            animation-duration: 1s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
            animation-name: loading-bar;
            -webkit-transform: rotate3d(0, 0, 1, 0deg);
            color: #00ff34;
            margin: -19px;
            filter: blur(1px);
            line-height: 1;
            border-radius: 50%;
            width: 124px;
            height: 124px;
        }

        @keyframes loading-bar {
            to {
                -webkit-transform: rotate3d(0, 0, 1, 0deg)
            }
            from {
                -webkit-transform: rotate3d(0, 0, 1, 359deg)
            }
        }

        .resimDuzelt {
            aspect-ratio: 4/3;
            object-fit: cover;
        }
        
        .page-item {
            cursor: pointer;
        }

        .nav {
            width: 100%;
        }
    </style>
@endsection
@section('body')
    @if(session('success'))
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-success alert-success-shadow" role="alert">
                    <i class="mdi mdi-check-all alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{__('admin.basarili')}}</strong> {{__('admin.basariliMetin')}}
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="alert icon-custom-alert alert-outline-danger alert-danger-shadow" role="alert">
                    <i class="mdi mdi-crosshairs alert-icon"></i>
                    <div class="alert-text">
                        <strong>{{__('admin.hata-2')}}</strong>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">

        <div class="col-md-2">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" data-get="meta" id="v-pills-meta-tab" data-toggle="pill" href="#v-pills-meta"
                   role="tab" aria-controls="v-pills-meta" aria-selected="true">Meta Bilgileri</a>
                <a class="nav-link" data-get="media" id="v-pills-media-tab" data-toggle="pill" href="#v-pills-media"
                   role="tab" aria-controls="v-pills-media" aria-selected="false">Medya Ayarlamaları</a>
                <a class="nav-link" data-get="oyunlar" id="v-pills-oyunlar-tab" data-toggle="pill" href="#v-pills-oyunlar"
                   role="tab" aria-controls="v-pills-oyunlar" aria-selected="false">Oyun Meta</a>
                <a class="nav-link" data-get="oyun_alt" id="v-pills-oyun_alt-tab" data-toggle="pill" href="#v-pills-oyun_alt"
                   role="tab" aria-controls="v-pills-oyun_alt" aria-selected="false">Oyun Alt Başlıklar Meta</a>
                <a class="nav-link" data-get="muve" id="v-pills-muve-tab" data-toggle="pill" href="#v-pills-muve"
                   role="tab" aria-controls="v-pills-muve" aria-selected="false">Muve Oyunları Meta</a>
            </div>
        </div>
        <div class="col-md-2 spin" id="info-area"></div>
        <div class="col-md-8 spin" id="data-area"></div>

    </div>

@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" type="text/javascript"></script>
    <script>
        let area1 = function () {
            if (!$("#data-area").hasClass("loading")) {
                $("#data-area").addClass("loading")
                $("#data-area").prepend("<div class='progress'><span><i class='mdi mdi-loading'></i></span></div>")
                $("#data-area").scrollTop(0)
            }
        }

        let area2 = function () {
            if (!$("#info-area").hasClass("loading")) {
                $("#info-area").addClass("loading")
                $("#info-area").prepend("<div class='progress'><span><i class='mdi mdi-loading'></i></span></div>")
            }
        }


        $('#v-pills-tab a').on('click', function (event) {
            seoPanel(event.currentTarget);
        })
        let seoPanel = function (event) {
            area1();
            area2();
            let gidilecek = $(event).data("get");
            $.get("{{route('seo_yonetim_view')}}?getir=" + gidilecek, function (data) {
                $("#info-area").html(data);
                $("#info-area").removeClass("loading")
            });
        }
        let seoPanel2 = function (event) {
            area1();
            let gidilecek = $(event).data("get");
            $.get("{{route('seo_yonetim_view')}}?getir=meta_data&id=" + gidilecek, function (data) {
                $("#data-area").html(data);
                $("#data-area").removeClass("loading")
            });
        }
        let seoPanel3 = function (event, page = 'page=0') {
            area1();
            let gidilecek = $(event).data("get");
            $.get("{{route('seo_yonetim_view')}}?getir=media_data&id=" + gidilecek + '&' + page, function (data) {
                $("#data-area").html(data);
                $("#data-area").removeClass("loading")
            });
        }

        let seoPanel4 = function (event) {
            area1();
            let gidilecek = $(event).data("get");
            $.get("{{route('seo_yonetim_view')}}?getir=oyunlar_data&id=" + gidilecek, function (data) {
                $("#data-area").html(data);
                $("#data-area").removeClass("loading")
            });
        }

        let seoPanel5 = function (event) {
            area1();
            let gidilecek = $(event).data("get");
            $.get("{{route('seo_yonetim_view')}}?getir=oyun_alt_data&id=" + gidilecek, function (data) {
                $("#data-area").html(data);
                $("#data-area").removeClass("loading")
            });
        }

        let seoPanel6 = function (event) {
            area1();
            let gidilecek = $(event).data("get");
            $.get("{{route('seo_yonetim_view')}}?getir=muve_data&id=" + gidilecek, function (data) {
                $("#data-area").html(data);
                $("#data-area").removeClass("loading")
            });
        }

        seoPanel(document.getElementById("v-pills-meta-tab"))
        area1();
        area2();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"
            type="text/javascript"></script>
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>


    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/select2/select2.min.js')}}"></script>
    <script>
        $(".select2").select2({
            width: '100%'
        });
    </script>
    <script>
        $(function () {
            $('.dropify').dropify({
                height: "300",
                allowedFileExtensions: ["png", "jpg", "jpeg", "gif"],
                messages: {
                    default: '{{__('admin.dropifyResimSec')}}',
                    replace: '{{__('admin.dropifyDegistir')}}',
                    remove: '{{__('admin.dropifySil')}}',
                    error: '{{__('admin.dropifyHata')}}',
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').DataTable({
                columnDefs: [{orderable: false, targets: [6]}],
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
                    "zeroRecords": "{{__('admin.eselsen-veri-bulunamadi')}}",
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
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/tinymce/tinymce.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            if ($("#3").length > 0) {
                tinymce.init({
                    selector: ".editorText",
                    theme: "modern",
                    language: '{{getLang()}}',
                    height: 300,
                    plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "save table contextmenu directionality emoticons template paste textcolor"
                    ],
                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
                    style_formats: [
                        {title: 'Bold text', inline: 'b'},
                        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                        {title: 'Example 1', inline: 'span', classes: 'example1'},
                        {title: 'Example 2', inline: 'span', classes: 'example2'},
                        {title: 'Table styles'},
                        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                    ]
                });
            }
        });
    </script>
@endsection
