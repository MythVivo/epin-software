    <?php
    if (isset($_GET['name'])) {$name = $_GET['name'];} else {$name = "";}
    if (isset($_GET['email'])) {$email = $_GET['email'];} else {$email = "";}
    if (isset($_GET['tel'])) {$tel = $_GET['tel'];} else {$tel = "";}
    if (isset($_GET['ref'])) {$ref = $_GET['ref'];} else {$ref = "";}
    if (isset($_GET['blk'])) {$blk = 1;}

    function checkEmail($email)
    {
        $find1 = strpos($email, '@');
        return ($find1 !== false);
    }
    ?>



<div class="row">
    <div class="col" style="text-align: center;"><form method="get"><div class="form-group"><input <? echo $name!=''?"value='$name'":""?>   required placeholder="İsim de geçen" type="text" name="name" class="col-md-5 form-check-inline form-control text-center"><button class="btn btn-success form-control-sm" type="submit">Ara</button></div></form></div>
    <div class="col" style="text-align: center;"><form method="get"><div class="form-group"><input <? echo $email!=''?"value='$email'":""?> required placeholder="E-mail de geçen" type="text" name="email" class="col-md-5 form-check-inline form-control text-center"><button class="btn btn-success form-control-sm" type="submit">Ara</button></div></form></div>
    <div class="col" style="text-align: center;"><form method="get"><div class="form-group"><input <? echo $tel!=''?"value='$tel'":""?>     required placeholder="Telefon da geçen" type="text" name="tel" class="col-md-5 form-check-inline form-control text-center"><button class="btn btn-success form-control-sm" type="submit">Ara</button></div></form></div>
    <div class="col" style="text-align: center;"><form method="get"><div class="form-group"><input <? echo $ref!=''?"value='$ref'":""?>     required placeholder="Referans ta geçen" type="text" name="ref" class="col-md-5 form-check-inline form-control text-center"><button class="btn btn-success form-control-sm" type="submit">Ara</button></div></form></div>
</div>

<table lang="{{getLang()}}" id="datatable" class="font-12 nowrap table-bordered table-hover table-sm" style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

    <tr>

        <th>Id</th>

        <th>@lang('admin.uyeIsmi')</th>

        <th>@lang('admin.uyeEposta')</th>

        <th>Üye Telefon</th>

        <th>Rol</th>

        <th>@lang('admin.eklenmeTarihi')</th>

        <th>@lang('admin.durum')</th>

        <th>Bakiye</th>

        <th>Referans</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </thead>

    <tbody>
    <?php
    if (isset($_GET['name'])) {
        $sorgu = \App\Models\User::whereNull('deleted_at')->whereRaw("name COLLATE utf8mb4_turkish_ci LIKE '%$name%'")->orderBy('created_at', 'desc')->get();
    } elseif (isset($_GET['email'])) {
        $sorgu = \App\Models\User::whereNull('deleted_at')->where('email', 'like', '%' . $email . '%')->orderBy('created_at', 'desc')->get();
    } elseif (isset($_GET['tel'])) {
        $sorgu = \App\Models\User::whereNull('deleted_at')->where('telefon', 'like', '%' . $tel . '%')->orderBy('created_at', 'desc')->get();
    } elseif (isset($_GET['ref'])) {
        $sorgu = DB::select("select * from referans where referans like '%$ref%' COLLATE utf8mb4_turkish_ci");
    } elseif (isset($_GET['blk']) && $_GET['blk']==316) {
        $sorgu = DB::select("SELECT u.* FROM users u join bakiye_bloke bb on bb.user=u.id where bb.aktif=1");
    }
    else {
        $sorgu = \App\Models\User::whereNull('deleted_at')->whereDate('created_at', date('Y-m-d'))->orderBy('created_at', 'desc')->get();
    }
    ?>

    @foreach($sorgu as $u)

        <tr id="row-{{$u->id}}">

            <td>
            @if(Auth::user()->id==40 || Auth::user()->id==12562)
                <a target="_blank" href="https://oyuneks.com/ykp_login/{{$u->id}}" > {{$u->id}}</a>
                @else
                    {{$u->id}}
            @endif

            </td>

            <td>{{$u->name}}</td>

            <td>{{$u->email}}</td>

            <td>{{$u->telefon}}</td>

            <td>

                @if($u->role == 0)

                    Yönetici -

                    @foreach(DB::table('user_group')->whereNull('deleted_at')->get() as $ug)

                        @if(DB::table('user_group_users')->where('user_group', $ug->id)->where('user', $u->id)->count() > 0)

                            {{$ug->title}}

                        @endif

                    @endforeach

                @else

                    Üye

                @endif

            </td>

            <td>{{$u->created_at}}</td>

            <td id="statusText"><?=getDataStatus($u->status)?></td>

            <td>{{MF($u->bakiye)}}</td>

            <td><?if($ref!=''){echo $u->referans;} else {echo findUserReference($u->id);} ?> </td>


            <td align="center">
                <i title="Üyeliği askıya al" id="status-icon" onclick="status({{$u->id}}, 'users', event)" class="btn fa fa-eye @if($u->status == 0) btn-outline-warning @else btn-outline-success @endif"></i>
                <?php
                if (!checkEmail($u->email)) {
                    $u->email = '?';
                }
                ?>
                @if($u->email == '?')
                    <i class="btn btn-lg btn-outline-primary waves-effect waves-light" class="btn far fa-edit"></i>
                @else
                    <i title="Üye Paneli" onclick="window.open('{{route('uye_detay', [$u->email])}}')" class="btn far fa-edit"></i>
                    <i title ="Üye Hareket İzleme" onclick="window.open('https://oyuneks.com/panel/uye-aktivite?uid={{$u->id}}')" class="btn fa fa-search" aria-hidden="true"></i>
                    <i title ="SMS gönder" uid="{{$u->telefon}}" class="sms fa btn fa-phone" style="color:gold"></i>
                    @if(bloke_kontrol($u->id))
                        <i title ="Bakiye Blokeli" uid="{{$u->id}}" dr="1" class="bloke fa btn fa-ban" style="color:Tomato"></i>
                    @else
                        <i title ="Bakiye Bloke Et" uid="{{$u->id}}" dr="2" class="bloke fa btn fa-dollar-sign" style="color:greenyellow"></i>
                    @endif
                @endif
                @if(userRoleIsAdmin(Auth::user()->id))
                    <i title="Sil" onclick="deleteContent('users', {{$u->id}})" class="btn far fa-trash-alt"></i>
                @endif
            </td>

        </tr>

    @endforeach

    </tbody>
</table>
