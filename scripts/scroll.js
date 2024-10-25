$(document).ready(function() {
    var $searchFormContainer = $('.search-form-container');
    var offset = $searchFormContainer.offset();
    var originalWidth = $searchFormContainer.outerWidth();
    var originalLeft = $searchFormContainer.offset().left;

    $(window).scroll(function() {
        if ($(window).scrollTop() > offset.top) {
            $searchFormContainer.addClass('sticky').css({
                'width': originalWidth,
                'left': originalLeft
            });
        } else {
            $searchFormContainer.removeClass('sticky').css({
                'width': '',
                'left': ''
            });
        }
    });
});
