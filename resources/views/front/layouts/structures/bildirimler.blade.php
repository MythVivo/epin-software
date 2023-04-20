
    <div class="dropdown dropdown-notification-frame">
        <a data-bildirim_id="0" class="nt-bell clear-notifications " href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="far fa-bell"></i></a>
        <ul id="notf-main-frame" class="dropdown-menu dropdown-menu-end"
            aria-labelledby="dropdownMenuButton2">
            <li class="notification-header"><h4><a href="{{route('bildirimlerim')}}">Bildirimler</a></h4></li>
            @foreach(DB::table('bildirim')->where('user', Auth::user()->id)->whereNull('deleted_at')->orderBy('created_at', 'desc')->take(4)->get()  as $b)
                <li class="notification-item @if($b->isRead == 0) new-nt @else old-nt @endif" @if($b->isRead == 0) data-set-id="{{$b->id}}" @endif>
                    <div class="nt-container">
                        <a href="{{$b->link}}">{{$b->title}}</a>
                        <h6>{{$b->text}}</h6>
                        <div class="nt-container-footer">
                            <div class="nt-cont-left">
                                <span>{{findBildirimTime($b->id)}}</span>
                            </div>
                            <div class="nt-cont-right">
                                @if($b->isRead == 0) <a data-bildirim_id="{{$b->id}}" class="read-nt">Okundu Olarak İşaretle</a> @else <i class="far fa-check-double"></i> @endif
                            </div>
                        </div>

                    </div>
                </li>
            @endforeach

            <li class="notification-footer">
                <a class="clear-notifications" data-bildirim_id="0">Tümünü Okundu İşaretle</a>
            </li>
        </ul>
    </div>
