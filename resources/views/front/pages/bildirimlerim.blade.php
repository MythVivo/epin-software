@extends('front.layouts.app')
@section('css')
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


                    <div class="row">
                        <form class="needs-validation" action="{{route('bildirimlerim_post')}}" method="POST"
                              autocomplete="off" novalidate>
                            @csrf
                            <div class="row mb-5">
                                @foreach(DB::table('bildirim_kategorileri')->get() as $bk)
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">{{$bk->title}}</label>
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
                                <div class="col-12 text-left mt-4">
                                    <button class="btn-inline color-darkgreen" type="submit">Kaydet</button>
                                </div>
                            </div>

                        </form>
                        <div class="col-md-12">
                            <ul class="nav nav-pills custom-nav mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if(!isset($_GET['tab'])) active @endif" id="pills-tumu-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#pills-tumu" type="button" role="tab"
                                            aria-controls="pills-tumu"
                                            @if(!isset($_GET['tab'])) aria-selected="true"
                                            @else aria-selected="false" @endif>
                                        Tümü
                                    </button>
                                </li>
                                @foreach(DB::table('bildirim_kategorileri')->get() as $bk)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if(isset($_GET['tab']) and $_GET['tab'] == Str::slug($bk->title)) active @endif"
                                                id="pills-{{Str::slug($bk->title)}}-tab"
                                                data-bs-toggle="pill"
                                                data-bs-target="#pills-{{Str::slug($bk->title)}}" type="button"
                                                role="tab"
                                                aria-controls="pills-{{Str::slug($bk->title)}}"
                                                @if(isset($_GET['tab']) and $_GET['tab'] == Str::slug($bk->title))
                                                aria-selected="true"
                                                @else
                                                aria-selected="false"
                                                @endif
                                        >
                                            {{$bk->title}}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade @if(!isset($_GET['tab'])) show active @endif" id="pills-tumu"
                                     role="tabpanel"
                                     aria-labelledby="pills-tumu-tab">
                                    <div class="row g-3">
                                        <div class="col-12 notification-list">
                                            <?php
                                            $bildirim = DB::table('bildirim')->where('user', Auth::user()->id)->whereNull('deleted_at')->orderBy('created_at', 'desc')->paginate(50);
                                            ?>
                                            @foreach($bildirim as $b)
                                                <div class="notification-item @if($b->isRead == 0) new-nt @else old-nt @endif">
                                                    <div class="nt-container">
                                                        <a href="{{$b->link}}">{{$b->title}}</a>
                                                        <h6>{{$b->text}}</h6>
                                                        <div class="nt-container-footer">
                                                            <div class="nt-cont-left">
                                                                <span>{{findBildirimTime($b->id)}}</span>
                                                            </div>
                                                            <div class="nt-cont-right">
                                                                <a onclick="bildirimOku({{$b->id}})" class="">Okundu
                                                                    Olarak
                                                                    İşaretle</a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach
                                            <nav aria-label="Page navigation example">
                                                <ul class="pagination justify-content-center">
                                                    <li class="page-item">
                                                        <a class="page-link" data-page="page=1"
                                                           href="?page=1">{{"|<<"}}</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <?php
                                                        $prevPage = $bildirim->currentPage() - 1;
                                                        if ($prevPage < '1') {
                                                            $prevPage = '1';
                                                        }
                                                        ?>
                                                        <a class="page-link"
                                                           data-page="page={{$prevPage}}"
                                                           href="?page={{$prevPage}}">{{"<"}}</a>
                                                    </li>
                                                    @for($i = 1; $i < $bildirim->lastPage()+1; $i++)
                                                        <li class="page-item @if($bildirim->currentPage() == $i) active @endif">
                                                            <a class="page-link" data-page="page={{$i}}"
                                                               href="?page={{$i}}">{{$i}}</a>
                                                        </li>
                                                    @endfor
                                                    <li class="page-item">
                                                        <?php
                                                        $nextPage = $bildirim->currentPage() + 1;
                                                        if ($nextPage > $bildirim->lastPage()) {
                                                            $nextPage = $bildirim->lastPage();
                                                        }
                                                        ?>
                                                        <a class="page-link" data-page="page={{$nextPage}}"
                                                           href="?page={{$nextPage}}">{{">"}}</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link" data-page="page={{$bildirim->lastPage()}}"
                                                           href="?page={{$bildirim->lastPage()}}">{{">>|"}}</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                @foreach(DB::table('bildirim_kategorileri')->get() as $bk)
                                    <div class="tab-pane fade @if(isset($_GET['tab']) and $_GET['tab'] == Str::slug($bk->title)) show active @endif"
                                         id="pills-{{Str::slug($bk->title)}}" role="tabpanel"
                                         aria-labelledby="pills-{{Str::slug($bk->title)}}-tab">
                                        <div class="row g-3">
                                            <div class="col-12 notification-list">
                                                <?php
                                                $bildirim = DB::table('bildirim')->where('category', $bk->id)->where('user', Auth::user()->id)->whereNull('deleted_at')->orderBy('created_at', 'desc')->paginate(50, ['*'], Str::slug($bk->title));
                                                ?>
                                                @foreach($bildirim as $b)
                                                    <div class="notification-item @if($b->isRead == 0) new-nt @else old-nt @endif">
                                                        <div class="nt-container">
                                                            <h4>{{$b->title}}</h4>
                                                            <h6>{{$b->text}}</h6>
                                                            <div class="nt-container-footer">
                                                                <div class="nt-cont-left">
                                                                    <span>{{findBildirimTime($b->id)}}</span>
                                                                </div>
                                                                <div class="nt-cont-right">
                                                                    <a onclick="bildirimOku({{$b->id}})" class="">Okundu
                                                                        Olarak
                                                                        İşaretle</a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                @endforeach
                                                <nav aria-label="Page navigation example">
                                                    <ul class="pagination justify-content-center">
                                                        <li class="page-item">
                                                            <a class="page-link" data-page="page=1"
                                                               href="?{{Str::slug($bk->title)}}=1">{{"|<<"}}</a>
                                                        </li>
                                                        <li class="page-item">
                                                            <?php
                                                            $prevPage = $bildirim->currentPage() - 1;
                                                            if ($prevPage < '1') {
                                                                $prevPage = '1';
                                                            }
                                                            ?>
                                                            <a class="page-link"
                                                               data-page="page={{$prevPage}}"
                                                               href="?{{Str::slug($bk->title)}}={{$prevPage}}">{{"<"}}</a>
                                                        </li>
                                                        @for($i = 1; $i < $bildirim->lastPage()+1; $i++)
                                                            <li class="page-item @if($bildirim->currentPage() == $i) active @endif">
                                                                <a class="page-link" data-page="page={{$i}}"
                                                                   href="?{{Str::slug($bk->title)}}={{$i}}&tab={{Str::slug($bk->title)}}">{{$i}}</a>
                                                            </li>
                                                        @endfor
                                                        <li class="page-item">
                                                            <?php
                                                            $nextPage = $bildirim->currentPage() + 1;
                                                            if ($nextPage > $bildirim->lastPage()) {
                                                                $nextPage = $bildirim->lastPage();
                                                            }
                                                            ?>
                                                            <a class="page-link" data-page="page={{$nextPage}}"
                                                               href="?{{Str::slug($bk->title)}}={{$nextPage}}">{{">"}}</a>
                                                        </li>
                                                        <li class="page-item">
                                                            <a class="page-link"
                                                               data-page="page={{$bildirim->lastPage()}}"
                                                               href="?{{Str::slug($bk->title)}}={{$bildirim->lastPage()}}">{{">>|"}}</a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                        </div>
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
