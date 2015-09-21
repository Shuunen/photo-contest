/* global afterModeration */

function initModeration() {
    $('.moderation-controls button').mousedown(function (event) {
        // event.stopPropagation();
        var action = $(this).data('action');
        var photoId = $(this).parents('.fullscreen-photo .item').find('img').data('photoid');
        console.log('moderate : ' + action + ' & photoId : ' + photoId);
        $.ajax({
            type: 'get',
            data: 'type=moderation&photoId=' + photoId + '&action=' + action + '&ajax=true',
            success: afterModeration
        });
    });
}
