var init = function() {
    var thumb = document.getElementById('thumb');
    var zoomThumb = function() {
        thumb.toggleClassName('zoomed');
    };

    if(thumb) {
        thumb.addEventListener( 'click', zoomThumb);
    }
};

window.addEventListener('DOMContentLoaded', init, false);