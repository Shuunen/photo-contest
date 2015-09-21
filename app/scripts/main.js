/* global qq, voteOpenDate */

$(document).ready(function () {

    handleLoginForm();

    initImageGrid();

    handleEvents();

});

/*
 * All events are handled and dispatched here
 */
function handleEvents() {

    $('body').on('click', '[event-emitter]', function (event) {

        var el = event.target;

        if (el.getAttribute('event-emitter') === null) {
            return; // TODO : why does jQuery doesn't filter already ?
        }

        if (el.className !== event.currentTarget.className) {
            return;
        }

        // console.log('clicked on', el);

        if (el.classList.contains('grid-filter')) {
            clickedOnGridFilter(el);
        } else if (el.classList.contains('grid-item-thumb')) {
            clickedOnGridItemThumb(el);
        } else if (el.classList.contains('delete-photo')) {
            clickedOnDeletePhoto(el);
        } else if (el.classList.contains('close-fullscreen-photo')) {
            clickedOnCloseFullscreenPhoto();
        } else if (el.classList.contains('moderation-control')) {
            clickedOnModerationControl(el);
        } else if (el.classList.contains('logout-link')) {
            clickedOnLogoutLink();
        } else if (el.getAttribute('data-target') === '#addUserModal') {
            clickedOnAddUserModal(el);
        } else if (el.getAttribute('data-target') === '#uploadModal') {
            clickedOnUploadModal(el);
        } else if (el.classList.contains('slide-control')) {
            clickedOnSlideControl(el);
        }

    });

    $(window).on('keydown', function (event) {

        // check if fullscreen
        if (!$('.fullscreen-photo > .item').size()) {
            return;
        }

        // console.log(event);

        // escape
        if (event.keyCode === 27) {
            clickedOnCloseFullscreenPhoto();
        }
        // right & left on fullscreen
        else if (event.keyCode === 39 || event.keyCode === 37) {
            var next = (event.keyCode === 39);
            nextPrevFullscreenPhoto(next);
        }
    });

}

function nextPrevFullscreenPhoto(next) {
    var activeItem = $('.grid-item:visible.active');
    var targetItem;
    if (next) {
        targetItem = activeItem.next('.grid-item:visible');
        if (!targetItem.length) {
            targetItem = $('.grid-item:visible').first();
        }
    } else {
        targetItem = activeItem.prev('.grid-item:visible');
        if (!targetItem.length) {
            targetItem = $('.grid-item:visible').last();
        }
    }
    // trigger fake click
    targetItem.find('img').click();
}

function clickedOnSlideControl(el) {

    var next = el.classList.contains('next');
    nextPrevFullscreenPhoto(next);

}

function clickedOnGridItemThumb(el) {

    //console.log('clicked on thumb, showing fullscreen image');
    $('.grid-item').removeClass('active');
    $(el).parent('.grid-item').addClass('active');

    // display loading gif while full photo is loading
    $('.fullscreen-photo').html('<div class="item"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');

    $.ajax({
        type: 'get',
        data: 'type=template&template=fullscreenPhoto&photoId=' + $(el).data('photoid'),
        success: function (data) {
            // console.log(data);
            $('.fullscreen-photo').html(data);
            initVoteOpenedForCountdown();
            initRating();
        }
    });
}

function clickedOnModerationControl(el) {
    var action = $(el).data('action');
    var photoId = $(el).parents('.fullscreen-photo .item').find('img').data('photoid');
    console.log('moderate : ' + action + ' & photoId : ' + photoId);
    $.ajax({
        type: 'get',
        data: 'type=moderation&photoId=' + photoId + '&action=' + action + '&ajax=true',
        success: afterModeration
    });
}

function clickedOnCloseFullscreenPhoto() {
    $('.fullscreen-photo').empty();
}

function clickedOnGridFilter(el) {

    $('.grid-filter').removeClass('active');
    $(el).addClass('active');

    var filter = $(el).data('filter');
    $('.grid').isotope({
        filter: filter
    });
}

function clickedOnDeletePhoto(el) {

    var photoId = $(el).parent().find('img').data('photoid');
    if (!photoId) {
        console.error('cannot delete photo without photoId');
        return false;
    }
    $.ajax({
        type: 'get',
        data: 'type=removePhoto&photoId=' + photoId + '&ajax=true',
        success: afterModeration
    });
}

function clickedOnLogoutLink() {
    $.ajax({
        type: 'get',
        data: 'type=logout&ajax=true',
        success: function () {
            window.location.reload();
        }
    });
}

function clickedOnUploadModal(el) {
    if (el.classList.contains('handled')) {
        return;
    } else {
        el.classList.add('handled');

        console.log('display countdown in add photo modal');

        $('.countdown.submitOpened').countdown(voteOpenDate)
            .on('update.countdown', function (event) {
                var totalHours = event.offset.totalDays * 24 + event.offset.hours;
                var totalSeconds = totalHours * 3600 + event.offset.seconds;
                var format = '%-D day%!D or ' + totalSeconds + ' seconds if you\'re a robot.';
                $(this).html(event.strftime(format));
            }).on('finish.countdown', function () {
                window.location.reload();
            });
    }
}

function clickedOnAddUserModal(el) {

    if (el.classList.contains('handled')) {
        return;
    } else {
        el.classList.add('handled');

        console.log('handle add-user-form submit');

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
                    $('form.add-user-form .message').text(data.message).addClass('alert').addClass(data.messageStatus === 'success' ? 'alert-success' : 'alert-danger');
                }
            });
        });
    }
}

function initImageGrid() {

    var nbPhotosToLoad = $('.gallery [data-layzr]').size();
    new Layzr({
        container: '.gallery',
        selector: '[data-layzr]',
        hiddenAttr: 'data-layzr-hidden',
        callback: function () {
            if (--nbPhotosToLoad === 0) {
                console.log('all images loaded');
                setTimeout(initMasonry, 100);
            }
        }
    });
}

function initMasonry() {

    $('.gallery').isotope({
        layoutMode: 'masonry',
        itemSelector: '.grid-item',
        masonry: {
            gutter: 5,
            columnWidth: 250,
            isFitWidth: true
        }
    });

    if (window.location.hash !== "") {
        var a = $('.grid-filter[href="' + window.location.hash + '"]');
        a.addClass('active');
        $('.grid')
            .isotope({
                filter: a.data('filter')
            })
            .one('arrangeComplete', function () {
                if ($('.grid-item:visible').size() === 0) {
                    $('.grid-filter').first().click();
                    window.location.hash = '';
                }
            });

    } else {
        $('.grid-filter').first().addClass('active');
    }
}

function handleLoginForm() {

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
}

function initRating() {

    $('input.rating').rating().on('change', function (event) {
        var category = $(event.currentTarget).parents(".rating-category");
        $.ajax({
            type: 'get',
            data: 'type=rate&photoId=' + category.attr("data-photo-id") + '&categoryId=' + category.attr("data-catgerory-id") + '&rate=' + $(this).val() + '&ajax=true',
            success: function (json) {
                //console.log(json);
            }
        });
    });
}

function initVoteOpenedForCountdown() {

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
        })
        .on('finish.countdown', function () {
            window.location.reload();
        });
}

function afterModeration(jsonData) {

    var ret = JSON.parse(jsonData);
    console.log('afterModeration return from B/E', ret);

    if (!ret.data) {
        console.warn('no data received');
        return;
    }

    var photoid = ret.data.photoid;
    var photostatus = ret.data.photostatus;
    var item = $('img[data-photoid="' + photoid + '"]').parent('.grid-item');
    if (photostatus === 'deleted') {
        item.remove();
    } else {
        item.attr('data-photostatus', photostatus);
    }

    $('.grid-filter.active').click();

    if (typeof ret.data.nbPhotosToModerate !== 'undefined') {
        if (ret.data.nbPhotosToModerate === 0) {
            $('.nbPhotosToModerate').remove();
            if (photostatus !== 'deleted') {
                ret.message = 'All photos have been approved !';
            }
        } else {
            $('.nbPhotosToModerate').text(ret.data.nbPhotosToModerate);
        }
    }

    if ($('.grid-item:visible').size() === 0) {
        $('.fullscreen-photo').empty();
        $('.grid-filter').first().click();
        window.location.hash = '';
    } else {
        nextPrevFullscreenPhoto(true);
    }

    if (ret.message && ret.messageStatus) {
        $.smkAlert({
            text: ret.message, type: ret.messageStatus, time: 4
        });
    }
}
