@extends('front.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('body')


    <section class="bg-gray pb-40">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 mb-4 text-center">
                    <button class="btn-inline color-darkgreen"
                        onclick="location.href='{{ route('twitch_support_yayinci_ol') }}'">Sen de Yayıncı Ol
                    </button>
                </div>
                <div class="col-12 col-md-6 mb-4 text-center">
                    <form method="get">
                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">
                                    <input placeholder="Yayıncı Ara" class="form-control style-input" name="q"
                                        @if (isset($_GET['q'])) value="{{ $_GET['q'] }} @endif">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <button class="btn-inline color-darkgreen">Ara</button>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>


                <div class="col-md-12 mt-4">
                    <div class="row">

                        @if (isset($_GET['q']))
                            @php $query = $_GET['q']; @endphp
                        @else
                            @php $query = ""; @endphp
                        @endif
                        <?php
                        $sorgu = DB::table('twitch_support_streamer')
                            ->where('twitch_id', '!=', null)
                            ->where('status', '1')
                            ->where('title', 'like', '%' . $query . '%')
                            ->orderBy('created_at', 'ASC')
                            ->whereNull('deleted_at')
                            ->paginate(15);
                        $twitchIds = null;
                        $i = 0;
                        foreach ($sorgu as $idler) {
                            if ($i != 0) {
                                $twitchIds .= '&';
                            }
                            $twitchIds .= 'id=' . $idler->twitch_id;
                            $i += 1;
                        }
                        ?>
                        <?php
                        $twitchInfos = Cache::rememberForever('tttwitch_list_page_' . $sorgu->currentPage() . '_query_' . $query, function () use ($twitchIds) {
                            return getTwitchLiveStreams($twitchIds);
                        });
                        
                        ?>
                        @if (is_array($twitchInfos))
                            @foreach ($twitchInfos as $twitch)
                                <?php
                                $yayinci = DB::table('twitch_support_streamer')
                                    ->where('twitch_id', $twitch->id)
                                    ->first();
                                ?>
                                <div class="colflex">
                                    <div class="col_cell">
                                        <a href="{{ route('twitch_support_yayinci', $yayinci->yayin_link) }}">
                                            <figure>
                                                <img src="{{ $twitch->profile_image_url }}">
                                            </figure>
                                            <div class="text-container flex">
                                                <h5>{{ $twitch->display_name }}</h5>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="alert alert-danger  fade show d-flex align-items-center" role="alert">
                                    <i class="fas fa-exclamation-triangle me-3"></i>
                                    <h5>
                                        Aradığınız yayıncı bulunamadı!
                                    </h5>
                                </div>
                            </div>
                        @endif
                        <?php /*
                        @foreach($sorgu as $u)
                            @if(isset($u->twitch_id) and $u->twitch_id != NULL)
                                @if(isset(Auth::user()->id) and Auth::user()->id == 2)

                                @else
                                    <div class="colflex">
                                        <div class="col_cell">
                                            <a href="{{route('twitch_support_yayinci', $u->yayin_link)}}">
                                                <figure>
                                                    <img src="{{$u->image}}">
                                                </figure>
                                                <div class="text-container flex">
                                                    <h5>{{$u->title}}</h5>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
 */
                        ?>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <a class="page-link" href="{{ $sorgu->url(1) }}">{{ '|<<' }}</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="{{ $sorgu->previousPageUrl() }}">{{ '<' }}</a>
                                </li>
                                <?php
                                $fromPage = $sorgu->currentPage() - 4;
                                $toPage = $sorgu->currentPage() + 4;
                                $fromPage = $fromPage < 1 ? 1 : $fromPage;
                                $toPage = $toPage > $sorgu->lastPage() ? $sorgu->lastPage() : $toPage;
                                ?>
                                @for ($i = $fromPage; $i < $toPage; $i++)
                                    <li class="page-item @if ($sorgu->currentPage() == $i) active @endif">
                                        <a class="page-link" href="{{ $sorgu->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item">
                                    <a class="page-link" href="{{ $sorgu->nextPageUrl() }}">{{ '>' }}</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $sorgu->url($sorgu->lastPage()) }}">{{ '>>|' }}</a>
                                </li>
                            </ul>
                        </nav>


                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js">
    </script>
    <script>
        $('.lazy').Lazy({
            scrollDirection: 'vertical',
            effect: 'fadeIn',
            visibleOnly: true,
        });
    </script>
@endsection
