/* global qq */

$(document).ready(function () {

    $('.gallery-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        // asNavFor: '.slider-for',
        // arrows: false,
        dots: false,
        centerMode: true,
        // centerPadding: '100px',
        focusOnSelect: true,
        lazyLoad: 'ondemand', // or progressive
        // variableWidth: true,
        // adaptiveHeight: true,
        speed: 500,
        fade: true,
        cssEase: 'linear'
    });

    $('.gallery img').click(function () {
        var index = $(this).data('index');
        $('.gallery-slider').slick('slickGoTo', index);
    });

    /*
     $('.gallery-nav.horizontal').slick({
     slidesToShow: 3,
     slidesToScroll: 1,
     asNavFor: '.gallery',
     arrows: false,
     dots: false,
     centerMode: true,
     focusOnSelect: true
     });

     $('.gallery-nav.vertical').slick({
     slidesToShow: 5,
     slidesToScroll: 1,
     asNavFor: '.gallery',
     arrows: false,
     dots: false,
     centerMode: true,
     focusOnSelect: true,
     vertical: true,
     verticalSwiping: true
     });
     */

    $('input.rating').rating();

    $('input.rating').on('change', function (event) {
//        console.info('Rating: ' + $(this).val());
//        console.log(event);
//        console.log($(event.currentTarget).parents(".rating-category").attr("data-catgerory-id"));
        var category = $(event.currentTarget).parents(".rating-category")
        $.ajax({
            type: 'get',
            data: 'type=rate&photoId=' + category.attr("data-photo-id") + '&categoryId=' + category.attr("data-catgerory-id") + '&rate=' + $(this).val() + '&ajax=true',
            success: function (json) {
                //console.log(json);
            }
        });
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

    $('#logoutLink').click(function () {
        $.ajax({
            type: 'get',
            data: 'type=logout&ajax=true',
            success: function () {
                window.location.reload();
            }
        });
    });

    $('.countdown.voteOpened').countdown('2015/09/25')
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

    $('.countdown.submitOpened').countdown('2015/09/25')
        .on('update.countdown', function (event) {
            var totalHours = event.offset.totalDays * 24 + event.offset.hours;
            var totalSeconds = totalHours * 3600 + event.offset.seconds;
            var format = '%-D day%!D or ' + totalSeconds + ' seconds if you\'re a robot.';
            $(this).html(event.strftime(format));
        }).on('finish.countdown', function () {
            window.location.reload();
        });

    var userId = document.getElementsByName('userId')[0];
    if (userId) {
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
                    waitingPath: './placeholders/waiting-generic.png',
                    notAvailablePath: './placeholders/not_available-generic.png'
                }
            },
            validation: {
                allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
            },
            callbacks: {
                onComplete: function (id, name, json) {
                    console.log(json);
                    $.ajax({
                        type: 'get',
                        data: 'type=addPhoto&photoUrl=' + json.uploadName + '&ajax=true',
                        success: function (json) {
                            console.log(json);
                            if (bAllUploaded) {
                                window.location.reload();
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


});
