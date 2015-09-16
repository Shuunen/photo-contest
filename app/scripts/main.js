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

        $.ajax({
            type: 'get',
            data: 'type=template&template=fullPhoto&photoId=' + $(this).attr('id'),
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
        $('.grid').isotope({
            filter: $(this).data('filter')
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
                        itemSelector: '.grid-item',
                        // percentPosition: true,
                        masonry: {
                            gutter: 10,
                            columnWidth: 200
                        }
                    });
                    var categoryHash = window.location.hash;
                    if(categoryHash!=""){
                      $('.grid').isotope({
                          filter: '.'+categoryHash.substr(1)
                      });
                    }

                }, 100);
            }
        }
    });

});

var voteOpenDate = '2015/09/25';

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
        var photoId = $(this).parent().find('img').attr('id');
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
    console.log('after slide ret', ret);
    window.location.reload();
}
