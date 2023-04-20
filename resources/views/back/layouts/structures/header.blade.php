<?php
if(!sayfaIzinKontrol(getPage())) {
    header("Location: " . URL::to(route('errors_403')), true, 302);
    exit();
}
if(isset($_GET['cacheClear'])) {
    Cache::flush();
    Header("Location: ?ok=Okey");
    exit();
    die();
}
?>
<!DOCTYPE html>
<html lang="{{getLang()}}">
<head>
    <meta charset="utf-8"/>
    <title>{{getSiteName()}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="{{getSiteDescription()}}" name="description"/>
    <meta content="{{getAuthorName()}}" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset(env('ROOT').env('FRONT').env('BRAND').getFavicon())}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@yield('css')
<!-- App css -->
    @if(isset($_COOKIE['theme']) and $_COOKIE['theme'] == 'dark')
        <?php
        $darkBootstrap = "-dark";
        ?>
    @endif
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'css/bootstrap-dark.min.css')}}"
          rel="stylesheet @if(!isset($darkBootstrap)) alternate @endif" id="dark"
          type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'css/bootstrap.min.css')}}" rel="stylesheet @if(isset($darkBootstrap)) alternate @endif"
          id="light"
          type="text/css"/>

    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'css/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'css/icons.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'css/metisMenu.min.css')}}" rel="stylesheet"
          type="text/css"/>

    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'css/app-dark.min.css')}}"
          rel="stylesheet @if(!isset($darkBootstrap)) alternate @endif" id="dark1"
          type="text/css"/>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'css/app.min.css')}}"
          rel="stylesheet @if(isset($darkBootstrap)) alternate @endif" id="light1"
          type="text/css"/>
    <?php $fileTime = filemtime(env('ROOT') . env('BACK') . env('ASSETS') . 'css/special.css'); ?>
    <link href="{{asset(env('ROOT').env('BACK').env('ASSETS').'css/special.css')}}?ver={{$fileTime}}" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css" rel="stylesheet">

</head>
