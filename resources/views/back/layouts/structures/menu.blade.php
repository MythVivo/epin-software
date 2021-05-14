<body>

<!-- Top Bar Start -->
<div class="topbar">

    @include('back.layouts.structures.modules.logo')
    <!-- Navbar -->
    <nav class="navbar-custom">
        <ul class="list-unstyled topbar-nav float-right mb-0">
            @include('back.layouts.structures.modules.lang')
            @include('back.layouts.structures.modules.notify')
            @include('back.layouts.structures.modules.user-menu')
        </ul><!--end topbar-nav-->
        <ul class="list-unstyled topbar-nav mb-0">
            <li>
                <button class="nav-link button-menu-mobile waves-effect waves-light">
                    <i class="ti-menu nav-icon"></i>
                </button>
            </li>
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
