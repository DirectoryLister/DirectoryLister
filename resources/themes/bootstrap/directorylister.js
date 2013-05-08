$(document).ready(function() {

    var originalTop = $('.breadcrumb-wrapper').offset().top;

    checkSubnav(originalTop);

    $(window).scroll(function() {
        checkSubnav(originalTop);
    });

    // Scroll page link on click action
    $('#pageTopLink').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 'fast');

        return false;
    });

    // Hash button on click action
    $('.hashButton').click(function(event) {

        // Get the file name and path
        var name = $(this).closest('a').attr('data-name');
        var path = $(this).closest('a').attr('href');

        $.ajax({
            url:     '/?hash=' + path,
            type:    'get',
            success: function(data) {

                // Parse the JSON data
                var obj = jQuery.parseJSON(data);

                console.log(obj);

                // Set modal title value
                $('#hashModal .modal-header h3').text(name);

                // Set modal pop-up hash values
                $('#hashTable .md5 .hash').text(obj.md5);
                $('#hashTable .sha1 .hash').text(obj.sha1);
                $('#hashTable .sha256 .hash').text(obj.sha256);

            }
        });

        // Show the modal
        $('#hashModal').modal('show');

        // Prevent default link action
        event.preventDefault();

    });

});

function checkSubnav(elTop) {
    if($(window).scrollTop() >= elTop) {
        $('body').addClass('breadcrumb-fixed');
        $('#pageTopLink').show();
    } else {
        $('body').removeClass('breadcrumb-fixed');
        $('#pageTopLink').hide();
    }
}