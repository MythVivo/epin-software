<link href="/back/assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
<style>.xd {padding-top: 20px;}</style>
<div class="container ">
    <form id="cypher">

        <div class="row xd">
            <div class="col-xxl-2 d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                <label style="font-size: large ;color: aquamarine;" >İlan Başlığı *</label>
            </div>
            <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                <input type="text" maxlength="200" class="form-control" name="baslik" placeholder="İlanınız için başlık girin">
            </div>
        </div>

        <div class="row xd">
            <div class="col-xxl-2 d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                <label style="font-size: large ;color: aquamarine;" >Server *</label>
            </div>
            <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                <select class="form-control form-select" required name="server">
                    <option value="">Server Seçin</option>
                    <?              $sor=DB::select("select value from games_titles_features where game_title=9 and title='Sunucu' and isnull(deleted_at)");
                    $r = json_decode($sor[0]->value);
                    sort($r);
                    foreach($r as $t) {
                        echo "<option value='$t'>$t</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row xd">
            <div class="col-xxl-2 d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                <label style="font-size: large ;color: aquamarine;">Karakter *</label>
            </div>
            <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center" style="text-align: center;">
                <select class="form-control form-select" name="karakter" id="karakter" required>
                    <option value="">Karakter ?</option>
                    <?              $sor=DB::select("select value from games_titles_features where game_title=9 and title='Karakter Türü' and isnull(deleted_at)");
                    $r = json_decode($sor[0]->value);
                    sort($r);
                    foreach($r as $t) {
                        echo "<option value='$t'>$t</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center" style="text-align: center;">
                <select class="form-control form-select" name="irk" id="irk" required>
                    <option value=""> Irk ?</option>
                    <?              $sor=DB::select("select value from games_titles_features where game_title=9 and title='Irk Türü' and isnull(deleted_at)");
                    $r = json_decode($sor[0]->value);
                    foreach($r as $t) {
                        echo "<option value='$t'>$t</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center" style="text-align: center;">
                <select class="form-control form-select" required name="level" id="level">
                    <option value="">Level ?</option>
                    <?              $sor=DB::select("select value from games_titles_features where game_title=9 and title='Level' and isnull(deleted_at)");
                    $r = json_decode($sor[0]->value);
                    foreach($r as $t) {
                        echo "<option value='$t'>$t</option>";
                    }
                    ?>

                </select>
            </div>

            <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center" style="text-align: center;">
                <select class="form-control form-select" name="yuzde" id="yuzde" required>
                    <option value="">Yüzde ? </option>
                    <? for($t=99;$t>-1;$t--){echo "<option value='$t'>%$t</option>";} ?>
                </select>
            </div>

        </div>
        <div class="row xd">
            <div class="col-xxl-2 d-flex d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                <label style="font-size: large;color: aquamarine;">NP *</label>
            </div>
            <div class="col-auto d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                <input class="form-control np" required type="number" min="0" pattern="\d*" maxlength="6" placeholder="NP Değeri" id="np" name="np">
            </div>
            <div class="col-auto align-items-xxl-center" style="align-self: center">
                <div class="form-theme-switch">
                    <label>
                        <input class="change-them" type="checkbox" id="k11" name="k11" checked>
                        <span><i class="">M</i><i class="">K</i></span>
                    </label>
                </div>
            </div>
            <div class="col-auto" style="padding-top: 15px">
                <div class="input-group mb-3">
                    <span class="input-group-text style-input" id="basic-addon1"> NP :</span>
                    <input type="text" step="0.01" id="NP" class="form-control style-input-pd" style="text-align: center" readonly="">
                    <span class="input-group-text style-input" id="mk">K</span>
                </div>
            </div>

        </div>
        <div class="row xd">
            <div class="col-xxl-2 d-xxl-flex justify-content-xxl-center align-items-xxl-center"><label style="font-size: large;color: aquamarine;">Fiyat *</label></div>
            <div class="col-auto" style="align-self: center; padding-top: 15px"><input required min="0" class="form-control rkm" type="number" maxlength="6" placeholder="Fiyatı Girin" id="fiyat" name="fiyat"></div>
            <div class="col-auto" style="padding-top: 10px">
                <label for="5" class="form-label">Hesabınıza Geçecek Tutar :</label><br>
                <div class="input-group mb-3">
                    <span class="input-group-text style-input" id="basic-addon1"> 1   % komisyon</span>
                    <input type="number" name="kazanc" step="0.01" class="form-control style-input-pd net" style="text-align: center" readonly="">
                    <span class="input-group-text style-input">₺</span>
                </div>
            </div>

            <div class="col-auto" style="display: flex; flex-direction: row-reverse; justify-content: flex-start; align-items: center; padding-top: 15px">
                <select name="sure" id="sure" class="form-control" style="width: 80px">
                    @for($f=168;$f>0;$f--)
                            <? if($f % 24==0) {$g=$f/24; $g.=" Gün";} else {$g=$f. " Saat";} ?>
                    <option value="{{$f}}">{{$g}} </option>
                    @endfor
                </select>
                <label class="form-label me-2">Yayın Süresi</label><br>
            </div>

        </div>


        <div class="row xd">
            <div class="col-xxl-2 d-flex d-xxl-flex justify-content-xxl-center align-items-xxl-center">
                <label style="font-size: large;color: aquamarine;">Achievement</label>
            </div>
            <div class="col d-inline-flex flex-wrap" style="align-content: center">
                <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="100DEF" id="100DEF"><label class="mt-1 form-check-label" for="100DEF">100 DEF</label></div>
                <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="90DEF" id="90DEF"><label class="mt-1 form-check-label"  for="90DEF">90 DEF</label></div>
                <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="40DEF" id="40DEF"><label class="mt-1 form-check-label"  for="40DEF">40 DEF</label></div>
                <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="10DEX" id="10DEX"><label class="mt-1 form-check-label"  for="10DEX">10 DEX</label></div>
                <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="7DEX"  id="7DEX"><label class="mt-1 form-check-label"   for="7DEX">7 DEX</label></div>
                <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="6DEX"  id="6DEX"><label class="mt-1 form-check-label"   for="6DEX">6 DEX</label></div>
                <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="9STR"  id="9STR"><label class="mt-1 form-check-label"   for="9STR">9 STR</label></div>
                <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="20INT" id="20INT"><label class="mt-1 form-check-label"  for="20INT">20 INT</label></div>
                <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="14INT" id="14INT"><label class="mt-1 form-check-label"  for="14INT">14 INT</label></div>
                <div class="form-check" style="padding-right: 10px;"><input class="form-check-input me-2" style="width: 20px;height: 20px" type="checkbox" name="9HP"   id="9HP"><label class="mt-1 form-check-label"    for="9HP"  >9 HP</label></div>
            </div>
        </div>
        <div class="row xd">
            <div class="col-xxl-2 d-flex d-xxl-flex justify-content-xxl-center ">
                <label style="font-size: large;color: aquamarine;">Açıklama</label>
            </div>
            <div class="col d-xxl-flex justify-content-xxl-center align-items-xxl-center"><textarea class="form-control" style="height: 90px" placeholder="Opsiyonel" name="aciklama"></textarea></div>
        </div>
        <div class="row p-4" style="align-items: end">
            <button class="btn col-3 color-darkgreen m-auto okey">Yayınla</button>
        </div>
    </form>
</div>


<script src="/back/assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
<script>

    $('.np').keyup(function(){
        if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);
        $('#NP').val($('.np').val().toString());
    })

    $('#k11, #m11').click(function () {
        let kk= $('#k11').is(':checked')?'K':'M';
        $('#NP').val($('.np').val().toString());
        $('#mk').text(kk);
    })

    $('.rkm').keyup(function(){
        if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);
        $('.net').val(($('.rkm').val()-($('.rkm').val()*1/100)).toFixed(2) );
    })

    $('.okey').click(function (){
        if ( !$('form')[1].checkValidity() ) {
            swal.fire({icon:'error',text:'Zorunlu Alanlar Boş Bırakılmış',showConfirmButton: false,timer:1500});
        } else {
            swal.fire({icon:'success',text:'Form Gönderiliyor..',showConfirmButton: false,timer:900});
            var item=$('#level').val()+' '+$('#irk').val()+' '+$('#karakter').val();
            $.get('', {item:item,cypher:316,npc:$('#k11').is(':checked')?'K':'M', veri: btoa($('#cypher').serialize())}, function (x) {
                if(x!=200){
                    swal.fire({icon:'error',text: x ,showConfirmButton: false,timer:5000});
                } else{ swal.fire({icon:'success',text:'Kayıt Başarılı',showConfirmButton: false,timer:2000}); location.href='/satici-panelim' }
            });
            return false
        }

    })

</script>

