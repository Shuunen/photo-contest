/* global qq, voteOpenDate, Isotope */

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
        } else if (el.getAttribute('data-target') === '#settingsModal') {
            clickedOnSettingsModal(el);
        } else if (el.getAttribute('data-target') === '#uploadModal') {
            clickedOnUploadModal(el);
        } else if (el.getAttribute('data-target') === '#resultsModal') {
            clickedOnResultsModal(el);
        } else if (el.classList.contains('slide-control')) {
            clickedOnSlideControl(el);
        } else if (el.classList.contains('expander')){
            togglePodiumBanner(el);
        }else if (el.classList.contains('forgot-password-link')){
            clickedOnForgotPasswordLink(el);
        }else{
          console.log(el);
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

    console.info('nextPrevFullscreenPhoto');

    var items = $('.grid').isotope('getFilteredItemElements');
    var targetItem;

    for (var i = 0; i < items.length; i++) {
        curItem = items[i];
        if (items[i].classList.contains('active')) {
            // console.log('got active item ! ', items[i]);
            if (next) {
                if (items[i + 1]) {
                    targetItem = items[i + 1];
                } else {
                    targetItem = items[0];
                }
            } else {
                if (items[i - 1]) {
                    targetItem = items[i - 1];
                } else {
                    targetItem = items[items.length - 1];
                }
            }
        }
    }

  if(!targetItem && items[0]){
    targetItem = items[0];
  }else if(!targetItem && items.length === 0){
    clickedOnCloseFullscreenPhoto();
  }

    // trigger fake click
    if (targetItem) {
        $('.photo-container').addClass('fade');
        setTimeout(function () {
            $(targetItem).find('img').click();
        }, 100);
    } else {
        console.warn('cannot find an item to go next');
    }
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

            lazyLoadPhotos(function(){
                // console.log('fullscreen photo loaded');
                $('.photo-container .fa-spinner').remove();
            });

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
}

function clickedOnGridFilter(el) {

    $('.grid-filter').removeClass('active');
    $(el).addClass('active');
    $(el).focus();
    window.location.hash = $(el).attr('href');

    var filter = $(el).data('filter');
    if (filter) {
        $('.grid').isotope({
            filter: filter
        });
    }

    var sort = $(el).data('sort');
    if (sort) {
        $('.grid').isotope({
          getSortData: {
            number: '[data-result-'+sort+'] parseFloat'
          },
          sortBy: 'number',
          sortAscending: false
        });
        $('.grid').isotope('updateSortData').isotope();
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

function clickedOnForgotPasswordLink(){
  $('form.login').hide();
  $('form.forgot').show();
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
            var format = '%-D day%!D %H:%M:%S or ' + totalSeconds + ' seconds if you\'re a robot.';
            $(this).html(event.strftime(format));
        })
        .on('finish.countdown', function () {
            refresh();
        });
}

function clickedOnResultsModal(el) {

    if (el.classList.contains('handled')) {
        return;
    } else {
        el.classList.add('handled');

      startFullscreenLoading();

       $.ajax({
          type: 'get',
          data: 'type=template&template=resultsModal',
          success: function (html) {
              if (html.length && html.length > 50) {

                  $('#resultsModal .modal-body').html(html);
                  $('.fullscreen-photo').html('');
              }
          }.bind(this)
      })

    }

}

function startFullscreenLoading() {
    var fullscreenPhoto = $('.fullscreen-photo');
    // fullscreenPhoto.children('.item').addClass('fade');
    fullscreenPhoto.html('<div class="item"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
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
                    $('form.add-user-form .message').text(data.message).addClass('alert').addClass(data.messageStatus === 'success' ? 'alert-success' : 'alert-danger');

                }
            });
        });
    }
}

function clickedOnSettingsModal(el) {

    if (el.classList.contains('handled')) {
        return;
    } else {
        el.classList.add('handled');

        console.log('handle settings-form submit & datepicker');

        $('form.settings-form').submit(function (event) {
            event.preventDefault();
            var data = $(this).serialize();
            data += '&ajax=true';
            $.ajax({
                type: 'get',
                data: data,
                success: function (ret) {
                    ret = JSON.parse(ret);
                    var message = $('form.settings-form .message');
                    message.removeClass('alert-danger', 'alert-success');
                    message.text(ret.message).addClass('alert').addClass(ret.messageStatus === 'success' ? 'alert-success' : 'alert-danger');
                }
            });
        });

        $('[data-type="date"]').datepicker({
            format: "yyyy-mm-dd",
            orientation: "top auto",
            autoclose: true
        });
    }
}

function initImageGrid() {

    initMasonry();

    lazyLoadPhotos(function(){
        $('.grid').isotope();
    });
}

function lazyLoadPhotos(callback) {

    var photo = document.querySelector('[data-lazy]');

    if (!photo) {
        // console.log('all photos loaded');
        return callback ? callback() : true;
    }

    var path = photo.getAttribute('data-lazy');

    // console.info('loading "' + path + '" ...');

    photo.classList.add('fade');

    photo.onload = function () {
        // console.log('photo loaded');
        this.removeAttribute('data-lazy');
        this.classList.add('in');
        lazyLoadPhotos(callback);
    };

    photo.onerror = function () {
        console.error('photo loading failed');
    };

    photo.src = path;
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
                'global': '[data-result-global] parseFloat',
                'travels': '[data-result-travels] parseFloat',
                '40': '[data-result-40] parseFloat',
                'most_creative': '[data-result-most_creative] parseFloat',
                'funniest': '[data-result-funniest] parseFloat'
            }
        })
        .one('layoutComplete', function () {
            console.info('layoutComplete removing fade');
            document.body.classList.add('loaded');
            $('.main').addClass('in');
        });

    if (window.location.hash !== "") {
        var a = $('.grid-filter[href="' + window.location.hash + '"]');
        a.addClass('active');
        var filter = a.data('filter');
        if (filter !== "") {
            grid.isotope({
                filter: filter
            });
        }

        var sort = a.data('sort');
        if (sort !== "") {
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

    document.querySelector('.main').classList.add('in');

    if (form.size() === 0) {
        return;
    }


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

function handleForgotForm() {

    var form = $('form.forgot');

    document.querySelector('.main').classList.add('in');

    if (form.size() === 0) {
        return;
    }


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
                // console.log(json);
                refreshThumb(json.data.photoid);
            }
        });
    });

    $('span.clear-vote').click(function (event) {
        var category = $(event.currentTarget).parents(".rating-category");

        clearVote(category.attr("data-photo-id"),
                  category.attr("data-catgerory-id"),
                  function (json) {
                      // var json = JSON.parse(json);
                      category.find('input.rating').rating('rate', 0);
                  }
       );
    });
}

function clearVote($photoId, $categoryId, callback){
  $.ajax({
      type: 'get',
      data: 'type=rate&photoId=' + $photoId + '&categoryId=' + $categoryId + '&rate=' + 0 + '&ajax=true',
      success: function (json) {
          // var json = JSON.parse(json);
          if(callback){
            callback(json);
          }
      }
  });
}

function refreshThumb(photoid) {
    console.log('will refresh photoid thumb', photoid);
    this.photoid = photoid;
    this.gridItem = document.querySelector('[data-griditem-photoid="' + this.photoid + '"]');
    if (!this.gridItem) {
        return;
    }
    this.gridItemStyle = this.gridItem.getAttribute("style");
    console.log('found gridItem', gridItem);
    $.ajax({
        type: 'get',
        data: 'type=template&template=thumb&photoid=' + photoid,
        success: function (html) {
            if (html.length && html.length > 50) {
                var bWasActive = this.gridItem.classList.contains('active');
                this.gridItem.outerHTML = html;
                this.gridItem = document.querySelector('[data-griditem-photoid="' + this.photoid + '"]');
                this.gridItem.setAttribute('style', this.gridItemStyle);
                if (bWasActive) {
                    this.gridItem.classList.add('active');
                }
                lazyLoadPhotos(function () {
                    console.log('thumb loaded, reload grid');
                    $('.grid').isotope("reloadItems").isotope();
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
        $('.grid').isotope('remove', item).isotope('layout');
        item.remove();
    } else {
        refreshThumb(photoid);
    }

    var activeFilter = $('.grid-filter.active').attr('href');

    nextPrevFullscreenPhoto(true);

    $.ajax({
        type: 'get',
        data: 'type=template&template=nav',
        success: function (html) {

            document.querySelector('nav').outerHTML = html;

            $('.grid-filter[href="' + activeFilter + '"]').click();

            if (ret.message && ret.messageStatus) {
                $.smkAlert({
                    text: ret.message, type: ret.messageStatus, time: 2
                });
            }
        }
    });

}

Isotope.prototype.getFilteredItemElements = function () {
    var elems = [];
    for (var i = 0, len = this.filteredItems.length; i < len; i++) {
        elems.push(this.filteredItems[i].element);
    }
    return elems;
};


function togglePodiumBanner(el){


  var podiumBanner = document.querySelector('.podium-banner');
  if(podiumBanner){
    var expandIcon = podiumBanner.querySelector('.expander');
    if (podiumBanner.classList.contains('expanded')){
      podiumBanner.classList.remove('expanded');
      podiumBanner.classList.add('collapsed');
      expandIcon.classList.remove('fa-angle-double-down');
      expandIcon.classList.add('fa-angle-double-up');

      document.body.classList.remove('podium-expanded');

    }else{
      podiumBanner.classList.remove('collapsed');
      podiumBanner.classList.add('expanded');
      expandIcon.classList.add('fa-angle-double-down');
      expandIcon.classList.remove('fa-angle-double-up');

      document.body.classList.add('podium-expanded');
    }
  }
}

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
  var elem = ev.target;
  //console.log(elem);
  ev.dataTransfer.setData("photoId", elem.getAttribute('data-photoid'));
  ev.dataTransfer.setData("photoSrc", elem.getAttribute('src'));
}

function drop(ev) {
    ev.preventDefault();
    //console.log(ev);

    var photoId = ev.dataTransfer.getData("photoId");
    var photoSrc = ev.dataTransfer.getData("photoSrc");
    if(photoId && photoSrc) {
      console.log("drop -> photoId : ",photoId);
      var podium = ev.target.parentElement;
      //console.log(podium);

      var podiumSteps = podium.children;
      var alreadyOnTheSteps = false;
      for(var i = 0; i < podiumSteps.length;i++){
        //console.log(podiumSteps[i]);
        if(podiumSteps[i].style.backgroundImage.indexOf(photoSrc) !== -1){
          podiumSteps[i].style.backgroundImage = "";
          podiumSteps[i].removeAttribute('data-photoid');
          alreadyOnTheSteps = true;
        }
      }
      if(!alreadyOnTheSteps && ev.target.getAttribute('data-photoid') && podium.getAttribute('data-category-id')){
        clearVote(ev.target.getAttribute('data-photoid'), podium.getAttribute('data-category-id'));
      }

      ev.target.style.backgroundImage = "url('"+photoSrc+"')";
      ev.target.setAttribute('data-photoid', photoId);
      var position = ev.target.getAttribute('data-position');
      voteForThisPhoto(photoId, podium.getAttribute('data-category-id'), position);
    }

}

function voteForThisPhoto(photoId, categoryId, position ){
  $.ajax({
            type: 'get',
            data: 'type=rate&photoId=' + photoId + '&categoryId=' + categoryId + '&position=' + position + '&ajax=true',
            success: function (json) {
                var json = JSON.parse(json);
                // console.log(json);
                refreshThumb(json.data.photoid);
            }
        });
}
