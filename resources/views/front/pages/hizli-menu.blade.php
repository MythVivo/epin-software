@if(isset($_GET['sil']))
    <?php
    DB::table('hizli_menu')->where('id', $_GET['sil'])->delete();
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
    <section class="bg-gray pb-40">
        <div class="container">

            <div class="row">
                @include('front.modules.user-menu')
                <div class="col-md-9">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">{{session('success')}}</h4>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">{{session('error')}}</h4>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-12">
                            <?php
                            $hizli_menu = DB::table('hizli_menu')->where('user', Auth::user()->id)->orderBy('id', 'desc');
                            ?>
                            @if($hizli_menu->count() > 0)
                                @if($hizli_menu->count() >= 7)
                                    <button type="button" class="btn-inline color-red w-100 mb-5">
                                        7 Adetten Fazla Oluşturamazsınız
                                    </button>
                                @else
                                    <button type="button" class="btn-inline color-darkgreen w-100 mb-5"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ekle">
                                        Hızlı Menü Oluştur
                                    </button>
                                @endif

                                <table id="datatable" class="table table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Başlık</th>
                                        <th>Link</th>
                                        <th>Simge</th>
                                        <th>İşlem</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($hizli_menu->get() as $u)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$u->title}}</td>
                                            <td><a href="{{env('APP_URL').$u->link}}" target="_blank">{{$u->link}}</a>
                                            </td>
                                            <td>{!! $u->icon !!}</td>
                                            <td>
                                                <button class="table-act-icon edit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detay{{$u->id}}"><i class="fal fa-edit"></i>
                                                </button>
                                                <div class="modal fade" id="detay{{$u->id}}" tabindex="-1"
                                                     aria-labelledby="exampleModalLabel"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="exampleModalLabel">{{$u->title}}
                                                                    Menüsünü Düzenle</h5>
                                                                <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                            </div>
                                                            <form method="post" action="{{route('hizli_menu_post')}}">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label" for="1">Menü
                                                                                Başlığı</label>
                                                                            <input type="text" id="1" name="title"
                                                                                   class="form-control style-input"
                                                                                   required value="{{$u->title}}">
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <label class="form-label" for="2">Menü
                                                                                Linki</label>
                                                                            <div class="input-group mb-3">
                                                                                <span class="input-group-text style-input">https://oyuneks.com/</span>
                                                                                <input type="text" id="2" name="link"
                                                                                       class="form-control"
                                                                                       required value="{{$u->link}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <label class="form-label" for="3">Menü
                                                                                İkonu</label>
                                                                            <div class="input-group mb-3">
                                                                                <input type="text" id="3" name="search"
                                                                                       class="form-control style-input icon-search">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="row set-mobile-height">
                                                                                @foreach(DB::table('icons')->whereNull('deleted_at')->get() as $uu)
                                                                                    <div class="col-4 col-md-3 col-lg-2 col-sm-3">
                                                                                        <div class="iconset">
                                                                                            <label>
                                                                                                <input type="radio"
                                                                                                       value="{{$uu->icon}}"
                                                                                                       name="icon"
                                                                                                       class="form-check-input"
                                                                                                       required
                                                                                                       @if($u->icon == $uu->icon) checked @endif >
                                                                                                <div class="icon-body">
                                                                                                    <span>{!! $uu->icon !!}</span>
                                                                                                    <h5 class="card-title">{{$uu->title}}</h5>

                                                                                                </div>

                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Kapat
                                                                    </button>
                                                                    <input type="hidden" name="id" value="{{$u->id}}">
                                                                    <button type="submit" class="btn btn-success">Kaydet
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button confirm-data='?sil={{$u->id}}' type="button"
                                                        class=" confirm-btn table-act-icon remove">
                                                    <i class="fal fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Başlık</th>
                                        <th>Link</th>
                                        <th>Simge</th>
                                        <th>İşlem</th>
                                    </tr>
                                    </tfoot>

                                </table>


                                <div class="control_popup hide">
                                    <article>
                                        <div class="pp_header">
                                            <h4>Dikkat!</h4>
                                        </div>
                                        <div class="pp_center">
                                            <h6>Silmek istediğinize emin misiniz?</h6>
                                        </div>
                                        <div class="pp_buttons">
                                            <a class="btn-inline color-red small del">Sil</a>
                                            <a class="btn-inline color-blue small cancel">Vazgeç</a>
                                        </div>
                                    </article>
                                </div>
                            @else
                                <div class="col-12">
                                    <div class="alert alert-danger text-center fade show"
                                         role="alert">
                                        <h5>
                                            Henüz hızlı menü oluşturmamışsınız. Hızlı menü oluşturduktan sonra buradan
                                            görüntüleyebilirsiniz.
                                        </h5>
                                        <button type="button" class="btn-inline color-red mt-4"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ekle">
                                            Hızlı Menü Oluştur
                                        </button>
                                    </div>
                                </div>

                            @endif


                            <div class="modal fade" id="ekle" tabindex="-1"
                                 aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">
                                                Hızlı Menü Ekle
                                            </h5>
                                            <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <form method="post" action="{{route('hizli_menu_post')}}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="1">Menü Başlığı</label>
                                                        <input type="text" id="1" name="title"
                                                               class="form-control style-input"
                                                               required>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label class="form-label" for="2">Menü Linki</label>
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text style-input">https://oyuneks.com/</span>
                                                            <input type="text" id="2" name="link"
                                                                   class="form-control style-input"
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label" for="3">Menü İkonu</label>
                                                        <div class="input-group mb-3">
                                                            <input type="text" id="3" name="search"
                                                                   class="form-control style-input icon-search">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="row set-mobile-height">
                                                            @foreach(DB::table('icons')->whereNull('deleted_at')->get() as $u)
                                                                <div class="col-4 col-md-3 col-lg-2 col-sm-3">
                                                                    <div class="iconset">
                                                                        <label>
                                                                            <input type="radio" value="{{$u->icon}}"
                                                                                   name="icon" class="form-check-input"
                                                                                   required>
                                                                            <div class="icon-body">
                                                                                <span>{!! $u->icon !!}</span>
                                                                                <h5 class="card-title">{{$u->title}}</h5>

                                                                            </div>

                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn-inline color-red"
                                                        data-bs-dismiss="modal">Kapat
                                                </button>
                                                <button type="submit" class="btn-inline color-darkgreen">Kaydet</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
@endsection
@section('js')
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script
            src="{{asset(env('ROOT').env('BACK').env('ASSETS').'plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#datatable').DataTable({
                pageLength: 10,
                "orderCellsTop": false,
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
