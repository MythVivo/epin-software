$(function() {

    let selectfirst = $(".custom-bank-select").find(".bank-card:first-child")
    let selecting = $(".selecting-bank .selecting-container").html(selectfirst.html())
    let selecting2 = $(".selecting-bank .selecting-container")



    $.each(selectfirst[0].dataset, function(key, value) {
        let valRex = value.replace(/[\[|\]|\']/g, '');
        let splits = valRex.split(',')
        selecting2.append("<div class='bank-info'><span>" + splits[0] + "</span>:<input type='text' readonly value='" + splits[1] + "'><div class='clipboard'><i></i><i></i></div></div>")
    });

    $(".bank-card").on("click", function(event) {

        selecting2.html(event.currentTarget.innerHTML)
        $.each(event.currentTarget.dataset, function(key, value) {

            let valRex = value.replace(/[\[|\]|\']/g, '');
            let splits = valRex.split(',')
            selecting2.append("<div class='bank-info'><span>" + splits[0] + "</span>:<input type='text' readonly value='" + splits[1] + "'><div class='clipboard'><i></i><i></i></div></div>")
        });

    });

    $(".selecting-container p i").on("click", function(event) {




    });

    function clip(a) {
        setTimeout(function() { $(a).removeClass("copy") }, 1000);
    }

    $("body").delegate(".selecting-container .clipboard", "click", function(e) {

        var copyText = this.parentElement.children[1]


        if ($(this).hasClass("copy")) {
            return false
        } else {
            $(this).addClass("copy")
            copyText.select();
            document.execCommand("copy");
            clip(this)

        }


    });

    $(".list-select-button").click(function() {
        $(".bank-list").slideToggle("fast");

        if ($(".bank-list").hasClass("open")) {
            $(".bank-list").removeClass("open")
        } else {
            $(".bank-list").addClass("open")
        }
        $(this).toggleClass("open");
    });

});
