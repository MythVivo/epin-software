<div class="row">
    <div class="col-lg-9 col-md-12">
        <style>
            /* @media screen and (max-width: 800px) {
                #oyuneks-slider {
                    display: block !important;
                }
            }  */
        </style>
        <?php
        $slider_get = getCacheHomeSlider();
        ?>
        <style>
            @media only screen and (hover: none) and (pointer: coarse) {
                #oyuneks-slider {
                    display: block;
                    height: calc(100vw - 24px)
                }
            }
        </style>
        <!-- <img src="{{ cdn(env('ROOT') . env('FRONT') . env('SLIDER') . $slider_get[0]->image_mobile) }}" alt="$slider_get[0]->alt"> -->
        <div id="oyuneks-slider" class="owl-carousel owl-theme">

            <!-- <link rel="preload" href="{{ cdn(env('ROOT') . env('FRONT') . env('SLIDER') . $slider_get[0]->image_mobile) }}" as="image">
            <link rel="preload" href="{{ cdn(env('ROOT') . env('FRONT') . env('SLIDER') . $slider_get[0]->image) }}" as="image"> -->
            @foreach ($slider_get as $k => $u)
                <div class="item">
                    <div class="slider-container">
                        <?php
                        if ($u->link == '') {
                            $link = env('APP_URL');
                        } else {
                            $link = $u->link;
                        }
                        ?>
                        <a rel="nofollow noreferrer noopener" href="{{ $link }}">
                            <?php
                            $mobileImg = cdn(env('ROOT') . env('FRONT') . env('SLIDER') . $u->image_mobile . '?w=400');
                            $desktopImg = cdn(env('ROOT') . env('FRONT') . env('SLIDER') . $u->image);
                            ?>

                            <!-- <img srcset="{{ $mobileImg }} 800w,{{ $desktopImg }} 801w" class="d-block w-100" imagesizes="100vw" alt="{{ $u->alt }}"> -->
                            <!-- <img data-src-desktop="{{ $desktopImg }}" data-src-mobile="{{ $mobileImg }}" class="d-block w-100" alt="{{ $u->alt }}"> -->

                            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                data-src-desktop="{{ $desktopImg }}" data-src-mobile="{{ $mobileImg }}"
                                class=" w-100" alt="{{ $u->alt }}">

                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <?php
    $mini1 = getCacheHomeSliderMini1();
    $mini2 = getCacheHomeSliderMini2();
    ?>
    <div class="col-lg-3 col-md-12 slider-right ps-lg-1 mt-4 mt-lg-0">
        <div class="slider-right-container row">
            <div class="col-6 col-md-6 col-lg-12 pb-0 pb-lg-2 sec-row">
                <article>
                    <a rel="nofollow noreferrer noopener" href="{{ $mini1->link }}">
                        <img data-src-desktop="{{ cdn(env('ROOT') . env('FRONT') . env('SLIDER') . $mini1->image) }}"
                            data-src-mobile="{{ cdn(env('ROOT') . env('FRONT') . env('SLIDER') . $mini1->image_mobile) }}"
                            src="{{ cdn(env('ROOT') . env('FRONT') . env('SLIDER') . $mini1->image) }}"
                            alt="{{ $mini1->title }}">
                    </a>
                </article>
            </div>
            <div class="col-6 col-md-6 col-lg-12 pt-0 pt-lg-2 sec-row">
                <article>
                    <a rel="nofollow noreferrer noopener" href="{{ $mini2->link }}">
                        <img data-src-desktop="{{ cdn(env('ROOT') . env('FRONT') . env('SLIDER') . $mini2->image) }}"
                            data-src-mobile="{{ cdn(env('ROOT') . env('FRONT') . env('SLIDER') . $mini2->image_mobile) }}"
                            src="{{ cdn(env('ROOT') . env('FRONT') . env('SLIDER') . $mini2->image) }}"
                            alt="{{ $mini2->title }}">
                    </a>
                </article>
            </div>
        </div>
    </div>
</div>
