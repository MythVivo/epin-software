$(function() {

    let selectfirst = $(".custom-bank-select").find(".bank-card:first-child").html()
    let selecting = $(".selecting-bank").html(selectfirst)
    let selecting2 = $(".selecting-bank")




    $(".bank-card").on("click", function(event) {

        console.log(event.currentTarget.dataset)
        selecting2.html(event.currentTarget.innerHTML)
        $.each(event.currentTarget.dataset, function(key, value) {
            selecting2.append("<span>" + value + "</span>")
        });

    });

    $(".selecting-bank span:after").on("click", function(event) {


        console.log(event)

    });

});