<div class="nav flex-column nav-pills meta" id="v-pills-tab-2" role="tablist" aria-orientation="vertical">
    <?php
    $sorgu = DB::table('pages')->where('lang', 'tr')->where('url', 'not like', 'panel%')->where('seo_page', '1')->get();
    ?>
    @foreach($sorgu as $u)
        <a class="nav-link @if($loop->iteration == 1) active @endif" data-get="{{$u->id}}" data-toggle="pill"
           href="#v-pills"
           role="tab" aria-controls="v-pills-meta"
           aria-selected="@if($loop->iteration == 1)true@else false @endif">{{$u->title}}</a>
    @endforeach
</div>
<script>
        $('.meta').on('shown.bs.tab', function (event) {
                seoPanel2($(event.target));
        })
        
        seoPanel2($("#v-pills-tab-2").find(".active"));
</script>
