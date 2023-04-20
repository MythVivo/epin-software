<div class="nav flex-column nav-pills oyun_alt" id="v-pills-tab-5" role="tablist" aria-orientation="vertical">
    <?php
    $anaOyunlar = DB::table('games')->whereNull('deleted_at')->where('lang', 'tr')->get();
    ?>
    @foreach($anaOyunlar as $u)
        @if($loop->iteration == 1)
            <?php $active = 1; ?>
            @else
                <?php $active = 0; ?>
            @endif
        <h5>{{$u->title}}</h5>
        <?php
        $sorgu = DB::table('games_titles')->where('game', $u->id)->where('lang', 'tr')->orderBy('game', 'asc')->whereNull('deleted_at')->get();
        ?>
        @foreach($sorgu as $uu)
            <a class="nav-link @if($loop->iteration == 1 and $active == 1) active @endif" data-get="{{$uu->id}}" data-toggle="pill"
               href="#v-pills"
               role="tab" aria-controls="v-pills-meta"
               aria-selected="@if($loop->iteration == 1 and $active == 1)true@else false @endif">{{$uu->title}}</a>
        @endforeach
        <hr>
    @endforeach
</div>
<script>
    $('.oyun_alt').on('shown.bs.tab', function (event) {
        seoPanel5($(event.target));
    })
    seoPanel5($("#v-pills-tab-5").find(".active"));
</script>
