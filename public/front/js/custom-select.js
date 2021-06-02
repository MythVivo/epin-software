$(function() {

    let selectfirst = $(".custom-bank-select").find(".bank-card:first-child")
    let selecting = $(".selecting-bank .selecting-container").html(selectfirst.html())
    let selecting2 = $(".selecting-bank .selecting-container")



    $.each(selectfirst[0].dataset, function(key, value) {
        let valRex = value.replace(/[\[|\]|\']/g, '');
        let splits = valRex.split(',')
        selecting2.append("<p>" + splits[0] + ":<input type='text' readonly value='" + splits[1] + "'><i></i></p>")
    });

    $(".bank-card").on("click", function(event) {

        selecting2.html(event.currentTarget.innerHTML)
        $.each(event.currentTarget.dataset, function(key, value) {

            let valRex = value.replace(/[\[|\]|\']/g, '');
            let splits = valRex.split(',')
            selecting2.append("<p>" + splits[0] + ":<input type='text' readonly value='" + splits[1] + "'><i></i></p>")
        });

    });

    $(".selecting-container p i").on("click", function(event) {




    });

    $("body").delegate(".selecting-container p i", "click", function(e) {

        var copyText = e.target.parentElement.children[0]

        copyText.select();

        document.execCommand("copy");
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