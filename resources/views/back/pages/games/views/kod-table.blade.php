<? $sor=DB::table('games_packages_codes')->where('package_id', $paket->id)->where('package_id', $paket->id)->orderBy('id','DESC')->paginate(500); ?>
<a href="{{ $sor->previousPageUrl() }}">Önceki Sayfa</a> - <b>{{ $sor->currentPage() }} /
    <?= ceil($sor->total() / $sor->perPage()) ?></b> -
<a href="{{ $sor->nextPageUrl() }}">Sonraki Sayfa</a>


<table lang="{{ getLang() }}" id="datatable" class="table table-bordered nowrap"
    style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

        <tr>
            <th>Id</th>

            <th>Paket</th>

            <th>Kod</th>

            <th>Alış Fiyatı</th>

            <th>Kdv</th>

            <th>Tedarikçi</th>

            <th>@lang('admin.durum')</th>

            <th>@lang('admin.eklenmeTarihi')</th>

            <th>@lang('admin.aksiyon')</th>

        </tr>

    </thead>

    <tbody>

        @foreach ($sor as $u)
            <tr>

                <td>{{ $u->id }}</td>
                <td>{{ $paket->title }}</td>
                <td>{{ \epin::DEC($u->code) }} </td>
                <td>{{ $u->alis_fiyati }}</td>
                <td>{{ $u->kdv }}</td>
                <td>
                    {{ findCodeSupplier($u->id) }}
                </td>
                <td style="text-align: center">
                    @if ($u->is_used == 1)
                        <span class='alert alert-primary' style="padding: 5px 10px">Satıldı</span>
                    @else
                        <span class='alert alert-success' style="padding: 5px 10px">Stokta</span>
                    @endif
                </td>
                <td>{{ $u->created_at }}</td>
                <td>
                    @if (userRoleIsAdmin(Auth::user()->id))
                        <button class="btn btn-outline-danger btn-sm"
                            onclick="deleteContent('games_packages_codes', {{ $u->id }})"><i
                                class="far fa-trash-alt"></i>
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach

    </tbody>

    <tfoot>

        <tr>
            <th>Id</th>

            <th>Paket</th>

            <th>Kod</th>

            <th>Alış Fiyatı</th>

            <th>Kdv</th>

            <th>Tedarikçi</th>

            <th>@lang('admin.durum')</th>

            <th>@lang('admin.eklenmeTarihi')</th>

            <th>@lang('admin.aksiyon')</th>

        </tr>

    </tfoot>

</table>
