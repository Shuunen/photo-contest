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

    $('input.rating-tooltip-manual').rating();

});
