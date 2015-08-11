jQuery(document).ready(function ($){
    $(".vrc-calendar .calendar-slides").owlCarousel({
        singleItem:true,
        navigation:false,
        pagination:false,
        autoHeight:true
    });
    $(".vrc-calendar .btn-prev").click(function(){
        var parent = $(this).parent().parent().parent().parent().parent().parent();
        $(".calendar-slides", parent).trigger('owl.prev');
    });
    $(".vrc-calendar .btn-next").click(function(){
        var parent = $(this).parent().parent().parent().parent().parent().parent();
        $(".calendar-slides", parent).trigger('owl.next');
    });

});