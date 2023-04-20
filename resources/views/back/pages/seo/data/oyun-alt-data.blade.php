<?php
$oyun = DB::table('games_titles')->where('id', $id)->first();
$oyunAna = DB::table('games')->where('id', $oyun->game)->first();
?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <form id="pageSave" method="post" action="{{route(('seo_yonetim_oyun_alt_meta_save'))}}">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="title">Sayfa Başlığı</label>
                                <input id="title" class="form-control" name="title" value="{{$oyun->title}}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="link">Sayfa link</label>
                                <input id="link" class="form-control" name="link" value="{{$oyun->link}}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Açıklama</label>
                                <textarea id="description" name="description" class="form-control" rows="3">{{$oyun->description}}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="keywords">Anahtar Kelimeler</label>
                                <input id="keywords" class="form-control" name="keywords" value="{{$oyun->keywords}}">
                                <small class="text-danger">*Kelimeleri virgül ile ayırınız.</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <input type="hidden" name="id" value="{{$oyun->id}}">
                            <button type="submit" class="btn btn-outline-success btn-block">Kaydet</button>
                        </div>

                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <a class="searchResult">
                    <?php
                    if($oyun->type == 1) {
                        $ara = 'item';
                    } elseif($oyun->type == 2) {
                        $ara = 'e-pin-detay';
                    } else {
                        $ara = 'game-gold';
                    }
                    ?>
                    <span>{{env('APP_URL').$ara}}/<t class="text-danger" id="pageLink">{{$oyun->link}}</t> </span>
                    <h3 id="pageTitle">{{$oyun->title}}</h3>
                    <p id="pageDesc">{{$oyun->description}}</p>
                </a>

            </div>
        </div>
    </div>
</div>
<script>
    $( "#title" ).keyup(function() {
        $("#pageTitle").html($("#title").val());
    });
    $( "#description" ).keyup(function() {
        $("#pageDesc").html($("#description").val());
    });
    $( "#link" ).keyup(function() {
        $("#pageLink").html($("#link").val());
    });

    $("#pageSave").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var actionUrl = form.attr('action');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(),
            success: function(data)
            {
                if(!data) {
                    data = 1;
                }
                if(data) {
                    toastr.success('Başarılı', 'İçerik başarıyla kaydedildi.', {"progressBar": true});
                } else {
                    toastr.error('Başarısız', 'İçerik kaydetme işlemi sırasında bir hata meydana geldi.', {"progressBar": true});
                }
            }
        });

    });
</script>

