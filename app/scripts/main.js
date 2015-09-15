/* global qq */

$(document).ready(function () {
/*
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
    });*/

    /*
     setTimeout(function () {
     $('.gallery-slider').parents('.modal-dialog').parent().addClass('modal fullscreen fade');
     }, 200);
     $('.gallery-slider').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
     console.log(nextSlide);
     });
     */

    $('.gallery').isotope({
      // options...
      itemSelector: 'img',
      masonry: {
        columnWidth: 200
      }
    });

    $('.gallery-filters .all').click(function(){
      $('.gallery').isotope({ filter: '*' });
    });

    $('.gallery-filters .vote').click(function(){
      $('.gallery').isotope({ filter: '.vote' });
    });

    $('.gallery-filters .user').click(function(){
      $('.gallery').isotope({ filter: '.user' });
    });

    $('.gallery-filters .censored').click(function(){
      $('.gallery').isotope({ filter: '.censored' });
    });

    $('.gallery img').click(function () {
        //var index = $(this).data('index');
        //console.log('slickGoTo', index);
       // $('.gallery-slider').slick('slickGoTo', index);
        $.ajax({
            type: 'get',
            data: 'type=template&template=fullPhoto&photoId='+$(this).attr('id'),
            success: function (data) {
              //console.log(data);
              $('.fullPhoto').html(data);
              initFullPhoto();
              initRating();
              initModeration();
              $('.fullPhoto .item img').click(function(){
                $('.fullPhoto').html('');
              })
            }
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
  });

  function initFullPhoto(){
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


    $('.delete-photo').click(function () {
        var photoId = $(this).parent().find('img').attr('id');
        if (!photoId) {
            console.error('cannot delete photo without photoId');
            return false;
        }
        $.ajax({
            type: 'get',
            data: 'type=removePhoto&photoId=' + photoId + '&ajax=true',
            success: afterSlideAction
        });

    });
  }



function afterSlideAction(jsonData) {
    var ret = JSON.parse(jsonData);
    if (ret.messageStatus === 'success') {
        var slider = $('.slick-slider:visible');
      //  if (slider.slick('getSlick').slideCount > 1) {
       //     slider.slick('slickRemove', slider.slick('slickCurrentSlide'));
            // console.info('moderation was saved, remove this slide');
        //} else {
            // console.info('moderation was saved, was the last slide, reload page');
            window.location.reload();
       // }
    } else {
        console.error('moderation fucked up');
    }
}
