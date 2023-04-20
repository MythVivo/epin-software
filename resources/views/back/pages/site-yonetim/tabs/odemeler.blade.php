<div class="tab-pane fade" id="odemeler">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-4">
                          Otomatik Ödeme Sistemleri
                        </div>

<? if(Auth::user()->id == 65778 ||  Auth::user()->id ==40 || Auth::user()->id ==4462 || Auth::user()->id == 12562) { ?>

                        <table class="font-12 table table-bordered table-condensed text-body " style="width: auto; text-align: center">
                            <tr><th>Tür</th><th>Otomatik Gönderim<br>Aktif/Pasif</th><th>Tek Seferde <br>Max. Çekilebilir Limit</th><th>Kullanıcı Günlük <br> Max. Çekim Limit *</th></tr>

<?
                            $durum = (object) DB::select("select * from _options");
                                foreach ($durum as $d){
                                    $r=json_decode($d->value);
?>
                                    <tr><td>{{strtoupper($d->name)}} Sistem </td>
                                        <td><input title="Oto Ödeme Aç/Kapa" style="width: 20px; height: 20px; filter:brightness(0.7)" type="checkbox"  @if($r->active=='true') checked @endif id="c_{{$d->name}}" class="border o_off"></td>
                                        <td><input min="0" id="l_{{$d->name}}" value="{{$r->limit}}" style="text-align: center" type="number"  class="form-control o_lmt"></td>
                                        <td><input min="0" id="om_{{$d->name}}" value="{{$d->name=='papara'?getCacheSetings()->papara_max:getCacheSetings()->banka_max}}" style="text-align: center" type="number"  class="form-control om_lmt"></td>
                                    </tr>
                            <?}?>
                        </table>
<?} else { echo "Ödeme düzenleme yetkiniz bulunmuyor.. :( "; }?>
                    </div>
                    <div class="row">
                        <label>
* Değer 0 olarak ayarlanırsa limit yok anlamına gelir
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div style="text-align: right; width: auto">Bu sayfada Buton işlevsel değildir</div>
</div>

