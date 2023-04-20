<?# @extends('front.layouts.app')


//dd($epin);
?>

    <section class="comment bg-gray pb-40">
        <div class="container">
            <div class="row">
            <?
            if(isset($epin)) { $sor=\App\Models\Comments::whereNull('deleted_at')->where('lang', getLang())->where('status', '1')->where('oyun',$epin->id)->orderBy('created_at', 'desc')->paginate(5); } ?>
@if(isset($sor) && $sor->total()>0)
            <div class="bg-transparent border-0 form-control ps-sm-3 text-center text-uppercase"><h6> {{$epin->alt}} ALAN KULLANICILARIN YORUMLARI ({{$sor->total()}}) </h6></div>
                <div class="col-12">
                    <div class="comment-frame-container">
                        <div class="comment-center-frame">
                            <div class="row row-cols-lg-1 flex-sm-column">
                                @foreach($sor as $u)
                                    <div data-rating="5.0" class="col-lg-6 m-auto">
                                        <article>

                                            <h6>
                                                <span>
                                                    <?php $user = DB::table('users')->where('id', $u->user)->first();  ?>
                                                     {{mb_substr($user->name, 0, 1)}}***  {{mb_substr($user->name, strpos($user->name,' '), 2)}}*** {!! userSeeControl($user->id) !!}
                                                     <span class="p-3 small"><div class="fa fa-check-circle text-success"><i class="i-checkmark"></i></div><span class="p-1 small text-success">Ürünü satın aldı</span></span>
                                                </span><span data-rating="5">
                                            @for($i=0; $i<$u->rate; $i++)
                                                        <i  class="rating-stars fas fa-star fill"></i>
                                                    @endfor
                                                    @for($i=$u->rate; $i < 5; $i++)
                                                        <i  class="rating-stars fas fa-star"></i>
                                                    @endfor
                                        </span></h6>
                                            <p>{{$u->text}}</p>
                                            <span class="date">{{$u->created_at}}</span>
                                        </article>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                    </div>
                </div>
    @if(isset($sor) && $sor->total()>5)
        <div class="text-center">
        <div title="Önceki" class="prw btn btn-success">&lt;&lt;</div>
        <span class="about btn btn-outline-success font-monospace" style="cursor: default">{{$sor->currentPage()}} > <?=ceil($sor->total()/$sor->perPage()) ?></span>
        <div title="Sonraki" class="nxt btn btn-success">&gt;&gt;</div>
        </div>
    @endif
@endif


@section('js')
<script>
@if(isset($sor) && strlen($sor->previousPageUrl())>10)
$('.prw').click(function(){ location.href="{{$sor->previousPageUrl()}}"});
@endif

@if(isset($sor) && strlen($sor->nextPageUrl())>10)
$('.nxt').click(function(){ location.href="{{$sor->nextPageUrl()}}"});
@endif

var z=location.href.split('?');
if(z.length>1){
    if(z[1].indexOf("page=")==0){
    $('html, body').animate({ scrollTop: $('#yorums').offset().top }, 10);
    }
}
</script>
@endsection

            </div>

        <?
        if(isset(Auth::user()->id))   {
        if($table=='gold'){$id=Auth::user()->id;
        $sor=DB::select("SELECT ggs.paket,ggs.user, gpt.games_titles FROM game_gold_satis ggs , games_packages_trade gpt where ggs.paket=gpt.id and gpt.games_titles='$epin->id' and user='$id' and status=1 limit 1");
        }
        elseif($table=='epin') {
          $sor=DB::select("select game_title from epin_satis where isnull(deleted_at) and status=1 and user=? and game_title=?", [Auth::user()->id, $epin->id]);
        }
        }
        ?>
            <div class="row">
                @if(isset(Auth::user()->id))    <?  #@if(count($sor)<=0) @else {{route('yorum_yap')}} @endif "> ?>
                        <form method="post" action="{{route('yorum_yap')}} ">
                            @csrf
                            <div class="row">
                                <div class="comment-send-wrapper">
                                    <div class="comment-send-form">
                                        <div class="col-12">
                                            <h6> {{$epin->alt}} hakkında yorumunuzu belirtebilirsiniz.</h6>
                                            <textarea class="form-control" name="text" id="yorum" maxlength="255" required> <? #@if(count($sor)<=0) disabled @endif>@if(count($sor)<=0)Yorum kapalı @endif ?></textarea></div>
                                        <div class="vote-stars">
                                            <label>
                                                <input type="radio" name="rate" value="1" required>
                                                <input type="radio" name="rate" value="2" required>
                                                <input type="radio" name="rate" value="3" required>
                                                <input type="radio" name="rate" value="4" required>
                                                <input type="radio"name="rate"  value="5" required>
                                                <div class="stars">
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                                </div>
                                            </label>
                                        </div>

  <?/*
                                @if(count($sor)<=0)
                                   <div class="col-wallet"> Yorum yapmak için ürünü satın almış olmanız gerekmektedir.</div>
                                @else
*/?>
                                        <div class="col-12 mt-2"><button type="submit" class="btn-inline color-blue"> Yorumu Gönder </button> </div>
<? #                            @endif ?>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="oyun" value="{{$epin->id}}">
                        </form>

                @else
                    <div class="col-lg-6 m-auto">
                    <article class="Thumbswiper radius20 text-center">
                        <p><span class="w_ico"><i class="fal fa-exclamation-triangle"></i></span>
                            Yorum yapabilmek için lütfen giriş yapın.</p>
                        <button type="button" class="btn-inline color-blue small" onclick="location.href='{{route('giris')}}'">Giriş Yap</button>
                    </article>
                    </div>
                @endif

            </div>

        </div>
    </section>



