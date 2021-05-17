<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="tr">
<head>
    {{getStatistic()}}
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{getPageTitle(getPage(), getLang())}} | {{getSiteName()}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{asset(env('root').env('front').env('css').'bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset(env('root').env('front').env('css').'style.css')}}" rel="stylesheet">

    <!-- owl carousel -->
    <link rel="stylesheet"
          href="{{asset(env('root').env('front').env('js').'owlcarousel/assets/owl.carousel.min.css')}}">
    <link rel="stylesheet"
          href="{{asset(env('root').env('front').env('js').'owlcarousel/assets/owl.theme.default.min.css')}}">

    <!-- font awesome -->
    <link rel="stylesheet" href="{{asset(env('root').env('front').env('vendors').'fontawesome/css/all.css')}}">
</head>
<body>
<div class="site-header-area">
    <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4">
            <a href="/"
               class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none site-logo">
                <img src="{{asset(env('root').env('brand').'brandlogo.png')}}">
            </a>
            <ul class="nav nav-pills">
                <li class="nav-item"><i class="far fa-search"></i></li>
                <li class="nav-item"><a href="{{route('homepageq')}}" class="nav-link active">Anasayfa</a></li>
                <li class="nav-item"><a href="{{route('homepage')}}" class="nav-link active">Anasayfa</a></li>
                <li class="nav-item"><a href="{{route('oyun_baslik', 'knight-online')}}" class="nav-link">Knight Online</a>
                <ul class="sub-mega-menu container">
<div class="mega-menu-container">
<div class="sub-menu-category">
<h4>Knight Online Ring</h4>

    <div class="menu-sub-category">
    <a href="" class="msc-link">Sunucular</a>
    <ul>
    <li><a href="">Sirius</a></li>
    <li><a href="">Vega</a></li>
    <li><a href="">Altar</a></li>
    </ul>
    </div>
    </div>
</div>
</ul>

                </li>
                <li class="nav-item"><a href="#" class="nav-link">Item <span>&</span> Skins</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Steam</a></li>
            </ul>
            <ul class="nav icon-area text-right">
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-center" href="#">
                        <i class="fas fa-credit-card align-self-center"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-center" titlex="giriş" href="@if(isset(Auth::user()->id)) {{route('hesabim')}} @else {{route('giris')}} @endif">
                        <i class="fas fa-user align-self-center"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-center" href="#">
                        <i class="fas fa-cog align-self-center"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <div class="form-theme-switch">
                    <label>
                    <input class="change-theme" type="checkbox" id="flexSwitchCheckDefault">
                    <span>
                    <img src="{{asset('/public/front/images/icons/type_sun.png')}}">
                    <img src="{{asset('/public/front/images/icons/type_night.png')}}">
                    </span>

                    </label>

                    </div>
                </li>
            </ul>
        </header>
    </div>

</div>

