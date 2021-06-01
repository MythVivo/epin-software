<footer class="footer">
    <div class="container">
        <div class="footer-top pt-100">
            <div class="row">
                <div class="footer-top-left col-sm-6">
                    <div class="col-sm-12 col-md-6">
                        <img class="brand-logo" src="{{asset(env('root').env('brand').'brandlogo.png')}}">
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
                                <li><a href="">@lang('general.hakkimizda')</a></li>
                                <li><a href="">@lang('general.haberler')</a></li>
                                <li><a href="">@lang('general.kurumsal')</a></li>
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
<script src="{{asset(env('root').env('front').env('js').'jquery.min.js')}}"></script>
<script src="{{asset(env('root').env('front').env('js').'bootstrap.bundle.js')}}"></script>

<!-- owlcarousel -->

<script src="{{asset(env('root').env('front').env('js').'owlcarousel/owl.carousel.min.js')}}"></script>

<script src="{{asset(env('root').env('front').env('js').'popper.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
<script src="{{asset(env('root').env('front').env('js').'custom.js')}}"></script>
@yield('js')
</html>
