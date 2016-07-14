jQuery(document).ready(function( $ ) {
    $("a.event_entry").click(function (event) {
        event.preventDefault();
        $(this).parent().children(".event_dropdown").slideToggle();
        if($(this).children(".content").children(".event_drop_down_icon").html().indexOf("+") >= 0)
            $(this).children(".content").children(".event_drop_down_icon").html("-");
        else
            $(this).children(".content").children(".event_drop_down_icon").html("+");
    });
});