<table lang="{{getLang()}}" id="datatable" class="font-12 table-sm table-hover table-bordered dt-responsive nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Paket Başlığı</th>
        <th>Tutar</th>
        <th>Üretilen Adet</th>
        <th>Kullanılmış</th>
        <th>Müsait</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>Sonlanma Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>
    @foreach(DB::table('hediye_kodlari')->whereNull('deleted_at')->get() as $u)
        <tr>
            <td>{{$u->title}}</td>
            <td>{{$u->price}}</td>
            <td>{{$u->adet}}</td>
            <td>
                {{DB::table('hediye_kodlari_kodlar')->where('isUsed', '1')->where('hediye_kodu', $u->id)->whereNull('deleted_at')->count()}}
            </td>
            <td>
                {{DB::table('hediye_kodlari_kodlar')->where('isUsed', '0')->where('hediye_kodu', $u->id)->whereNull('deleted_at')->count()}}
            </td>
            <td>{{$u->created_at}}</td>
            <td>{{$u->expired_at}}</td>
            <td>
                
                
                    <i data-toggle="modal" data-target=".goruntule{{$u->id}}" class="btn far fa-eye"></i>
                
                <div class="modal fade goruntule{{$u->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title mt-0" id="myModalLabel">{{$u->title}} İçin Hediye Kodları</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-success w-100" onclick="location.href='?indir={{$u->id}}&kullanim=0'">Müsait Kodları İndir</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-danger w-100" onclick="location.href='?indir={{$u->id}}&kullanim=1'">Kullanılmış Kodları İndir</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    {{view('back.pages.hediye-kodlari.tableKodlar')->with('u', $u)}}
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                @if(userRoleIsAdmin(Auth::user()->id))
                
                
                    <i onclick="deleteContent('hediye_kodlari', {{$u->id}})" class="btn far fa-trash-alt"></i>
                
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Paket Başlığı</th>
        <th>Tutar</th>
        <th>Üretilen Adet</th>
        <th>Kullanılmış</th>
        <th>Müsait</th>
        <th>@lang('admin.eklenmeTarihi')</th>
        <th>Sonlanma Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

