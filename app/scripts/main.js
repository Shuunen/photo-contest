/* global qq */

$(document).ready(function () {

    $('.gallery img').click(function () {

        console.log('clicked on thumb, showing fullscreen image');
        
        /*
         $(this).parent().find('.fullscreen-image').remove();

         var container = document.createElement('div');
         container.classList.add('fullscreen-image', 'mini');

         var image = document.createElement('img');
         image.setAttribute('src', './images/loader.gif');
         image.setAttribute('data-layzr', $(this).data('full'));
         container.appendChild(image);

         this.insertAdjacentElement('afterend', container);
         */

        $('.fullPhoto').html('<div class="item"><img src="./images/loader.gif"></div>');

        $.ajax({
            type: 'get',
            data: 'type=template&template=fullPhoto&photoId=' + $(this).data('photoid'),
            success: function (data) {
                //console.log(data);
                $('.fullPhoto').html(data);
                initFullPhoto();
                initRating();
                initModeration();
                $('.fullPhoto .item img').click(function () {
                    $('.fullPhoto').html('');
                });
            }
        });
        /*
         var layzr = new Layzr({
         container: '.fullscreen-image',
         callback: function () {
         console.log('fullscreen image loaded');
         container.classList.remove('mini');
         setTimeout(function () {
         smoothScroll(container);
         }, 300);
         }
         });


         $(container).click(function () {
         var container = this;
         $(container).addClass('closed');
         setTimeout(function () {
         $(container).remove();
         }, 300);
         });
         */
    });


    $('.grid-filter').click(function () {
        $('.grid-filter').removeClass('active');
        $(this).addClass('active');
        var filter = $(this).data('filter');
        $('.grid').isotope({
            filter: filter
        });
    });

    /* Fix for first time opening slider in modal : if the modal is hidden, there is no room to calculate slider width */
    $('[data-toggle="modal"]').click(function () {
        var slide = $('.gallery-slider .slick-current');
        if (slide.width() === 0) {
            var width = (document.body.getBoundingClientRect().width - 100) + 'px';
            slide.width(width);
            // console.log('applying fix on slide', slide, 'width width', width);
        }
    });

    $('form.login').submit(function (event) {
        event.preventDefault();
        var data = $(this).serialize();
        data += '&ajax=true';
        $.ajax({
            type: 'get',
            data: data,
            success: function () {
                window.location.reload();
            }
        });
    });

    $('form.add-user-form').submit(function (event) {
        event.preventDefault();
        var data = $(this).serialize();
        data += '&ajax=true';
        $.ajax({
            type: 'get',
            data: data,
            success: function (data) {
                $('form.add-user-form .message').removeClass('alert-danger', 'alert-success');
                data = JSON.parse(data);
                console.log(data);
                console.log(data.message);
                console.log(data.messageStatus);
                $('form.add-user-form .message').text(data.message).addClass('alert').addClass(data.messageStatus === 'success' ? 'alert-success' : 'alert-danger');
            }
        });
    });

    $('.countdown.submitOpened').countdown(voteOpenDate)
        .on('update.countdown', function (event) {
            var totalHours = event.offset.totalDays * 24 + event.offset.hours;
            var totalSeconds = totalHours * 3600 + event.offset.seconds;
            var format = '%-D day%!D or ' + totalSeconds + ' seconds if you\'re a robot.';
            $(this).html(event.strftime(format));
        }).on('finish.countdown', function () {
        window.location.reload();
    });

    $('#logoutLink').click(function () {
        $.ajax({
            type: 'get',
            data: 'type=logout&ajax=true',
            success: function () {
                window.location.reload();
            }
        });
    });

    $('.reloadButton').click(function () {
        window.location.reload();
    });

    var nbPhotos = $('.gallery [data-layzr]').size();
    var i = 0;
    var layzr = new Layzr({
        container: '.gallery',
        selector: '[data-layzr]',
        hiddenAttr: 'data-layzr-hidden',
        callback: function () {
            if (++i === nbPhotos) {
                console.log('all images loaded');
                setTimeout(function () {
                    $('.gallery').isotope({
                        // layoutMode: 'fitRows',
                        // layoutMode: 'vertical',
                        layoutMode: 'masonry',
                        itemSelector: '.grid-item',
                        // percentPosition: true,
                        masonry: {
                            gutter: 5,
                            columnWidth: 250,
                            isFitWidth: true
                        }
                    });
                    var categoryHash = window.location.hash;
                    if (categoryHash !== "") {
                        var a = $('.grid-filter[href="' + categoryHash + '"]');
                        a.addClass('active');
                        $('.grid').isotope({
                            filter: a.data('filter')
                        });
                    }

                }, 100);
            }
        }
    });

});

function initFullPhoto() {

    $('.countdown.voteOpened').countdown(voteOpenDate)
        .on('update.countdown', function (event) {
            var format = '';
            if (event.offset.weeks > 0) {
                format += '%-w week%!w ';
            }
            if (event.offset.days > 0) {
                format += '%-d day%!d ';
            }
            if (event.offset.hours > 0) {
                format += '%-H hour%!H ';
            }
            if (event.offset.minutes > 0) {
                format += '%-M minute%!M ';
            }
            if (event.offset.seconds > 0) {
                format += '... and %-S second%!S !';
            }
            $(this).html(event.strftime(format));
        }).on('finish.countdown', function () {
        window.location.reload();
    });

    $('.delete-photo').click(function () {
        var photoId = $(this).parent().find('img').data('photoid');
        if (!photoId) {
            console.error('cannot delete photo without photoId');
            return false;
        }
        $.ajax({
            type: 'get',
            data: 'type=removePhoto&photoId=' + photoId + '&ajax=true',
            success: afterModeration
        });
    });

}

function afterModeration(jsonData) {
    var ret = JSON.parse(jsonData);
    console.log('afterModeration return from B/E', ret);
    var photoid = ret.data.photoid;
    var photostatus = ret.data.photostatus;
    var item = $('img[data-photoid="' + photoid + '"]').parent('.grid-item');
    if (photostatus === 'deleted') {
        item.remove();
    } else {
        item.attr('data-photostatus', photostatus);
    }
    $('.grid-filter.active').click();
    $('.fullPhoto').empty();
    if (typeof ret.data.nbPhotosToModerate !== 'undefined') {
        if (ret.data.nbPhotosToModerate === 0) {
            $('.nbPhotosToModerate').remove();
            if (window.location.hash === '#submitted') {
                $('.grid-filter[data-filter="*"]').click();
            }
        } else {
            $('.nbPhotosToModerate').text(ret.data.nbPhotosToModerate);
        }
    }

    if (ret.message && ret.messageStatus) {
        $.smkAlert({
            text: ret.message, type: ret.messageStatus, time: 5
        });
    }
}
