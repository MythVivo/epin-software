<div class="nav flex-column nav-pills mediaa" id="v-pills-tab-3" role="tablist" aria-orientation="vertical">
    <a class="nav-link active" data-get="slider" data-toggle="pill"
       href="#v-pills"
       role="tab" aria-controls="v-pills-media"
       aria-selected="true">Slider</a>
    <a class="nav-link" data-get="news" data-toggle="pill"
       href="#v-pills"
       role="tab" aria-controls="v-pills-media"
       aria-selected="false">Haber</a>
    <a class="nav-link" data-get="games" data-toggle="pill"
       href="#v-pills"
       role="tab" aria-controls="v-pills-media"
       aria-selected="false">Oyunlar</a>
    <a class="nav-link" data-get="games_titles" data-toggle="pill"
       href="#v-pills"
       role="tab" aria-controls="v-pills-media"
       aria-selected="false">Oyun Alt Başlıkları</a>
    <a class="nav-link" data-get="games_packages" data-toggle="pill"
       href="#v-pills"
       role="tab" aria-controls="v-pills-media"
       aria-selected="false">E-pin Paketleri</a>
    <a class="nav-link" data-get="games_packages_trade" data-toggle="pill"
       href="#v-pills"
       role="tab" aria-controls="v-pills-media"
       aria-selected="false">Gold Bar Paketleri</a>
    <a class="nav-link" data-get="games_titles_items_photos" data-toggle="pill"
       href="#v-pills"
       role="tab" aria-controls="v-pills-media"
       aria-selected="false">Hazır İtemler</a>
    <a class="nav-link" data-get="muve_games" data-toggle="pill"
       href="#v-pills"
       role="tab" aria-controls="v-pills-media"
       aria-selected="false">Muve Oyunları</a>
</div>

<script>
    $(".mediaa").on('shown.bs.tab', function (event) {
        seoPanel3($(event.target));
    })

    seoPanel3($("#v-pills-tab-3").find(".active"));
</script>
