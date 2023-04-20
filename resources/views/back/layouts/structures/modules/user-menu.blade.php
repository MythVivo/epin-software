<li class="dropdown">
    <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
       aria-haspopup="false" aria-expanded="false">
        @if(getUserAvatar() == "")
            <img src="{{asset('public/brand/brandicon.png')}}" alt="{{getUserName()}} avatar"
                 class="rounded-circle"/>
        @else
            <img src="{{asset(env('ROOT').env('FRONT').env('AVATARS').getUserAvatar())}}" alt="{{getUserName()}} avatar"
                 class="rounded-circle"/>
        @endif

        <span class="ml-1 nav-user-name hidden-sm">{{getUserName()}} <i class="mdi mdi-chevron-down"></i> </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        <?php /*
        <a class="dropdown-item" href="#"><i class="ti-user text-muted mr-2"></i> @lang('admin.profil')</a>
        <a class="dropdown-item" href="#"><i class="ti-settings text-muted mr-2"></i> @lang('admin.ayarlar')</a>
        <div class="dropdown-divider mb-0"></div> */ ?>
        <a class="dropdown-item" href="{{route('cikis')}}"><i class="ti-power-off text-muted mr-2"></i> @lang('admin.cikis')</a>
    </div>
</li>
