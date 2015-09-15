//$(document).ready(function () {


//    var slider = $('#moderatePhotosModal .slick-slider');
  function initModeration(){
      $('.moderation-controls button').mousedown(function (event) {

          // avoid swapping by mistake
          event.stopPropagation();

          var action = $(this).data('action');
          var photoId = $(this).parents('.fullPhoto .item').find('img').attr('id');
          console.log('moderate : ' + action + ' photoId : ' + photoId);

          $.ajax({
              type: 'get',
              data: 'type=moderation&photoId=' + photoId + '&action=' + action + '&ajax=true',
              success: afterSlideAction
          });

      });
  }
//});
