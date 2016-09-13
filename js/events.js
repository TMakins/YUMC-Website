jQuery(document).ready(function( $ ) {
    var h = window.location.hash;
    if(h) {
        $(h).parent().children(".event_dropdown").slideToggle();
        if($(h).children(".content").children(".event_drop_down_icon").html().indexOf("+") >= 0)
            $(h).children(".content").children(".event_drop_down_icon").html("-");
        else
            $(h).children(".content").children(".event_drop_down_icon").html("+");
    }

    $("a.event_entry").click(function (event) {
        event.preventDefault();
        $(this).parent().children(".event_dropdown").slideToggle();
        if($(this).children(".content").children(".event_drop_down_icon").html().indexOf("+") >= 0)
            $(this).children(".content").children(".event_drop_down_icon").html("-");
        else
            $(this).children(".content").children(".event_drop_down_icon").html("+");
    });
});