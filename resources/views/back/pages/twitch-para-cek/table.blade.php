<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"

       style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

    <tr>

        <th>Id</th>

        <th>Talep Eden Kullanıcı</th>

        <th>Tutar</th>

        <th>Kesinti</th>

        <th>Ödeme Bilgileri</th>

        <th>Durumu</th>

        <th>İşlem Tarihi</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </thead>

    <tbody>

    @foreach(DB::table('twitch_support_cevirmeler')->where('tur', '2')->whereNull('deleted_at')->get() as $u)

        <tr>

            <td>{{$u->id}}</td>

            <td>
                <?php $uye = DB::table('users')->where('id', $u->user)->first(); ?>
                <a href="{{route('uye_detay', [$uye->email])}}" target="_blank">
                    {{$uye->name}}
                </a>
            </td>

            <td>{{$u->amount}}</td>

            <td>{{$u->kesinti}}</td>

            <td>

                @if(DB::table('odeme_kanallari')->where('id', $u->id)->count() > 0)

                    <?php $odemeKanallari = DB::table('odeme_kanallari')->where('id', $u->odeme_kanali)->first(); ?>

                    Alıcı : {{$odemeKanallari->alici}} <br>

                    IBAN : TR{{$odemeKanallari->iban}} <br>

                @else

                    Ödeme Kanalı Seçmemiş

                @endif

            </td>

            <td>

                @if($u->status == '0')

                    Onay Bekliyor

                @elseif($u->status == '1')

                    Ödeme Onaylandı

                @else

                    Reddedildi

                @endif

            </td>

            <td>

                {{$u->created_at}}

            </td>

            <td>

                @if($u->status != 1)

                    <button onclick="location.href='{{route('twitch_para_cek_yonetim_onayla', [1, $u->id])}}'"

                            type="button" class="btn btn-lg btn-outline-success waves-effect waves-light">

                        <i class="fas fa-check"></i>

                    </button>

                @endif

                @if($u->status != 2)

                    <button onclick="location.href='{{route('twitch_para_cek_yonetim_onayla', [2, $u->id])}}'"

                            type="button" class="btn btn-lg btn-outline-warning waves-effect waves-light">

                        <i class="fas fa-times"></i>

                    </button>

                @endif
                @if(userRoleIsAdmin(Auth::user()->id))
                <button onclick="deleteContent('twitch_support_cevirmeler', {{$u->id}})" type="button"

                        class="btn btn-lg btn-outline-danger waves-effect waves-light">

                    <i class="far fa-trash-alt"></i>

                </button>
                @endif
            </td>

        </tr>

    @endforeach

    </tbody>

    <tfoot>

    <tr>

        <th>Id</th>

        <th>Talep Eden Kullanıcı</th>

        <th>Tutar</th>

        <th>Kesinti</th>

        <th>Ödeme Bilgileri</th>

        <th>Durumu</th>

        <th>İşlem Tarihi</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </tfoot>

</table>



