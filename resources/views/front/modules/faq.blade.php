<div class="row title-area" data-lang="{{getLang()}}">
    <div class="col-sm-12 col-md-12 title s-title">
        <h1 class="heading-primary style-2"><span>Sıkça Sorulan Sorular</span></h1>
    </div>

</div>
<div class="row">
    <div class="accordion accordion-flush" id="sss">
        <?php
        $faqCategory = getCacheHomeFaqCategory();
        ?>
        @foreach($faqCategory as $a)
            <?php
            $faqByCategory = getCacheHomeFaqCategoryById($a->id);
            ?>
            @if($faqByCategory)
                @foreach($faqByCategory as $u)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading{{$u->id}}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapse{{$u->id}}" aria-expanded="false"
                                    aria-controls="flush-collapse{{$u->id}}">
                                <span class="sss_cat">{{findFaqCategory($u->category)}}</span> {{$u->title}}
                            </button>
                        </h2>
                        <div id="flush-collapse{{$u->id}}" class="accordion-collapse collapse"
                             aria-labelledby="flush-heading{{$u->id}}" data-bs-parent="#sss">
                            <div class="accordion-body">{{$u->text}}</div>
                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach
    </div>


</div>
<div class="row justify-content-md-center mt-5">
    <div class="col-12 text-center">
        <button class="btn-inline page-button"
                onclick="location.href='{{route('sssSayfasi')}}'">Sıkça Sorulan Sorular'a Geç
        </button>
    </div>
</div>