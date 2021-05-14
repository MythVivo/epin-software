<?php
use App\Models\Language;
?>
<li class="hidden-sm">
    <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="{{route('lang', getLang())}}" role="button"
       aria-haspopup="false" aria-expanded="false">
        {{getLangLongName(getLang())}} <img src="{{asset(env('root').env('back').'assets/images/flags/'.getLang().'.jpg')}}" class="ml-2" height="16" alt=""/> <i class="mdi mdi-chevron-down"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        @foreach(Language::whereNull('deleted_at')->get() as $u)
        <a class="dropdown-item" href="{{route('lang', $u->lang)}}"><span> {{$u->langName}} </span><img src="{{asset(env('root').env('back').'assets/images/flags/'.$u->lang.'.jpg')}}" alt="{{$u->langName}}" class="ml-2  float-right" height="16"/></a>
        @endforeach
    </div>
</li>
