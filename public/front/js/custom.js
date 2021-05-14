$(function () {
    var lastScrollTop = 0;
    var headerHeight = $(".site-header-area");

    if($(window).scrollTop() > headerHeight.height()) {
        headerHeight.addClass('sticky-header');
    }
    $(window).scroll(function (event) {
        var st = $(this).scrollTop();
        if (st > lastScrollTop) { // downscroll code
            if (headerHeight.hasClass('header-show')) {
                headerHeight.removeClass('header-show');
            }
            if(st >= headerHeight.height()) {
                headerHeight.addClass('sticky-header');
            }

        } else { // upscroll code
            if (st > headerHeight.height() && !headerHeight.hasClass('header-show')) {
                headerHeight.addClass('header-show');
            } else if(st <= 0) {
                if(headerHeight.hasClass('header-show')) {
                    headerHeight.removeClass('header-show');
                }
                if(headerHeight.hasClass('sticky-header')) {
                    headerHeight.removeClass('sticky-header');
                }
            }
        }
        lastScrollTop = st;
    });


});
