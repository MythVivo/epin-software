<div class="tab-pane fade" id="bakiye">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12" style="text-align: -webkit-center">
                            <label class="text-danger font-italic"> Aşağıdaki ödeme kanallarından gelen tutarlar için üst limit belirttiğinizde limiti aşan tutarlar operatör onayına düşecektir.<br>
                                Operatör onay verene tutar havuzda bekletilecek bakiye ye yansıtılmayacaktır. <br>
                                Onaya düşen ödemeler <b>Ödeme Onay</b> sayfasından izlenebilecektir. Üst limit iptali için 0 kullanın.<br><br>

                            </label>

                            <table class="font-12 table table-bordered table-condensed text-body " style="width: auto; text-align: center">
                                <tr><th>No</th><th>Kanal</th><th>Değer</th></tr>

                                <?
                                $sor=DB::select("select pc.*, l.tutar from payment_channels pc left join bakiye_bloke_odeme l on l.kanal=pc.id");
                                $no=0;
                                foreach ($sor as $al){$no++;
                                ?>
                                <tr><td>{{$no}}</td><td>{{$al->name}}</td><td><input min="0" value="{{$al->tutar}}" style="text-align: center" type="number" ooid="{{$al->id}}" class="form-control lmtx"></td></tr>
                                <?} ?>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="text-align: right; width: auto">Bu sayfada Buton işlevsel değildir</div>
</div>

