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
                @else
                {{getPageTitle(getPage(), getLang())}}
                    @endif
            </h4>
        </div><!--end page-title-box-->
    </div><!--end col-->
</div>

