<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="tr">
<head>
    {{getStatistic()}}
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{getPageTitle(getPage(), getLang())}} | {{getSiteName()}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{asset(env('root').env('front').env('css').'bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset(env('root').env('front').env('css').'style.css')}}" rel="stylesheet">
    <!-- font awesome -->
    <link rel="stylesheet" href="{{asset(env('root').env('front').env('vendors').'fontawesome/css/all.css')}}">

</head>
<body>

<form method="post">
    @csrf
    <input type="text" name="name_1">
    <br>
    <input type="text" name="name_2">
    <br>
    <input type="text" name="email">
    <br>
    <input type="password" name="password">
    <br>
    <input type="submit">
</form>

<footer class="footer">
    <div class="container">
        <div class="footer-top">
            <div class="row">
                <div class="footer-top-left col-sm-6">
                    <div class="col-sm-12 col-md-6">
                        <img src="{{asset(env('root').env('brand').'brandlogo.png')}}">
                    </div>

                </div>
                <div class="footer-top-right col-sm-6">

                    <div class="row">
                        <div class="col-sm-12 col-md mt-5">
                            <h2 class="footer-title">
                                <span>{{getSiteName()}}</span>
                            </h2>
                            <ul>
                                <li><a href="">@lang('general.hakkimizda')</a></li>
                                <li><a href="">@lang('general.haberler')</a></li>
                                <li><a href="">@lang('general.kurumsal')</a></li>
                                <li><a href="">@lang('general.yorumlar')</a></li>

                            </ul>
                        </div>

                        <div class="col-sm-12 col-md mt-5">
                            <h2 class="footer-title">
                                <span>@lang('general.uyelik')</span>
                            </h2>
                            <ul>
                                <li><a href="">@lang('general.odemelerim')</a></li>
                                <li><a href="">@lang('general.gizlilikPolitikasi')</a></li>
                                <li><a href="">@lang('general.kvk')</a></li>
                                <li><a href="">@lang('general.uyelikSozlesmesi')</a></li>

                            </ul>
                        </div>

                        <div class="col-sm-12 col-md mt-5">
                            <h2 class="footer-title">
                                <span>@lang('general.iletisim')</span>
                            </h2>
                            <ul>
                                <li><a href="">@lang('general.destek')</a></li>
                                <li><a href="">@lang('general.sifremiUnuttum')</a></li>

                            </ul>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="footer-center">

        </div>
        <div class="footer-bottom">

        </div>

    </div>
</footer>
</body>
<script src="{{asset(env('root').env('front').env('js').'bootstrap.bundle.js')}}"></script>
<script src="{{asset(env('root').env('front').env('js').'jquery.min.js')}}"></script>


<script src="{{asset(env('root').env('front').env('js').'custom.js')}}"></script>
</html>

