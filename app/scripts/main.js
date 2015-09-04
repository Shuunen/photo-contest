$(document).ready(function () {

    $('.gallery').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        // asNavFor: '.slider-for',
        dots: true,
        centerMode: true,
        // centerPadding: '100px',
        focusOnSelect: true,
        // variableWidth: true,
        // adaptiveHeight: true,
        speed: 500,
        fade: true,
        cssEase: 'linear'
    });

    /*
     $('.slider-for').slick({
     slidesToShow: 1,
     slidesToScroll: 1,
     arrows: false,
     fade: true,
     asNavFor: '.slider-nav'
     });

     $('.slider-nav').slick({
     slidesToShow: 3,
     slidesToScroll: 1,
     asNavFor: '.slider-for',
     dots: true,
     centerMode: true,
     focusOnSelect: true
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

    var galleryUploader = new qq.FineUploader({
        element: document.getElementById("fine-uploader-gallery"),
        template: 'qq-template-gallery',
        request: {
            endpoint: './php/fine-uploader/endpoint.php'
        },
        thumbnails: {
            placeholders: {
                waitingPath: './placeholders/waiting-generic.png',
                notAvailablePath: './placeholders/not_available-generic.png'
            }
        },
        validation: {
            allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
        }
    });

});
