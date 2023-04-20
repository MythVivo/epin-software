<body class="@if(!isset($_COOKIE['theme']) or (isset($_COOKIE['theme']) and $_COOKIE['theme'] == 'dark')) dark @endif"> <!-- dark theme -->

<!-- Top Bar Start -->
<div class="topbar">

@include('back.layouts.structures.modules.logo')
<!-- Navbar -->
    <nav class="navbar-custom">
        <ul class="list-unstyled topbar-nav float-right mb-0">
            <li class="hidden-sm mt-3">
                <div class="form-theme-switch">
                    <label>
                        <input class="change-theme" type="checkbox" id="flexSwitchCheckDefault"
                               @if(!isset($_COOKIE['theme']) or (isset($_COOKIE['theme']) and $_COOKIE['theme'] == 'dark')) checked @endif>
                        <span>
                                    <i class="fas fa-sun"></i>
                                    <i class="fas fa-moon"></i>
                                </span>
                    </label>
                </div>
            </li>
            <li class="hidden-sm backto-website">
                <a class="nav-link waves-effect waves-light" href="{{route('homepage')}}" role="button"
                   aria-haspopup="false" aria-expanded="false">
                   <i class="fas fa-external-link-alt"></i>
                </a>
            </li>
            <?php /* @include('back.layouts.structures.modules.lang') */ ?>
            <?php /* @include('back.layouts.structures.modules.notify') */ ?>
            @include('back.layouts.structures.modules.user-menu')
        </ul><!--end topbar-nav-->
        <ul class="list-unstyled topbar-nav mb-0">
            <li>
                <button class="nav-link button-menu-mobile waves-effect waves-light">
                    <i class="ti-menu nav-icon"></i>
                </button>
            </li>
            @foreach(DB::table('hizli_menu')->where('user', Auth::user()->id)->get() as $menu)
                <li><a class="nav-link waves-effect waves-light" href="{{env('APP_URL').$menu->link}}">{!! $menu->icon !!} </a></li>
            @endforeach
        </ul>
    </nav>
    <!-- end navbar-->
</div>
<!-- Top Bar End -->


<!-- Left Sidenav -->
@include('back.layouts.structures.modules.left-sidebar')
<!-- end left-sidenav-->

<div class="page-wrapper" lang="{{getLang()}}">
    <!-- Page Content-->
    <div class="page-content">

        <div class="container-fluid">
            <!-- Page-Title -->
        @include('back.modules.breadcrumb')
        <!-- end page title end breadcrumb -->
<script>
    $(function() {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>