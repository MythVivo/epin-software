<div class="tab-pane fade" id="satis">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="text-danger font-italic">Oyunlar için epin satış şeklini sipariş yada stoktan olarak belirtebilirsiniz. Yeni eklenen oyunlar default olarak STOKTAN tanımlı gelir. Stoğu yetersiz olan ürün buradaki tanım dikkate alınmaksızın otomatik olarak siparişe düşer.<br>
                                Satışa kapatma oyun genelinde yapılırsa alt paketlerin tümü durumuna bakılmaksızın satışa kapanır. Aşağıdaki tablo yapısı gereği dinamik sıralama/aramayı desteklememektedir. Oyunlar alfabetik sıra halindedir.<br>
                                Oyun adına tıklayıp sublisti açabilir, paketleri görebilirsiniz. Paket bazında satışa kapatma ve Stok ikaz girişleri sublist üzerinde yapılmaktadır.<br>
                                Bu sayfada değişiklik yaptığınızda <a href='?cacheClear=1'>BURAYA</a> tıklayarak mevcut önbelleği temizlemeniz gerekir. Axi halde yapılan değişiklikler aktif olmayacaktır.
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?
                                Cache::flush();
                                $al=DB::select("select title,id from games where isnull(deleted_at) order by title");
                                $no=0;$n=0;
                                ?>
                                OYUN TABLOSU
                                <table class="table small table-hover">
                                    <tr><th>No</th><th>Oyun</th><th>Sipariş</th><th>Stoktan</th><th>Satışa Kapat</th><th>Düşük Stok İkaz (0 Off)</th></tr>

                                    @foreach ($al as $i)
                                        <?  // varsa yeni oyunları tabloya ekle
                                        $no++;
                                        $varmi=DB::table('oyun_siparis')->where('oyun',$i->id);
                                        if ($varmi->doesntExist()) {DB::insert("insert into oyun_siparis values(null,'$i->id',2,0,0 )");} else {$al=$varmi->first();}
                                        ?>
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td id="tr_{{$i->id}}" class="trr">{{$i->title}}</td>
                                            <td><input type="radio" title="Sipariş" value="1" id="siparis_{{$i->id}}" name="r{{$i->id}}" class="btn-block rad" style="height: 20px; filter:brightness(0.7)" <? echo  $al->durum==1?'checked':''?> ></td>
                                            <td><input type="radio" title="Stok" value="2" id="siparis_{{$i->id}}" name="r{{$i->id}}" class="btn-block rad" style="height: 20px; filter:brightness(0.7)" <? echo  $al->durum==2?'checked':''?> ></td>
                                            <td  style="text-align: center"><input title="Satışa kapalı" style="width: 20px; height: 20px; filter:brightness(0.7)" type="checkbox" id="u_{{$i->id}}" class="border skapat"  <? echo  $al->kapat==1?'checked':''?>></td>
                                            <td><input title="Paket bazında ikazı kullanın" type="number" min="0" id="u_{{$i->id}}" disabled style="background-color: #d3d3d3" class="text-lg-center border uyar" value="{{$al->uyar}}"></td>

                                        </tr>
                                        <tr style="display: none;" id="trg_{{$i->id}}"><td colspan="6">
                                                <table class="table table-hover" style="background-color: #38383821">
                                                    <tr><th>No</th> <th>Paket</th> <th>Satışa Kapat</th> <th>Düşük Stok İkaz</th></tr>

                                                    <? $paket=DB::select("SELECT gp.id, gt.game, gp.title, gp.kapat,gp.ikaz FROM games_titles gt, games_packages gp WHERE gt.game='$i->id' and gt.id=gp.games_titles and isnull(gt.deleted_at) and isnull(gp.deleted_at) ");?>
                                                    @foreach($paket as $p)
                                                        <?$n++;?>

                                                        <tr>
                                                            <td>{{$n}}</td>
                                                            <td>{{$p->title}}</td>
                                                            <td  style="text-align: center"><input style=" filter:brightness(0.7)" type="checkbox" id="p_{{$p->id}}" class="border pkapat"  <? echo  $p->kapat==1?'checked':''?>></td>
                                                            <td><input type="number" min="0" id="t_{{$p->id}}" style="background-color: #d3d3d3" class="text-lg-center border puyar" value="{{$p->ikaz}}"></td>
                                                        </tr>

                                                    @endforeach
                                                    <? $n=0;?>

                                                </table>
                                            </td></tr>
                                    @endforeach

                                </table>
                                Bu sayfada yapılan değişiklikler anında kayıt edilmektedir. <br>Aşağıdaki KAYIT butonu işlevsel <u>değildir</u>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

