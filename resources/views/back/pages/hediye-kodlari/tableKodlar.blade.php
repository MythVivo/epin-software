<table lang="{{getLang()}}" id="datatable2" class="font-12 table-sm table-bordered table-hover dt-responsive nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Kod</th>
        <th>Kullanım Durumu</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>Sonlanma Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    @foreach(DB::table('hediye_kodlari_kodlar')->where('hediye_kodu', $u->id)->whereNull('deleted_at')->get() as $uu)
        <tr>
            <td>{{\epin::DEC($uu->kod)}}</td>
            <td>
                @if($uu->isUsed == 0)
                    <span class="text-success">Müsait</span>
                @else
                    <span class="text-danger">Kullanılmış</span>
                @endif
            </td>
            <td>{{$uu->created_at}}</td>
            <td>{{$uu->expired_at}}</td>
            <td>


                    <i onclick="deleteContent('hediye_kodlari_kodlar', {{$uu->id}})" class="btn far fa-trash-alt"></i>

            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Kod</th>
        <th>Kullanım Durumu</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>Sonlanma Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

