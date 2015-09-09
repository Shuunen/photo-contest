$(document).ready(function () {


    var slider = $('#moderatePhotosModal .slick-slider');

    $('.moderation-controls button').mousedown(function (event) {

        // avoid swapping by mistake
        event.stopPropagation();

        var action = $(this).data('action');
        var photoId = $('.slick-slide.slick-current.slick-active .moderation-photo').attr('id');
        console.log('moderate : ' + action + ' photoId : ' + photoId);

        if (action === 'delete') {

            $.ajax({
                type: 'get',
                data: 'type=removePhoto&photoId=' + photoId + '&ajax=true',
                success: function (data) {
                    // window.location.reload();
                }
            });

        } else {

            $.ajax({
                type: 'get',
                data: 'type=moderation&photoId=' + photoId + '&action=' + action + '&ajax=true',
                success: function (jsonData) {
                    var ret = JSON.parse(jsonData);
                    if (ret.messageStatus === 'success') {
                        if (slider.slick('getSlick').slideCount > 1) {
                            slider.slick('slickRemove', slider.slick('slickCurrentSlide'));
                            // console.info('moderation was saved, remove this slide');
                        } else {
                            // console.info('moderation was saved, was the last slide, reload page');
                            window.location.reload();
                        }
                    } else {
                        console.error('moderation fucked up');
                    }
                    // window.location.reload();
                }
            });

        }

    });
});
