<div class="tab-pane fade" id="pazar_yeri">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div>
                            Pazar yeri özet tablo :
                            <br><br>
                        <?
                            $sor=DB::select("select gt.title, count(pyi.id) TOPLAM,
                            (select count(py.id) from pazar_yeri_ilanlar py  where isnull(py.deleted_at) and pazar=pyi.pazar and userStatus=1 and status=1) AKTIF,
                            (select count(py.id) from pazar_yeri_ilanlar py  where isnull(py.deleted_at) and pazar=pyi.pazar and userStatus=0) KTP,
                            (select count(py.id) from pazar_yeri_ilanlar py  where isnull(py.deleted_at) and pazar=pyi.pazar and red_neden=7) ZA,
                            (select count(py.id) from pazar_yeri_ilanlar py  where isnull(py.deleted_at) and pazar=pyi.pazar and status=3) RED,
                            (select count(py.id) from pazar_yeri_ilanlar py  where isnull(py.deleted_at) and pazar=pyi.pazar and status=6) TAMAM,
                            (select count(py.id) from pazar_yeri_ilanlar py  where py.deleted_at is not null and pazar=pyi.pazar) DELETED
                            from pazar_yeri_ilanlar pyi
                            join games_titles gt on gt.id=pyi.pazar
                            GROUP by pyi.pazar order by TOPLAM desc");
                        ?>

                            <table class="small table-bordered table-hover table-sm table-striped" style="text-align: center">
                                <tr><th>PAZAR</th><th>TOPLAM</th><th>SİLİNEN</th><th>RED</th><th>AKTİF</th><th>SÜRESİ DOLAN</th><th title="Kullanıcı Tarafından Pasif">K.T.P.</th><th>TAMAM</th></tr>

                                <?
                                $sor=(object)$sor;
                                    foreach($sor as $s){
                                    echo "<tr><td>$s->title</td><td>$s->TOPLAM</td><td>$s->DELETED</td><td>$s->RED</td><td>$s->AKTIF</td><td>$s->ZA</td><td>$s->KTP</td><td>$s->TAMAM</td></tr>";
                                }

                            ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

