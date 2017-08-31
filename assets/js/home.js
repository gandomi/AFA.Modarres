$(function(){

    $(document).on( 'scroll', function(){
        if ($(window).scrollTop() > 1000) {
            $('.scroll-top').fadeIn('slow');
        } else {
            $('.scroll-top').fadeOut('slow');
        }
    });

    $(".downloadCntr").click(function(){
        var badge = $(this).siblings('span.badge');
        badge.text(+badge.text() + 1);
    });

});