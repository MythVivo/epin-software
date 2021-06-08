<footer class="footer">
    <div class="container">
        <div class="footer-top pt-100">
            <div class="row">
                <div class="footer-top-left col-sm-6">
                    <div class="col-sm-12 col-md-6">
                        <img class="brand-logo" src="{{asset(env('ROOT').env('BRAND').'brandlogo.png')}}">
                    </div>
                    <p class="copyright-text">&copy; <?=date('Y')?> Tüm Hakları Saklıdır.</p>
                    <ul class=social-links>
                        <li><a><i class="fab fa-instagram"></i></a></li>
                        <li><a><i class="fab fa-facebook-f"></i></a></li>
                        <li><a><i class="fab fa-twitter"></i></a></li>
                        <li><a><i class="fab fa-youtube"></i></a></li>
                        <li><a><i class="fab fa-tiktok"></i></a></li>
                        <li><a><i class="fab fa-whatsapp"></i></a></li>
                    </ul>
                </div>
                <div class="footer-top-right col-sm-6">

                    <div class="row">
                        <div class="col-sm-12 col-md">
                            <h2 class="footer-title">
                                <span>{{getSiteName()}}</span>
                            </h2>
                            <ul>
                                <li><a href="{{route('sayfa', 'hakkimizda')}}">@lang('general.hakkimizda')</a></li>
                                <li><a href="">@lang('general.haberler')</a></li>
                                <li><a href="{{route('sayfa', 'kurumsal')}}">@lang('general.kurumsal')</a></li>
                                <li><a href="">@lang('general.yorumlar')</a></li>

                            </ul>
                        </div>

                        <div class="col-sm-12 col-md">
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

                        <div class="col-sm-12 col-md">
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
            <div class="col-sm-12 col-md">
                <p class="text-center">ifeelcode</p>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="col-sm-12 col-md">
                <div class="payment-method">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>

    </div>
</footer>
</body>
<script src="{{asset(env('ROOT').env('FRONT').env('JS').'jquery.min.js')}}"></script>
<script src="{{asset(env('ROOT').env('FRONT').env('JS').'bootstrap.bundle.js')}}"></script>

<!-- owlcarousel -->

<script src="{{asset(env('ROOT').env('FRONT').env('JS').'owlcarousel/owl.carousel.min.js')}}"></script>

<script src="{{asset(env('ROOT').env('FRONT').env('JS').'popper.js')}}"></script>
<script src="{{asset(env('ROOT').env('FRONT').env('JS').'custom.js')}}"></script>
@yield('js')
</html>
