$(document).ready(function () {

      zoomwall.create(document.getElementById('gallery'));


//    $('.gallery').slick({
//        slidesToShow: 1,
//        slidesToScroll: 1,
//        // asNavFor: '.slider-for',
//        dots: true,
//        centerMode: true,
//        // centerPadding: '100px',
//        focusOnSelect: true,
//        // variableWidth: true,
//        // adaptiveHeight: true,
//        speed: 500,
//        fade: true,
//        cssEase: 'linear'
//    });


    $('input.rating').rating();

    $('input.rating').on('change', function (event) {
        console.info('Rating: ' + $(this).val());
    });

    $('form.login').submit(function (event) {
        event.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            type: 'get',
            data: data,
            success: function (json) {
                console.log(JSON.parse(json));
            }
        });
    });

});
