$(document).ready(function() {
    
    var originalTop = $('.breadcrumb-wrapper').offset().top;
    
    checkSubnav(originalTop);
    
    $(window).scroll(function() {
        checkSubnav(originalTop);
    });
    
});

function checkSubnav(elTop) {
    if($(window).scrollTop() >= elTop) {
        $('.breadcrumb-wrapper').addClass('breadcrumb-fixed');
    } else {
        $('.breadcrumb-wrapper').removeClass('breadcrumb-fixed');
    }
}
