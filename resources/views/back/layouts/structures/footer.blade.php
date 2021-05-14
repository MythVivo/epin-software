@include('back.layouts.structures.footer-text')
<!-- jQuery  -->
<script src="{{asset(env('root').env('back').env('assets').'js/jquery.min.js')}}"></script>
<script src="{{asset(env('root').env('back').env('assets').'js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset(env('root').env('back').env('assets').'js/metismenu.min.js')}}"></script>
<script src="{{asset(env('root').env('back').env('assets').'js/waves.js')}}"></script>
<script src="{{asset(env('root').env('back').env('assets').'js/feather.min.js')}}"></script>
<script src="{{asset(env('root').env('back').env('assets').'js/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset(env('root').env('back').env('assets').'js/jquery-ui.min.js')}}"></script>

<!-- App js -->
<script src="{{asset(env('root').env('back').env('assets').'js/app.js')}}"></script>

@yield('js')

</body>

</html>
