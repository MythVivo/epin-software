<div class="nav flex-column nav-pills oyunlar" id="v-pills-tab-4" role="tablist" aria-orientation="vertical">
    <?php
    $sorgu = DB::table('games')->where('lang', 'tr')->whereNull('deleted_at')->get();
    ?>
    @foreach($sorgu as $u)
        <a class="nav-link @if($loop->iteration == 1) active @endif" data-get="{{$u->id}}" data-toggle="pill"
           href="#v-pills"
           role="tab" aria-controls="v-pills-meta"
           aria-selected="@if($loop->iteration == 1)true@else false @endif">{{$u->title}}</a>
    @endforeach
</div>
<script>
    $('.oyunlar').on('shown.bs.tab', function (event) {
        seoPanel4($(event.target));
    })
    seoPanel4($("#v-pills-tab-4").find(".active"));
</script>
