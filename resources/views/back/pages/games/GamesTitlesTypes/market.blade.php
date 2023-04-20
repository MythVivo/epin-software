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
    <style>
        table.dataTable.nowrap td:nth-child(2) {
            white-space: unset !important;
        }
        #ozellikArea{
            position: relative;
            min-height: 320px
        }

        #ozellikArea .progress1 {
            position: absolute;
            top: 0;
            left: 0;
            background: transparent;
            width: 100%;
            height: calc(100% - 70px);
            z-index: 11;
            display: flex;
            justify-content: center
        }

        #ozellikArea .progress1 span {
            display: inline-block;
            width: 103px;
            height: 103px;
            border-radius: 50%;
            box-shadow: 0 0 0 1px #fff, inset 0 0 0 1px #fff;
            margin-top: 100px;
            border: 9px solid #0000002b;
            position: relative
        }
        #ozellikArea .progress1 i {
            position: absolute;
            animation-duration: 1s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
            animation-name: loading-bar;
            -webkit-transform: rotate3d(0, 0, 1, 0deg);
            color: #00ff34;
            width: calc(100% + 18px);
            height: calc(100% + 18px);
            border-radius: 50%;
            border: 9px solid #00ff34;
            clip-path: polygon(100% 50%, 50% 50%, 0% 50%, 0% 0%, 100% 0%);
            left: -9px;
            top: -9px;
        }

        @keyframes loading-bar {
            to {
                -webkit-transform: rotate3d(0, 0, 1, 0deg);



            }
            from {
                -webkit-transform: rotate3d(0, 0, 1, 359deg);

            }
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
    <div class="row mt-3">
        <div class="col-lg-12 mb-4">
            <div class="card card-border client-card">
                <div class="card-body text-center">
                    <h2 class="client-name">
                        <span>{{$u->title}}</span></h2>
                    <br>
                    <div class="row mb-5" style="margin-right: 0px; margin-left: 0px;">
                        @php $ozellikler = DB::table('games_titles_features')->where('game_title', $u->id)->whereNull('deleted_at')->get(); @endphp
                        @foreach($ozellikler as $uu)
                            <div class="col-md-12">
                                <div class="altBaslik float-left">
                                    <a href="javascript:void(0)" data-toggle="modal"
                                       data-target=".ozellikDuzenle{{$uu->id}}">
                                        {{$uu->title}} :
                                        @foreach(json_decode($uu->value) as $deger)
                                            {{$deger}}  @if(!$loop->last) - @endif
                                        @endforeach
                                    </a>
                                    <hr>
                                </div>
                                <div class="modal fade ozellikDuzenle{{$uu->id}}" tabindex="-1" role="dialog"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0"
                                                    id="myModalLabel">@lang('admin.ozellikDuzenle')</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">
                                                    X
                                                </button>
                                            </div>
                                            <form id="ozellikDuzenle" method="post"
                                                  action="{{route('oyun_market_ozellik_edit')}}"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                {!! getLangInput() !!}
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-6">
                                                            <div class="form-group">
                                                                <label for="1">@lang('admin.ozellikBasligi')</label>
                                                                <input name="title" type="text" class="form-control"
                                                                       id="1"
                                                                       placeholder="@lang('admin.ozellikBasligi')"
                                                                       value="{{$uu->title}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-md-6">
                                                            <div class="form-group">
                                                                <label for="2">@lang('admin.ozellikTipi')</label>
                                                                <select name="type" id="2"
                                                                        class="select2 form-control custom-select">
                                                                    <option value="1"
                                                                            @if($uu->type == 1) selected @endif>
                                                                        Tekli Seçim
                                                                    </option>
                                                                    <option value="2"
                                                                            @if($uu->type == 2) selected @endif>
                                                                        Çoklu Seçim
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $string = null;
                                                        ?>
                                                        @foreach(json_decode($uu->value) as $deger)
                                                            @if(!$loop->last)
                                                                @php $string .= $deger."&#13;&#10;"; @endphp
                                                            @else
                                                                @php $string .= $deger; @endphp
                                                            @endif
                                                        @endforeach
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label for="5">@lang('admin.ozellikDegerler')</label>
                                                                <textarea class="form-control"
                                                                          placeholder="@lang('admin.ozellikDegerler')"
                                                                          id="5" name="value">{!! $string !!}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-danger waves-effect"
                                                            onclick="deleteContent('games_titles_features', {{$uu->id}})">@lang('admin.ozellikSil')</button>
                                                    <button type="button" class="btn btn-outline-secondary waves-effect"
                                                            data-dismiss="modal">@lang('admin.kapat')</button>
                                                    <input type="hidden" name="id" value="{{$uu->id}}">
                                                    <button type="submit"
                                                            class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                                </div>
                                            </form>

                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->
                            </div>
                        @endforeach
                    </div>
                    <div class="card-bottom mt-3">
                        <button type="button" data-toggle="modal" data-target=".ozellikEkle{{$u->id}}"
                                class="btn btn-block btn-outline-secondary">@lang('admin.ozellikEkle')</button>
                        @if(DB::table('games_titles_special')->where('games_titles', $u->id)->count() < 1)
                            <button type="button" data-toggle="modal" data-target=".itemEkle{{$u->id}}"
                                    class="btn btn-block btn-outline-success">@lang('admin.itemEkle')</button>
                        @endif
                        <button onclick="deleteContent('games_titles', {{$u->id}})" type="button"
                                class="btn btn-block btn-outline-danger">@lang('admin.baslikSil')
                        </button>
                    </div>
                    <div class="modal fade ozellikEkle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.ozellikEkle')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="ozellikEkle" method="post" action="{{route('oyun_market_ozellik_add')}}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label for="1">@lang('admin.ozellikBasligi')</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="@lang('admin.ozellikBasligi')">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label for="2">@lang('admin.ozellikTipi')</label>
                                                    <select name="type" id="2"
                                                            class="select2 form-control custom-select">
                                                        <option value="1">Tekli Seçim</option>
                                                        <option value="2">Çoklu Seçim</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="5">@lang('admin.ozellikDegerler')</label>
                                                    <textarea class="form-control"
                                                              placeholder="@lang('admin.ozellikDegerler')"
                                                              id="5"
                                                              name="value"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary waves-effect"
                                                data-dismiss="modal">@lang('admin.kapat')</button>
                                        <input type="hidden" name="game_title" value="{{$u->id}}">
                                        <button type="submit"
                                                class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                    </div>
                                </form>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class="modal fade itemEkle{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">@lang('admin.itemEkle')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                    </button>
                                </div>
                                <form id="itemEkle" method="post" action="{{route('oyun_market_item_add')}}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    {!! getLangInput() !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="1">@lang('admin.itemBasligi')</label>
                                                    <input name="title" type="text" class="form-control" id="1"
                                                           placeholder="@lang('admin.itemBasligi')">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="2">@lang('admin.itemAciklama')</label>
                                                    <textarea class="form-control"
                                                              placeholder="@lang('admin.itemAciklama')" id="2"
                                                              name="description"></textarea>
                                                </div>
                                            </div>
                                            <hr>
                                            @php $ozellikler = DB::table('games_titles_features')->whereNull('deleted_at')->where('game_title', $u->id)->get(); @endphp
                                            @foreach($ozellikler as $f)
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="f{{$f->id}}">{{$f->title}}</label>
                                                    @if($f->type == 1) <!-- tekli seçim -->
                                                        <select class="select2 form-control custom-select"
                                                                name="feature_{{$f->id}}"
                                                                required>
                                                            <option value="0" selected>Seçim Boş</option>
                                                            @foreach(json_decode($f->value) as $deger)
                                                                <option value="{{Str::slug($deger)}}">{{$deger}}</option>
                                                            @endforeach
                                                        </select>
                                                    @elseif($f->type == 2) <!-- çoklu seçim -->
                                                        <select class="select2 form-control custom-select" multiple
                                                                name="feature_{{$f->id}}[]"
                                                                required>
                                                            <option value="0">Seçim Boş</option>
                                                            @foreach(json_decode($f->value) as $deger)
                                                                <option value="{{Str::slug($deger)}}">{{$deger}}</option>
                                                            @endforeach
                                                        </select>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="4">@lang('admin.itemResmi')</label>
                                                    <input name="image" type="file" id="4" class="dropify"
                                                           accept="image/*">
                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary waves-effect"
                                                data-dismiss="modal">@lang('admin.kapat')</button>
                                        <input type="hidden" name="game_title" value="{{$u->id}}">
                                        <button type="submit"
                                                class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                                    </div>
                                </form>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div>
            </div>
        </div>
    </div>
    @if(DB::table('games_titles_special')->where('games_titles', $u->id)->count() < 1)
        <div class="row" data-lang="{{getLang()}}">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-3">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 text-left">
                                    İtemler
                                </div>
                            </div>
                        </h4>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                if (isset($_GET['getir'])) {
                                    $sec = $_GET['getir'];
                                    $text = $_GET['getirText'];
                                } else {
                                    $sec = "10";
                                    $text = "";
                                }
                                ?>
                                <form method="get">
                                    Son eklenen <input class="w-25" type="number" value="{{$sec}}" name="getir"> veriyi
                                    ve
                                    içinde <input class="w-25" type="text" value="{{$text}}" name="getirText"> geçenleri
                                    <button class="btn btn-outline-success">getir</button>
                                </form>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table lang="{{getLang()}}" id="datatable2"
                                   class="table table-bordered nowrap datatable2"
                                   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>@lang('admin.itemBasligi')</th>
                                    <th>@lang('admin.eklenmeTarihi')</th>
                                    <th>@lang('admin.aksiyon')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $bilgiler = DB::table('games_titles_items_info')->whereNull('deleted_at')->where('game_title', $u->id)->where('title', 'like', '%' . $text . '%')->orderBy('created_at', 'desc')->take($sec)->get(); @endphp
                                @foreach($bilgiler as $items)
                                    <tr>
                                        <td>{{$items->title}}</td>
                                        <td>{{$items->created_at}}</td>
                                        <td>
                                            <button data-toggle="modal"
                                                    data-target=".itemDuzenle"
                                                    type="button"
                                                    onclick="edit({{$items->id}}, 'games_titles_items_info', event)"
                                                    class="btn btn-lg btn-outline-primary waves-effect waves-light">
                                                <i class="far fa-edit"></i>
                                            </button>

                                            <button onclick="deleteItem({{$items->id}})" type="button"
                                                    class="btn btn-lg btn-outline-danger waves-effect waves-light">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>@lang('admin.itemBasligi')</th>
                                    <th>@lang('admin.eklenmeTarihi')</th>
                                    <th>@lang('admin.aksiyon')</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal fade itemDuzenle" tabindex="-1"
             role="dialog"
             aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0"
                            id="myModalLabel">İtem Düzenle</h5>
                        <button type="button" class="close"
                                data-dismiss="modal"
                                aria-hidden="true">X
                        </button>
                    </div>
                    <form id="itemDuzenle" method="post"
                          action="{{route('oyun_market_item_edit')}}"
                          enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        {!! getLangInput() !!}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="1">@lang('admin.itemBasligi')</label>
                                        <input name="title" type="text"
                                               class="form-control"
                                               id="1"
                                               placeholder="@lang('admin.itemBasligi')">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="2">@lang('admin.itemAciklama')</label>
                                        <textarea class="form-control"
                                                  placeholder="@lang('admin.itemAciklama')"
                                                  id="2"
                                                  name="description"></textarea>
                                    </div>
                                </div>
                                <div id="ozellikArea" class="col-md-12">

                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="4">@lang('admin.itemResmi')</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <img id="resim" class="form-controll img img-thumbnail"
                                                     src="">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="file"
                                                       name="image">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-outline-secondary waves-effect"
                                    data-dismiss="modal">@lang('admin.kapat')</button>
                            <input type="hidden" id="item_id" name="item_id"
                                   value="">
                            <button type="submit"
                                    class="btn btn-outline-success waves-effect waves-light">@lang('admin.kaydet')</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    @endif


@endsection
@section('js')
    <script>



        $( "body" ).delegate( "button.close", "click", function() {
            $( "#ozellikArea" ).html("")
        });


        function callback(a) {

            $(".progress1").remove()

            var mainElem = document.getElementById("ozellikArea");

            let ozellikData=a[0].ozellikler
            var colRow = document.createElement("div");
            colRow.classList.add('row');


            jQuery.each( ozellikData, function( i, val ) {
                var colElem = document.createElement("div");
                colElem.classList.add('col-md-6');
                colElem.classList.add('mb-4');
                mainElem.append(colElem)
                let Elemlabel=document.createElement("label");
                const newContent = document.createTextNode(val.title);
                Elemlabel.appendChild(newContent);
                colElem.append(Elemlabel)
                let Elemselect=document.createElement("select");

                Elemselect.classList.add('form-control');
                if(val.type == "2"){
                    Elemselect.multiple=true
                    Elemselect.name="feature_"+val.id+"[]";
                }else{
                    Elemselect.name="feature_"+val.id;
                }
                jQuery.each( val.degerler, function( i2, val2 ) {
                    if(i2 == 0) {
                        let ElemOptions=document.createElement("option");
                        let a=ElemOptions.text = "Seçim Boş";
                        let b=ElemOptions.value = 0;
                        Elemselect.add(ElemOptions);
                    }
                    let ElemOptions=document.createElement("option");

                    let a=ElemOptions.text = val2.deger;
                    let b=ElemOptions.value = val2.value;
                    if(val2.selected == "1"){
                        ElemOptions.selected = "selected";
                    }
                    Elemselect.add(ElemOptions);

                });
                colElem.append(Elemselect)
                colRow.append(colElem)
                mainElem.append(colRow)


            });

            $(".itemDuzenle").find('input#1').val(a[0].title);
            $(".itemDuzenle").find('input#2').val(a[0].description);
            $(".itemDuzenle").find('#resim').attr("src",a[0].media.image);
            $(".itemDuzenle").find('input#item_id').val(a[0].id);
            
        }

        function edit(id, table, event) {
            $( "#ozellikArea" ).html("<div class='progress1'><span><i class='fal fa-spinner-third'></i></span></div>")
            let gelen;
            $.get("{{route('getDataItem')}}", {table: table, id: id})
                .done(function (data, status) {
                    gelen = JSON.parse(data);
                    callback(gelen);
                });
        }


        var baslikturu = $(".baslikturu").val();
        if (baslikturu == 1) {
            $(".bt").removeClass("col-md-4");
            $(".bt").addClass("col-md-3");
            $("#pazaryeri").css("display", "block");
            $("#epin").css("display", "none");
        } else if (baslikturu == 2) {
            $(".bt").removeClass("col-md-4");
            $(".bt").addClass("col-md-3");
            $("#epin").css("display", "block");
            $("#pazaryeri").css("display", "none");
        } else {
            $(".bt").addClass("col-md-4");
            $(".bt").removeClass("col-md-3");
            $("#epin").css("display", "none");
            $("#pazaryeri").css("display", "none");
        }
        $(".baslikturu").change(function () {
                baslikturu = $(".baslikturu").val();
                if (baslikturu == 1) {
                    $(".bt").removeClass("col-md-4");
                    $(".bt").addClass("col-md-3");
                    $("#pazaryeri").css("display", "block");
                    $("#epin").css("display", "none");
                } else if (baslikturu == 2) {
                    $(".bt").removeClass("col-md-4");
                    $(".bt").addClass("col-md-3");
                    $("#epin").css("display", "block");
                    $("#pazaryeri").css("display", "none");
                } else {
                    $(".bt").addClass("col-md-4");
                    $(".bt").removeClass("col-md-3");
                    $("#epin").css("display", "none");
                    $("#pazaryeri").css("display", "none");
                }
            }
        );
    </script>
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
                columnDefs: [{orderable: false, targets: [1]}],
                pageLength: 10,
                "order": [[1, "desc"]],
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

            $('.datatable2').DataTable({
                columnDefs: [{orderable: false, targets: [2]}],
                pageLength: 10,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Hepsi"]],
                "order": [[1, "desc"]],
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
                        location.href = '{{route('oyunlar')}}'
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
    <script>
        function deleteItem(id) {
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
                    $.get("{{route('deleteItem')}}", {id: id});
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
