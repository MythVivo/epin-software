@if (isset($_GET['sil']))
    <?php
    error_reporting(E_PARSE);
    DB::table('odeme_kanallari')
        ->where('id', $_GET['sil'])
        ->update([
            'deleted_at' => date('YmdHis'),
        ]);
    header('Location: ?okey');
    exit();
    ?>
@endif
@extends('front.layouts.app')
@section('css')
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        .modal {
            z-index: 9999;
        }
    </style>
@endsection
@section('body')
    <section class="bg-gray pb-40">
        <div class="container">

            <div class="row">
                @include('front.modules.user-menu')
                <div class="col-md-9">
                    <div class="row">
                        @if (session('success'))
                            <!--Mesaj bildirimi--->
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fas fa-check me-2"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                            <!--Mesaj bildirim END--->
                        @endif
                        @if (session('error'))
                            <!--Mesaj bildirimi--->
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                            <!--Mesaj bildirim END--->
                        @endif

                        <div class="col-12">
                            <div class="alert alert-success" role="alert">
                                <div>Çekilebilir bakiyeniz
                                    : {{ Auth::user()->bakiye_cekilebilir }}
                                    TL
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <?php
                            $odemeKanaliSayisi = DB::table('odeme_kanallari')
                                ->where('user', Auth::user()->id)
                                ->whereNull('deleted_at')
                                ->count();
                            ?>
                            @if ($odemeKanaliSayisi > 0)
                                <button type="button" class="btn-inline color-darkgreen w-100 mb-5" data-bs-toggle="modal"
                                    data-bs-target="#odemeTalebi">
                                    Yeni Ödeme Talebi
                                </button>
                            @else
                                <button type="button" class="btn-inline color-darkgreen w-100 mb-5" disabled>
                                    Ödeme Talebi İçin Banka Hesabı Ekleyin
                                </button>
                            @endif
                            <div class="modal fade" id="odemeTalebi" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">
                                                Yeni Ödeme Talebi
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <form method="post" action="{{ route('bakiye_cek_post') }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="1">Hesabınıza Geçecek
                                                            Tutar</label>
                                                        <input type="number" id="1" name="amount"
                                                            class="form-control style-input"
                                                            min="{{ DB::table('settings')->first()->yayin_komisyon }}"
                                                            required step="0.01"
                                                            max="{{ Auth::user()->bakiye_cekilebilir > 10000 ? 10000 : Auth::user()->bakiye_cekilebilir }}">
                                                        <small class="text-danger">Uygulanacak kesinti tutarı
                                                            <?if(Auth::user()->para_cek_kom==1) {echo DB::table('settings')->first()->yayin_komisyon;} else { echo  "0"; } ?>
                                                            TL
                                                        </small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="2">Ödeme Kanalı</label>
                                                        <select id="2" class="form-control style-input"
                                                            name="odeme_kanali" required>
                                                            @foreach (DB::table('odeme_kanallari')->where('user', Auth::user()->id)->whereNull('deleted_at')->get() as $u)
                                                                <option value="{{ $u->id }}">{{ $u->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <input type="hidden" name="text" value="">
                                                    <!--div class="col-md-12 mt-3">
                                                                                                <label class="form-label" for="3">Ek Not</label>
                                                                                                <input type="text" id="3" name="text"
                                                                                                       class="form-control style-input">
                                                                                            </div-->
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn-inline color-red"
                                                    data-bs-dismiss="modal">Kapat
                                                </button>
                                                <button type="submit" class="btn-inline color-darkgreen">Kaydet</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="col-md-4">
                            <button type="button" class="btn-inline color-white w-100 mb-5" data-bs-toggle="modal"
                                data-bs-target="#papara"><img src="/front/images/papara.jpg" height="22px"> Ödeme
                                Talebi</button>


                            <div class="modal fade" id="papara" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Papara Ödeme Talebi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <?php
                                        $tutar = Auth::user()->bakiye_cekilebilir;
                                        ?>
                                        <form method="post" action="{{ route('bakiye_cek_post') }}">
                                            @csrf
                                            <input type="hidden" name="odeme_kanali" value="PAPARA">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="tutar">Tutar</label>
                                                        <input type="number" id="tutar" name="amount"
                                                            class="form-control style-input" min="5" required
                                                            step="0.01" max="{{ $tutar }}">
                                                        <small class="text-danger">Uygulanacak kesinti tutarı %1.5 </small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="net">Hesabınıza Geçecek Net
                                                            Tutar</label>
                                                        <input type="text" id="net" name="" disabled
                                                            class="form-control style-input">
                                                    </div>

                                                    <div class="col-md-6 mt-3">
                                                        <label class="form-label" for="2">İsim Soyisim</label>
                                                        <input type="text" id="2" name="isim"
                                                            class="form-control style-input" required
                                                            value="{{ Auth::user()->name }}">
                                                    </div>

                                                    <div class="col-md-6 mt-3">
                                                        <label class="form-label" for="3">Papara Hesap No</label>
                                                        <input type="text" id="3"
                                                            class="form-control style-input" required <? $para=DB::table('odeme_kanallari')->where('user',Auth::user()->id)->where('title','PAPARA')->whereNull('deleted_at')->first();
                                                                                                    if(isset($para->iban) && $para->iban!=''){ echo "value='$para->iban' disabled";} else {echo 'name="papara"';}
                                                                                                    ?>>
                                                        <small class="text-danger">Hesap numaranız bu değil ise lütfen
                                                            kullanmadığınız hesapları (Ekle/Çıkar) menüsünden siliniz yada
                                                            değiştiriniz.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn-inline color-red"
                                                    data-bs-dismiss="modal">Kapat
                                                </button>
                                                <button type="submit" class="btn-inline color-darkgreen">Kaydet</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-4">
                            <button type="button" class="btn-inline color-darkgreen w-100 mb-5" data-bs-toggle="modal"
                                data-bs-target="#odemeKanallarim">
                                Banka Hesabı (IBAN) Ekle/Çıkar
                            </button>
                            <div class="modal fade" id="odemeKanallarim" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">
                                                Ödeme Kanallarım
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <button class="btn-inline color-blue mb-3" data-bs-target="#yeniOdemeKanali"
                                                data-bs-toggle="modal" data-bs-dismiss="modal">Yeni Ödeme Yöntemi
                                                Ekle
                                            </button>
                                            <div class="row">

                                                <table id="datatable2" class="table table-hover table-striped ">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Banka Adı</th>
                                                            <th>Alıcı</th>
                                                            <th>Hesap/IBAN</th>
                                                            {{--                                                        <th>Açıklama</th> --}}
                                                            <th>İşlem</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach (DB::table('odeme_kanallari')->where('user', Auth::user()->id)->whereNull('deleted_at')->get() as $u)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $u->title }}</td>
                                                                <td>{{ $u->alici }}</td>
                                                                <td>{{ $u->iban }}</td>
                                                                {{--                                                            <td>{{$u->text}}</td> --}}
                                                                <td>

                                                                    <button class="table-act-icon edit"
                                                                        @if (!Str::contains($u->title, 'PAPARA')) data-bs-toggle="modal"
                                                                            data-bs-target="#odemeKanallarimDuzenle{{ $u->id }}"> @endif
                                                                        <i class="fal fa-edit"></i>
                                                                    </button>

                                                                    <button confirm-data='?sil={{ $u->id }}'
                                                                        type="button"
                                                                        class=" confirm-btn table-act-icon remove">
                                                                        <i class="fal fa-trash-alt"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <div class="modal fade"
                                                                id="odemeKanallarimDuzenle{{ $u->id }}"
                                                                tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLabel">
                                                                                {{ $u->title }} Ödeme Kanalını Düzenle
                                                                            </h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <form method="post"
                                                                            action="{{ route('bakiye_cek_odeme_kanali_duzenle') }}">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                value="{{ $u->id }}">
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <label class="form-label"
                                                                                            for="1">Banka
                                                                                            Adı</label>
                                                                                        <input type="text"
                                                                                            id="1" name="title"
                                                                                            class="form-control style-input"
                                                                                            required
                                                                                            <? if(Str::contains($u->title, 'PAPARA')) { echo 'disabled';}
                                                                                                                                   ?>
                                                                                            value="{{ $u->title }}">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label class="form-label"
                                                                                            for="2">Alıcı Ad
                                                                                            Soyad</label>
                                                                                        <input type="text"
                                                                                            id="2" name="alici"
                                                                                            class="form-control style-input"
                                                                                            required
                                                                                            value="{{ $u->alici }}"
                                                                                            readonly>
                                                                                    </div>
                                                                                    <div class="col-md-5">
                                                                                        <label class="form-label"
                                                                                            for="3">IBAN</label>
                                                                                        <div class="input-group mb-3">
                                                                                            <span
                                                                                                class="input-group-text">TR</span>
                                                                                            <input type="number"
                                                                                                id="3"
                                                                                                name="iban"
                                                                                                class="form-control style-input"
                                                                                                required
                                                                                                value="{{ $u->iban }}"
                                                                                                onchange="checkIBAN(this)"
                                                                                                onkeyup="checkIBAN(this)">
                                                                                        </div>
                                                                                    </div>
                                                                                    {{--                                                                                <div class="col-md-12"><label class="form-label" for="4">Ek Not / Açıklama</label><input type="text" id="4" name="text" class="form-control style-input" required value="{{$u->text}}"></div> --}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                    class="btn-inline color-red"
                                                                                    data-bs-dismiss="modal">
                                                                                    Kapat
                                                                                </button>
                                                                                <button type="submit"
                                                                                    class="btn-inline color-darkgreen"
                                                                                    disabled>
                                                                                    Kaydet
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Banka Adı</th>
                                                            <th>Alıcı</th>
                                                            <th>IBAN</th>
                                                            {{--                                                        <th>Açıklama</th> --}}
                                                            <th>İşlem</th>
                                                        </tr>
                                                    </tfoot>

                                                </table>

                                                <div class="control_popup hide">
                                                    <article>
                                                        <div class="pp_header">
                                                            <h4>Dikkat!</h4>
                                                        </div>
                                                        <div class="pp_center">
                                                            <h6>Silmek istediğinize emin misiniz?</h6>
                                                        </div>
                                                        <div class="pp_buttons">
                                                            <a class="del">Sil</a>
                                                            <a class="cancel">Vazgeç</a>
                                                        </div>
                                                    </article>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn-inline color-red"
                                                data-bs-dismiss="modal">Kapat
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="yeniOdemeKanali" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        Yeni Ödeme Kanalı Ekle
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form method="post" action="{{ route('bakiye_cek_odeme_kanali') }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label" for="1">Banka Adı</label>
                                                <input type="text" id="b1" name="title"
                                                    class="form-control style-input" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label" for="2">Alıcı Ad Soyad</label>
                                                <input type="text" id="2" name="alici"
                                                    class="form-control style-input" readonly required
                                                    value="{{ Auth::user()->name }}">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label" for="3">IBAN</label>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text">TR</span>
                                                    <input type="number" id="3" name="iban"
                                                        class="form-control style-input" onchange="checkIBAN(this)"
                                                        onkeyup="checkIBAN(this)" required>
                                                </div>
                                            </div>
                                            {{--                                            <div class="col-md-12"> --}}
                                            {{--                                                <label class="form-label" for="4">Ek Not / Açıklama</label> --}}
                                            {{--                                                <input type="text" id="4" name="text" class="form-control style-input" --}}
                                            {{--                                                       required> --}}
                                            {{--                                            </div> --}}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn-inline color-red" data-bs-dismiss="modal">Kapat
                                        </button>
                                        <button type="submit" class="btn-inline color-darkgreen" disabled>Kaydet</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <?php
                        $para_cek = DB::table('para_cek')
                            ->where('user', Auth::user()->id)
                            ->whereNull('deleted_at')
                            ->orderBy('created_at');
                        ?>
                        @if ($para_cek->count() > 0)
                            <table id="datatable" class="table table-hover table-striped ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tutar</th>
                                        <th>Kesinti</th>
                                        <th>Açıklama</th>
                                        <th>İşlem Durumu</th>
                                        <th>Tarih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($para_cek->get() as $u)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $u->amount }} TL</td>
                                            <td>{{ $u->kesinti }} </td>
                                            <td>{{ $u->text }}</td>
                                            <td>
                                                @if ($u->status < 1)
                                                    Onay Bekliyor
                                                @elseif($u->status == '1')
                                                    Ödeme Onaylandı
                                                @else
                                                    Reddedildi
                                                @endif
                                            </td>
                                            <td>{{ $u->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Tutar</th>
                                        <th>Kesinti</th>
                                        <th>Açıklama</th>
                                        <th>İşlem Durumu</th>
                                        <th>Tarih</th>
                                    </tr>
                                </tfoot>

                            </table>
                        @else
                            <div class="col-12">
                                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
                                    role="alert">
                                    <i class="fas fa-exclamation-triangle me-3"></i>
                                    <h5>
                                        Henüz bir ödeme talebinde bulunmamışsınız. İlk talebinizden sonra burada
                                        görüntülenecektir.
                                    </h5>
                                </div>
                            </div>
                        @endif
                        <div class="col-12">
                            <div class="alert alert-info alert-dismissible fade show d-flex align-items-center"
                                role="alert">
                                <i class="fas fa-exclamation-triangle me-3"></i>
                                <h5>
                                    <span style="font-weight:bold">
                                        * 1.500 TL' ye kadar olan çekim talepleriniz 7/24, 1.500 TL üzerindeki talepleriniz ise
                                    bankaların çalışma günleri <span style="font-size:14px;color:#af6317">(Pazartesi-Cuma Resmi Tatiller Hariç)</span> ve
                                    saatleri <span style="font-size:14px;color:#af6317">(10:00-17:00)</span> içerisinde gönderilmektedir
                                    </span>
                                     <br><br>

                                    *EFT ve Havale işlemlerinizde tüm bankalar için her 10.000 TL para çekiminde 5 TL
                                    kesinti olur. Kesinti talep ettiğiniz tutar üzerinden sağlanmaktadır. <br><br>

                                    * Bakiye nakit taleplerinde Hesap sahibi Ad-Soyad ve İban numarası doğru olmalıdır.
                                    <br><br>

                                    * Sitemizde satış yaparak elde ettiğiniz bakiyenizi nakit olarak istediğiniz banka
                                    hesabına çekebilirsiniz. <br><br>

                                    * Kredi kartı ile yüklediğiniz bakiyenizi nakit olarak çekemezsiniz. Kredi kartı
                                    iadelerinizi ödeme yaptığınız tutarda ödeme yapılan karta iade alabilirsiniz, bu
                                    süreç bankaya göre değişken olup ortalama 10-14 iş günüdür. <br><br>

                                    * Banka yoluyla yapılan ödemelerin iadesi aynı banka üzerinden sadece ödeme yapan
                                    kişi adına kayıtlı bir banka hesabına yapılabilir. <br><br>

                                    * Mobil ödeme ve ön ödemeli kartlar ile yüklenen bakiyelerin iadesi mümkün değildir.
                                    <br><br>

                                    * Resmi tatillerde para çekim talepleriniz takip eden ilk iş günü gönderilmektedir.
                                </h5>
                            </div>
                        </div>


                    </div>
                </div>


            </div>
        </div>
    </section>
@endsection
@section('js')
    <script src="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/jquery.dataTables.min.js') }}">
    </script>
    <script
        src="{{ asset(env('ROOT') . env('BACK') . env('ASSETS') . 'plugins/datatables/dataTables.bootstrap4.min.js') }}">
    </script>
    <script>
        function checkIBAN(that) {
            var modulo = function(divident, divisor) {
                var m = 0;
                for (var i = 0; i < divident.length; ++i)
                    m = (m * 10 + parseInt(divident.charAt(i))) % divisor;
                return m;
            };
            // var iban = "75001110000000010738802222";
            var iban = $(that).val();
            console.log(iban);
            var newIban = iban.substring(2) + "292700";
            var calc = 98 - modulo(newIban, 97);
            var cur = iban.substring(0, 2);
            if (cur == calc) {
                $(that).closest('.modal-content').find('button[type="submit"]').prop('disabled', false);
            } else {
                $(that).closest('.modal-content').find('button[type="submit"]').prop('disabled', true);
            }
            //if(cur == calc)


        }
        $(document).ready(function() {
            $('#datatable').DataTable({
                pageLength: 10,
                "order": [
                    [0, "desc"]
                ],
                "language": {
                    "decimal": "",
                    "emptyTable": "{{ __('admin.hic-veri-yok') }}",
                    "info": "{{ __('admin.adet-veri-gosterim', ['TOTAL' => '_TOTAL_', 'START' => '_START_', 'END' => '_END_']) }}",
                    "infoEmpty": "{{ __('admin.sifir-veri-var') }}",
                    "infoFiltered": "{{ __('admin.adet-veri-araniyor', ['MAX' => '_MAX_']) }}",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "{{ __('admin.veri-gosteriliyor', ['MENU' => '_MENU_']) }}",
                    "loadingRecords": "{{ __('admin.yukleniyor') }}",
                    "processing": "{{ __('admin.isleniyor') }}",
                    "search": "{{ __('admin.ara') }}",
                    "zeroRecords": "{{ __('admin.eslesen-veri-bulunamadi') }}",
                    "paginate": {
                        "first": "{{ __('admin.ilk') }}",
                        "last": "{{ __('admin.son') }}",
                        "next": "{{ __('admin.sonraki') }}",
                        "previous": "{{ __('admin.onceki') }}"
                    },
                }
            });

            $('#datatable2').DataTable({
                pageLength: 10,
                "order": [
                    [0, "desc"]
                ],
                "language": {
                    "decimal": "",
                    "emptyTable": "{{ __('admin.hic-veri-yok') }}",
                    "info": "{{ __('admin.adet-veri-gosterim', ['TOTAL' => '_TOTAL_', 'START' => '_START_', 'END' => '_END_']) }}",
                    "infoEmpty": "{{ __('admin.sifir-veri-var') }}",
                    "infoFiltered": "{{ __('admin.adet-veri-araniyor', ['MAX' => '_MAX_']) }}",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "{{ __('admin.veri-gosteriliyor', ['MENU' => '_MENU_']) }}",
                    "loadingRecords": "{{ __('admin.yukleniyor') }}",
                    "processing": "{{ __('admin.isleniyor') }}",
                    "search": "{{ __('admin.ara') }}",
                    "zeroRecords": "{{ __('admin.eslesen-veri-bulunamadi') }}",
                    "paginate": {
                        "first": "{{ __('admin.ilk') }}",
                        "last": "{{ __('admin.son') }}",
                        "next": "{{ __('admin.sonraki') }}",
                        "previous": "{{ __('admin.onceki') }}"
                    },
                }
            });

            $('#tutar').keyup(function(x) {
                $('#net').val($('#tutar').val() - $('#tutar').val() / 100 * 1.5);
            });

            $('#b1').keyup(function(x) {
                if ($('#b1').val().toUpperCase() == 'PAPARA') {
                    alert(
                        "PAPARA için hesap eklemenize gerek yok.\nPAPARA Ödeme Talebi butonuna basıp hesap numarınızı girerek çekim talebi oluşturabilirsiniz."
                    );
                    location.href = "https://oyuneks.com/bakiye-cek";
                }
            });

        });
    </script>
@endsection
