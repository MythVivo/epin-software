@extends('back.layouts.app')
@section('css')
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/dropify/css/dropify.min.css')}}"  rel="stylesheet">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/sweet-alert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/select2/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/x-editable/css/bootstrap-editable.css')}}" rel="stylesheet" type="text/css">
    <style>
        table.dataTable.nowrap td:nth-child(2) {white-space: unset !important;}
    </style>
@endsection
@section('body')

    <?php
    $u = \App\Models\Settings::first();
    ?>

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
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills mb-0" id="pills-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="genel_tab" data-toggle="pill" href="#genel">Genel</a></li>
                        <li class="nav-item"><a class="nav-link" id="iletisim_tab" data-toggle="pill" href="#iletisim">İletişim</a></li>
                        <li class="nav-item"><a class="nav-link" id="sosyal_tab" data-toggle="pill" href="#sosyal">Sosyal</a></li>
                        <li class="nav-item"><a class="nav-link" id="finans_tab" data-toggle="pill" href="#finans">Finans</a></li>
                        <li class="nav-item"><a class="nav-link" id="sms_tab" data-toggle="pill" href="#sms">Sms</a></li>
                        <li class="nav-item"><a class="nav-link" id="mail_tab" data-toggle="pill" href="#mail">Mail</a></li>
                        <li class="nav-item"><a class="nav-link" id="api_tab" data-toggle="pill" href="#api">API</a></li>
                        <li class="nav-item"><a class="nav-link" id="satis_tab" data-toggle="pill" href="#satis">Satış Sistemi</a></li>
                        <li class="nav-item"><a class="nav-link" id="bakiye_tab" data-toggle="pill" href="#bakiye">Bakiye Takip</a></li>
                        <li class="nav-item"><a class="nav-link" id="fatura_tab" data-toggle="pill" href="#faturalar">Faturalar</a></li>
                        <li class="nav-item"><a class="nav-link" id="odemeler_tab" data-toggle="pill" href="#odemeler">Ödemeler</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <form method="post" action="{{route('site_yonetim_post')}}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="tab-content detail-list" id="pills-tabContent">
                    @include('back.pages.site-yonetim.tabs.genel')
                    @include('back.pages.site-yonetim.tabs.iletisim')
                    @include('back.pages.site-yonetim.tabs.sosyal')
                    @include('back.pages.site-yonetim.tabs.finans')
                    @include('back.pages.site-yonetim.tabs.sms')
                    @include('back.pages.site-yonetim.tabs.mail')
                    @include('back.pages.site-yonetim.tabs.api')
                    @include('back.pages.site-yonetim.tabs.satis')
                    @include('back.pages.site-yonetim.tabs.bakiye')
                    @include('back.pages.site-yonetim.tabs.faturalar')
                    @include('back.pages.site-yonetim.tabs.odemeler')
                </div>
                <div class="card">
                    <div class="card-footer text-right">
                        <button type="submit"
                                class="btn btn-lg btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"
            type="text/javascript"></script>
    <script>
        $('input[name="telefon"]').mask('(000) 000 00-00');
        $('input[name="tcno"]').mask('00000000000');
    </script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
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
    <script>
        function deleteContent(table, id) {
            swal.fire({
                title: "{{__("admin.silme")}}",
                text: "{{__("admin.silmeText")}}",
                icon: "info",
                type: 'error',
                showCancelButton: true,
                confirmButtonText: '{{__("admin.onayliyorum")}}',
                cancelButtonText: '{{__("admin.vazgec")}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get("{{route('deleteContent')}}", {table: table, id: id});
                    swal.fire(
                        '{{__("admin.basarili")}}',
                        '{{__("admin.basariliMetin")}}',
                        'success'
                    )
                    setTimeout(function () {
                        location.reload();
                    }, 2000);

                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire(
                        '{{__("admin.iptal-edildi")}}',
                        '',
                        'error'
                    )
                }
            })
        }

    </script>
    <script src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/tinymce/tinymce.min.js')}}"></script>
    <script>
        $('.o_off').click(function (){
            let id=$(this).attr('id').split('_')[1];
            let dr=$(this).prop('checked')?'true':'false';
            let js='{"limit": '+$('#l_'+id).val()+',"active":'+ dr +'}';
            $.post('/ykp.php',{odemeler:id,ojs:js},function (){
                swal.fire({icon:'success',title:'Kaydedildi..',showConfirmButton: false,timer:1500});
            });
        });

        $('.o_lmt').change(function (){
            let id=$(this).attr('id').split('_')[1];
            let dr=$('#c_'+id).prop('checked')?'true':'false';
            let js='{"limit": '+$('#l_'+id).val()+',"active":'+ dr +'}';
            $.post('/ykp.php',{odemeler:id,ojs:js},function (){
                swal.fire({icon:'success',title:'Kaydedildi..',showConfirmButton: false,timer:1500});
            });
        });

        $('.om_lmt').change(function (){
            let id=$(this).attr('id').split('_')[1];
            var deg;
            var tut=$(this).val();
            if(id=='bulut') {deg='banka_max';} else {deg='papara_max';}
            $.post('/ykp.php',{max_lmt:deg,tutar:tut},function (){
                swal.fire({icon:'success',title:'Kaydedildi..',showConfirmButton: false,timer:1500});
            });
        });

        $('.rad').click(function (){
            //console.log($(this).attr('id') + '---'+ $(this).val() )
            $.post('/ykp.php',{siparis:$(this).val(), oid:$(this).attr('id')});
        });

        $('.uyar').blur(function (){
            if($(this).val()=='') {$(this).val('0');}
            $.post('/ykp.php',{uyar:'_'+$(this).val(), oid:$(this).attr('id')});
        });

        $('.skapat').click(function (){
            let f=$(this).prop('checked')?'1':'0';
            $.post('/ykp.php',{kapat:'_'+ f, oid:$(this).attr('id')});
        });

        $('.pkapat').click(function (){
            let f=$(this).prop('checked')?'1':'0';
            $.post('/ykp.php',{paket:'_'+ f, oid:$(this).attr('id')});
        });

        $('.trr').click(function (){
            $('#trg_'+$(this).attr('id').split('_')[1]).toggle('slow');
        });

         $('.lmtx').blur(function (){
             var ido =$(this).attr('ooid');var dego=$(this).val();var bu=$(this);$(this).addClass('border-success');setTimeout(function (){bu.removeClass('border-success')},700);
             if(dego==''){$(this).val(0)}
             $.post('/ykp.php', {set_limit:316, kanal:ido, tutar:dego , _token: $('meta[name="csrf-token"]').attr('content')}, function (x) {});
         });

         $('.off').click(function(){
            var off=$(this).prop('checked')?'1':'0';
            var tip=$(this).attr('id').slice(-1);
            $.post('/ykp.php', {oto_fat:tip, durum:off, _token: $('meta[name="csrf-token"]').attr('content')}, function (x) {});
        })

        $('.lmtf').blur(function (){
            var tip =$(this).attr('id').slice(-1);var bu=$(this);$(this).addClass('border-success');setTimeout(function (){bu.removeClass('border-success')},700);
            var deger=$(this).val();
            deger=deger==''?0:deger;
            $.post('/ykp.php', {fat_limit:tip, deger:deger, _token: $('meta[name="csrf-token"]').attr('content')}, function (x) {});
        });

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

