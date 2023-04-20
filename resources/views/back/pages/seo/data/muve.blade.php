<div class="nav flex-column nav-pills muve" id="v-pills-tab-6" role="tablist" aria-orientation="vertical">
    <?php
    $sorgu = DB::table('muve_games')->whereNull('deleted_at')->get();
    ?>
    @foreach($sorgu as $u)
        <a class="nav-link @if($loop->iteration == 1) active @endif" data-get="{{$u->id}}" data-toggle="pill"
           href="#v-pills"
           role="tab" aria-controls="v-pills-meta"
           aria-selected="@if($loop->iteration == 1)true@else false @endif">{{$u->title}}</a>
    @endforeach
</div>
<script>
    $('.muve').on('shown.bs.tab', function (event) {
        seoPanel6($(event.target));
    })
    seoPanel6($("#v-pills-tab-6").find(".active"));
</script>
