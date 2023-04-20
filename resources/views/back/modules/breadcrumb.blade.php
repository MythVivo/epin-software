<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-right">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('homepage')}}">{{getSiteName()}}</a></li>
                    <li class="breadcrumb-item"><a href="{{route('panel')}}">@lang('admin.panel')</a></li>
                    @if(Route::currentRouteName() == 'oyun_detay')
                        <li class="breadcrumb-item"><a href="{{route('oyunlar')}}">@lang('admin.oyunYonetimi')</a></li>
                        <li class="breadcrumb-item active">
                            <a href="{{route(Route::currentRouteName(), $link)}}">
                                {{getGameTitle($link, getLang())}}
                            </a>
                        </li>
                    @elseif(Route::currentRouteName() == 'oyun_detay_market')
                        <li class="breadcrumb-item"><a href="{{route('oyunlar')}}">@lang('admin.oyunYonetimi')</a></li>
                        <li class="breadcrumb-item"><a href="{{route('oyun_detay', $oyun->link)}}">{{$oyun->title}}</a></li>
                        <li class="breadcrumb-item active">
                            <a href="{{route(Route::currentRouteName(), [$oyun->link, $u->link])}}">
                                {{$u->title}}
                            </a>
                        </li>
                    @elseif(Route::currentRouteName() == 'oyun_detay_trade')
                        <li class="breadcrumb-item"><a href="{{route('oyunlar')}}">@lang('admin.oyunYonetimi')</a></li>
                        <li class="breadcrumb-item"><a href="{{route('oyun_detay', $oyun->link)}}">{{$oyun->title}}</a></li>
                        <li class="breadcrumb-item active">
                            <a href="{{route(Route::currentRouteName(), [$oyun->link, $u->link])}}">
                                {{$u->title}}
                            </a>
                        </li>
                    @elseif(Route::currentRouteName() == 'uye_detay')
                        <li class="breadcrumb-item"><a href="{{route('uyeler')}}">@lang('admin.uyeYonetimi')</a></li>
                        <li class="breadcrumb-item active">
                            <a href="{{route(Route::currentRouteName(), $email)}}">
                                {{$email}}
                            </a>
                        </li>
                    @elseif(Route::currentRouteName() == 'oyun_paket_kod_view')
                        <li class="breadcrumb-item"><a href="{{route('oyunlar')}}">@lang('admin.oyunYonetimi')</a></li>
                        <li class="breadcrumb-item active">{{$paket->title}} Kodları</li>
                    @elseif(Route::currentRouteName() == 'oyun_paket_kod_edit')
                        <li class="breadcrumb-item"><a href="{{route('oyunlar')}}">@lang('admin.oyunYonetimi')</a></li>
                        <li class="breadcrumb-item active">{{$paket->title}} Kodları Düzenle</li>
                    @else
                        <li class="breadcrumb-item active">
                            <a href="{{route(Route::currentRouteName(), '')}}">
                                {{getPageTitle(getPage(), getLang())}}
                            </a>
                        </li>
                    @endif
                </ol>
            </div>
            <h4 class="page-title">
                @if(Route::currentRouteName() == 'oyun_detay')
                    {{getGameTitle($link, getLang())}} - @lang('admin.oyunBasliklar')
                @elseif(Route::currentRouteName() == 'uye_detay')
                    {{$user->name}} - {{$email}}
                @elseif(Route::currentRouteName() == 'oyun_paket_kod_view')
                   {{$paket->title}} Kodları
                @elseif(Route::currentRouteName() == 'oyun_paket_kod_edit')
                    {{$paket->title}} Kodları Düzenle
                @else
                    {{getPageTitle(getPage(), getLang())}}
                @endif
            </h4>
        </div><!--end page-title-box-->
    </div><!--end col-->
</div>

