$(function() {
    // Open/Close sidemenu
    $('.navbar-toggle').on('click', function(e) {
        // Prevent default yolo
        e.preventDefault();

        // Toggle class on wrapper
        $('#wrapper').toggleClass('open');
    });

    // For pretty code
    $('pre').addClass('prettyprint');
});