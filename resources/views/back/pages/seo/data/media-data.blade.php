<?php
$datas = DB::table($id);
if ($id != "games_titles_items_photos" and $id != "games_packages") {
    $datas = $datas->orderBy('created_at', 'desc')->whereNull('deleted_at')->get();
    } elseif($id == 'games_packages') {
    $datas = $datas->orderBy('created_at', 'desc')->whereNull('deleted_at')->paginate(16);
} else {
    $datas = $datas->orderBy('created_at', 'desc')->paginate(16);
}
$envName = mb_strtoupper($id);
if ($id == "muve_games") {
    $envName = "GAMES";
}
if ($id == "news") {
    $image = true;
} else {
    $image = false;
}
?>
<div class="card">
    <div class="card-body">
        <div class="row">

            @foreach($datas as $data)
                @if($id == "muve_games")
                    <?php
                    if ($data->steamId > 0) {
                        $imageSrc = $data->image;
                    } else {
                        $imageSrc = asset(env('ROOT') . env('FRONT') . env($envName) . $data->image);
                    }
                    ?>
                @else
                    <?php
                    if ($id != "games_titles_items_photos") {
                        $imageSrc = asset(env('ROOT') . env('FRONT') . env($envName) . $data->image);
                    } else {
                        $imageSrc = asset(env('ROOT') . env('FRONT') . '/games_items/' . $data->image);
                    }

                    ?>
                @endif
                <div class="col-md-3">
                    <div class="card">
                        <img style="cursor: pointer;" data-toggle="collapse" href="#altArea{{$data->id}}" role="button"
                             aria-expanded="false" aria-controls="collapseExample"
                             class="btn-block @if($image) resimDuzelt @endif"
                             src="{{$imageSrc}}">
                        <div class="collapse" id="altArea{{$data->id}}">
                            <div class="card card-body">
                                <form class="mediaForm" method="post" action="{{route('seo_yonetim_media_save')}}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="alt{{$data->id}}">Alt Açıklama</label>
                                                <input id="alt{{$data->id}}" type="text" name="alt"
                                                       value="{{$data->alt}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="hidden" name="table" value="{{$id}}">
                                            <input type="hidden" name="id" value="{{$data->id}}">
                                            <button type="submit" class="btn btn-outline-success btn-block">Kaydet
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @if($loop->iteration%4 == 0)
                    <div class="col-12">
                        <hr>
                    </div>
                @endif
            @endforeach
            @if($id == 'games_titles_items_photos' or $id == 'games_packages')
                    <nav aria-label="Page navigation example" class="nav">
                        <ul class="pagination justify-content-center">
                            <li class="page-item">
                                <a class="page-link" data-page="page=1">{{"|<<"}}</a>
                            </li>
                            <li class="page-item">
                                <?php
                                $prevPage = $datas->currentPage() - 1;
                                if ($prevPage < '1') {
                                    $prevPage = '1';
                                }
                                ?>
                                <a class="page-link"
                                   data-page="page={{$prevPage}}">{{"<"}}</a>
                            </li>
                            @if($datas->lastPage()+1 > 7)
                                @if($datas->currentPage() < 3)
                                    @for($i = 1; $i < 4; $i++)
                                        <li class="page-item @if($datas->currentPage() == $i) active @endif">
                                            <a class="page-link" data-page="page={{$i}}">{{$i}}</a>
                                        </li>
                                    @endfor
                                    <li class="page-item">
                                        <a class="page-link">...</a>
                                    </li>
                                    @for($i = $datas->lastPage()-2; $i < $datas->lastPage()+1; $i++)
                                        <li class="page-item @if($datas->currentPage() == $i) active @endif">
                                            <a class="page-link" data-page="page={{$i}}">{{$i}}</a>
                                        </li>
                                    @endfor
                                @endif
                                @if($datas->currentPage() >= 3 AND $datas->currentPage() < $datas->lastPage()+1 - 2)
                                    <li class="page-item @if($datas->currentPage() == 1) active @endif">
                                        <a class="page-link" data-page="page=1">1</a>
                                    </li>
                                    @if($datas->currentPage()-1 != 2)
                                        <li class="page-item">
                                            <a class="page-link">...</a>
                                        </li>
                                    @endif
                                    @for($i = $datas->currentPage()-1; $i <= $datas->currentPage()+1; $i++)
                                        <li class="page-item @if($datas->currentPage() == $i) active @endif">
                                            <a class="page-link" data-page="page={{$i}}">{{$i}}</a>
                                        </li>
                                    @endfor
                                    @if($datas->currentPage()+1 != $datas->lastPage()-1)
                                        <li class="page-item">
                                            <a class="page-link">...</a>
                                        </li>
                                    @endif
                                    <li class="page-item @if($datas->currentPage() == $datas->lastPage()) active @endif">
                                        <a class="page-link" data-page="page={{$datas->lastPage()}}">{{$datas->lastPage()}}</a>
                                    </li>
                                @endif
                                @if($datas->currentPage() >= $datas->lastPage()+1 - 2)
                                    @for($i = 1; $i < 3; $i++)
                                        <li class="page-item @if($datas->currentPage() == $i) active @endif">
                                            <a class="page-link" data-page="page={{$i}}">{{$i}}</a>
                                        </li>
                                    @endfor
                                    <li class="page-item">
                                        <a class="page-link">...</a>
                                    </li>
                                    @for($i = $datas->lastPage()-2; $i < $datas->lastPage()+1; $i++)
                                        <li class="page-item @if($datas->currentPage() == $i) active @endif">
                                            <a class="page-link" data-page="page={{$i}}">{{$i}}</a>
                                        </li>
                                    @endfor
                                @endif
                            @else
                                @for($i = 1; $i < $datas->lastPage()+1; $i++)
                                    <li class="page-item @if($datas->currentPage() == $i) active @endif">
                                        <a class="page-link" data-page="page={{$i}}">{{$i}}</a>
                                    </li>
                                @endfor
                            @endif
                            <li class="page-item">
                                <?php
                                $nextPage = $datas->currentPage() + 1;
                                if ($nextPage > $datas->lastPage()) {
                                    $nextPage = $datas->lastPage();
                                }
                                ?>
                                <a class="page-link" data-page="page={{$nextPage}}">{{">"}}</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" data-page="page={{$datas->lastPage()}}">{{">>|"}}</a>
                            </li>
                        </ul>
                    </nav>

                @endif
        </div>
    </div>
</div>
<script>
    $( ".page-link" ).click(function() {
        area1();
        let page = $(this).data('page');
        seoPanel3($("#v-pills-tab-3").find(".active"), page);
    });

    $(".mediaForm").submit(function (e) {
        e.preventDefault();
        let collapse = $(this).closest(".collapse");
        var form = $(this);
        var actionUrl = form.attr('action');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(),
            success: function (data) {
                if (!data) {
                    data = 1;
                }
                if (data) {
                    toastr.success('Başarılı', 'İçerik başarıyla kaydedildi.', {"progressBar": true});
                    $(collapse).collapse("toggle");
                } else {
                    toastr.error('Başarısız', 'İçerik kaydetme işlemi sırasında bir hata meydana geldi.', {"progressBar": true});
                }
            }
        });

    });
</script>
