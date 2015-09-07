/* global qq */

$(document).ready(function () {

    $('.gallery').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        // asNavFor: '.slider-for',
        dots: true,
        centerMode: true,
        // centerPadding: '100px',
        focusOnSelect: true,
        lazyLoad: 'ondemand',
        // variableWidth: true,
        // adaptiveHeight: true,
        speed: 500,
        fade: true,
        cssEase: 'linear'
    });

    $('.gallery-nav').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.gallery',
        dots: true,
        centerMode: true,
        focusOnSelect: true
    });

    /*
     $('.slider-for').slick({
     slidesToShow: 1,
     slidesToScroll: 1,
     arrows: false,
     fade: true,
     asNavFor: '.slider-nav'
     });


     */

    $('input.rating').rating();

    $('input.rating').on('change', function (event) {
        console.info('Rating: ' + $(this).val());
    });

    $('form.login').submit(function (event) {
        event.preventDefault();
        var data = $(this).serialize();
        data += '&ajax=true';
        $.ajax({
            type: 'get',
            data: data,
            success: function (json) {
                // console.log(json);
                window.location.reload();
            }
        });
    });

    var userId = document.getElementsByName('userId')[0];
    if (userId) {
        userId = userId.value;
        var bAllUploaded = false;
        var bAllAdded = false;
        var galleryUploader = new qq.FineUploader({
            element: document.getElementById("fine-uploader-gallery"),
            template: 'qq-template-gallery',
            request: {
                endpoint: './app/php/fine-uploader/endpoint.php',
                params: {
                    userId: userId
                }
            },
            thumbnails: {
                placeholders: {
                    waitingPath: './app/placeholders/waiting-generic.png',
                    notAvailablePath: './app/placeholders/not_available-generic.png'
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
    }


});
