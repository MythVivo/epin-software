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
                <li class="nav-item"><a href="#" class="nav-link active">Anasayfa</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Knight Online</a></li>
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
                    <a class="nav-link d-flex justify-content-center" href="{{route('giris')}}">
                        <i class="fas fa-user align-self-center"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-center" href="#">
                        <i class="fas fa-cog align-self-center"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                    </div>
                </li>
            </ul>
        </header>
    </div>

</div>
