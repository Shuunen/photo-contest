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

        if (el.classList.contains('refresh-button')) {
            refresh();
        } else if (el.classList.contains('grid-filter')) {
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

function refresh() {

    document.body.classList.remove('loaded');

    startFullscreenLoading();

    $.ajax({
        type: 'get',
        data: 'type=template&template=main',
        success: function (html) {
            // console.log(data);
            // container.classList.toggle('fade');
            document.querySelector('.main').outerHTML = html;
            initImageGrid();
            handleLoginForm();
        }
    });

}

function nextPrevFullscreenPhoto(next) {
    var activeItem = $('.grid-item:visible.active');
    var targetItem;
    // console.log('active item :', activeItem);
    if (next) {
        targetItem = activeItem.nextAll('.grid-item:visible:eq(0)');
        // console.log('next :', targetItem);
        if (!targetItem.length) {
            // console.info('next :', targetItem);
            targetItem = $('.grid-item:visible').first();
        }
    } else {
        targetItem = activeItem.prevAll('.grid-item:visible:eq(0)');
        // console.log('prev :', targetItem);
        if (!targetItem.length) {
            // console.info('prev :', targetItem);
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
    startFullscreenLoading();

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
    startFullscreenLoading();
    console.log('moderate : ' + action + ' & photoId : ' + photoId);
    $.ajax({
        type: 'get',
        data: 'type=moderation&photoId=' + photoId + '&action=' + action + '&ajax=true',
        success: afterModeration
    });
}

function clickedOnCloseFullscreenPhoto() {
    $('.fullscreen-photo').empty();
  refresh();
}

function clickedOnGridFilter(el) {

    $('.grid-filter').removeClass('active');
    $(el).addClass('active');
    $(el).focus();
    window.location.hash = $(el).attr('href');

    var filter = $(el).data('filter');
    if(filter !==""){
      $('.grid').isotope({
          filter: filter
      });
    }

    var sort = $(el).data('sort');
    if(sort !== ""){
      $('.grid').isotope({
          sortBy: sort,
          sortAscending: false
      });
    }
}

function clickedOnDeletePhoto(el) {

    var photoId = $(el).parent().find('img').data('photoid');
    if (!photoId) {
        console.error('cannot delete photo without photoId');
        return false;
    }
    startFullscreenLoading();
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
            refresh();
        }
    });
}

function clickedOnUploadModal(el) {

    if (el.classList.contains('handled')) {
        return;
    }

    el.classList.add('handled');

    handlePhotoUpload();

    console.log('display countdown in add photo modal');

    $('.countdown.submitOpened')
        .countdown(voteOpenDate)
        .on('update.countdown', function (event) {
            var totalHours = event.offset.totalDays * 24 + event.offset.hours;
            var totalSeconds = totalHours * 3600 + event.offset.seconds;
            var format = '%-D day%!D or ' + totalSeconds + ' seconds if you\'re a robot.';
            $(this).html(event.strftime(format));
        })
        .on('finish.countdown', function () {
            refresh();
        });
}

function startFullscreenLoading() {
    $('.fullscreen-photo').html('<div class="item"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
}

function handlePhotoUpload() {

    var userId = document.getElementsByName('userId')[0];
    if (!userId) {
        return;
    }

    userId = userId.value;
    var bAllUploaded = false;
    var bAllAdded = false;
    var galleryUploader = new qq.FineUploader({
        element: document.getElementById("fine-uploader-gallery"),
        template: 'qq-template-gallery',
        autoUpload: false,
        request: {
            endpoint: './php/fine-uploader/endpoint.php',
            params: {
                userId: userId
            }
        },
        thumbnails: {
            placeholders: {
                waitingPath: './images/placeholders/waiting-generic.png',
                notAvailablePath: './images/placeholders/not_available-generic.png'
            }
        },
        validation: {
            allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
        },
        callbacks: {
            onProgress: function () {
                startFullscreenLoading();
            },
            onComplete: function (id, name, json) {
                // console.log(json);
                $.ajax({
                    type: 'get',
                    data: 'type=addPhoto&photoUrl=' + json.uploadName + '&ajax=true',
                    success: function (json) {
                        // console.log(json);
                        if (bAllUploaded) {
                            refresh();
                        }
                    }
                });
            },
            onAllComplete: function () {
                bAllUploaded = true;
            }
        }
    });

    qq(document.getElementById("uploadButton")).attach('click', function () {
        galleryUploader.uploadStoredFiles();
    });
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

    if (nbPhotosToLoad) {
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
    } else {
        $('.main').addClass('in');
    }
}

function initMasonry() {

    var grid = $('.grid')
        .isotope({
            layoutMode: 'masonry',
            itemSelector: '.grid-item',
            masonry: {
                gutter: 5,
                columnWidth: 250,
                isFitWidth: true
            },
            getSortData: {
              global: '[data-result-gobal]',
              travels: '[data-result-travels]',
              '40': '[data-result-40]',
              most_creative: '[data-result-most_creative]',
              funniest: '[data-result-funniest]'
            }
        })
        .one('layoutComplete', function () {
            console.info('layoutComplete removing fade');
            document.body.classList.add('loaded');
            $('.main').addClass('in');
        })
        .on('arrangeComplete', function () {

            var fullscreen = document.querySelector('.fullscreen-photo');

            if ($('.grid-item:visible').size() === 0) {
                console.log('no more photo');
                if (fullscreen.childElementCount) {
                    console.log('exiting fullscreen');
                    fullscreen.innerHTML = '';
                }
                console.log('going back to "all" filter');
                $('.grid-filter').first().click();
            } else if (fullscreen.childElementCount && !fullscreen.classList.contains('voteOpened')) {
                console.log('automagically goes to next photo');
                nextPrevFullscreenPhoto(true);
            }
        });

    if (window.location.hash !== "") {
        var a = $('.grid-filter[href="' + window.location.hash + '"]');
        a.addClass('active');
        var filter = a.data('filter');
        if(filter !==""){
          grid.isotope({
              filter: filter
          });
        }

        var sort = a.data('sort');
        if(sort !== ""){
          grid.isotope({
              sortBy: sort,
              sortAscending: false
          });
        }
    } else {
        $('.grid-filter').first().addClass('active').click();
    }
}

function handleLoginForm() {

    var form = $('form.login');

    if (form.size() === 0) {
        return;
    }

    document.querySelector('.main').classList.add('in');

    form.submit(function (event) {
        event.preventDefault();
        var data = $(this).serialize();
        data += '&ajax=true';
        $.ajax({
            type: 'get',
            data: data,
            success: function () {
                refresh();
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
                var json = JSON.parse(json);
                console.log(json);
                refreshThumb(json.data.photoid);
            }
        });
    });

    $('span.clear-vote').click(function(event){
      var category = $(event.currentTarget).parents(".rating-category");
      $.ajax({
            type: 'get',
            data: 'type=rate&photoId=' + category.attr("data-photo-id") + '&categoryId=' + category.attr("data-catgerory-id") + '&rate=' + 0 + '&ajax=true',
            success: function (json) {
                var json = JSON.parse(json);
                category.find('input.rating').rating('rate', 0);
            }
        });
    });
}

function refreshThumb(photoid) {
    console.log('will refresh photoid thumb', photoid);
    this.photoid = photoid;
    this.gridItem = document.querySelector('[data-griditem-photoid="' + this.photoid + '"]');
    if (!this.gridItem) {
        return;
    }
    console.log('found gridItem', gridItem);
    $.ajax({
        type: 'get',
        data: 'type=template&template=thumb&photoid=' + photoid,
        success: function (html) {
            if (html.length && html.length > 50) {
                this.gridItem.outerHTML = html;
                new Layzr({
                    container: '.grid',
                    selector: '[data-layzr]',
                    hiddenAttr: 'data-layzr-hidden',
                    callback: function () {
                        console.log('thumb loaded, reload grid');
                        $('.grid').isotope("reloadItems").isotope();
                    }
                });
            }
        }.bind(this)
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
            refresh();
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
        $('.grid').isotope( 'remove', item ).isotope('layout');
        item.remove();
    } else {
        item.attr('data-photostatus', photostatus);
    }

    var activeFilter = $('.grid-filter.active').attr('href');

    $.ajax({
        type: 'get',
        data: 'type=template&template=nav',
        success: function (html) {

            document.querySelector('nav').outerHTML = html;

            $('.grid-filter[href="' + activeFilter + '"]').click();

            if (ret.message && ret.messageStatus) {
                $.smkAlert({
                    text: ret.message, type: ret.messageStatus, time: 4
                });
            }
        }
    });

}
