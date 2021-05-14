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
    <link rel="shortcut icon" href="{{asset(env('root').env('front').env('brand').getFavicon())}}">

@yield('css')
<!-- App css -->
    <link href="{{asset(env('root').env('back').env('assets').'css/bootstrap-dark.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset(env('root').env('back').env('assets').'css/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset(env('root').env('back').env('assets').'css/icons.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset(env('root').env('back').env('assets').'css/metisMenu.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset(env('root').env('back').env('assets').'css/app-dark.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset(env('root').env('back').env('assets').'css/special.css')}}" rel="stylesheet" type="text/css"/>


</head>
