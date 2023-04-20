<div class="tab-pane fade" id="faturalar">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-4">
                          Fatura Kesim Ayarları
                        </div>

                        <table class="font-12 table table-bordered table-condensed text-body " style="width: auto; text-align: center">
                            <tr><th>Tür</th><th>Otomatik Fatura<br>Aktif/Pasif</th><th>Üst Limit</th></tr>

<?
                            $durum = (object) DB::select("select * from e_fatura_ayar");
                                foreach ($durum as $d){
                                    if($d->tip==1) {$d1=$d->durum==1?'checked':''; $l1=$d->ulimit;}
                                    if($d->tip==2) {$d2=$d->durum==1?'checked':''; $l2=$d->ulimit;}
                                    if($d->tip==3) {$d3=$d->durum==1?'checked':''; $l3=$d->ulimit;}
                                }
?>
                            <tr><td>EPIN Fatura</td><td><input title="Oto Fatura Aç/Kapa" style="width: 20px; height: 20px; filter:brightness(0.7)" type="checkbox"       {{ $d1 }} id="of1" class="border off" ></td><td><input min="0" id="lmt1" value="{{$l1}}" style="text-align: center" type="number"  class="form-control lmtf"></td></tr>
                            <tr><td>Game Gold Fatura</td><td><input title="Oto Fatura Aç/Kapa" style="width: 20px; height: 20px; filter:brightness(0.7)" type="checkbox"  {{ $d2 }} id="of2" class="border off" ></td><td><input min="0" id="lmt2" value="{{$l2}}" style="text-align: center" type="number"  class="form-control lmtf"></td></tr>
                            <tr><td>Pazar Yeri Fatura</td><td><input title="Oto Fatura Aç/Kapa" style="width: 20px; height: 20px; filter:brightness(0.7)" type="checkbox" {{ $d3 }} id="of3" class="border off" ></td><td><input min="0" id="lmt3" value="{{$l3}}" style="text-align: center" type="number"  class="form-control lmtf"></td></tr>

                        </table>
                    </div>
                    <div class="row">
                        <label>
                            Otomatik fatura modu aktif edilirse yapılan işlem onaylandığı anda alıcıya fatura kesilir.
                            <br> Yapılan işlem toplamı belirtilen üst limit altında ise işlem gerçekleşir.
                            <br> Geçiyorsa işlem yapılmaz manuel fatura kesim ekranına düşer.
                            <br> Üst limit girilmez yada boş bırakılırsa limit gözetmeksizin tüm işlemlere fatura kesilir.
                            <br><br>
                            TEST  AŞAMASINDA
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div style="text-align: right; width: auto">Bu sayfada Buton işlevsel değildir</div>
</div>

