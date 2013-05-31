$(document).ready(function() {

    // Get breadcrumbs original position
    var originalTop = $('.breadcrumb').offset().top;

    // Run pinBreadcrumbs() on page load
    pinBreadcrumbs(originalTop);

    // Run pinBreadcrumbs() on scroll
    $(window).scroll(function() {
        pinBreadcrumbs(originalTop);
    });

    // Scroll page on click action
    $('#page-top').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 'fast');
        return false;
    });

    // Hash button on click action
    $('.file-info-button').click(function(event) {

        // Get the file name and path
        var name = $(this).closest('li').attr('data-name');
        var path = $(this).closest('li').attr('data-href');

        $.ajax({
            url:     '/?hash=' + path,
            type:    'get',
            success: function(data) {

                // Parse the JSON data
                var obj = jQuery.parseJSON(data);

                console.log(obj);

                // Set modal title value
                $('#file-info-modal .modal-header h3').text(name);

                // Set modal pop-up hash values
                $('#file-info .md5-hash').text(obj.md5);
                $('#file-info .sha1-hash').text(obj.sha1);
                $('#file-info .sha256-hash').text(obj.sha256);

            }
        });

        // Show the modal
        $('#file-info-modal').modal('show');

        // Prevent default link action
        event.preventDefault();

    });

});

function pinBreadcrumbs(elTop) {
    if($(window).scrollTop() >= elTop) {
        $('body').addClass('breadcrumb-fixed');
        $('#page-top').show();
    } else {
        $('body').removeClass('breadcrumb-fixed');
        $('#page-top').hide();
    }
}