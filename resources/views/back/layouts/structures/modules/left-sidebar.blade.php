<div class="left-sidenav">
    <ul class="metismenu left-sidenav-menu">
        <li @if(getPage() == 'panel') class="mm-active" @endif>
            <a href="{{route('panel')}}"><i class="mdi mdi-desktop-mac-dashboard"></i> @lang('admin.anasayfa')</a>
        </li>
        <li>
            <a @if(getUrl() == route('slider')) class="link-active" @endif href="javascript:void(0);"><i class="mdi mdi-cogs"></i><span>@lang('admin.siteYonetimi')</span>
                <span class="menu-arrow">
                    <i class="mdi mdi-chevron-right"></i>
                </span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li class="nav-item"><a class="nav-link" href="{{route('slider')}}"><i class="ti-control-record"></i>@lang('admin.sliderYonetimi')</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('haberler')}}"><i class="ti-control-record"></i>@lang('admin.haberYonetimi')</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('yorumlar')}}"><i class="ti-control-record"></i>@lang('admin.yorumYonetimi')</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('sayfalar')}}"><i class="ti-control-record"></i>@lang('admin.sayfaYonetimi')</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('sss')}}"><i class="ti-control-record"></i>@lang('admin.sssYonetimi')</a></li>
            </ul>
        </li>
        <li>
            <a @if(getUrl() == route('kategoriler')) class="link-active" @endif href="javascript:void(0);"><i class="mdi mdi-cogs"></i><span>@lang('admin.urunYonetimi')</span>
                <span class="menu-arrow">
                    <i class="mdi mdi-chevron-right"></i>
                </span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li class="nav-item"><a class="nav-link" href="{{route('kategoriler')}}"><i class="ti-control-record"></i>@lang('admin.kategoriYonetimi')</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('oyunlar')}}"><i class="ti-control-record"></i>@lang('admin.oyunYonetimi')</a></li>

            </ul>
        </li>
        <li>
            <a @if(getUrl() == route('uyeler')) class="link-active" @endif href="javascript:void(0);"><i class="mdi mdi-cogs"></i><span>@lang('admin.uyeYonetimi')</span>
                <span class="menu-arrow">
                    <i class="mdi mdi-chevron-right"></i>
                </span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li class="nav-item"><a class="nav-link" href="{{route('uyeler')}}"><i class="ti-control-record"></i>@lang('admin.uyeYonetimi')</a></li>
            </ul>
        </li>
    </ul>
</div>
