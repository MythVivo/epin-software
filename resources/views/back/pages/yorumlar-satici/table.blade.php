<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"

       style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

    <tr>

        <th>Yapan Kullanıcı</th>

        <th>Satıcı</th>

        <th>Yorum</th>

        <th>Puan</th>

        <th>Durumu</th>

        <th>İşlem Tarihi</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </thead>

    <tbody>

    @foreach(DB::table('satici_yorumlar')->whereNull('deleted_at')->get() as $u)

        <tr>

            <td>{{DB::table('users')->where('id', $u->yapan)->first()->name}}</td>

            <td>{{DB::table('users')->where('id', $u->satici)->first()->name}}</td>

            <td>{{$u->text}}</td>

            <td>{{$u->puan}} puan</td>

            <td>

                @if($u->status == 0)

                    Onay Aşamasında

                @elseif($u->status == 1)

                    Onaylandı

                @elseif($u->status == 2)

                    Reddedildi

                @else

                    Bir Sorun Oluştu

                @endif

            </td>

            <td>

                {{$u->created_at}}

            </td>

            <td>

                @if($u->status != 1)

                    <button onclick="location.href='{{route('yorumlar_satici_onayla', [1, $u->id])}}'" type="button"

                            class="btn btn-lg btn-outline-success waves-effect waves-light">

                        <i class="fas fa-check"></i>

                    </button>

                @endif

                @if($u->status != 2)

                    <button onclick="location.href='{{route('yorumlar_satici_onayla', [2, $u->id])}}'" type="button"

                            class="btn btn-lg btn-outline-warning waves-effect waves-light">

                        <i class="fas fa-times"></i>

                    </button>

                @endif
                @if(userRoleIsAdmin(Auth::user()->id))
                <button onclick="deleteContent('satici_yorumlar', {{$u->id}})" type="button"

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

        <th>Yapan Kullanıcı</th>

        <th>Satıcı</th>

        <th>Yorum</th>

        <th>Puan</th>

        <th>Durumu</th>

        <th>İşlem Tarihi</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </tfoot>

</table>



